<?php

declare(strict_types=1);

namespace Bolton\Tests\Invoice\System;

use Bolton\Invoice\Infrastructure\Common\DatabaseCleaner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class SystemTestCase extends WebTestCase
{
    private DatabaseCleaner $databaseCleaner;

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->databaseCleaner = new DatabaseCleaner($this->getEntityManager());
        $this->client = self::$container->get('test.client');
        $this->cleanDatabase();
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return self::$container->get(EntityManagerInterface::class);
    }

    protected function cleanDatabase()
    {
        $this->databaseCleaner
            ->cleanDatabase();
    }

    protected function get(string $serviceId)
    {
        return self::$container->get($serviceId);
    }
}
