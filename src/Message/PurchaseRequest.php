<?php

namespace Omnipay\FlashPay\Message;

use DateTime;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\FlashPay\Traits\HasAmount;
use Omnipay\FlashPay\Traits\HasCreditCard;
use Omnipay\FlashPay\Traits\HasFlashPay;

class PurchaseRequest extends AbstractRequest
{
    use HasFlashPay;
    use HasCreditCard;
    use HasAmount;

    /**
     * @return array
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('hashKey', 'hashIv', 'mer_id', 'transactionId', 'amount', 'description', 'notifyUrl');

        return [
            'hashKey' => $this->getHashKey(),
            'hashIv' => $this->getHashIv(),
            'mer_id' => $this->getMerId(),
            'stage_id' => $this->getStageId(),
            'sto_id' => $this->getStoId(),
            'ord_no' => $this->getTransactionId(),
            'ord_time' => $this->getOrdTime() ?? (new DateTime())->format('Y-m-d H:i:s'),
            'tx_type' => $this->getTxType() ?? '101',
            'pay_type' => $this->getPayType() ?? '1',
            'amt' => $this->getAmount(),
            'cur' => $this->getCurrency() ?? 'NTD',
            'order_desc' => $this->getDescription(),
            'install_period' => $this->getInstallPeriod(),
            'use_redeem' => $this->getUseRedeem(),
            'cell_phone_no' => $this->getCellPhoneNo(),
            'return_url' => $this->getNotifyUrl(),
            'client_url' => $this->getReturnUrl(),
        ];
    }

    /**
     * @param  array  $data
     * @return PurchaseResponse
     */
    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
