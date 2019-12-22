<?php

declare(strict_types=1);

namespace Bolton\Tests\Invoice\Integration\Infrastructure\InvoiceSource\ArquiveiAPI;

use Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI\InvalidXmlFormat;
use Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI\TotalValueExtractor;
use PHPUnit\Framework\TestCase;

class TotalValueExtractorTest extends TestCase
{
    /**
     * @param $xmlPath
     * @param $expectedValue
     *
     * @dataProvider provideXMLsAndValues()
     * @test
     */
    public function shouldExtractTheInvoiceTotalValue($xmlPath, $expectedValue)
    {
        $extractor = new TotalValueExtractor();

        $contents = $this->getXmlContents('invoices/' . $xmlPath);

        $totalValue = $extractor->extract($contents);

        $this->assertSame($expectedValue, $totalValue);
    }

    public function provideXMLsAndValues(): array
    {
        return [
            ['31160811200418000673550010035830601384132439.xml', 198.0],
            ['31170213481309019535550120026847481270524261.xml', 189.90],
            ['31170213481309019535550120026894631752036643.xml', 189.90],
            ['33191100776574001390550050215366301101554847.xml', 999.0],
            ['35180400776574002280550010006270641213101221.xml', 51.32],
            ['35190211200418000401550010172178341295679999.xml', 149.0],
            ['35190911200418000401550010192604101669599958.xml', 110.99],
            ['43191127862973000196550010000722321988739275.xml', 76.80],
            ['35110883932854000133550010000001141840787155.xml', 53773.20],
        ];
    }

    private function getXmlContents(string $xmlName)
    {
        $fullPath = __DIR__ . '/../../../../../../resources/' . $xmlName;
        return file_get_contents($fullPath);
    }

    /**
     * @test
     * @param $xmlContents
     *
     * #@dataProvider provideInvalidXMLs()
     */
    public function shouldThrowExceptionWhenTheProvidedStringIsNotAValidXml($xmlContents)
    {
        $this->expectException(InvalidXmlFormat::class);
        $extractor = new TotalValueExtractor();
        $extractor->extract($xmlContents);
    }

    public function provideInvalidXMLs()
    {
        return [
            ['this is not a xml file'],
            [''],
            [$this->getXmlContents('invalid.xml')]
        ];
    }
}
