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

use Omnireceipt\Dummy\Entities\Receipt;

/**
 * @method static Receipt create(string $className)
 */
class ReceiptFactory extends Factory
{
    const SOURCE_TYPE = 'BUILDER';

    public static function definition(): array
    {
        return [
            'uuid'       => '24b94598-000f-5000-9000-1b68e7b15f3f',
            'type'       => 'payment',
            'payment_id' => '24b94598-000f-5000-9000-1b68e7b15f3f',
            'info'       => 'Lego Bricks',
            'date'       => '2024-05-05 13:48:01',
            'asd_sdf'    => 123,
        ];
    }
}