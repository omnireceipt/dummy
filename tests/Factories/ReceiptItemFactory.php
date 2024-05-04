<?php
/**
 * Dummy driver for Omnireceipt fiscal receipt processing library
 *
 * @link      https://github.com/omnireceipt/dummy
 * @package   omnireceipt/dummy
 * @license   MIT
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnireceipt\Dummy\Tests\Factories;

use Omnireceipt\Dummy\Entities\ReceiptItem;

/**
 * @method static ReceiptItem create(string $className)
 */
class ReceiptItemFactory extends Factory
{
    const SOURCE_TYPE = 'BUILDER';

    public static function definition(): array
    {
        return [
            'asd_sdf' => 123,
            'name' => 'FLAG, W/ 2 HOLDERS, NO. 22',
            'code' => '6446963/104515',
            'type' => 'product',
            'amount' => 2.12,
            'currency' => 'USD',
            'quantity' => 2,
            'unit' => 'pc',
            'tax' => 0,
        ];
    }
}
