<?php

namespace Omnipay\FlashPay\Traits;

use FlashPay\Lib\Services\FeedbackService;
use Http\Client\Exception;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;

trait HasHttpClient
{
    /**
     * @throws InvalidRequestException
     * @throws Exception
     * @throws \Exception
     */
    public function run($request, $url)
    {
        $response = $this->client->request('POST', $url, ['Content-Type' => 'application/json'], $request);
        if ($response->getStatusCode() !== 200) {
            throw new InvalidResponseException($response->getReasonPhrase());
        }

        $returnDate = (string) $response->getBody();
        $feedback = new FeedbackService($this->getHashKey(), $this->getHashIv(), $returnDate);

        return $feedback->getRetrunJson();
    }
}
