<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\ProcessSchedule;
use CleverAge\ProcessUiBundle\Controller\Admin\ProcessDashboardController as UiProcessDashboardController;
use CleverAge\ProcessUiBundle\Entity\LogRecord;
use CleverAge\ProcessUiBundle\Entity\ProcessExecution;
use CleverAge\ProcessUiBundle\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;

class ProcessDashboardController extends UiProcessDashboardController
{
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::subMenu('Process', 'fas fa-gear')->setSubItems(
            [
                MenuItem::linkToRoute('Process list', 'fas fa-list', 'process_list'),
                MenuItem::linkToCrud('Executions', 'fas fa-rocket', ProcessExecution::class),
                MenuItem::linkToCrud('Logs', 'fas fa-pen', LogRecord::class),
                MenuItem::linkToCrud('Scheduler', 'fas fa-solid fa-clock', ProcessSchedule::class),
            ]
        );
        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::subMenu('Users', 'fas fa-user')->setSubItems(
                [
                    MenuItem::linkToCrud('User List', 'fas fa-user', User::class),
                ]
            );
        }
    }
}