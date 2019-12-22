<?php

declare(strict_types=1);

namespace Bolton\Tests\Invoice\Integration\Infrastructure\InvoiceSource\ArquiveiAPI;

use Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI\ArquiveiCredentials;
use Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI\ArquiveiInvoiceSource;
use Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI\InvoiceDataCollection;
use Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI\Mock\MockedInvoiceSource;
use Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI\Mock\MockInvoiceXmlProvider;
use Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI\TotalValueExtractor;
use PHPUnit\Framework\TestCase;

class ArquiveiInvoiceSourceTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnACollectionOfInvoiceData()
    {
        $arquiveiSource = new ArquiveiInvoiceSource(
            new ArquiveiCredentials(
                $_ENV['ARQUIVEI_API_ENDPOINT'],
                $_ENV['ARQUIVEI_API_ID'],
                $_ENV['ARQUIVEI_API_KEY']
            ),
            new TotalValueExtractor()
        );
        $collection = $arquiveiSource->getAll();

        $this->assertEquals(
            $this->expectedInvoiceData(),
            $collection
        );
    }

    private function expectedInvoiceData(): iterable
    {
        $mockSource = new MockedInvoiceSource(
            new MockInvoiceXmlProvider(),
            new TotalValueExtractor()
        );

        return $mockSource->getAll();
    }
}
