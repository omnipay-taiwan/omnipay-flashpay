<?php

namespace Omnipay\FlashPay\Message;

use DateTime;
use Exception;
use FlashPay\Lib\Services\OrderService;
use FlashPay\Lib\Services\UtilService;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

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
        return $this->request->getTestMode()
            ? UtilService::$stageURL
            : UtilService::$ProdutionURL;
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * @throws Exception
     */
    public function getRedirectResponse()
    {
        $this->validateRedirect();
        $data = $this->data;

        if (! $data['ord_time'] instanceof DateTime) {
            $data['ord_time'] = new DateTime($data['ord_time']);
        }

        $orderService = new OrderService([
            'hashKey' => $data['HashKey'],
            'hashIv' => $data['HashIv'],
        ]);

        unset($data['HashKey'], $data['HashIv']);

        return new HttpResponse(
            $orderService->checkout($orderService->createOrder($data), $this->getRedirectUrl())
        );
    }
}
