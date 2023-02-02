<?php

namespace Omnipay\FlashPay\Message;

use Exception;
use FlashPay\Lib\Services\FeedbackService;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\FlashPay\Traits\HasDecode;
use Omnipay\FlashPay\Traits\HasFlashPay;

class AcceptNotificationRequest extends AbstractRequest implements NotificationInterface
{
    use HasFlashPay;
    use HasDecode;

    public function setVer($value)
    {
        return $this->setParameter('ver', $value);
    }

    public function getVer()
    {
        return $this->getParameter('ver');
    }

    public function setDat($value)
    {
        return $this->setParameter('dat', $value);
    }

    public function getDat()
    {
        return $this->getParameter('dat');
    }

    public function setChk($value)
    {
        return $this->setParameter('chk', $value);
    }

    public function getChk()
    {
        return $this->getParameter('chk');
    }

    /**
     * @throws Exception
     */
    public function getData()
    {
        $input = http_build_query(['ver' => $this->getVer(), 'dat' => $this->getDat(), 'chk' => $this->getChk()]);
        $feedbackService = new FeedbackService($this->getHashKey(), $this->getHashIv(), $input);

        return $this->decode($feedbackService->getRetrunJson());
    }

    public function sendData($data)
    {
        return $this->response = new AcceptNotificationResponse($this, $data);
    }

    public function getTransactionStatus()
    {
        return $this->getNotificationResponse()->getTransactionStatus();
    }

    public function getTransactionReference()
    {
        return $this->getNotificationResponse()->getTransactionReference();
    }

    public function getMessage()
    {
        return $this->getNotificationResponse()->getMessage();
    }

    public function getReply()
    {
        return $this->getNotificationResponse()->getReply();
    }

    /**
     * @return AcceptNotificationResponse
     */
    private function getNotificationResponse()
    {
        return ! $this->response ? $this->send() : $this->response;
    }
}
