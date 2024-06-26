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
    use BaseRequestTrait;

    protected function getEndpoint(): string
    {
        return 'https://www.example.com/api/v1/receipt';
    }

    public function getRequestMethod(): string
    {
        return 'GET';
    }

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

        $response = $this->request([$options]);

        return new ListReceiptsResponse($this, $response->getBody(), $response->getStatusCode());
    }
}
