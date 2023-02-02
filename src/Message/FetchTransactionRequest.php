<?php

namespace Omnipay\FlashPay\Message;

use FlashPay\Lib\Services\QueryOrderService;
use FlashPay\Lib\Services\UtilService;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\FlashPay\Traits\HasFlashPay;

class FetchTransactionRequest extends AbstractRequest
{
    use HasFlashPay;

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

        $output = substr($output, 0, strrpos($output, '}') + 1);
        $data = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidRequestException(json_last_error_msg().': '.$output);
        }

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new FetchTransactionResponse($this, $data);
    }

    protected function fetch($data)
    {
        $endpoint = $this->getTestMode() ? UtilService::$ProdutionURL : UtilService::$stageURL;
        $queryOrderService = new QueryOrderService(['hashKey' => $data['hashKey'], 'hashIv' => $data['hashIv']]);

        return $queryOrderService->queryOrder($data['mer_id'], $data['ord_no'], $endpoint);
    }
}
