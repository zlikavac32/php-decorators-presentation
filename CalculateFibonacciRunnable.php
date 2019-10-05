<?php

declare(strict_types=1);

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zlikavac32\SymfonyExtras\Command\Runnable\RunnableWithDescription;

class CalculateFibonacciRunnable implements RunnableWithDescription
{

    public function configure(InputDefinition $inputDefinition): void
    {
        $inputDefinition->addArgument(
            new InputArgument('n', InputArgument::REQUIRED, 'Fibonacci number to calculate')
        );
    }

    public function run(InputInterface $input, OutputInterface $output): int
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

    public function description(): string
    {
        return <<<'STR'
Uses a recursive algorithm without memoization to compute a
given Fibonacci number.
STR;
    }
}
