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
use Omnireceipt\Dummy\Gateway;
use Omnireceipt\Dummy\Tests\TestCase;
use Omnireceipt\Omnireceipt;
use PHPUnit\Framework\Attributes\Depends;

class GatewayTest extends TestCase
{
    public function testBase()
    {
        $omnireceipt = self::createOmnireceipt(false);

        $this->assertInstanceOf(AbstractGateway::class, $omnireceipt);

        $this->assertEquals('Dummy', $omnireceipt->getName());
        $this->assertEquals('Dummy', $omnireceipt->getShortName());
        $this->assertIsArray($omnireceipt->getDefaultParameters());
        $this->assertEmpty($omnireceipt->getDefaultParameters());
        $this->assertIsArray($omnireceipt->getParameters());
        $this->assertEmpty($omnireceipt->getParameters());
        $this->assertFalse($omnireceipt->validate());

        $omnireceipt->initialize(['auth' => 'ok']);
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
        $omnireceipt = (self::createOmnireceipt())
                       ->initialize(['auth' => 'ok']);

        $initializeData = self::initializeData();

        $this->assertNotEmpty($initializeData);

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
        $omnireceipt = (self::createOmnireceipt())
                       ->initialize(['auth' => 'ok']);

        /** @var \Omnireceipt\Dummy\Entities\Receipt $receipt */
        $receipt = ReceiptFactory::create($omnireceipt::classNameReceipt());
        $receipt->setUuid('0ecab77f-7062-4a5f-aa20-35213db1397c');
        $receipt->setDocNum('ТД00-000001');

        $receipt->setCustomer(
            CustomerFactory::create(
                $omnireceipt::classNameCustomer(),
            ),
        );

        /** @var ReceiptItem $receiptItem */
        $receiptItem = ReceiptItemFactory::create($omnireceipt::classNameReceiptItem());
        $receiptItem->setVatRate(0);
        $receiptItem->setVatSum(0);
        $receipt->addItem($receiptItem);

        $this->assertTrue($receipt->validate());

        $classNameSeller = $omnireceipt::classNameSeller();
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
        $omnireceipt = (self::createOmnireceipt())
                       ->initialize(['auth' => 'ok']);

        /** @var \Omnireceipt\Dummy\Entities\Receipt $receipt */
        $receipt = ReceiptFactory::create($omnireceipt::classNameReceipt());
        $receipt->setUuid('0ecab77f-7062-4a5f-aa20-35213db1397c');
        $receipt->setDocNum('ТД00-000001');

        $receipt->setCustomer(
            CustomerFactory::create(
                $omnireceipt::classNameCustomer(),
            ),
        );

        /** @var ReceiptItem $receiptItem */
        $receiptItem = ReceiptItemFactory::create($omnireceipt::classNameReceiptItem());
        $receiptItem->setVatRate(0);
        $receiptItem->setVatSum(0);
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
        $omnireceipt = (self::createOmnireceipt())
                       ->initialize(['auth' => 'ok']);

        $response = $omnireceipt->listReceipts([
            'date_from' => '2016-08-25 00:00:00',
            'date_to' => '2016-08-25 23:59:59',
            'deleted' => false,
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
     */
    #[Depends('testInitialize')]
    public function testListReceiptsUseDefaultParameters()
    {
        $omnireceipt = (self::createOmnireceipt())
            ->initialize(['auth' => 'ok']);

        try {
            $omnireceipt->listReceipts();
            $this->fail('Exception didn\'t work');
        } catch (ParameterValidateException $exception) {
            $this->assertIsArray($exception->error);
            $this->assertIsArray($exception->error['parameters']);
            $this->assertArrayHasKey('date_from', $exception->error['parameters']);
            $this->assertArrayHasKey('date_to', $exception->error['parameters']);
            $this->assertArrayNotHasKey('deleted', $exception->error['parameters']);
        }
    }

    /**
     * @depends testListReceipts
     * @return void
     * @throws ParameterValidateException
     */
    #[Depends('testListReceipts')]
    public function testListReceiptsNotFound()
    {
        $omnireceipt = self::createOmnireceipt();

        $response = $omnireceipt->listReceipts([
            'date_from' => '2049-08-25 00:00:00',
            'date_to' => '2049-08-25 23:59:59',
            'deleted' => false,
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
        $omnireceipt = self::createOmnireceipt();

        $uuid = 'pending-2da5c87d-0384-50e8-a7f3-8d5646dd9e10';
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
    public function testDetailsReceiptPending()
    {
        $omnireceipt = self::createOmnireceipt();

        $id = 'pending-2da5c87d-0384-50e8-a7f3-8d5646dd9e10';
        $response = $omnireceipt->detailsReceipt($id);
        $this->assertTrue($response->isSuccessful());

        /** @var \Omnireceipt\Dummy\Entities\Receipt $receipt */
        $receipt = $response->getReceipt();
        $this->assertTrue($receipt->isPending());
        $this->assertFalse($receipt->isSuccessful());
        $this->assertFalse($receipt->isCancelled());
        $this->assertEquals('pending', $receipt->getState());
    }

    /**
     * @depends testDetailsReceipt
     * @return void
     * @throws ParameterValidateException
     */
    #[Depends('testDetailsReceipt')]
    public function testDetailsReceiptSuccessful()
    {
        $omnireceipt = self::createOmnireceipt();

        $id = 'succeeded-2da5c87d-0384-50e8-a7f3-8d5646dd9e10';
        $response = $omnireceipt->detailsReceipt($id);
        $this->assertTrue($response->isSuccessful());

        /** @var \Omnireceipt\Dummy\Entities\Receipt $receipt */
        $receipt = $response->getReceipt();
        $this->assertFalse($receipt->isPending());
        $this->assertTrue($receipt->isSuccessful());
        $this->assertFalse($receipt->isCancelled());
        $this->assertEquals('succeeded', $receipt->getState());
    }

    /**
     * @depends testDetailsReceipt
     * @return void
     * @throws ParameterValidateException
     */
    #[Depends('testDetailsReceipt')]
    public function testDetailsReceiptCancelled()
    {
        $omnireceipt = self::createOmnireceipt();

        $id = 'canceled-2da5c87d-0384-50e8-a7f3-8d5646dd9e10';
        $response = $omnireceipt->detailsReceipt($id);
        $this->assertTrue($response->isSuccessful());

        /** @var \Omnireceipt\Dummy\Entities\Receipt $receipt */
        $receipt = $response->getReceipt();
        $this->assertFalse($receipt->isPending());
        $this->assertFalse($receipt->isSuccessful());
        $this->assertTrue($receipt->isCancelled());
        $this->assertEquals('canceled', $receipt->getState());
    }

    /**
     * @depends testDetailsReceipt
     * @return void
     * @throws ParameterValidateException
     */
    #[Depends('testDetailsReceipt')]
    public function testDetailsReceiptNotFound()
    {
        $omnireceipt = self::createOmnireceipt();

        $id = 'not-found';
        $response = $omnireceipt->detailsReceipt($id);
        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getData());
    }

    public static function initializeData(): array
    {
        return [
            'keyAccess' => 'KeyAccess-123',
            'userID' => 'UserID-123',
            'storeUUID' => 'StoreUUID-123',
            'qwe_qwe' => 'StoreUUID-123',
        ];
    }

    protected static function createOmnireceipt(bool $initialize = true): Gateway
    {
        /** @var  $omnireceipt */
        $omnireceipt = Omnireceipt::create('Dummy');
        if ($initialize) {
            $omnireceipt->initialize(['auth' => 'ok']);
        }
        return $omnireceipt;
    }
}
