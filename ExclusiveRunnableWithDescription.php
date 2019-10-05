<?php

declare(strict_types=1);

use Symfony\Component\Lock\Factory;
use Zlikavac32\SymfonyExtras\Command\Runnable\RunnableWithDescription;

class ExclusiveRunnableWithDescription extends ExclusiveRunnable implements RunnableWithDescription
{

    /**
     * @var RunnableWithDescription
     */
    private $runnable;

    public function __construct(RunnableWithDescription $runnable, Factory $lockFactory, string $lockName)
    {
        parent::__construct(
            $runnable, $lockFactory, $lockName
        );

        $this->runnable = $runnable;
    }

    public function description(): string
    {
        return $this->runnable->description();
    }
}
