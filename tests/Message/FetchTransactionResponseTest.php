<?php

namespace Omnipay\FlashPay\Tests\Message;

use Mockery as m;
use Omnipay\FlashPay\Message\FetchTransactionRequest;
use Omnipay\FlashPay\Message\FetchTransactionResponse;
use PHPUnit\Framework\TestCase;

class FetchTransactionResponseTest extends TestCase
{
    /**
     * @dataProvider orderStatusProvider
     */
    public function testOrderStatus($code, $expected)
    {
        $this->assertOrderStatus($code, $expected);
    }

    protected function orderStatusProvider()
    {
        return [
            ['00', 'pending'],
            ['ZP', 'pending'],
            ['02', 'successful'],
            ['ZF', 'failed'],
            ['12', 'cancel'],
            ['03', 'successful'],
            ['04', 'successful'],
            ['06', 'cancel'],
            ['08', 'cancel'],
        ];
    }

    private function assertOrderStatus($orderStatus, $expected): void
    {
        $request = m::mock(FetchTransactionRequest::class);
        $response = new FetchTransactionResponse($request, ['order_status' => $orderStatus]);

        if ($expected === 'successful') {
            self::assertTrue($response->isSuccessful());
        } else {
            self::assertFalse($response->isSuccessful());
        }

        if ($expected === 'failed') {
            self::assertFalse($response->isSuccessful());
        }

        if ($expected === 'pending') {
            self::assertTrue($response->isPending());
        } else {
            self::assertFalse($response->isPending());
        }

        if ($expected === 'cancel') {
            self::assertTrue($response->isCancelled());
        } else {
            self::assertFalse($response->isCancelled());
        }
    }
}
