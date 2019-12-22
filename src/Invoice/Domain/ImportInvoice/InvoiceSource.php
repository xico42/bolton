<?php

declare(strict_types=1);

namespace Bolton\Invoice\Domain\ImportInvoice;

use Bolton\Invoice\Domain\Model\Invoice;

interface InvoiceSource
{
    /**
     * @return iterable<InvoiceData>
     */
    public function getAll(): iterable;
}
