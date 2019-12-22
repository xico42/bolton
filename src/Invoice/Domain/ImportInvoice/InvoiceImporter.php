<?php

declare(strict_types=1);

namespace Bolton\Invoice\Domain\ImportInvoice;

use Bolton\Invoice\Domain\Model\Invoice;
use Bolton\Invoice\Domain\Model\InvoiceNotFound;
use Bolton\Invoice\Domain\Model\InvoiceRepository;

class InvoiceImporter
{
    private InvoiceSource $source;

    private InvoiceRepository $repository;

    public function __construct(InvoiceSource $source, InvoiceRepository $repository)
    {
        $this->source = $source;
        $this->repository = $repository;
    }

    public function import(): void
    {
        /** @var InvoiceData $invoiceData */
        foreach ($this->source->getAll() as $invoiceData) {
            $this->repository->save(
                $this->makeInvoice($invoiceData)
            );
        }
    }

    private function makeInvoice(InvoiceData $invoiceData): Invoice
    {
        try {
            $invoice = $this->repository
                ->findByAccessKey($invoiceData->getAccessKey());
            $invoice->updateValue($invoiceData->getValue());
            return $invoice;
        } catch (InvoiceNotFound $exception) {
            return new Invoice(
                $this->repository->nextIdentity(),
                $invoiceData->getAccessKey(),
                $invoiceData->getValue()
            );
        }
    }
}
