<?php
/**
 * Dummy driver for Omnireceipt fiscal receipt processing library
 *
 * @link      https://github.com/omnireceipt/dummy
 * @package   omnireceipt/dummy
 * @license   MIT
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnireceipt\Dummy\Tests\Unit;

use Doctrine\Common\Collections\ArrayCollection;
use Omnireceipt\Common\AbstractGateway;
use Omnireceipt\Dummy\Entities\Customer;
use Omnireceipt\Dummy\Entities\Receipt;
use Omnireceipt\Dummy\Entities\Seller;
use Omnireceipt\Common\Exceptions\Parameters\ParameterValidateException;
use Omnireceipt\Common\Exceptions\RuntimeException;
use Omnireceipt\Common\Supports\Helper;
use Omnireceipt\Dummy\Tests\Factories\CustomerFactory;
use Omnireceipt\Dummy\Tests\Factories\ReceiptFactory;
use Omnireceipt\Dummy\Tests\Factories\ReceiptItemFactory;
use Omnireceipt\Dummy\Tests\Factories\SellerFactory;
use Omnireceipt\Dummy\Entities\ReceiptItem;
use Omnireceipt\Dummy\Tests\Fixtures\FixtureTrait;
use Omnireceipt\Dummy\Tests\TestCase;
use Omnireceipt\Omnireceipt;
use PHPUnit\Framework\Attributes\Depends;

class GatewayTest extends TestCase
{
    use FixtureTrait;

    public function testBase()
    {
        $omnireceipt = $this->createOmnireceipt(false);

        $this->assertInstanceOf(AbstractGateway::class, $omnireceipt);

        $this->assertEquals('Dummy', $omnireceipt->getName());
        $this->assertEquals('Dummy', $omnireceipt->getShortName());
        $this->assertIsArray($omnireceipt->getDefaultParameters());
        $this->assertEmpty($omnireceipt->getDefaultParameters());
        $this->assertIsArray($omnireceipt->getParameters());
        $this->assertEmpty($omnireceipt->getParameters());
        $this->assertFalse($omnireceipt->validate());

        $omnireceipt->initialize($this->fixtureAsArray('config_fixture'));
        $this->assertNotEmpty($omnireceipt->getParameters());
        $this->assertTrue($omnireceipt->validate());

        // Customer
        $customerDefinition = CustomerFactory::definition();
        $customer = $omnireceipt->customerFactory($customerDefinition);
        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals($customerDefinition['name'], $customer->getName());
        $this->assertTrue($customer->validate());

        // Seller
        $seller = $omnireceipt->sellerFactory(
            SellerFactory::definition(),
        );
        $this->assertInstanceOf(Seller::class, $seller);

        $this->assertTrue($seller->validate());

        // Receipt
        $receipt = $omnireceipt->receiptFactory(
            ReceiptFactory::definition(),
            array_merge(
                ReceiptItemFactory::definition(),
                ['amount' => 2.12],
            ),
            array_merge(
                ReceiptItemFactory::definition(),
                ['amount' => 1.54],
            ),
        );
        $this->assertInstanceOf(Receipt::class, $receipt);
        $this->assertFalse($receipt->validate());
        $this->assertArrayHasKey('customer', $receipt->validateLastError()['parameters'] ?? []);

        $receipt->setCustomer(
            $omnireceipt->customerFactory()
        );
        $this->assertTrue($receipt->validate());
        $this->assertEquals(3.66, $receipt->getAmount());
        $this->assertEquals(2, $receipt->getItemList()->count());
    }

    /**
     * @depends testBase
     * @return void
     */
    #[Depends('testBase')]
    public function testBaseException()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Class "\Omnireceipt\Qwe\Gateway" not found');

        Omnireceipt::create('Qwe');
    }

    /**
     * @depends testBase
     * @return void
     */
    #[Depends('testBase')]
    public function testInitialize()
    {
        $omnireceipt = $this->createOmnireceipt();

        $initializeData = [
            'auth'    => 'ok',
            'fixture' => true,
            'qwe_asd' => 123,
        ];

        $omnireceipt->initialize($initializeData);

        foreach ($initializeData as $key => $value) {
            $method = Helper::getGetterMethodName($key);
            $this->assertEquals($value, $omnireceipt->$method());
        }
    }

    /**
     * @depends testInitialize
     * @return void
     * @throws ParameterValidateException
     */
    #[Depends('testInitialize')]
    public function testCreateReceipt()
    {
        $omnireceipt = $this->createOmnireceipt();

        /** @var \Omnireceipt\Dummy\Entities\Receipt $receipt */
        $receipt = ReceiptFactory::create($omnireceipt->classNameReceipt());
        $receipt->setUuid('0ecab77f-7062-4a5f-aa20-35213db1397c');

        $receipt->setCustomer(
            CustomerFactory::create(
                $omnireceipt->classNameCustomer(),
            ),
        );

        /** @var ReceiptItem $receiptItem */
        $receiptItem = ReceiptItemFactory::create($omnireceipt->classNameReceiptItem());
        $receipt->addItem($receiptItem);

        $this->assertTrue($receipt->validate());

        $classNameSeller = $omnireceipt->classNameSeller();
        $seller = new $classNameSeller([
            'address' => 'www.example.com',
        ]);

        $response = $omnireceipt->createReceipt(
            $receipt,
            [
                'qwe' => 'qwe',
            ],
            seller: $seller,
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getData());
        $this->assertEquals(200, $response->getCode());
    }

    /**
     * @depends testInitialize
     * @return void
     * @throws ParameterValidateException
     */
    #[Depends('testInitialize')]
    public function testCreateReceiptTwo()
    {
        $omnireceipt = $this->createOmnireceipt();

        /** @var \Omnireceipt\Dummy\Entities\Receipt $receipt */
        $receipt = ReceiptFactory::create($omnireceipt->classNameReceipt());
        $receipt->setUuid('0ecab77f-7062-4a5f-aa20-35213db1397c');

        $receipt->setCustomer(
            CustomerFactory::create(
                $omnireceipt->classNameCustomer(),
            ),
        );

        /** @var ReceiptItem $receiptItem */
        $receiptItem = ReceiptItemFactory::create($omnireceipt->classNameReceiptItem());
        $receipt->addItem($receiptItem);

        $this->assertTrue($receipt->validate());

        $response = $omnireceipt->createReceipt(
            $receipt,
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getData());
        $this->assertEquals(200, $response->getCode());
    }

    /**
     * @depends testInitialize
     * @return void
     * @throws ParameterValidateException
     */
    #[Depends('testInitialize')]
    public function testListReceipts()
    {
        $omnireceipt = $this->createOmnireceipt();

        $response = $omnireceipt->listReceipts([
            'date_from' => '2024-05-05 00:00:00',
            'date_to' => '2024-05-05 23:59:59',
        ]);

        $this->assertEquals(200, $response->getCode());

        $list = $response->getList();
        $this->assertInstanceOf(ArrayCollection::class, $list);
        $this->assertEquals(1, $list->count());

        $answer = $response->getData();
        $this->assertIsArray($answer);
        $this->assertCount(1, $answer);
    }

    /**
     * @depends testInitialize
     * @return void
     * @throws \Omnireceipt\Common\Exceptions\Parameters\ParameterValidateException
     */
    #[Depends('testInitialize')]
    public function testListReceiptsUseDefaultParameters()
    {
        $omnireceipt = $this->createOmnireceipt();

        $omnireceipt->listReceipts();
        $this->assertTrue(true);
    }

    /**
     * @depends testListReceipts
     * @return void
     * @throws ParameterValidateException
     */
    #[Depends('testListReceipts')]
    public function testListReceiptsNotFound()
    {
        $omnireceipt = $this->createOmnireceipt();

        $response = $omnireceipt->listReceipts([
            'date_from' => '2049-08-25 00:00:00',
            'date_to' => '2049-08-25 23:59:59',
        ]);

        $this->assertEquals(404, $response->getCode());

        $list = $response->getList();
        $this->assertInstanceOf(ArrayCollection::class, $list);
        $this->assertEquals(0, $list->count());

        $answer = $response->getData();
        $this->assertNull($answer);
    }

    /**
     * @depends testInitialize
     * @return void
     * @throws ParameterValidateException
     */
    #[Depends('testInitialize')]
    public function testDetailsReceipt()
    {
        $omnireceipt = $this->createOmnireceipt();

        $uuid = 'details-2da5c87d-0384-50e8-a7f3-8d5646dd9e10';
        $response = $omnireceipt->detailsReceipt($uuid);
        $this->assertTrue($response->isSuccessful());
        $receipt = $response->getReceipt();
        $this->assertInstanceOf(Receipt::class, $receipt);
        $this->assertEquals($uuid, $receipt->getUuid());
        $this->assertEquals($uuid, $receipt->getId());
        $this->assertNotEmpty($receipt->getDate());
        $answer = $response->getData();
        $this->assertIsArray($answer);
        $this->assertEquals($uuid, $answer['uuid']);
    }

    /**
     * @depends testDetailsReceipt
     * @return void
     * @throws ParameterValidateException
     */
    #[Depends('testDetailsReceipt')]
    public function testDetailsReceiptNotFound()
    {
        $omnireceipt = $this->createOmnireceipt();

        $id = 'not-found';
        $response = $omnireceipt->detailsReceipt($id);
        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getData());
    }
}
