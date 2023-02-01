<?php

namespace Omnipay\FlashPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\FlashPay\Message\PurchaseRequest;

/**
 * FlashPay Gateway
 */
class Gateway extends AbstractGateway
{
    use Traits\HasFlashPay;

    public function getName()
    {
        return 'FlashPay';
    }

    public function getDefaultParameters()
    {
        return [
            'hashKey' => '',
            'hashIv' => '',
            'mer_id' => '',
            'testMode' => false,
        ];
    }

    /**
     * @return PurchaseRequest
     */
    public function purchase(array $options = [])
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }
}
