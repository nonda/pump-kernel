<?php

namespace Nonda\Push;

use Nonda\Exception\BaseException;
use ZendService\Apple\Exception;
use ZendService\Apple\Apns\Message as ApnsMessage;
use ZendService\Apple\Apns\Response\Message as MessageResponse;
use ZendService\Apple\Apns\Client\Message as Client;

/**
 * Message Client
 */
class ApnsClientMessage extends Client
{


    /**
     * Send Message
     *
     * @param  ApnsMessage          $message
     * @return MessageResponse
     *
     * @throws
     */
    public function send(ApnsMessage $message)
    {
        if (!$this->isConnected()) {
            throw new Exception\RuntimeException('You must first open the connection by calling open()');
        }

        $ret = $this->write($message->getPayloadJson());

        if ($ret === 0) {
            throw new BaseException('fwrite broken', BaseException::PUSH_WRITE_BROKEN);
        }

        if ($ret === false) {
            throw new Exception\RuntimeException('Server is unavailable; please retry later');
        }

        return new MessageResponse($this->read());
    }
}
