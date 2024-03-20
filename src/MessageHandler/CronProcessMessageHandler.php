<?php

namespace App\MessageHandler;

use App\Message\CronProcessMessage;
use CleverAge\ProcessUiBundle\Message\ProcessExecuteMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
readonly final class CronProcessMessageHandler
{
    public function __construct(private MessageBusInterface $bus)
    {

    }
    public function __invoke(CronProcessMessage $message): void
    {
        $schedule = $message->processSchedule;
        $this->bus->dispatch(
            new ProcessExecuteMessage($schedule->getProcess(), null, $message->processSchedule->getContext())
        );
    }
}
