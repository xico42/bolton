<?php

declare(strict_types=1);

namespace Bolton\Invoice\Domain\Model;

use Bolton\Invoice\Domain\DomainException;

final class InvoiceNotFound extends DomainException
{
    public static function withAccessKey(string $accessKey): self
    {
        $message = "Invoice with access key {$accessKey} not found. Maybe it is needed to sync?";
        return new self($message);
    }
}
