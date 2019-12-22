<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI;

use Bolton\Invoice\Domain\ImportInvoice\InvoiceData;
use Bolton\Invoice\Domain\ImportInvoice\InvoiceSource;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ArquiveiInvoiceSource implements InvoiceSource
{
    private string $endpoint;

    private HttpClientInterface $client;
    /**
     * @var TotalValueExtractor
     */
    private TotalValueExtractor $valueExtractor;

    public function __construct(
        ArquiveiCredentials $credentials,
        TotalValueExtractor $valueExtractor
    ) {
        $this->endpoint = $credentials->getEndpoint();
        $this->client = HttpClient::create([
            'headers' => [
                'Content-Type' => 'application/json',
                'X-API-ID' => $credentials->getApiId(),
                'X-API-KEY' => $credentials->getApiKey(),
            ]
        ]);
        $this->valueExtractor = $valueExtractor;
    }

    public function getAll(): iterable
    {
        $allInvoices = [];
        $count = 0;
        $endpoint = $this->endpoint;
        do {
            $rawResponse = $this->client->request('GET', $endpoint)
                ->toArray();
            $invoices = $this->processData($rawResponse);
            $allInvoices = array_merge($allInvoices, $invoices);

            $endpoint = $rawResponse['page']['next'] ?? $this->endpoint;
            $count = count($invoices);
        } while ($count > 0);

        return $allInvoices;
    }


    private function processData(array $rawData): array
    {
        $data = $rawData['data'] ?? [];
        return array_map(function (array $invoiceData) {
            return new InvoiceData(
                $invoiceData['access_key'],
                $this->valueExtractor->extract(base64_decode($invoiceData['xml']))
            );
        }, $data);
    }
}
