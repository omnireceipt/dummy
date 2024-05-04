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

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Omnireceipt\Common\Http\Request\AbstractListReceiptRequest;
use Omnireceipt\Common\Http\Response\AbstractResponse;
use Omnireceipt\Dummy\Fixtures\Helper;

/**
 * @method string getDateFrom()
 * @method self setDateFrom(string $value)
 * @method string getDateTo()
 * @method self setDateTo(string $value)
 * @method bool getDeleted()
 * @method self setDeleted(bool $value)
 */
class ListReceiptsRequest extends AbstractListReceiptRequest
{
    public function getDefaultParameters(): array
    {
        return [
            'deleted' => false,
        ];
    }

    public static function rules(): array
    {
        return [
            'date_from' => ['required', 'string'],
            'date_to' => ['required', 'string'],
            'deleted' => ['required', 'bool'],
        ];
    }

    public function getData(): array
    {
        return [
            'date_from' => $this->getDateFrom(),
            'date_to' => $this->getDateTo(),
            'deleted' => $this->getDeleted(),
        ];
    }

    public function sendData(array $data): AbstractResponse
    {
        $options = [
            'date_from' => $data['date_from'],
            'date_to' => $data['date_to'],
            'deleted' => $data['deleted'],
        ];

        $collection = (new ArrayCollection(Helper::getFixtureAsArray('receipts')))
                      ->filter(static function (array $item) use ($options) {
                          if (! empty($options['date_from']) && ! Carbon::parse($item['doc_date'])->gte(Carbon::parse($options['date_from']))) {
                              return false;
                          }
                          if (! empty($options['date_to']) && ! Carbon::parse($item['doc_date'])->lte(Carbon::parse($options['date_to']))) {
                              return false;
                          }
                          if (! empty($options['deleted']) && false) {
                              // @TODO PASS..
                              return false;
                          }
                          return true;
                      });

        return $collection->count()
            ? new ListReceiptsResponse($this, $collection->toArray(), 200)
            : new ListReceiptsResponse($this, null, 404);
    }
}
