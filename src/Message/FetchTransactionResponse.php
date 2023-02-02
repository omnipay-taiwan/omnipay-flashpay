<?php

namespace Omnipay\FlashPay\Message;

use Omnipay\Common\Message\AbstractResponse;

class FetchTransactionResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return $this->getCode() === '00';
    }

    public function getCode()
    {
        return $this->data['ret_code'];
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
