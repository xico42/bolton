<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

final class TotalValueExtractor
{
    private XmlEncoder $decoder;

    public function __construct()
    {
        $this->decoder = new XmlEncoder();
    }

    /**
     * Extracts the total value from an xml invoice
     *
     * @param string $xml
     * @return float
     * @throws InvalidXmlFormat if the given xml is in an incorrect format
     */
    public function extract(string $xml): float
    {
        $decodedData = $this->decodeXml($xml);
        $totalValue = $decodedData['NFe']['infNFe']['total']['ICMSTot']['vNF']
            ?? $decodedData['infNFe']['total']['ICMSTot']['vNF']
            ?? null;

        if (null === $totalValue) {
            throw InvalidXmlFormat::because('The total value is missing');
        }

        return (float) $totalValue;
    }

    /**
     * Decodes the invoice xml
     *
     * @param string $xml
     * @return array<mixed>
     */
    private function decodeXml(string $xml): array
    {
        try {
            $decodedData = $this->decoder->decode($xml, 'xml');
            return $this->formatDecodedData($decodedData);
        } catch (NotEncodableValueException $exception) {
            throw InvalidXmlFormat::because(
                $exception->getMessage(),
                $exception
            );
        }
    }

    /**
     * Formats the decoded xml data
     *
     * @param array<mixed> $decodedData
     * @return array<mixed>
     */
    private function formatDecodedData(array $decodedData): array
    {
        $formattedData = [];
        foreach ($decodedData as $key => $value) {
            $newKey = is_string($key)
                ? preg_replace('/^.*?:/', '', $key)
                : $key;

            $newValue = is_array($value)
                ? $this->formatDecodedData($value)
                : $value;

            $formattedData[$newKey] = $newValue;
        }
        return $formattedData;
    }
}
