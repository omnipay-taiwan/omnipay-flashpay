<?php

namespace Omnipay\FlashPay\Message;

use DateTime;
use DateTimeZone;
use FlashPay\Lib\Services\OrderService;
use FlashPay\Lib\Services\UtilService;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Response
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful(): bool
    {
        return false;
    }

    public function isRedirect(): bool
    {
        return true;
    }

    public function getRedirectUrl()
    {
        $url = $this->request->getTestMode() ? UtilService::$stageURL : UtilService::$ProdutionURL;

        return $url.'/trade';
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData(): array
    {
        $data = array_filter($this->getData());

        $datetime = $this->getOrderTime($data);
        $timezone = new DateTimeZone('Asia/Taipei');
        $datetime->setTimezone($timezone);
        $data['ord_time'] = $datetime;

        $orderService = new OrderService(['hashKey' => $data['hashKey'], 'hashIv' => $data['hashIv']]);

        unset($data['hashKey'], $data['hashIv']);

        return $this->parseFormData(
            $orderService->checkout($orderService->createOrder($data), $this->getRedirectUrl())
        );
    }

    private function parseFormData(string $output)
    {
        $data = [];

        preg_match_all('/<input[^>]+>/', $output, $matches);
        foreach ($matches[0] as $input) {
            preg_match('/<input.+name="(?<name>[^"]+)" value="(?<value>[^"]+)"/', $input, $matched);
            if ($matched) {
                $data[$matched['name']] = $matched['value'];
            }
        }

        return $data;
    }

    private function getOrderTime($data): DateTime
    {
        if (empty($data['ord_time'])) {
            return new DateTime();
        }

        if (! $data['ord_time'] instanceof DateTime) {
            return new DateTime($data['ord_time']);
        }

        return $data['ord_time'];
    }
}
