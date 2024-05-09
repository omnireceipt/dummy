<?php
/**
 * Dummy driver for Omnireceipt fiscal receipt processing library
 *
 * @link      https://github.com/omnireceipt/dummy
 * @package   omnireceipt/dummy
 * @license   MIT
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnireceipt\Dummy\Tests\Feature\Http;

use Omnireceipt\Dummy\Exceptions\GatewayException;
use PHPUnit\Framework\Attributes\Depends;

class ListReceiptsTest extends HttpTestCase
{
    /**
     * @return void
     * @throws \Omnireceipt\Common\Exceptions\Parameters\ParameterValidateException
     */
    public function testMain()
    {
        $this->setMockHttpResponse('List_Successful.txt');

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
    public function testCode400()
    {
        $this->setMockHttpResponse('Response_Failure_400.txt');

        $response = $this->gateway->listReceipts();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(0, $response->getList()->count());
    }

    /**
     * @depends testMain
     * @return void
     * @throws \Omnireceipt\Common\Exceptions\Parameters\ParameterValidateException
     */
    #[Depends('testMain')]
    public function testCode402()
    {
        $this->setMockHttpResponse('Response_Failure_402.txt');

        $this->expectException(GatewayException::class);
        $this->gateway->listReceipts();
    }
}
