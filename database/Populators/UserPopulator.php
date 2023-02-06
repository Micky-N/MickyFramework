<?php

namespace Database\Populators;

use App\UserModule\Managers\UserManager;
use MkyCore\Abstracts\Populator;
use MkyCore\Crypt;

class UserPopulator extends Populator
{

    protected string $manager = UserManager::class;


    public function definition(): array
    {
        return [
            'name' => $this->faker->firstNameFemale(),
            'email' => $this->faker->email(),
            'password' => Crypt::hash('password')
        ];
    }

}