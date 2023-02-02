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

    /**
     * @param  string  $value
     * @return AcceptNotificationRequest
     */
    public function setVer($value)
    {
        return $this->setParameter('ver', $value);
    }

    /**
     * @return string
     */
    public function getVer()
    {
        return $this->getParameter('ver');
    }

    /**
     * @param  string  $value
     * @return AcceptNotificationRequest
     */
    public function setDat($value)
    {
        return $this->setParameter('dat', $value);
    }

    /**
     * @return string
     */
    public function getDat()
    {
        return $this->getParameter('dat');
    }

    /**
     * @param  string  $value
     * @return AcceptNotificationRequest
     */
    public function setChk($value)
    {
        return $this->setParameter('chk', $value);
    }

    /**
     * @return string
     */
    public function getChk()
    {
        return $this->getParameter('chk');
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function getData()
    {
        $input = http_build_query(['ver' => $this->getVer(), 'dat' => $this->getDat(), 'chk' => $this->getChk()]);
        $feedbackService = new FeedbackService($this->getHashKey(), $this->getHashIv(), $input);

        return $this->decode($feedbackService->getRetrunJson());
    }

    /**
     * @param  array  $data
     * @return AcceptNotificationResponse
     */
    public function sendData($data)
    {
        return $this->response = new AcceptNotificationResponse($this, $data);
    }

    /**
     * @return string
     */
    public function getTransactionStatus()
    {
        return $this->getNotificationResponse()->getTransactionStatus();
    }

    /**
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->getNotificationResponse()->getTransactionReference();
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getNotificationResponse()->getMessage();
    }

    /**
     * @return string
     */
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
