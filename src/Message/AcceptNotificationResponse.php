<?php

namespace Omnipay\FlashPay\Message;

use Omnipay\Common\Message\NotificationInterface;

class AcceptNotificationResponse extends FetchTransactionResponse implements NotificationInterface
{
    public function getTransactionStatus()
    {
        return $this->isSuccessful() ? self::STATUS_COMPLETED : self::STATUS_FAILED;
    }

    public function getReply()
    {
        return '1|OK';
    }
}
