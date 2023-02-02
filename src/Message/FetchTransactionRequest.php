<?php

namespace Omnipay\FlashPay\Message;

use FlashPay\Lib\Services\UtilService;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\FlashPay\Services\QueryOrderService;
use Omnipay\FlashPay\Traits\HasDecode;
use Omnipay\FlashPay\Traits\HasFlashPay;

class FetchTransactionRequest extends AbstractRequest
{
    use HasFlashPay;
    use HasDecode;

    /**
     * @param  string  $value
     * @return FetchTransactionRequest
     */
    public function setOrdNo($value)
    {
        return $this->setTransactionId($value);
    }

    /**
     * @return string
     */
    public function getOrdNo()
    {
        return $this->getTransactionId();
    }

    /**
     * @return array
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('mer_id', 'transactionId');

        $output = $this->query([
            'hashKey' => $this->getHashKey(),
            'hashIv' => $this->getHashIv(),
            'mer_id' => $this->getMerId(),
            'ord_no' => $this->getTransactionId(),
        ]);

        return $this->decode($output);
    }

    /**
     * @param  array  $data
     * @return FetchTransactionResponse
     */
    public function sendData($data)
    {
        return $this->response = new FetchTransactionResponse($this, $data);
    }

    /**
     * @param  array  $data
     * @return string
     */
    protected function query(array $data)
    {
        $endpoint = $this->getTestMode() ? UtilService::$ProdutionURL : UtilService::$stageURL;
        $queryOrderService = new QueryOrderService($this->httpClient, [
            'hashKey' => $data['hashKey'],
            'hashIv' => $data['hashIv'],
        ]);

        return $queryOrderService->queryOrder($data['mer_id'], $data['ord_no'], $endpoint);
    }
}
