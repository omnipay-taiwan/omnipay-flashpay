<?php

namespace Omnipay\FlashPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\FlashPay\Message\AcceptNotificationRequest;
use Omnipay\FlashPay\Message\FetchTransactionRequest;
use Omnipay\FlashPay\Message\PurchaseRequest;
use Omnipay\FlashPay\Message\VoidRequest;

/**
 * FlashPay Gateway
 */
class Gateway extends AbstractGateway
{
    use Traits\HasFlashPay;

    /**
     * @return string
     */
    public function getName()
    {
        return 'FlashPay';
    }

    /**
     * @return array
     */
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
     * @return AbstractRequest|RequestInterface
     */
    public function purchase(array $options = [])
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    /**
     * @return AbstractRequest|NotificationInterface
     */
    public function acceptNotification(array $options = [])
    {
        return $this->createRequest(AcceptNotificationRequest::class, $options);
    }

    /**
     * @return AbstractRequest|RequestInterface
     */
    public function fetchTransaction(array $options = [])
    {
        return $this->createRequest(FetchTransactionRequest::class, $options);
    }

    public function void(array $options = [])
    {
        return $this->createRequest(VoidRequest::class, $options);
    }
}
