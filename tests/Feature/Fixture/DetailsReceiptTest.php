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

use Omnireceipt\Dummy\Entities\Receipt;
use PHPUnit\Framework\Attributes\Depends;

class DetailsReceiptTest extends FixtureTestCase
{
    /**
     * @return void
     * @throws \Omnireceipt\Common\Exceptions\Parameters\ParameterValidateException
     */
    public function testMain()
    {
        $uuid = 'details-2da5c87d-0384-50e8-a7f3-8d5646dd9e10';
        $response = $this->gateway->detailsReceipt($uuid);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->getCode());
        $this->assertInstanceOf(Receipt::class, $response->getReceipt());
        $this->assertEquals($uuid, $response->getReceipt()->getId());
    }

    /**
     * @depends testMain
     * @return void
     * @throws \Omnireceipt\Common\Exceptions\Parameters\ParameterValidateException
     */
    #[Depends('testMain')]
    public function test404()
    {
        $uuid = 'notfound-2da5c87d-0384-50e8-a7f3-8d5646dd9e10';
        $response = $this->gateway->detailsReceipt($uuid);

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(404, $response->getCode());
        $this->assertNull($response->getReceipt());
    }
}
