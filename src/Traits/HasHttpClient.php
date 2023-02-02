<?php

namespace Omnipay\FlashPay\Traits;

use FlashPay\Lib\Services\FeedbackService;
use Omnipay\Common\Exception\InvalidRequestException;

trait HasHttpClient
{
    public function run($request, $url)
    {
        $response = $this->client->request('POST', $url, ['Content-Type' => 'application/json'], $request);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidRequestException($response->getReasonPhrase());
        }

        $returnDate = (string) $response->getBody();
        $feedback = new FeedbackService($this->getHashKey(), $this->getHashIv(), $returnDate);

        return $feedback->getRetrunJson();
    }
}
