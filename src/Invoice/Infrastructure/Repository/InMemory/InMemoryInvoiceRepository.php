<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\Repository\InMemory;

use Bolton\Invoice\Domain\Model\Invoice;
use Bolton\Invoice\Domain\Model\InvoiceNotFound;
use Bolton\Invoice\Domain\Model\InvoiceRepository;
use Bolton\Invoice\Infrastructure\Common\UuidGenerator;
use Bolton\Invoice\Infrastructure\Repository\UuidIdentity;

class InMemoryInvoiceRepository implements InvoiceRepository
{
    use UuidIdentity;

    /**
     * @var array<Invoice>
     */
    private array $data = [];

    public function findByAccessKey(string $accessKey): Invoice
    {
        $invoice = $this->data[$accessKey] ?? null;

        if ($invoice instanceof Invoice) {
            return $invoice;
        }

        throw InvoiceNotFound::withAccessKey($accessKey);
    }

    public function save(Invoice $invoice): void
    {
        $this->data[$invoice->getAccessKey()] = $invoice;
    }
}
