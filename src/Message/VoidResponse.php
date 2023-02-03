<?php

namespace Omnipay\FlashPay\Message;

class VoidResponse extends FetchTransactionResponse
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return in_array($this->getCode(), ['06', '08', '12'], true);
    }

    /**
     * @return bool
     */
    public function isCancelled()
    {
        return false;
    }
}
