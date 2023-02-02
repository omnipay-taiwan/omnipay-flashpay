<?php

namespace Omnipay\FlashPay\Message;

use Omnipay\Common\Message\AbstractResponse;

class FetchTransactionResponse extends AbstractResponse
{
    /**
     * @return bool
     */
    public function isPending()
    {
        return in_array($this->getCode(), ['00', 'ZP'], true);
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return in_array($this->getCode(), ['02', '03', '04'], true);
    }

    /**
     * @return bool
     */
    public function isCancelled()
    {
        return in_array($this->getCode(), ['06', '08', '12'], true);
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->data['order_status'];
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->data['ord_no'];
    }

    /**
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->data['order_no'];
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->data['ret_msg'];
    }
}
