<?php
/**
 * Dummy driver for Omnireceipt fiscal receipt processing library
 *
 * @link      https://github.com/omnireceipt/dummy
 * @package   omnireceipt/dummy
 * @license   MIT
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnireceipt\Dummy\Tests\Feature\Fixture;

use Carbon\Carbon;
use Omnireceipt\Dummy\Entities\Receipt;
use PHPUnit\Framework\Attributes\Depends;

class ListReceiptsTest extends FixtureTestCase
{
    /**
     * @return void
     * @throws \Omnireceipt\Common\Exceptions\Parameters\ParameterValidateException
     */
    public function testMain()
    {
        $response = $this->gateway->listReceipts();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(3, $response->getList()->count());
    }

    /**
     * @depends testMain
     * @return void
     * @throws \Omnireceipt\Common\Exceptions\Parameters\ParameterValidateException
     */
    #[Depends('testMain')]
    public function testFilterDateOk()
    {
        $options = [
            'date_from' => '2024-05-09 00:00:00',
            'date_to'   => '2024-05-09 23:59:59',
        ];
        $response = $this->gateway->listReceipts($options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(2, $response->getList()->count());

        /** @var Receipt $receiptPending */
        $receiptPending = $response->getList()
                                   ->filter(fn (Receipt $receipt) => $receipt->getId() === 'pending-2da5c87d-0384-50e8-a7f3-8d5646dd9e10')
                                   ->first();

        $this->assertInstanceOf(Receipt::class, $receiptPending);
        $this->assertTrue(Carbon::parse($receiptPending->getDate())->gte(Carbon::parse($options['date_from'])));
        $this->assertTrue(Carbon::parse($receiptPending->getDate())->lte(Carbon::parse($options['date_to'])));

        /** @var Receipt $receiptSucceeded */
        $receiptSucceeded = $response->getList()
                                     ->filter(fn (Receipt $receipt) => $receipt->getId() === 'succeeded-2da5c87d-0384-50e8-a7f3-8d5646dd9e10')
                                     ->first();

        $this->assertInstanceOf(Receipt::class, $receiptSucceeded);
        $this->assertTrue(Carbon::parse($receiptPending->getDate())->gte(Carbon::parse($options['date_from'])));
        $this->assertTrue(Carbon::parse($receiptPending->getDate())->lte(Carbon::parse($options['date_to'])));
    }

    /**
     * @depends testMain
     * @return void
     * @throws \Omnireceipt\Common\Exceptions\Parameters\ParameterValidateException
     */
    #[Depends('testMain')]
    public function testFilterDateEmpty()
    {
        $options = [
            'date_from' => '2024-04-09 00:00:00',
            'date_to'   => '2024-04-09 23:59:59',
        ];
        $response = $this->gateway->listReceipts($options);

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(0, $response->getList()->count());
    }
}
