<?php

declare(strict_types=1);

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\Factory;

class CalculateFibonacciCommand extends Command
{

    /**
     * @var Factory
     */
    private $lockFactory;

    public function __construct(Factory $lockFactory, string $name) {
        parent::__construct($name);
        $this->lockFactory = $lockFactory;
    }

    protected function configure()
    {
        $this->addArgument('n', InputArgument::REQUIRED, 'Fibonacci number to calculate');
    }

    private function executeExclusive(InputInterface $input, OutputInterface $output)
    {
        $n = $input->getArgument('n');

        if ($n < 1) {
            $output->writeln('<error>n must be >= 1</error>', $n);

            return 1;
        }

        $output->writeln((string) $this->fibonacci((int) $n));

        return 0;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lock = $this->lockFactory->createLock(sha1($this->getName()));

        if (!$lock->acquire()) {
            return 1;
        }

        try {
            return $this->executeExclusive($input, $output);
        } finally {
            $lock->release();
        }
    }

    private function fibonacci(int $n): int
    {
        if ($n < 2) {
            return $n;
        }

        return $this->fibonacci($n - 1) + $this->fibonacci($n - 2);
    }
}
