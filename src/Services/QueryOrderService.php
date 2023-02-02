<?php

namespace Omnipay\FlashPay\Services;

use Exception;
use FlashPay\Lib\obj\AesObj;
use Omnipay\Common\Http\Client;
use Omnipay\FlashPay\Traits\HasHttpClient;

class QueryOrderService extends AesObj
{
    use HasHttpClient;

    /** @var Client */
    private $client;

    public function __construct($client, $input)
    {
        parent::__construct($input['hashKey'], $input['hashIv']);
        $this->client = $client;
    }

    public function queryOrder($merID, $orderNo, $url)
    {
        $data = [
            'mer_id' => $merID,
            'ord_no' => $orderNo,
            'tx_type' => 107,
        ];
        $dataJson = json_encode($data);
        $request = $this->getEnData($merID, $dataJson);
        $jsonReq = json_encode($request);

        return $this->run($jsonReq, $url.'/querytrade.php');
    }

    public function queryMultiOrder($merID, $beginDate, $endDate, $url)
    {
        $diffDay = $endDate->diff($beginDate)->format('%a');
        if ($diffDay > 30) {
            throw new Exception('The number of days cannot be greater than 30 days');
        }
        $data = [
            'mer_id' => $merID,
            'start_date' => $beginDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'tx_type' => 106,
        ];
        $dataJson = json_encode($data);
        $request = $this->getEnData($merID, $dataJson);
        $jsonReq = json_encode($request);

        return $this->run($jsonReq, $url.'/querytrade.php');
    }
}
