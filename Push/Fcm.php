<?php

namespace Nonda\Push;

use Sly\NotificationPusher\Adapter\Gcm as BaseGcm;
use Sly\NotificationPusher\Collection\DeviceCollection;
use Sly\NotificationPusher\Model\BaseOptionedModel;
use Sly\NotificationPusher\Model\DeviceInterface;
use Sly\NotificationPusher\Model\PushInterface;
use ZendService\Google\Exception\RuntimeException as ServiceRuntimeException;

class Fcm extends BaseGcm
{
    protected $id = 'fcm';

    /**
     * 获取 Adapter 的标识
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    public function getServiceMessageFromOrigin(array $tokens, BaseOptionedModel $message)
    {
        $serviceMessage = parent::getServiceMessageFromOrigin($tokens, $message);

        return GcmMessage::newInstanceFromParent($serviceMessage);
    }

    public function pushWithCallback(PushInterface $push, callable $callback = null)
    {
        $client        = $this->getOpenedClient();
        $pushedDevices = new DeviceCollection();
        $tokens        = array_chunk($push->getDevices()->getTokens(), 100);

        foreach ($tokens as $tokensRange) {
            $message = $this->getServiceMessageFromOrigin($tokensRange, $push->getMessage());

            try {
                /** @var \ZendService\Google\Gcm\Response $response */
                $response        = $client->send($message);
                $responseResults = $response->getResults();

                foreach ($tokensRange as $token) {
                    /** @var DeviceInterface $device */
                    $device = $push->getDevices()->get($token);

                    // map the overall response object
                    // into a per device response
                    $tokenResponse = [];
                    if (isset($responseResults[$token]) && is_array($responseResults[$token])) {
                        $tokenResponse = $responseResults[$token];
                    }

                    $responseData = $response->getResponse();
                    if ($responseData && is_array($responseData)) {
                        $tokenResponse = array_merge(
                            $tokenResponse,
                            array_diff_key($responseData, ['results' => true])
                        );
                    }

                    $push->addResponse($device, $tokenResponse);

                    $pushedDevices->add($device);

                    $this->response->addOriginalResponse($device, $response);
                    $this->response->addParsedResponse($device, $tokenResponse);

                    if (is_callable($callback)) {
                        call_user_func(
                            $callback,
                            $this->id,
                            $device,      // 在外边实例化好的device
                            $tokenResponse, // 响应的数组数据，经过格式化的
                            $response,    // 原始响应
                            null          // 异常对象，如果没有，传null
                        );
                    }
                }
            } catch (ServiceRuntimeException $e) {
                if (is_callable($callback)) {
                    call_user_func(
                        $callback,
                        $this->id,
                        $device,
                        null,
                        null,
                        $e
                    );
                }
            }
        }

        return $pushedDevices;
    }
}
