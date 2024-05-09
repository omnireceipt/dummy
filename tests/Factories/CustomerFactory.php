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

use Omnireceipt\Dummy\Entities\Customer;

/**
 * @method static Customer create(string $className)
 */
class CustomerFactory extends Factory
{
    const SOURCE_TYPE = 'BUILDER';

    public static function definition(): array
    {
        return [
            'id'    => '4a65ecb6-8b1b-11df-be16-e0cb4ed5f70f',
            'name'  => 'Alexander Arhitov',
            'email' => 'clgsru@gmail.com',
        ];
    }
}
