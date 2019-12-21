<?php

declare(strict_types=1);

namespace Bolton\Invoice\Domain\Model;

interface InvoiceRepository
{
    /**
     * Generates the next identity for the entity
     *
     * @return string
     */
    public function nextIdentity(): string;

    /**
     * Finds an invoice by its access key
     *
     * @param string $accessKey
     * @return Invoice
     * @throws InvoiceNotFound
     */
    public function findByAccessKey(string $accessKey): Invoice;

    /**
     * Saves an invoice
     *
     * @param Invoice $invoice
     */
    public function save(Invoice $invoice): void;
}
