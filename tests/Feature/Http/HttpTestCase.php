<?php
/**
 * Dummy driver for Omnireceipt fiscal receipt processing library
 *
 * @link      https://github.com/omnireceipt/dummy
 * @package   omnireceipt/dummy
 * @license   MIT
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnireceipt\Dummy\Tests\Feature\Http;

use Omnireceipt\Common\AbstractGateway;
use Omnireceipt\Dummy\Gateway;
use Omnireceipt\Dummy\Tests\Fixtures\FixtureTrait;
use Omnireceipt\Dummy\Tests\MockTestCase;

class HttpTestCase extends MockTestCase
{
    use FixtureTrait;

    protected AbstractGateway $gateway;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Gateway $gateway */
        $this->gateway = $this->createOmnireceipt(
            'config_http',
            httpClient: $this->getHttpClient(),
        );
    }
}
