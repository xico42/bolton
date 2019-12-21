<?php

declare(strict_types=1);

namespace Bolton\Tests\Invoice\Unit\Infrastructure\UserInterface\Console;

use Bolton\Invoice\Domain\Model\Invoice;
use Bolton\Invoice\Infrastructure\Repository\InMemory\InMemoryInvoiceRepository;
use Bolton\Invoice\Infrastructure\UserInterface\Console\GetInvoiceCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class GetInvoiceCommandTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnTheInvoiceValueWhenTheInvoiceExists()
    {
        $input = new ArrayInput([
            'accessKey' => '31170213481309019535550120026847481270524261'
        ]);

        $repository = new InMemoryInvoiceRepository();
        $repository->save(
            new Invoice(
                $repository->nextIdentity(),
                '31170213481309019535550120026847481270524261',
                42.98
            )
        );

        $output = new BufferedOutput();

        $command = new GetInvoiceCommand($repository);
        $command->run($input, $output);

        $this->assertStringContainsString('42.98', $output->fetch());
    }
}
