<?php

namespace Omnipay\FlashPay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\NotificationInterface;

class AcceptNotificationResponse extends AbstractResponse implements NotificationInterface
{
    public function isSuccessful()
    {
        return $this->getCode() === '00';
    }

    public function getCode()
    {
        return $this->data['ret_code'];
    }

    public function getMessage()
    {
        return $this->data['ret_msg'];
    }

    public function getTransactionId()
    {
        return $this->data['ord_no'];
    }

    public function getTransactionReference()
    {
        return $this->data['order_no'];
    }

    public function getTransactionStatus()
    {
        return $this->isSuccessful() ? self::STATUS_COMPLETED : self::STATUS_FAILED;
    }

    public function getReply()
    {
        return '1|OK';
    }
}
