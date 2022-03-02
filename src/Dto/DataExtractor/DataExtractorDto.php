<?php

namespace OumarTraore\Scraper\Dto\DataExtractor;

use OumarTraore\Scraper\Feature\ExtractData\ExtractDataTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Panther\DomCrawler\Crawler;

class DataExtractorDto extends AbstractDataExtractorDto
{
    use ExtractDataTrait {
        extractData as _extractData;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->define('cssSelector')
            ->default(null)
            ->allowedTypes('null', 'string')
            ->info('CSS selector of data container')
        ;

        $resolver->define('dataExtractor')
            ->required()
            ->allowedTypes(DataExtractorDtoInterface::class, DataExtractorDtoInterface::class.'[]', \Closure::class)
            ->info('DataExtractorDtoInterface | DataExtractorDtoInterface[] | Closure (function to extract data from Crawler instance (signature is function(Crawler $crawler): mixed)')
        ;

        $resolver->define('multiple')
            ->default(false)
            ->allowedTypes('boolean')
            ->info('If true, extractData method return array')
        ;
    }

    public function extractData(Crawler $crawler): mixed
    {
        $elementCrawler = $crawler;
        $cssSelector = $this->options['cssSelector'];
        $multiple = $this->options['multiple'];

        if (null !== $cssSelector) {
            $elementCrawler = $elementCrawler
                ->filter($cssSelector)
            ;
        }

        $result = null;
        if ($multiple) {
            $result = $elementCrawler->each(fn (Crawler $valueCrawler) => $this->extractValue($valueCrawler));
        } else {
            $result = $this->extractValue($elementCrawler);
        }

        return $result;
    }

    protected function extractValue(Crawler $crawler): mixed
    {
        $value = null;

        $dataExtractor = $this->options['dataExtractor'];
        if ($dataExtractor instanceof \Closure) {
            $value = $this->options['dataExtractor']($crawler);
        } else {
            $value = $this->_extractData($crawler, $dataExtractor);
        }

        return $value;
    }
}
