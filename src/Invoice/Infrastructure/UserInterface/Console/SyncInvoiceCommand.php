<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\UserInterface\Console;

use Bolton\Invoice\Domain\ImportInvoice\InvoiceImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncInvoiceCommand extends Command
{
    /**
     * @var InvoiceImporter
     */
    private InvoiceImporter $importer;

    public function __construct(InvoiceImporter $importer)
    {
        parent::__construct();
        $this->importer = $importer;
    }

    protected function configure()
    {
        $this
            ->setName('bolton:sync-invoices')
            ->setDescription('Syncs invoices with external sources');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importer->import();
    }
}
