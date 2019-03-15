<?php

namespace Nonda\Push;

use Nonda\Util\Str;
use Zend\Json\Json;
use ZendService\Google\Gcm\Message;

class GcmMessage extends Message
{
    protected $pushId;

    public function setPushId($pushId)
    {
        $this->pushId = $pushId;

        return $this;
    }

    public function getPushId()
    {
        if (!$this->pushId) {
            $this->pushId = Str::randNumberWordStr();
        }

        return $this->pushId;
    }

    public function toJson()
    {
        $rawJson = Json::decode(parent::toJson(), true);

        $json = array_merge([], $rawJson);

        unset($json['data']);

        $json['data'] = [
            'push_id' => $this->getPushId(),
            'time'=> Str::makeMongoIsoDate(new \DateTime('now')),
            'data' => isset($rawJson['data']) ? Json::encode($rawJson['data']) : '',
        ];

        return Json::encode($json);
    }

    public static function newInstanceFromParent(Message $message)
    {
        $serviceMessage = new self();

        $serviceMessage
            ->setCollapseKey($message->getCollapseKey())
            ->setData($message->getData())
            ->setDelayWhileIdle($message->getDelayWhileIdle())
            ->setDryRun($message->getDryRun())
            ->setNotification($message->getNotification())
            ->setPriority($message->getPriority())
            ->setRegistrationIds($message->getRegistrationIds())
            ->setRestrictedPackageName($message->getRestrictedPackageName())
            ->setTimeToLive($message->getTimeToLive())
        ;

        return $serviceMessage;
    }
}