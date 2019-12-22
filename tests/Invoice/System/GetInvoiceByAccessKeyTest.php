<?php

declare(strict_types=1);

namespace Bolton\Tests\Invoice\System;

use Bolton\Invoice\Domain\Model\Invoice;
use Bolton\Invoice\Domain\Model\InvoiceRepository;
use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;

class GetInvoiceByAccessKeyTest extends SystemTestCase
{
    use PHPMatcherAssertions;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var InvoiceRepository $repository */
        $repository = $this->get(InvoiceRepository::class);
        $repository->save(
            new Invoice(
                $repository->nextIdentity(),
                '31170213481309019535550120026847481270524261',
                43.4
            )
        );
    }

    /**
     * @test
     */
    public function shouldReturnTheInvoiceByItsAccessKey(): void
    {
        $this->client->request('GET', '/api/invoice/31170213481309019535550120026847481270524261');
        $responseContent = $this->client->getResponse()->getContent();

        $expectedJson = <<<JSON
        {
            "access_key": "31170213481309019535550120026847481270524261",
            "value": 43.4
        }
        JSON;

        $this->assertMatchesPattern($expectedJson, $responseContent);
    }
}
