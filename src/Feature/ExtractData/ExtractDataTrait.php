<?php

namespace OumarTraore\Scraper\Feature\ExtractData;

use OumarTraore\Scraper\Dto\DataExtractor\DataExtractorDtoInterface;
use Symfony\Component\Panther\DomCrawler\Crawler;

trait ExtractDataTrait
{
    public function extractData(
        Crawler $crawler,
        DataExtractorDtoInterface|array $dataExtractorDtos,
    ): mixed {
        $data = [];

        if (is_array($dataExtractorDtos)) {
            /** @var DataExtractorDtoInterface $dataExtractorDto */
            foreach ($dataExtractorDtos as $property => $dataExtractorDto) {
                $data[$property] = $dataExtractorDto->extractData($crawler);
            }
        } else {
            $data = $dataExtractorDtos->extractData($crawler);
        }

        return $data;
    }
}
