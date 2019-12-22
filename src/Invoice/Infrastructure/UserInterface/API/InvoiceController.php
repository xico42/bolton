<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\UserInterface\API;

use Bolton\Invoice\Domain\ImportInvoice\InvoiceImporter;
use Bolton\Invoice\Domain\Model\InvoiceNotFound;
use Bolton\Invoice\Domain\Model\InvoiceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController
{
    /**
     * @Route("/api/invoice/{accessKey<\d+>}", methods={"GET"})
     * @param string $accessKey
     * @param InvoiceRepository $repository
     * @return JsonResponse
     */
    public function getInvoice(string $accessKey, InvoiceRepository $repository): JsonResponse
    {
        try {
            $invoice = $repository->findByAccessKey($accessKey);
            return new JsonResponse([
                'access_key' => $invoice->getAccessKey(),
                'value' => $invoice->getValue(),
            ]);
        } catch (InvoiceNotFound $exception) {
            return new JsonResponse([
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @Route("/api/invoice/sync", methods={"PUT"})
     * @param InvoiceImporter $importer
     * @return Response
     */
    public function syncInvoices(InvoiceImporter $importer)
    {
        $importer->import();
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
