<?php

namespace OumarTraore\Scraper\Dto\DataExtractor;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Panther\DomCrawler\Crawler;

abstract class AbstractDataExtractorDto implements DataExtractorDtoInterface
{
    protected array $options;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    abstract public function extractData(Crawler $crawler): mixed;
}
