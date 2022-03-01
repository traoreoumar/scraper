<?php

namespace OumarTraore\Scraper\Dto\DataExtractor;

use Symfony\Component\Panther\DomCrawler\Crawler;

interface DataExtractorDtoInterface
{
    /**
     * Get options.
     */
    public function getOptions(): array;

    /**
     * Extract data from crawler.
     */
    public function extractData(Crawler $crawler): mixed;
}
