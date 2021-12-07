<?php


use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $faker = Factory::create();
        $data = [];
        for ($i = 0; $i < 7; $i++) {
            $data[] = [
                'username'      => $faker->userName,
                'password'      => password_hash('0000000', PASSWORD_BCRYPT),
                'email'         => $faker->email,
                'first_name'    => $faker->firstName,
                'last_name'     => $faker->lastName,
                'role_id'       => rand(1, 3),
                'created'       => $faker->dateTimeThisYear('-1 week', '-12 months')->format('Y-m-d H:i:s')
            ];
        }

        $this->insert('users', $data);
    }
}
