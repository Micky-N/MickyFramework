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
            'name' => $this->faker->name('female'),
            'email' => 'ndingamickael@gmail.com',
            'password' => 'password'
        ];
    }

}