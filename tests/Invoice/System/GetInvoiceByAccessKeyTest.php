<?php

declare(strict_types=1);

namespace Bolton\Tests\Invoice\System;

use Bolton\Invoice\Domain\Model\Invoice;
use Bolton\Invoice\Domain\Model\InvoiceRepository;
use Bolton\Invoice\Infrastructure\Common\DatabaseCleaner;
use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetInvoiceByAccessKeyTest extends WebTestCase
{
    use PHPMatcherAssertions;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $entityManager = self::$container->get(EntityManagerInterface::class);
        (new DatabaseCleaner($entityManager))->cleanDatabase();

        $repository = self::$container->get(InvoiceRepository::class);
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
        $client = $this->getApiClient();
        $client->request('GET', '/api/invoice/31170213481309019535550120026847481270524261');
        $responseContent = $client->getResponse()->getContent();

        $expectedJson = <<<JSON
        {
            "access_key": "31170213481309019535550120026847481270524261",
            "value": 43.4
        }
        JSON;

        $this->assertMatchesPattern($expectedJson, $responseContent);
    }

    private function getApiClient(): KernelBrowser
    {
        return self::$container->get('test.client');
    }
}
