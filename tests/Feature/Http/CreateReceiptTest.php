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

use Omnireceipt\Common\Exceptions\Parameters\ParameterValidateException;
use Omnireceipt\Dummy\Tests\Factories\CustomerFactory;
use Omnireceipt\Dummy\Tests\Factories\ReceiptFactory;
use Omnireceipt\Dummy\Tests\Factories\ReceiptItemFactory;
use Omnireceipt\Dummy\Tests\Factories\SellerFactory;
use PHPUnit\Framework\Attributes\Depends;

class CreateReceiptTest extends HttpTestCase
{
    /**
     * @return void
     * @throws \Omnireceipt\Common\Exceptions\Parameters\ParameterValidateException
     */
    public function testMain()
    {
        $receipt = $this->gateway->receiptFactory(
            ReceiptFactory::definition(),
            ReceiptItemFactory::definition(),
        );

        $customer = $this->gateway->customerFactory(
            CustomerFactory::definition(),
        );
        $receipt->setCustomer($customer);

        $seller = $this->gateway->sellerFactory(
            SellerFactory::definition(),
        );

        $this->setMockHttpResponse('Create_Successful.txt');

        $response = $this->gateway->createReceipt($receipt, seller: $seller);

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @depends testMain
     * @return void
     * @throws \Omnireceipt\Common\Exceptions\Parameters\ParameterValidateException
     */
    #[Depends('testMain')]
    public function testCode400()
    {
        $receipt = $this->gateway->receiptFactory(
            ReceiptFactory::definition(),
            ReceiptItemFactory::definition(),
        );

        $customer = $this->gateway->customerFactory(
            CustomerFactory::definition(),
        );
        $receipt->setCustomer($customer);

        $seller = $this->gateway->sellerFactory(
            SellerFactory::definition(),
        );

        $this->setMockHttpResponse('Response_Failure_400.txt');

        $response = $this->gateway->createReceipt($receipt, seller: $seller);

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(400, $response->getCode());
    }

    /**
     * @depends testMain
     * @return void
     */
    #[Depends('testMain')]
    public function testFailure()
    {
        $receipt = $this->gateway->receiptFactory(
            [],
            [],
        );

        $this->expectException(ParameterValidateException::class);
        $this->gateway->createReceipt($receipt);
    }
}
