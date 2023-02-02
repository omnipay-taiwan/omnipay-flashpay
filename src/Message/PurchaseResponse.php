<?php

namespace Omnipay\FlashPay\Message;

use DateTime;
use DateTimeZone;
use Exception;
use FlashPay\Lib\Services\OrderService;
use FlashPay\Lib\Services\UtilService;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * @return false
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * @return true
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        $endpoint = $this->request->getTestMode() ? UtilService::$stageURL : UtilService::$ProdutionURL;

        return $endpoint.'/trade';
    }

    /**
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function getRedirectData()
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

    /**
     * @param  string  $output
     * @return array
     */
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

    /**
     * @param  array  $data
     * @return DateTime
     *
     * @throws Exception
     */
    private function getOrderTime(array $data)
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
