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

use Doctrine\Common\Collections\ArrayCollection;
use Omnireceipt\Common\Http\Response\AbstractListReceiptsResponse;

class ListReceiptsResponse extends AbstractListReceiptsResponse
{
    public function getList(): ArrayCollection
    {
        return new ArrayCollection(
            $this->isSuccessful() && is_array($this->getData())
                ? $this->getData()
                : []
        );
    }
}
