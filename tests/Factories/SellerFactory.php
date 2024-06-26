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

use Omnireceipt\Dummy\Entities\Seller;

/**
 * @method static Seller create(string $className)
 */
class SellerFactory extends Factory
{
    const SOURCE_TYPE = 'BUILDER';

    public static function definition(): array
    {
        return [
            'uuid' => 'cb4ed5f7-8b1b-11df-be16-e04a65ecb60f',
            'name' => 'LLC "HORNS AND HOOVES"',
        ];
    }
}
