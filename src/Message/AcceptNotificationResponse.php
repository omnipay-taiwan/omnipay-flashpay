<?php

namespace Omnipay\FlashPay\Message;

use Omnipay\Common\Message\NotificationInterface;

class AcceptNotificationResponse extends FetchTransactionResponse implements NotificationInterface
{
    public function getTransactionStatus()
    {
        if ($this->isPending()) {
            return self::STATUS_PENDING;
        }

        if ($this->isSuccessful()) {
            return self::STATUS_COMPLETED;
        }

        return self::STATUS_FAILED;
    }

    public function getReply()
    {
        return '1|OK';
    }
}
