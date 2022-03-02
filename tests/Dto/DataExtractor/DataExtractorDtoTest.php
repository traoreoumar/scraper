<?php

declare(strict_types=1);

namespace OumarTraore\Scraper\Tests\Dto\DataExtractor;

use OumarTraore\Scraper\Dto\DataExtractor\DataExtractorDto;
use OumarTraore\Scraper\Dto\DataExtractor\DataExtractorDtoInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\Panther\DomCrawler\Crawler;

class DataExtractorDtoTest extends DataExtractorDtoTestCase
{
    protected static function getDataExtractorFqcn(): string
    {
        return DataExtractorDto::class;
    }

    public function createInstanceWithInvalidOptionsProvider(): iterable
    {
        return [
            [
                [
                    'cssSelector' => 5,
                    'dataExtractor' => fn (Crawler $crawler) => $crawler->getAttribute('class'),
                ],
                InvalidOptionsException::class,
                [
                    'The option "cssSelector"',
                    'is expected to be of type "null" or "string"',
                ],
            ],
            [
                [
                    'cssSelector' => '.selector',
                ],
                MissingOptionsException::class,
                [
                    'dataExtractor',
                ],
            ],
            [
                [
                    'cssSelector' => '.selector',
                    'dataExtractor' => 'test',
                ],
                InvalidOptionsException::class,
                [
                    'The option "dataExtractor"',
                    sprintf(
                        'is expected to be of type "%s" or "%s" or "%s"',
                        str_replace('\\', '\\\\', DataExtractorDtoInterface::class),
                        str_replace('\\', '\\\\', DataExtractorDtoInterface::class).'\[\]',
                        \Closure::class
                    ),
                ],
            ],
            [
                [
                    'dataExtractor' => fn (Crawler $crawler) => $crawler->getAttribute('class'),
                    'multiple' => '1',
                ],
                InvalidOptionsException::class,
                [
                    'The option "multiple"',
                    'is expected to be of type "boolean"',
                ],
            ],
        ];
    }

    public function createInstanceWithValidOptionsProvider(): iterable
    {
        return [
            [
                [
                    'dataExtractor' => fn (Crawler $crawler) => $crawler->getAttribute('class'),
                ],
                [
                    'cssSelector' => null,
                    'dataExtractor' => fn (Crawler $crawler) => $crawler->getAttribute('class'),
                    'multiple' => false,
                ],
            ],
            [
                [
                    'cssSelector' => '.selector',
                    'dataExtractor' => fn (Crawler $crawler) => $crawler->getAttribute('class'),
                    'multiple' => true,
                ],
                [
                    'cssSelector' => '.selector',
                    'dataExtractor' => fn (Crawler $crawler) => $crawler->getAttribute('class'),
                    'multiple' => true,
                ],
            ],
        ];
    }
}
