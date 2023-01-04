<?php

namespace Database\Populators;

use MkyCore\Abstracts\Populator;

class RunnerPopulator extends Populator
{

    public function populate(): void
    {
        $users = new UserPopulator();
        $users->populate();
    }
}