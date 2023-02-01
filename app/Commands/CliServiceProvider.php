<?php

namespace App\Commands;

use MkyCore\Console\Scheduler\Schedule;

class CliServiceProvider extends \MkyCore\Abstracts\ServiceProvider
{

    public array $commands = [
    ];

    public function schedule(Schedule $schedule)
    {

    }
}