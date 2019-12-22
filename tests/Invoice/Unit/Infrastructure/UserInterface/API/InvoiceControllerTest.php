<?php

declare(strict_types=1);

namespace Bolton\Tests\Invoice\Unit\Infrastructure\UserInterface\API;

use Bolton\Invoice\Domain\Model\Invoice;
use Bolton\Invoice\Infrastructure\Repository\InMemory\InMemoryInvoiceRepository;
use Bolton\Invoice\Infrastructure\UserInterface\API\ApiResponse;
use Bolton\Invoice\Infrastructure\UserInterface\API\InvoiceController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class InvoiceControllerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnASuccessFullResponseWhenTheInvoiceIsFound(): void
    {
        $repository = new InMemoryInvoiceRepository();
        $accessKey = '31170213481309019535550120026847481270524261';
        $invoice = new Invoice(
            $repository->nextIdentity(),
            $accessKey,
            42.4
        );

        $repository->save($invoice);

        $controller = new InvoiceController();
        $response = $controller->getInvoice($accessKey, $repository);

        $this->assertEquals(
            new JsonResponse([
                'access_key' => $invoice->getAccessKey(),
                'value' => $invoice->getValue(),
            ]),
            $response
        );
    }

    /**
     * @test
     */
    public function shouldReturnAnErrorResponseWhenTheRepositoryIsEmpty(): void
    {
        $repository = new InMemoryInvoiceRepository();
        $accessKey = '31170213481309019535550120026847481270524261';


        $controller = new InvoiceController();
        $response = $controller->getInvoice($accessKey, $repository);

        $this->assertEquals(
            new JsonResponse([
                'error' => "Invoice with access key {$accessKey} not found. Maybe it is needed to sync?"
            ]),
            $response
        );
    }
}
