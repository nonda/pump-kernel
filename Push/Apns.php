<?php

namespace Nonda\Push;

use Nonda\Exception\BaseException;
use Sly\NotificationPusher\Adapter\Apns as BaseApns;
use Sly\NotificationPusher\Collection\DeviceCollection;
use Sly\NotificationPusher\Model\PushInterface;
use Nonda\Push\ApnsClientMessage as ServiceClient;
use ZendService\Apple\Apns\Response\Message as ServiceResponse;

class Apns extends BaseApns
{
    use PushAdapterTrait;

    /**
     * @var ServiceClient
     */
    private $openedClient;

    protected $id = 'apns';

    /**
     * 获取 Adapter 的标识
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    public function pushWithCallback(PushInterface $push, callable $callback = null)
    {
        $client = $this->getOpenedServiceClient();
        $pushedDevices = new DeviceCollection();

        foreach ($push->getDevices() as $device) {
            /** @var \ZendService\Apple\Apns\Message $message */
            $message = $this->getServiceMessageFromOrigin($device, $push->getMessage());

            try {

                try {
                    /** @var \ZendService\Apple\Apns\Response\Message $response */
                    $response = $client->send($message);
                } catch (BaseException $e) {
                    if ($e->getCode() === BaseException::PUSH_WRITE_BROKEN) {
                        $client = $this->resetServiceClient();
                        continue;
                    }

                    throw $e;
                }

                $responseArr = [
                    'id'    => $response->getId(),
                    'token' => $response->getCode(),
                ];
                $push->addResponse($device, $responseArr);
                
                if (ServiceResponse::RESULT_OK === $response->getCode()) {
                    $pushedDevices->add($device);
                }

                $this->response->addOriginalResponse($device, $response);
                $this->response->addParsedResponse($device, $responseArr);

                if (is_callable($callback)) {
                    call_user_func(
                        $callback,
                        $this->id,
                        $device,      // 在外边实例化好的device
                        $responseArr, // 响应的数组数据，经过格式化的
                        $response,    // 原始响应
                        null          // 异常对象，如果没有，传null
                    );
                }
            } catch (\RuntimeException $e) {
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

    /**
     * Get opened ServiceClient
     *
     * @return ServiceClient
     */
    protected function getOpenedServiceClient()
    {
        if (!isset($this->openedClient)) {
            $this->openedClient = $this->getOpenedClient(new ServiceClient());
        }

        return $this->openedClient;
    }

    protected function resetServiceClient()
    {
        unset($this->openedClient);

        return $this->getOpenedServiceClient();
    }

    public function forgetServiceClient()
    {
        unset($this->openedClient);

        return $this;
    }
}
