<?php

declare(strict_types=1);

namespace OumarTraore\Scraper\Tests\Dto\DataExtractor;

use OumarTraore\Scraper\Dto\DataExtractor\AbstractDataExtractorDto;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class AbstractDataExtractorDtoTest extends DataExtractorDtoTestCase
{
    protected static function getDataExtractorFqcn(): string
    {
        return AbstractDataExtractorDto::class;
    }

    protected function createDataExtractor($options): AbstractDataExtractorDto
    {
        return new class($options) extends AbstractDataExtractorDto {
            public function extractData(Crawler $crawler): mixed
            {
                return null;
            }
        };
    }

    public function createInstanceWithInvalidOptionsProvider(): iterable
    {
        return [
            [
                [
                    'key' => 'value',
                ],
                UndefinedOptionsException::class,
                [
                    'The option "key" does not exist',
                ],
            ],
        ];
    }

    public function createInstanceWithValidOptionsProvider(): iterable
    {
        return [
            [
                [],
                [],
            ],
        ];
    }
}
