<?php
/**
 * Dummy driver for Omnireceipt fiscal receipt processing library
 *
 * @link      https://github.com/omnireceipt/dummy
 * @package   omnireceipt/dummy
 * @license   MIT
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnireceipt\Dummy\Entities;

use Omnireceipt\Common\Entities\Customer as BaseCustomer;

/**
 * @method string getUuid()
 * @method self setUuid(string $value)
 */
class Customer extends BaseCustomer
{
    public function getId(): string
    {
        return $this->getUuid();
    }
}
