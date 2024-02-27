<?php

namespace Omnipay\FlashPay\Message;

use FlashPay\Lib\Services\UtilService;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\FlashPay\Services\DoTradeService;
use Omnipay\FlashPay\Traits\HasAmount;
use Omnipay\FlashPay\Traits\HasAmt;
use Omnipay\FlashPay\Traits\HasDecode;
use Omnipay\FlashPay\Traits\HasFlashPay;
use Omnipay\FlashPay\Traits\HasOrdNo;
use Omnipay\FlashPay\Traits\HasTxType;

class VoidRequest extends AbstractRequest
{
    use HasAmount;
    use HasAmt;
    use HasDecode;
    use HasFlashPay;
    use HasOrdNo;
    use HasTxType;

    /**
     * @return array
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        return [
            'hashKey' => $this->getHashKey(),
            'hashIv' => $this->getHashIv(),
            'mer_id' => $this->getMerId(),
            'ord_no' => $this->getTransactionId(),
            'tx_type' => $this->getTxType() ?? 8,
            'amt' => $this->getAmount(),
        ];
    }

    /**
     * @return VoidResponse
     *
     * @throws InvalidRequestException
     */
    public function sendData($data)
    {
        $endpoint = $this->getTestMode() ? UtilService::$stageURL : UtilService::$ProdutionURL;
        $doTradeService = new DoTradeService($this->httpClient, [
            'hashKey' => $data['hashKey'],
            'hashIv' => $data['hashIv'],
        ]);

        return $this->response = new VoidResponse(
            $this,
            $this->decode($doTradeService->cancelAuth($data['mer_id'], $data['ord_no'], $data['amt'], $endpoint))
        );
    }
}
