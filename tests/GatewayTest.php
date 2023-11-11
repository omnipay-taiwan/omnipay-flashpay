<?php

namespace Omnipay\FlashPay\Tests;

use FlashPay\Lib\obj\AesObj;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\FlashPay\Gateway;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    protected $gateway;

    public function setUp(): void
    {
        parent::setUp();
//        $this->gateway = new Gateway(new Client(new \Http\Client\Curl\Client()), $this->getHttpRequest());
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->initialize([
            'HashKey' => 'hULtXjAWIHP6QDhLK1Oxp7Mi47MtPJwg',
            'HashIV' => 'JX3YbUmQYZm6ZTAZ',
            'mer_id' => 'HT00000003',
        ]);
    }

    public function testPurchase()
    {
        // 5521-9944-4400-1849
        // 05/23
        // 616

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
            'hashKey' => $this->gateway->getHashKey(),
            'hashIv' => $this->gateway->getHashIv(),
            'mer_id' => $this->gateway->getMerId(),
            'stage_id' => null,
            'sto_id' => null,
            'ord_no' => '270397',
            'ord_time' => '2022-01-15 10:31:37',
            'tx_type' => '101',
            'pay_type' => '1',
            'amt' => '405',
            'cur' => 'NTD',
            'order_desc' => '網路商品一批 405',
            'install_period' => null,
            'use_redeem' => null,
            'cell_phone_no' => null,
            'return_url' => 'https://fl-pay.com/return_url',
            'client_url' => 'https://fl-pay.com/client_url',
        ], $response->getData());
        $redirectData = ($response->getRedirectData());
        self::assertArrayHasKey('ver', $redirectData);
        self::assertArrayHasKey('mid', $redirectData);
        self::assertArrayHasKey('dat', $redirectData);
        self::assertArrayHasKey('key', $redirectData);
        self::assertArrayHasKey('chk', $redirectData);
    }

    public function testAcceptNotification()
    {
        $options = $this->encrypt([
            'ver' => '1.0.0',
            'tx_type' => 104,
            'mer_id' => $this->gateway->getMerId(),
            'sto_id' => '',
            'order_no' => 'FP2302020600004133',
            'order_status' => '02',
            'ord_no' => '11245678',
            'ord_time' => '2023-02-02 06:23:08',
            'ret_code' => '00',
            'ret_msg' => '交易成功(Approved or completed successfully',
            'purchase_date' => '2023-02-02 06:23:49',
            'auth_id_resp' => '952931',
            'amt' => '0.00',
            'tx_amt' => '100.00',
            'tot_pay_amt' => '0.00',
            'install_period' => '0',
            'use_redeem' => '',
            'return_url' => 'https://fl-pay.com/return_url',
            'client_url' => 'https://fl-pay.com/client_url',
            'pay_type' => '0',
        ]);

        $this->getHttpRequest()->request->add($options);
        $request = $this->gateway->acceptNotification();
        self::assertEquals('交易成功(Approved or completed successfully', $request->getMessage());
        self::assertEquals(NotificationInterface::STATUS_COMPLETED, $request->getTransactionStatus());
        self::assertEquals('FP2302020600004133', $request->getTransactionReference());

        self::assertEquals('11245678', $request->getTransactionId());
        self::assertEquals('FP2302020600004133', $request->getTransactionReference());
        self::assertEquals('1|OK', $request->getReply());
    }

    public function testFetchTransaction()
    {
        $this->getMockClient()->addResponse($this->getMockHttpResponse('FetchTransaction.txt'));

        $options = ['transactionId' => '120'];

        $response = $this->gateway->fetchTransaction($options)->send();
        self::assertTrue($response->isSuccessful());
        self::assertEquals('02', $response->getCode());
        self::assertEquals('120', $response->getTransactionId());
        self::assertEquals('FP2302020600004133', $response->getTransactionReference());
        self::assertEquals('交易成功(Approved or completed successfully', $response->getMessage());
    }

    public function testVoid()
    {
        $this->getMockClient()->addResponse($this->getMockHttpResponse('Void.txt'));
        $options = ['transactionId' => '120', 'amount' => '100'];

        $response = $this->gateway->void($options)->send();
        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isPending());
        self::assertFalse($response->isRedirect());
    }

    public function testVoidError()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('交易資料狀態不符');

        $this->getMockClient()->addResponse($this->getMockHttpResponse('VoidError.txt'));
        $options = ['transactionId' => '120', 'amount' => '100'];

        $this->gateway->void($options)->send();
    }

    private function encrypt($input)
    {
        $hashKey = $this->gateway->getHashKey();
        $hashIv = $this->gateway->getHashIv();
        $merId = $this->gateway->getMerId();

        $AES = new AesObj($hashKey, $hashIv);
        $encrypt = $AES->getEnData($merId, json_encode($input));
        unset($encrypt['mid'], $encrypt['key']);

        return $encrypt;
    }
}
