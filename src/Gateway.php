<?php
/**
 * Dummy driver for Omnireceipt fiscal receipt processing library
 *
 * @link      https://github.com/omnireceipt/dummy
 * @package   omnireceipt/dummy
 * @license   MIT
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnireceipt\Dummy;

use Omnireceipt\Common\AbstractGateway;
use Omnireceipt\Dummy\Entities\Customer;
use Omnireceipt\Dummy\Entities\Receipt;
use Omnireceipt\Dummy\Entities\Seller;
use Omnireceipt\Dummy\Http\CreateReceiptRequest;
use Omnireceipt\Dummy\Http\DetailsReceiptRequest;
use Omnireceipt\Dummy\Http\ListReceiptsRequest;

/**
 * @method self setKeyAccess(string $value)
 * @method string getKeyAccess()
 * @method self setUserID(string $value)
 * @method string getUserID()
 * @method self setStoreUUID(string $value)
 * @method string getStoreUUID()
 */
class Gateway extends AbstractGateway
{
    public static function rules(): array
    {
        return [
            'auth' => ['required', 'string', 'in:ok'],
        ];
    }

    public function getName(): string
    {
        return 'Dummy';
    }

    public static function classNameSeller(): string
    {
        return Seller::class;
    }

    public static function classNameCustomer(): string
    {
        return Customer::class;
    }

    public static function classNameReceipt(): string
    {
        return Receipt::class;
    }

    public static function classNameCreateReceiptRequest(): string
    {
        return CreateReceiptRequest::class;
    }

    public static function classNameListReceiptsRequest(): string
    {
        return ListReceiptsRequest::class;
    }

    public static function classNameDetailsReceiptRequest(): string
    {
        return DetailsReceiptRequest::class;
    }
}
