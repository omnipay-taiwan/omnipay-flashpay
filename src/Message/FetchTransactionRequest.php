<?php

namespace Omnipay\FlashPay\Message;

use FlashPay\Lib\Services\UtilService;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\FlashPay\Services\QueryOrderService;
use Omnipay\FlashPay\Traits\HasDecode;
use Omnipay\FlashPay\Traits\HasFlashPay;
use Omnipay\FlashPay\Traits\HasOrdNo;

class FetchTransactionRequest extends AbstractRequest
{
    use HasDecode;
    use HasFlashPay;
    use HasOrdNo;

    /**
     * @return array
     *
     * @throws InvalidRequestException
     * @throws InvalidResponseException
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
     * @return string
     */
    protected function query(array $data)
    {
        $endpoint = $this->getTestMode() ? UtilService::$stageURL : UtilService::$ProdutionURL;
        $queryOrderService = new QueryOrderService($this->httpClient, [
            'hashKey' => $data['hashKey'],
            'hashIv' => $data['hashIv'],
        ]);

        return $queryOrderService->queryOrder($data['mer_id'], $data['ord_no'], $endpoint);
    }
}
