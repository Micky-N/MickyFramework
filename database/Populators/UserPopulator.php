<?php

namespace Database\Populators;

use App\UserModule\Managers\UserManager;
use MkyCore\Abstracts\Populator;

class UserPopulator extends Populator
{

    protected string $manager = UserManager::class;


    public function definition(): array
    {
        return [
            'name' => $this->faker->firstNameFemale(),
            'email' => $this->faker->email(),
            'password' => password_hash('password', PASSWORD_BCRYPT)
        ];
    }

}