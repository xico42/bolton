<?php

declare(strict_types=1);

namespace Bolton\Tests\Invoice\System;

use Bolton\Invoice\Domain\Model\InvoiceRepository;

class SyncInvoicesTest extends SystemTestCase
{
    private InvoiceRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->get(InvoiceRepository::class);
    }

    /**
     * @test
     */
    public function shouldImportAllTheInvoices()
    {
        $this->client->request('PUT', '/api/invoice/sync');

        $this->assertSame(
            198.0,
            $this->repository->findByAccessKey('31160811200418000673550010035830601384132439')
                ->getValue()
        );

        $this->assertSame(
            189.90,
            $this->repository->findByAccessKey('31170213481309019535550120026847481270524261')
                ->getValue()
        );

        $this->assertSame(
            189.90,
            $this->repository->findByAccessKey('31170213481309019535550120026894631752036643')
                ->getValue()
        );

        $this->assertSame(
            999.0,
            $this->repository->findByAccessKey('33191100776574001390550050215366301101554847')
                ->getValue()
        );

        $this->assertSame(
            51.32,
            $this->repository->findByAccessKey('35180400776574002280550010006270641213101221')
                ->getValue()
        );

        $this->assertSame(
            149.0,
            $this->repository->findByAccessKey('35190211200418000401550010172178341295679999')
                ->getValue()
        );

        $this->assertSame(
            110.99,
            $this->repository->findByAccessKey('35190911200418000401550010192604101669599958')
                ->getValue()
        );

        $this->assertSame(
            76.80,
            $this->repository->findByAccessKey('43191127862973000196550010000722321988739275')
                ->getValue()
        );
    }
}
