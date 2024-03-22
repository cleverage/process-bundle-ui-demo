<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\ProcessSchedule;
use EasyCorp\Bundle\EasyAdminBundle\Event\AbstractLifecycleEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\KernelInterface;

final readonly class AfterEntityActionEventListener
{
    public function __construct(private KernelInterface $kernel)
    {

    }

    #[AsEventListener(event: AfterEntityUpdatedEvent::class)]
    #[AsEventListener(event: AfterEntityDeletedEvent::class)]
    #[AsEventListener(event: AfterEntityPersistedEvent::class)]

    public function onAfterEntityUpdatedEvent(AbstractLifecycleEvent $event): void
    {
        if (ProcessSchedule::class === get_class($event->getEntityInstance()) ) {
            $application = new Application($this->kernel);
            $application->setAutoExit(false);
            $output = new BufferedOutput();
            $application->run(new ArrayInput(['command' => 'messenger:stop-workers']), $output);
            $output->fetch();
        }
    }
}
