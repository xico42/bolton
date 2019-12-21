<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\UserInterface\API;

use Bolton\Invoice\Domain\Model\InvoiceNotFound;
use Bolton\Invoice\Domain\Model\InvoiceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController
{
    private InvoiceRepository $invoiceRepository;

    public function __construct(
        InvoiceRepository $invoiceRepository
    ) {
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * @Route("/api/invoice/{accessKey}", methods={"GET"})
     * @param string $accessKey
     * @return JsonResponse
     */
    public function getInvoice(string $accessKey): JsonResponse
    {
        try {
            $invoice = $this->invoiceRepository->findByAccessKey($accessKey);
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
}
