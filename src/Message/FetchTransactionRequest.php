<?php

namespace Omnipay\FlashPay\Message;

use FlashPay\Lib\Services\QueryOrderService;
use FlashPay\Lib\Services\UtilService;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\FlashPay\Traits\HasDecode;
use Omnipay\FlashPay\Traits\HasFlashPay;

class FetchTransactionRequest extends AbstractRequest
{
    use HasFlashPay;
    use HasDecode;

    public static $mock = false;

    public function setOrdNo($value)
    {
        return $this->setTransactionId($value);
    }

    public function getOrdNo()
    {
        return $this->getTransactionId();
    }

    /**
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('mer_id', 'transactionId');

        $output = $this->fetch([
            'hashKey' => $this->getHashKey(),
            'hashIv' => $this->getHashIv(),
            'mer_id' => $this->getMerId(),
            'ord_no' => $this->getTransactionId(),
        ]);

        return $this->decode($output);
    }

    public function sendData($data)
    {
        return $this->response = new FetchTransactionResponse($this, $data);
    }

    protected function fetch($data)
    {
        if (self::$mock === true) {
            $response = $this->httpClient->request('POST', UtilService::$stageURL, $data);

            return (string) $response->getBody();
        }
        $endpoint = $this->getTestMode() ? UtilService::$ProdutionURL : UtilService::$stageURL;
        $queryOrderService = new QueryOrderService(['hashKey' => $data['hashKey'], 'hashIv' => $data['hashIv']]);

        return $queryOrderService->queryOrder($data['mer_id'], $data['ord_no'], $endpoint);
    }
}
