<?php

declare(strict_types=1);

namespace OumarTraore\Scraper\Tests;

use OumarTraore\Scraper\Dto\DataExtractor\AbstractDataExtractorDto;
use OumarTraore\Scraper\Dto\DataExtractor\DataExtractorDto;
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
            'DataExtractorDto with DataExtractorDto' => [
                new DataExtractorDto(
                    [
                        'cssSelector' => '.container > div',
                        'dataExtractor' => [
                            'title' => new DataExtractorDto(
                                [
                                    'cssSelector' => 'h2',
                                    'dataExtractor' => fn (Crawler $crawler) => $crawler->getText(),
                                ]
                            ),
                            'images' => new DataExtractorDto(
                                [
                                    'cssSelector' => '.image',
                                    'dataExtractor' => fn (Crawler $crawler) => [
                                        'src' => $crawler->getAttribute('src'),
                                        'alt' => $crawler->getAttribute('alt'),
                                        'cssClass' => $crawler->getAttribute('class'),
                                    ],
                                    'multiple' => true,
                                ]
                            ),
                        ],
                        'multiple' => true,
                    ]
                ),
                [
                    [
                        'title' => 'Images',
                        'images' => [
                            [
                                'src' => 'https://via.placeholder.com/32',
                                'alt' => 'Placeholder Image 01',
                                'cssClass' => 'image first',
                            ],
                            [
                                'src' => 'https://via.placeholder.com/64',
                                'alt' => 'Placeholder Image 02',
                                'cssClass' => 'image',
                            ],
                            [
                                'src' => 'https://via.placeholder.com/128',
                                'alt' => 'Placeholder Image 03',
                                'cssClass' => 'image',
                            ],
                            [
                                'src' => 'https://via.placeholder.com/256',
                                'alt' => 'Placeholder Image 04',
                                'cssClass' => 'image',
                            ],
                            [
                                'src' => 'https://via.placeholder.com/512',
                                'alt' => 'Placeholder Image 05',
                                'cssClass' => 'image last',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Other images',
                        'images' => [
                            [
                                'src' => 'https://via.placeholder.com/8',
                                'alt' => 'Placeholder Image 06',
                                'cssClass' => 'image first',
                            ],
                            [
                                'src' => 'https://via.placeholder.com/16',
                                'alt' => 'Placeholder Image 07',
                                'cssClass' => 'image last',
                            ],
                        ],
                    ],
                ],
            ],
            'DataExtractorDto with callable' => [
                new DataExtractorDto(
                    [
                        'cssSelector' => '.images > .image',
                        'dataExtractor' => fn (Crawler $crawler) => [
                            'src' => $crawler->getAttribute('src'),
                            'alt' => $crawler->getAttribute('alt'),
                            'cssClass' => $crawler->getAttribute('class'),
                        ],
                    ]
                ),
                [
                    'src' => 'https://via.placeholder.com/32',
                    'alt' => 'Placeholder Image 01',
                    'cssClass' => 'image first',
                ],
            ],
            'DataExtractorDto multiple' => [
                new DataExtractorDto(
                    [
                        'cssSelector' => '.images > .image',
                        'dataExtractor' => fn (Crawler $crawler) => [
                            'src' => $crawler->getAttribute('src'),
                            'alt' => $crawler->getAttribute('alt'),
                            'cssClass' => $crawler->getAttribute('class'),
                        ],
                        'multiple' => true,
                    ]
                ),
                [
                    [
                        'src' => 'https://via.placeholder.com/32',
                        'alt' => 'Placeholder Image 01',
                        'cssClass' => 'image first',
                    ],
                    [
                        'src' => 'https://via.placeholder.com/64',
                        'alt' => 'Placeholder Image 02',
                        'cssClass' => 'image',
                    ],
                    [
                        'src' => 'https://via.placeholder.com/128',
                        'alt' => 'Placeholder Image 03',
                        'cssClass' => 'image',
                    ],
                    [
                        'src' => 'https://via.placeholder.com/256',
                        'alt' => 'Placeholder Image 04',
                        'cssClass' => 'image',
                    ],
                    [
                        'src' => 'https://via.placeholder.com/512',
                        'alt' => 'Placeholder Image 05',
                        'cssClass' => 'image last',
                    ],
                ],
            ],
            'DataExtractorDto multiple (multiple DataExtractorDto)' => [
                [
                    'src' => new DataExtractorDto(
                        [
                            'cssSelector' => '.images > .image',
                            'dataExtractor' => fn (Crawler $crawler) => $crawler->getAttribute('src'),
                            'multiple' => true,
                        ]
                    ),
                    'alt' => new DataExtractorDto(
                        [
                            'cssSelector' => '.images > .image',
                            'dataExtractor' => fn (Crawler $crawler) => $crawler->getAttribute('alt'),
                            'multiple' => true,
                        ]
                    ),
                    'cssClass' => new DataExtractorDto(
                        [
                            'cssSelector' => '.images > .image',
                            'dataExtractor' => fn (Crawler $crawler) => $crawler->getAttribute('class'),
                            'multiple' => true,
                        ]
                    ),
                ],
                [
                    'src' => [
                        'https://via.placeholder.com/32',
                        'https://via.placeholder.com/64',
                        'https://via.placeholder.com/128',
                        'https://via.placeholder.com/256',
                        'https://via.placeholder.com/512',
                    ],
                    'alt' => [
                        'Placeholder Image 01',
                        'Placeholder Image 02',
                        'Placeholder Image 03',
                        'Placeholder Image 04',
                        'Placeholder Image 05',
                    ],
                    'cssClass' => [
                        'image first',
                        'image',
                        'image',
                        'image',
                        'image last',
                    ],
                ],
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
