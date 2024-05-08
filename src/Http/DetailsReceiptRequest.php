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
use Omnireceipt\Common\Http\Request\AbstractDetailsReceiptRequest;
use Omnireceipt\Common\Http\Response\AbstractResponse;
use Omnireceipt\Dummy\Fixtures\Helper;

/**
 * @method string getId()
 * @method string|null getIdOrNull()
 * @method self setId(string $value)
 */
class DetailsReceiptRequest extends AbstractDetailsReceiptRequest
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
        ];
    }

    public function getData(): array
    {
        return [
            'id' => $this->getId(),
        ];
    }

    public function sendData(array $data): AbstractResponse
    {
        $options = [
            'uuid' => $data['id'],
        ];

        $answer = (new ArrayCollection(Helper::getFixtureAsArray('details_collection')))
                  ->filter(static function(array $item) use ($options) {
                      return $item['uuid'] === $options['uuid'];
                  })
                  ->first() ?: null;

        return $answer
            ? new DetailsReceiptResponse($this, $answer, 200)
            : new DetailsReceiptResponse($this, null, 404);
    }
}
