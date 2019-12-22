<?php

declare(strict_types=1);

namespace Bolton\Invoice\Domain\ImportInvoice;

class InvoiceData
{
    private string $accessKey;

    private float $value;

    public function __construct(string $accessKey, float $value)
    {
        $this->accessKey = $accessKey;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getAccessKey(): string
    {
        return $this->accessKey;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}
