<?php

namespace OumarTraore\Scraper\Feature\ExtractData;

use OumarTraore\Scraper\Dto\DataExtractor\DataExtractorDtoInterface;
use Symfony\Component\Panther\DomCrawler\Crawler;

interface ExtractDataInterface
{
    /**
     * Extract data from Crawler.
     *
     * @param DataExtractorDtoInterface|DataExtractorDtoInterface[] $dataExtractorDtos
     */
    public function extractData(
        Crawler $crawler,
        DataExtractorDtoInterface|array $dataExtractorDtos,
    ): mixed;
}
