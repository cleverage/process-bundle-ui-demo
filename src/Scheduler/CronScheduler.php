<?php

namespace App\Scheduler;

use App\Message\CronProcessMessage;
use App\Repository\ProcessScheduleRepository;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('cron')]
readonly class CronScheduler implements ScheduleProviderInterface
{
    public function __construct(private ProcessScheduleRepository $repository)
    {

    }
    public function getSchedule(): Schedule
    {
        $schedule = new Schedule();
        foreach ($this->repository->findAll() as $processSchedule) {
            $schedule->add(
                RecurringMessage::cron(
                    $processSchedule->getCronExpression(),
                    new CronProcessMessage($processSchedule)
                )
            );
        }

        return $schedule;
    }
}
