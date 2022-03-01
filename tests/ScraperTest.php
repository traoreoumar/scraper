<?php

declare(strict_types=1);

namespace OumarTraore\Scraper\Tests;

use OumarTraore\Scraper\Dto\DataExtractor\AbstractDataExtractorDto;
use OumarTraore\Scraper\Dto\DataExtractor\DataExtractorDtoInterface;
use OumarTraore\Scraper\Scraper;
use Symfony\Component\Panther\DomCrawler\Crawler;

class ScraperTest extends ScraperTestCase
{
    public function extractDataProvider(): iterable
    {
        return $this->updateClientFactoryProvider([
            'AbstractDataExtractorDto' => [
                new class() extends AbstractDataExtractorDto {
                    public function extractData(Crawler $crawler): mixed
                    {
                        return $crawler->filter('img')->count();
                    }
                },
                7,
            ],
        ]);
    }

    /**
     * @dataProvider extractDataProvider
     */
    public function testExtractData(\Closure $clientFactory, DataExtractorDtoInterface|array $dataExtractorDtos, mixed $expectedExtractedData): void
    {
        static::$client = $clientFactory();
        $crawler = static::$client->request(
            'GET',
            sprintf(
                'http://%s:%s/example.html',
                static::$defaultOptions['hostname'],
                static::$defaultOptions['port']
            )
        );

        $scraper = new Scraper();
        $extractedData = $scraper->extractData($crawler, $dataExtractorDtos);

        $this->assertEquals($expectedExtractedData, $extractedData);
    }
}
