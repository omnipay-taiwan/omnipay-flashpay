<?php

namespace PHPSTORM_META {

    /** @noinspection PhpIllegalArrayKeyTypeInspection */
    /** @noinspection PhpUnusedLocalVariableInspection */
    $STATIC_METHOD_TYPES = [
      \Omnipay\Omnipay::create('') => [
        'FlashPay' instanceof \Omnipay\FlashPay\Gateway,
      ],
      \Omnipay\Common\GatewayFactory::create('') => [
        'FlashPay' instanceof \Omnipay\FlashPay\Gateway,
      ],
    ];
}
