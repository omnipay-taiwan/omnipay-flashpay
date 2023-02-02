<?php

namespace Omnipay\FlashPay\Traits;

trait HasOrdNo
{
    /**
     * ord_no "string(32)" "訂單編號每筆訂單編號，不可重複" "270397"
     */
    public function setOrdNo($value)
    {
        return $this->setTransactionId($value);
    }

    public function getOrdNo()
    {
        return $this->getTransactionId();
    }
}
