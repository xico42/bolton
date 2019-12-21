<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\UserInterface\Console;

use Bolton\Invoice\Domain\Model\InvoiceRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetInvoiceCommand extends Command
{
    private InvoiceRepository $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepository)
    {
        parent::__construct();
        $this->invoiceRepository = $invoiceRepository;
    }

    protected function configure(): void
    {
        $this
            ->setName('bolton:get-value')
            ->setDescription('Gets an invoice value by it\'s access key')
            ->addArgument(
                'accessKey',
                InputArgument::REQUIRED,
                'The invoice\'s access key'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $accessKey = $input->getArgument('accessKey');
        $invoice = $this->invoiceRepository->findByAccessKey($accessKey);
        $output->writeln(sprintf('The invoice value is %.2f', $invoice->getValue()));
        return 0;
    }
}
