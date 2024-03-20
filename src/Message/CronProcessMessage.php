<?php

namespace App\Message;

use App\Entity\ProcessSchedule;

readonly final class CronProcessMessage
{
    public function __construct(public ProcessSchedule $processSchedule)
    {
    }
}
