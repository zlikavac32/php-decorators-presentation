<?php

declare(strict_types=1);

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zlikavac32\SymfonyExtras\Command\Runnable\Runnable;

class CheckIsPrimeRunnable implements Runnable
{

    public function configure(InputDefinition $inputDefinition): void
    {
        $inputDefinition->addArgument(
            new InputArgument('n', InputArgument::REQUIRED, 'Number to check')
        );
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $n =  (int) $input->getArgument('n');

        if ($n < 1) {
            $output->writeln('<error>n must be >= 2</error>', $n);

            return 1;
        }

        if ($n % 2 === 0) {
            $output->writeln('Not prime');

            return 0;
        }

        for ($limit = sqrt($n), $i = 3; $i <= $limit; $i += 2) {
            if ($n % $i === 0) {
                $output->writeln('Not prime');

                return 0;
            }
        }

        $output->writeln('Is prime');

        return 0;
    }
}
