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

use Omnireceipt\Common\Entities\ReceiptItem as BaseReceiptItem;

/**
 *
 * @method string getCode()
 * @method string|null getCodeOrNull()
 * @method self setCode(string $value)
 */
class ReceiptItem extends BaseReceiptItem
{
}
