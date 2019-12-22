<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI\Mock;

use Bolton\Invoice\Domain\ImportInvoice\InvoiceData;
use Bolton\Invoice\Domain\ImportInvoice\InvoiceSource;
use Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI\TotalValueExtractor;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * Invoice Source that gets the invoices from local saved xmls
 */
class MockedInvoiceSource implements InvoiceSource
{
    /**
     * @var MockInvoiceXmlProvider
     */
    private MockInvoiceXmlProvider $mockProvider;
    /**
     * @var TotalValueExtractor
     */
    private TotalValueExtractor $extractor;

    public function __construct(
        MockInvoiceXmlProvider $mockProvider,
        TotalValueExtractor $extractor
    ) {
        $this->mockProvider = $mockProvider;
        $this->extractor = $extractor;
    }

    public function getAll(): iterable
    {
        return array_map(function (array $mockData) {
            return new InvoiceData(
                $mockData['access_key'],
                $this->extractor->extract(
                    base64_decode($mockData['xml'])
                )
            );
        }, $this->mockProvider->getAll());
    }
}
