<?php

declare(strict_types=1);

namespace Bolton\Tests\Invoice\Integration\Infrastructure\Repository\Doctrine;

use Bolton\Invoice\Domain\Model\Invoice;
use Bolton\Invoice\Infrastructure\Common\DatabaseCleaner;
use Bolton\Invoice\Infrastructure\Repository\Doctrine\DoctrineInvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineInvoiceRepositoryTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        (new DatabaseCleaner($this->getEntityManager()))
            ->cleanDatabase();
    }

    /**
     * @test
     */
    public function shouldSaveAndRetrieveAnInvoice()
    {
        $accessKey = '31170213481309019535550120026847481270524261';

        $repository = new DoctrineInvoiceRepository(
            $this->getEntityManager()
        );

        $invoice = new Invoice(
            $repository->nextIdentity(),
            $accessKey,
            45.8
        );

        $repository->save($invoice);

        $this->getEntityManager()->clear();

        $recoveredInvoice = $repository->findByAccessKey($accessKey);

        $this->assertEquals($invoice, $recoveredInvoice);
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return self::$container->get('doctrine.orm.entity_manager');
    }
}
