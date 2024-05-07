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

use Carbon\Carbon;
use Omnireceipt\Common\AbstractGateway;
use Omnireceipt\Dummy\Entities\Customer;
use Omnireceipt\Dummy\Entities\Receipt;
use Omnireceipt\Dummy\Entities\Seller;
use Omnireceipt\Dummy\Http\CreateReceiptRequest;
use Omnireceipt\Dummy\Http\DetailsReceiptRequest;
use Omnireceipt\Dummy\Http\ListReceiptsRequest;

/**
 * @method string getKeyAccess()
 * @method string|null getKeyAccessOrNull()
 * @method self setKeyAccess(string $value)
 *
 * @method string getUserId()
 * @method string|null getUserIdOrNull()
 * @method self setUserId(string $value)
 *
 * @method string getStoreUuid()
 * @method string|null getStoreUuidOrNull()
 * @method self setStoreUuid(string $value)
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

    //########
    // Seller
    //########

    public static function classNameSeller(): string
    {
        return Seller::class;
    }

    public function getDefaultParametersSeller(): array
    {
        $properties = $this->getParameters()['default_properties']['seller'] ?? [];
        $properties['uuid'] ??= 'cb4ed5f7-8b1b-11df-be16-e04a65ecb60f';
        return $properties;
    }

    //##########
    // Customer
    //##########

    public static function classNameCustomer(): string
    {
        return Customer::class;
    }

    public function getDefaultParametersCustomer(): array
    {
        $properties = $this->getParameters()['default_properties']['customer'] ?? [];
        $properties['uuid'] ??= '4a65ecb6-8b1b-11df-be16-e0cb4ed5f70f';
        return $properties;
    }

    //#########################
    // Receipt and ReceiptItem
    //#########################

    public static function classNameReceipt(): string
    {
        return Receipt::class;
    }

    public function getDefaultParametersReceipt(): array
    {
        $properties = $this->getParameters()['default_properties']['receipt'] ?? [];

        $properties['uuid'] ??= '24b94598-000f-5000-9000-1b68e7b15f3f';
        $properties['date'] ??= Carbon::now()->toString();

        $seller = $this->getSeller();
        $properties['firm_uuid'] ??= $seller->getUuidOrNull();
        $properties['firm_name'] ??= $seller->getNameOrNull();

        $customer = $this->getCustomer();
        if ($customer) {
            $properties['client_uuid'] ??= $customer->getUuidOrNull();
            $properties['client_name'] ??= $customer->getNameOrNull();
        }

        return array_filter($properties);
    }

    public function getDefaultParametersReceiptItem(): array
    {
        $properties = $this->getParameters()['default_properties']['receipt_item'] ?? [];
        $properties['uuid'] ??= '5f3f68e7-24b9-5000-9000-45981bb1000f';
        return $properties;
    }

    //######################
    // HTTP Request Methods
    //######################

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
