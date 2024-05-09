<?php
/**
 * Dummy driver for Omnireceipt fiscal receipt processing library
 *
 * @link      https://github.com/omnireceipt/dummy
 * @package   omnireceipt/dummy
 * @license   MIT
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnireceipt\Dummy\Tests\Fixtures;

use Omnireceipt\Common\Contracts\Http\ClientInterface;
use Omnireceipt\Dummy\Gateway;
use Omnireceipt\Omnireceipt;
use RuntimeException;

trait FixtureTrait
{
    /**
     * @param string $name
     * @return string|array
     */
    protected function fixture(string $name): string|array
    {
        if (str_contains($name, '..')) {
            throw new RuntimeException("Bad name fixture");
        }

        $fixture = __DIR__ . '/fixture/' . $name;
        if (file_exists($fixture . '.json')) {
            return file_get_contents($fixture . '.json');
        }
        if (file_exists($fixture . '.php')) {
            return require $fixture . '.php';
        }

        throw new RuntimeException("Not found fixture \"{$name}\"");
    }

    /**
     * @param string $name
     * @return array
     */
    protected function fixtureAsArray(string $name): array
    {
        $data = $this->fixture($name);
        return is_string($data)
            ? json_decode($data, true, JSON_UNESCAPED_UNICODE)
            : $data;
    }

    protected function createOmnireceipt(string|null $initialize = 'config_fixture', ClientInterface $httpClient = null, \Symfony\Component\HttpFoundation\Request $httpRequest = null): Gateway
    {
        /** @var  $omnireceipt */
        $omnireceipt = Omnireceipt::create('Dummy', $httpClient, $httpRequest);
        if ($initialize) {
            $omnireceipt->initialize(
                $this->fixtureAsArray($initialize)
            );
        }
        return $omnireceipt;
    }
}
