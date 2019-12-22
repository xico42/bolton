<?php

declare(strict_types=1);

namespace Bolton\Tests\Invoice\Unit\Domain\ImportInvoice;

use Bolton\Invoice\Domain\ImportInvoice\InvoiceData;
use Bolton\Invoice\Domain\ImportInvoice\InvoiceImporter;
use Bolton\Invoice\Domain\ImportInvoice\InvoiceSource;
use Bolton\Invoice\Domain\Model\Invoice;
use Bolton\Invoice\Infrastructure\Repository\InMemory\InMemoryInvoiceRepository;
use PHPUnit\Framework\TestCase;

class InvoiceImporterTest extends TestCase implements InvoiceSource
{
    /**
     * @test
     */
    public function shouldInsertNewInvoicesWhenItDoesNotAlreadyExist()
    {
        $repository = new InMemoryInvoiceRepository();
        $importer = new InvoiceImporter($this, $repository);

        $importer->import();

        $this->assertEquals(
            198.0,
            $repository->findByAccessKey('31160811200418000673550010035830601384132439')
                ->getValue()
        );

        $this->assertEquals(
            189.90,
            $repository->findByAccessKey('31170213481309019535550120026847481270524261')
                ->getValue()
        );

        $this->assertEquals(
            149.00,
            $repository->findByAccessKey('35190211200418000401550010172178341295679999')
                ->getValue()
        );
    }

    /**
     * @test
     */
    public function shouldUpdateExistingInvoiceValueWhenItExists()
    {
        $accessKey = '31170213481309019535550120026847481270524261';

        $repository = new InMemoryInvoiceRepository();
        $invoice = new Invoice(
            $repository->nextIdentity(),
            $accessKey,
            42.5
        );
        $repository->save($invoice);

        $importer = new InvoiceImporter($this, $repository);
        $importer->import();

        $recoveredInvoice = $repository
            ->findByAccessKey($accessKey);

        $this->assertEquals(
            189.90,
            $recoveredInvoice->getValue()
        );

        $this->assertEquals(
            $invoice->getId(),
            $recoveredInvoice->getId()
        );
    }

    public function getAll(): iterable
    {
        return [
            new InvoiceData(
                '31160811200418000673550010035830601384132439',
                198.0
            ),
            new InvoiceData(
                '31170213481309019535550120026847481270524261',
                189.90
            ),
            new InvoiceData(
                '35190211200418000401550010172178341295679999',
                149.00
            )
        ];
    }
}
