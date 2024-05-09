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

use Doctrine\Common\Collections\ArrayCollection;
use Omnireceipt\Common\Http\Response\AbstractListReceiptsResponse;
use Omnireceipt\Dummy\Entities\Receipt;
use Omnireceipt\Dummy\Entities\ReceiptItem;

class ListReceiptsResponse extends AbstractListReceiptsResponse
{
    public function getList(): ArrayCollection
    {
        $collection = new ArrayCollection;

        if ($this->isSuccessful() && is_array($this->getData())) {
            foreach ($this->getData() as $item) {
                $goods = $item['goods'] ?? [];
                unset($item['goods']);
                $receipt = new Receipt($item);
                foreach ($goods as $good) {
                    $receipt->addItem(
                        new ReceiptItem($good)
                    );
                }
                $collection->add($receipt);
            }
        }

        return $collection;
    }
}
