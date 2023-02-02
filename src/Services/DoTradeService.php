<?php

namespace Omnipay\FlashPay\Services;

use FlashPay\Lib\obj\AesObj;
use Omnipay\Common\Http\Client;
use Omnipay\FlashPay\Traits\HasHttpClient;

class DoTradeService extends AesObj
{
    use HasHttpClient;

    /** @var Client */
    private $client;

    public function __construct($client, $input)
    {
        parent::__construct($input['hashKey'], $input['hashIv']);
        $this->client = $client;
    }

    public function cancelAuth($merID, $orderNo, $orderPrice, $url)
    {
        $data = [
            'mer_id' => $merID,
            'ord_no' => $orderNo,
            'amt' => $orderPrice,
            'tx_type' => 8,
        ];
        $dataJson = json_encode($data);
        $request = $this->getEnData($merID, $dataJson);
        $jsonReq = json_encode($request);

        return $this->run($jsonReq, $url.'/querytrade.php');
    }
}
