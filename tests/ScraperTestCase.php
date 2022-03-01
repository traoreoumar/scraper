<?php

declare(strict_types=1);

namespace OumarTraore\Scraper\Tests;

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

class ScraperTestCase extends PantherTestCase
{
    public static ?Client $client = null;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::startWebServer();
    }

    public function tearDown(): void
    {
        if (null !== static::$client) {
            static::$client = null;
        }

        parent::tearDown();
    }

    public function clientFactoryProvider(): iterable
    {
        return [
            'ClientFactory::CLIENT_CHROME' => [
                function () {
                    return Client::createChromeClient();
                },
            ],
            'ClientFactory::CLIENT_FIREFOX' => [
                function () {
                    return Client::createFirefoxClient();
                },
            ],
        ];
    }

    protected function updateClientFactoryProvider(array $data): iterable
    {
        $clients = $this->clientFactoryProvider();

        $testCases = [];
        foreach ($data as $key => $values) {
            foreach ($clients as $keyClient => $client) {
                $testCases[sprintf('%s (%s)', $key, $keyClient)] = [...$client, ...$values];
            }
        }

        return $testCases;
    }
}
