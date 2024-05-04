<?php
/**
 * Dummy driver for Omnireceipt fiscal receipt processing library
 *
 * @link      https://github.com/omnireceipt/dummy
  * @package   omnireceipt/dummy
 * @license   MIT
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnireceipt\Dummy\Tests;

use Omnireceipt\Dummy\Entities\Customer;
use Omnireceipt\Dummy\Entities\Receipt;
use Omnireceipt\Dummy\Entities\ReceiptItem;
use Omnireceipt\Dummy\Entities\Seller;
use PHPUnit\Framework\TestCase as UnitTestCase;

abstract class TestCase extends UnitTestCase
{
    public static function makeSeller(array $parameters = []): Seller
    {
        return new Seller($parameters);
    }

    public static function makeCustomer(array $parameters = []): Customer
    {
        return new Customer($parameters);
    }

    public static function makeReceipt(array $parameters = []): Receipt
    {
        return new Receipt($parameters);
    }

    public static function makeReceiptItem(array $parameters = []): ReceiptItem
    {
        return new ReceiptItem($parameters);
    }
}
