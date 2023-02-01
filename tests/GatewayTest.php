<?php

namespace Omnipay\FlashPay\Tests;

use Omnipay\FlashPay\Gateway;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    protected $gateway;

    public function setUp(): void
    {
        parent::setUp();
        //5521-9944-4400-1849
        //05/23
        //616
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->initialize([
            'HashKey' => 'hULtXjAWIHP6QDhLK1Oxp7Mi47MtPJwg',
            'HashIV' => 'JX3YbUmQYZm6ZTAZ',
            'mer_id' => 'HT00000003',
        ]);
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase([
            'transactionId' => '270397',
            'amount' => '405',
            'description' => '網路商品一批 405',
            'ord_time' => '2022-01-15 10:31:37',
            'return_url' => 'https://fl-pay.com/client_url',
            'notify_url' => 'https://fl-pay.com/return_url',
        ])->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals([
            'HashKey' => 'eppdKQ9h36nC4yk8e7B8U4aVsHF3hgxI',
            'HashIv' => 's2EKlch9pDgyFeI3',
            'mer_id' => 'TES0067999',
            'stage_id' => null,
            'sto_id' => null,
            'ord_no' => '270397',
            'ord_time' => '2022-01-15 10:31:37',
            'tx_type' => '101',
            'pay_type' => '1',
            'amt' => 405,
            'cur' => 'NTD',
            'order_desc' => '網路商品一批 405',
            'install_period' => null,
            'use_redeem' => null,
            'cell_phone_no' => null,
            'return_url' => 'https://fl-pay.com/return_url',
            'client_url' => 'https://fl-pay.com/client_url',
        ], $response->getData());
        $response->getRedirectResponse();
    }
}
