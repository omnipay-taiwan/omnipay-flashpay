<?php

namespace Omnipay\FlashPay\Traits;

trait HasTxType
{
    /**
     * tx_type "int" "交易類別填入固定值 101" "101"
     */
    public function setTxType($value)
    {
        return $this->setParameter('tx_type', $value);
    }

    public function getTxType()
    {
        return $this->getParameter('tx_type');
    }
}
