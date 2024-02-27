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
    use HasDecode;
    use HasFlashPay;

    /**
     * @return array
     *
     * @throws Exception
     */
    public function getData()
    {
        $feedbackService = new FeedbackService(
            $this->getHashKey(),
            $this->getHashIv(),
            http_build_query($this->httpRequest->request->all())
        );

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
    public function getTransactionId()
    {
        return $this->getNotificationResponse()->getTransactionId();
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
    public function getTransactionStatus()
    {
        return $this->getNotificationResponse()->getTransactionStatus();
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
