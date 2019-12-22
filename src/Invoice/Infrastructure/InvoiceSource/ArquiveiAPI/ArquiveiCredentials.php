<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI;

class ArquiveiCredentials
{
    private string $endpoint;

    private string $apiId;

    private string $apiKey;

    public function __construct(
        string $endpoint,
        string $apiId,
        string $apiKey
    ) {
        $this->endpoint = $endpoint;
        $this->apiId = $apiId;
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return string
     */
    public function getApiId(): string
    {
        return $this->apiId;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
