<?php

namespace Omnipay\FlashPay\Services;

use FlashPay\Lib\Services\FeedbackService;
use Omnipay\Common\Http\Client;

class QueryOrderService extends \FlashPay\Lib\Services\QueryOrderService
{
    /** @var Client */
    private $client;

    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    public function run($request, $url)
    {
        $response = $this->client->request('POST', $url, ['Content-Type' => 'application/json'], $request);
        $returnDate = (string) $response->getBody();
        $feedback = new FeedbackService($this->getHashKey(), $this->getHashIv(), $returnDate);

        return $feedback->getRetrunJson();
    }
}
