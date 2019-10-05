<?php

declare(strict_types=1);

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateFibonacciCommand extends Command
{

    protected function configure()
    {
        $this->addArgument('n', InputArgument::REQUIRED, 'Fibonacci number to calculate');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $n = $input->getArgument('n');

        if ($n < 1) {
            $output->writeln('<error>n must be >= 1</error>', $n);

            return 1;
        }

        $output->writeln((string) $this->fibonacci((int) $n));

        return 0;
    }

    private function fibonacci(int $n): int
    {
        if ($n < 2) {
            return $n;
        }

        return $this->fibonacci($n - 1) + $this->fibonacci($n - 2);
    }
}
