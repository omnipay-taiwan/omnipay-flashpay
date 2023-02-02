<?php

namespace FlashPay\Lib\Services;

use Exception;
use FlashPay\Lib\obj\AesObj;
use FlashPay\Lib\obj\CurlObj;

class QueryOrderService extends AesObj
{
    public function __construct($input)
    {
        parent::__construct($input['hashKey'], $input['hashIv']);
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

    public function run($request, $url)
    {
        $curl = new CurlObj();
        $curl->setHeaders('Content-Type: application/json');
        $returnDate = $curl->run($request, $url);
        $feeback = new FeedbackService($this->getHashKey(), $this->getHashIv(), $returnDate);

        return $feeback->getRetrunJson();
    }
}
