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

use Omnireceipt\Common\Exceptions\Parameters\ParameterValidateException;
use Omnireceipt\Dummy\Tests\Factories\CustomerFactory;
use Omnireceipt\Dummy\Tests\Factories\ReceiptFactory;
use Omnireceipt\Dummy\Tests\Factories\ReceiptItemFactory;
use Omnireceipt\Dummy\Tests\Factories\SellerFactory;

class CreateReceiptTest extends FixtureTestCase
{
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

        $response = $this->gateway->createReceipt($receipt, seller: $seller);

        $this->assertTrue($response->isSuccessful());
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
