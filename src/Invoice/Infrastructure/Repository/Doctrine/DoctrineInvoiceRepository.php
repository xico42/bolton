<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\Repository\Doctrine;

use Bolton\Invoice\Domain\Model\Invoice;
use Bolton\Invoice\Domain\Model\InvoiceNotFound;
use Bolton\Invoice\Domain\Model\InvoiceRepository;
use Bolton\Invoice\Infrastructure\Repository\UuidIdentity;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineInvoiceRepository implements InvoiceRepository
{
    use UuidIdentity;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByAccessKey(string $accessKey): Invoice
    {
        $invoice = $this->entityManager->getRepository(Invoice::class)
            ->findOneBy([
                'accessKey' => $accessKey
            ]);

        if ($invoice instanceof Invoice) {
            return $invoice;
        }

        throw InvoiceNotFound::withAccessKey($accessKey);
    }

    public function save(Invoice $invoice): void
    {
        $this->entityManager->persist($invoice);
        $this->entityManager->flush();
    }
}
