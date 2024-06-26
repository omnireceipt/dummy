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

use Omnireceipt\Common\Http\Request\AbstractDetailsReceiptRequest;
use Omnireceipt\Common\Http\Response\AbstractResponse;

/**
 * @method string getId()
 * @method string|null getIdOrNull()
 * @method self setId(string $value)
 */
class DetailsReceiptRequest extends AbstractDetailsReceiptRequest
{
    use BaseRequestTrait;

    protected function getEndpoint(): string
    {
        return 'https://www.example.com/api/v1/receipt/{uuid}';
    }

    public function getRequestMethod(): string
    {
        return 'GET';
    }

    protected function getRequestUrl(array $queryParams = null): string
    {
        return str_replace('{uuid}', $this->getId(), $this->getEndpoint());
    }

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
        $response = $this->request([$data]);

        return new DetailsReceiptResponse($this, $response->getBody(), $response->getStatusCode());
    }
}
