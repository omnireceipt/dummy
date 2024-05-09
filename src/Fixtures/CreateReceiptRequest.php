<?php
/**
 * Dummy driver for Omnireceipt fiscal receipt processing library
 *
 * @link      https://github.com/omnireceipt/dummy
 * @package   omnireceipt/dummy
 * @license   MIT
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnireceipt\Dummy\Fixtures;

use Omnireceipt\Common\Http\Request\AbstractCreateReceiptRequest;
use Omnireceipt\Common\Http\Response\AbstractResponse;
use Omnireceipt\Dummy\Entities\Receipt;
use Omnireceipt\Dummy\Entities\ReceiptItem;

class CreateReceiptRequest extends AbstractCreateReceiptRequest
{
    static public function rules(): array
    {
        return [];
    }

    public function getData(): array
    {
        /** @var Receipt $receipt */
        $receipt = $this->getReceipt();

        $receipt->validateOrFail();

        $goods = [];
        /** @var ReceiptItem $item */
        foreach ($receipt->getItemList() as $item) {
            $goods[] = [
                'uuid'     => $receipt->getUuid(),
                'name'     => $item->getName(),
                'code'     => $item->getCode(),
                'type'     => $item->getType(),
                'quantity' => $item->getQuantity(),
                'price'    => $item->getAmount() / $item->getQuantity(),
                'amount'   => $item->getAmount(),
                'currency' => $item->getCurrency(),
                'unit'     => $item->getUnit(),
                'tax'      => $item->getTax(),
            ];
        }

        return [
            'uuid'         => $receipt->getUuid(),
            'date'         => $receipt->getDate(),
            'payment_id'   => $receipt->getPaymentId(),
            'client_uuid'  => $receipt->getCustomer()->getId(),
            'client_name'  => $receipt->getCustomer()->getName(),
            'client_email' => $receipt->getCustomer()->getEmail(),
            'amount'       => $receipt->getAmount(),
            'info'         => $receipt->getInfo(),
            'firm_address' => $receipt->getSeller()->getAddressOrNull(),
            'goods'        => $goods,
        ];
    }

    public function sendData(array $data): AbstractResponse
    {
        return new CreateReceiptResponse($this, null, 200);
    }
}
