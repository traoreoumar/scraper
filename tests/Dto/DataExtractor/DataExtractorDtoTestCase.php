<?php

declare(strict_types=1);

namespace OumarTraore\Scraper\Tests\Dto\DataExtractor;

use OumarTraore\Scraper\Dto\DataExtractor\DataExtractorDtoInterface;
use PHPUnit\Framework\TestCase;

abstract class DataExtractorDtoTestCase extends TestCase
{
    /**
     * @dataProvider createInstanceWithInvalidOptionsProvider
     */
    public function testCreateInstanceWithInvalidOptions(
        array $options,
        string $exceptionFqcn,
        array $exceptionMessageMatches
    ): void {
        $this->expectException($exceptionFqcn);
        foreach ($exceptionMessageMatches as $exceptionMessageMatch) {
            $this->expectExceptionMessageMatches('/'.$exceptionMessageMatch.'/');
        }

        $this->createDataExtractor($options);
    }

    /**
     * @dataProvider createInstanceWithValidOptionsProvider
     */
    public function testCreateInstanceWithValidOptions(array $options, array $expectedOptions): void
    {
        $dataExtractorDto = $this->createDataExtractor($options);

        $this->assertEquals(
            $expectedOptions,
            $dataExtractorDto->getOptions(),
        );
    }

    protected function createDataExtractor(array $options): DataExtractorDtoInterface
    {
        $dataExtractorDtoFqcn = static::getDataExtractorFqcn();

        return new $dataExtractorDtoFqcn($options);
    }

    abstract protected static function getDataExtractorFqcn(): string;

    abstract protected function createInstanceWithInvalidOptionsProvider(): iterable;

    abstract protected function createInstanceWithValidOptionsProvider(): iterable;
}
