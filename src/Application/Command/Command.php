<?php declare(strict_types=1);

namespace Milkbar\Application\Command;

use Milkbar\Domain\ValueObject\DateTime;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends \Symfony\Component\Console\Command\Command
{
    protected function generateOutput(OutputInterface $output, string $message) : void
    {
        $output->writeln(DateTime::create()->format('Y-m-d H:i:s:u') . ' - ' . $message);
    }
}
