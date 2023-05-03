<?php
namespace App\Schedule;

use Zenstruck\ScheduleBundle\Schedule;
use Zenstruck\ScheduleBundle\Schedule\ScheduleBuilder;

class AppScheduleBuilder implements ScheduleBuilder
{
    public function buildSchedule(Schedule $schedule): void
    {

        $schedule->addCommand('import:rechnungen')
            ->description('Import für neue Rechnungen.')
            ->hourly()
            ->at(1);

        $schedule->addCommand('import:artikel')
            ->description('Import für Artikel von Bestellungen.')
            ->everyMinute();

        $schedule->addCommand('import:basis_artikel')
            ->description('Import für "Basis Artikel".')
            ->hourly()
            ->at(1);

        $schedule->addCommand('uebertragung:intertrans')
            ->description('Übertragung für Intertrans".')
            ->hourly()
            ->at(1);
        // ...
    }
}