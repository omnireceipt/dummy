<?php
/**
 * Dummy driver for Omnireceipt fiscal receipt processing library
 *
 * @link      https://github.com/omnireceipt/dummy
 * @package   omnireceipt/dummy
 * @license   MIT
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnireceipt\Dummy\Tests\Feature\Fixture;

use Omnireceipt\Common\AbstractGateway;
use Omnireceipt\Dummy\Gateway;
use Omnireceipt\Dummy\Tests\FeatureTestCase;
use Omnireceipt\Dummy\Tests\Fixtures\FixtureTrait;

abstract class FixtureTestCase extends FeatureTestCase
{
    use FixtureTrait;

    protected AbstractGateway $gateway;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Gateway $gateway */
        $this->gateway = $this->createOmnireceipt();
    }
}