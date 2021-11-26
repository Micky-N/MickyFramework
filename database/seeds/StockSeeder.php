<?php


use Faker\Factory;
use Phinx\Seed\AbstractSeed;
use Bezhanov\Faker\Provider\Team;

class StockSeeder extends AbstractSeed
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
        $faker->addProvider(new Team($faker));
        $data = [];
        $stok = [];
        for ($i = 0; $i < 20; $i++) {
            $fs = $faker->team;
            while(!empty($stok) && in_array($fs, $stok)){
                $fs = $faker->team;
            }
            $stok[] = $fs;
            $data[] = [
                'name'  => $fs
            ];
        }

        $this->table('stocks')->insert($data)->saveData();
    }
}
