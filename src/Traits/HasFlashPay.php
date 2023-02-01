<?php

namespace Omnipay\FlashPay\Traits;

trait HasFlashPay
{
    public function setHashKey($value)
    {
        return $this->setParameter('hashKey', $value);
    }

    public function getHashKey()
    {
        return $this->getParameter('hashKey');
    }

    public function setHashIv($value)
    {
        return $this->setParameter('hashIv', $value);
    }

    public function getHashIv()
    {
        return $this->getParameter('hashIv');
    }

    /**
     * mer_id "string(32)" "特店編號" "簽約時提供的店商代號" "F213060TEEST0000"
     */
    public function setMerId($value)
    {
        return $this->setParameter('mer_id', $value);
    }

    public function getMerId()
    {
        return $this->getParameter('mer_id');
    }
}
