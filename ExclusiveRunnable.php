<?php

declare(strict_types=1);

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\Factory;
use Zlikavac32\SymfonyExtras\Command\Runnable\Runnable;

class ExclusiveRunnable implements Runnable
{

    /**
     * @var Factory
     */
    private $lockFactory;
    /**
     * @var Runnable
     */
    private $runnable;
    /**
     * @var string
     */
    private $lockName;

    public function __construct(Runnable $runnable, Factory $lockFactory, string $lockName)
    {
        $this->runnable = $runnable;
        $this->lockFactory = $lockFactory;
        $this->lockName = $lockName;
    }

    public function configure(InputDefinition $inputDefinition): void
    {
        $this->runnable->configure($inputDefinition);
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $lock = $this->lockFactory->createLock(sha1($this->lockName));

        if (!$lock->acquire()) {
            return 1;
        }

        try {
            return $this->runnable->run($input, $output);
        } finally {
            $lock->release();
        }
    }
}
