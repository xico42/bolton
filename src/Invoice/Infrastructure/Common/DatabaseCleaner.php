<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\Common;

use Doctrine\ORM\EntityManagerInterface;

class DatabaseCleaner
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function cleanDatabase(): void
    {
        $this->entityManager
            ->getConnection()
            ->executeUpdate('DELETE FROM invoice;');
    }
}
