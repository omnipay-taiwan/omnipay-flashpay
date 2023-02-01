<?php

namespace Omnipay\FlashPay\Message;

use Exception;
use FlashPay\Lib\Services\FeedbackService;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\FlashPay\Traits\HasFlashPay;

class AcceptNotificationRequest extends AbstractRequest implements NotificationInterface
{
    use HasFlashPay;

    /**
     * @var mixed
     */
    private $data = [];

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
        if (count($this->data) > 0) {
            return $this->data;
        }

        $input = http_build_query(['ver' => $this->getVer(), 'dat' => $this->getDat(), 'chk' => $this->getChk()]);

        $feedbackService = new FeedbackService($this->getHashKey(), $this->getHashIv(), $input);
        $output = $feedbackService->getRetrunJson();
        $output = substr($output, 0, strrpos($output, '}') + 1);
        $data = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidRequestException(json_last_error_msg().': '.$output);
        }

        return $this->data = $data;
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
