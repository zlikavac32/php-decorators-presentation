<?php

declare(strict_types=1);

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\Factory;

abstract class AbstractExclusiveCommand extends Command
{

    /**
     * @var Factory
     */
    private $lockFactory;

    public function __construct(Factory $lockFactory, string $name)
    {
        parent::__construct($name);
        $this->lockFactory = $lockFactory;
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

    abstract protected function executeExclusive(InputInterface $input, OutputInterface $output): int;
}
