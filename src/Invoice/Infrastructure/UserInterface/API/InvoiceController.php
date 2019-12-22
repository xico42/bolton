<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\UserInterface\API;

use Bolton\Invoice\Domain\ImportInvoice\InvoiceImporter;
use Bolton\Invoice\Domain\Model\InvoiceNotFound;
use Bolton\Invoice\Domain\Model\InvoiceRepository;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController
{
    /**
     * Gets invoice information by its access key
     *
     * Note that you may need to sync with external invoice sources first in order
     * to have value available here.
     *
     * @Route("/api/invoice/{accessKey<\d+>}", methods={"GET"})
     *
     * @SWG\Tag(name="API")
     *
     * @SWG\Response(
     *     response="200",
     *     description="Invoice data",
     *     @SWG\Schema(
     *      @SWG\Property(property="access_key", type="string", example="31160811200418000673550010035830601384132439"),
     *      @SWG\Property(property="value", type="float", example=498.54)
     *     )
     * )
     *
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
     * Forces to sync invoices with external sources
     *
     * @SWG\Tag(name="API")
     *
     * @SWG\Response(
     *     response="204",
     *     description="No content when successful"
     * )
     *
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
