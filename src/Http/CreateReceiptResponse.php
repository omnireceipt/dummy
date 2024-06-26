<?php
/**
 * Dummy driver for Omnireceipt fiscal receipt processing library
 *
 * @link      https://github.com/omnireceipt/dummy
 * @package   omnireceipt/dummy
 * @license   MIT
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnireceipt\Dummy\Http;

use Omnireceipt\Common\Http\Response\AbstractCreateReceiptResponse;

class CreateReceiptResponse extends AbstractCreateReceiptResponse
{
    use BaseResponseTrait;

    public function isSuccessful(): bool
    {
        $payload = $this->getPayload();
        return 200 === $this->getCode() && array_key_exists('added', $payload ?: []);
    }
}
