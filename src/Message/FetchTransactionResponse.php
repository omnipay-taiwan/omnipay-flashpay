<?php

namespace Omnipay\FlashPay\Message;

use Omnipay\Common\Message\AbstractResponse;

class FetchTransactionResponse extends AbstractResponse
{
    public function isPending()
    {
        return in_array($this->getCode(), ['00', 'ZP'], true);
    }

    public function isSuccessful()
    {
        return in_array($this->getCode(), ['02', '03', '04'], true);
    }

    public function isCancelled()
    {
        return in_array($this->getCode(), ['06', '08', '12'], true);
    }

    public function getCode()
    {
        return $this->data['order_status'];
    }

    public function getTransactionId()
    {
        return $this->data['ord_no'];
    }

    public function getTransactionReference()
    {
        return $this->data['order_no'];
    }

    public function getMessage()
    {
        return $this->data['ret_msg'];
    }
}
