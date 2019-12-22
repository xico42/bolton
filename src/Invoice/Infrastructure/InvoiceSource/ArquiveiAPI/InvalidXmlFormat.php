<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI;

use InvalidArgumentException;
use Throwable;

class InvalidXmlFormat extends InvalidArgumentException
{
    public static function because(
        string $reason,
        Throwable $previous = null
    ): self {
        $message = sprintf('The provided xml is not valid. Reason: %s', $reason);
        return new self($message, 0, $previous);
    }
}
