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

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Omnireceipt\Common\Http\Request\AbstractListReceiptRequest;
use Omnireceipt\Common\Http\Response\AbstractResponse;

/**
 * @method string getDateFrom()
 * @method string getDateFromOrNull()
 * @method self setDateFrom(string $value)
 *
 * @method string getDateTo()
 * @method string getDateToOrNull()
 * @method self setDateTo(string $value)
 */
class ListReceiptsRequest extends AbstractListReceiptRequest
{
    public static function rules(): array
    {
        return [
            'date_from' => ['nullable', 'string'],
            'date_to'   => ['nullable', 'string'],
        ];
    }

    public function getData(): array
    {
        return [
            'date_from' => $this->getDateFromOrNull(),
            'date_to' => $this->getDateToOrNull(),
        ];
    }

    public function sendData(array $data): AbstractResponse
    {
        $options = [
            'date_from' => $data['date_from'],
            'date_to' => $data['date_to'],
        ];

        $collection = (new ArrayCollection(Helper::getFixtureAsArray('list')))
                      ->filter(static function (array $item) use ($options) {
                          if (! empty($options['date_from']) && ! Carbon::parse($item['date'])->gte(Carbon::parse($options['date_from']))) {
                              return false;
                          }
                          if (! empty($options['date_to']) && ! Carbon::parse($item['date'])->lte(Carbon::parse($options['date_to']))) {
                              return false;
                          }
                          return true;
                      });

        return $collection->count()
            ? new ListReceiptsResponse($this, $collection->toArray(), 200)
            : new ListReceiptsResponse($this, null, 404);
    }
}
