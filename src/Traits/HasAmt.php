<?php

namespace Omnipay\FlashPay\Traits;

trait HasAmt
{
    /**
     * amt "double" "交易金額" "填入交易金額，單位(元)" "405"
     */
    public function setAmt($value)
    {
        return $this->setAmount($value);
    }

    public function getAmt()
    {
        return $this->getAmount();
    }
}
