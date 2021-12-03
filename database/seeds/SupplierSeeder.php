<?php


use Faker\Factory;
use Phinx\Seed\AbstractSeed;
use Bezhanov\Faker\Provider\Educator;

class SupplierSeeder extends AbstractSeed
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
        $faker = Factory::create('fr_FR');
        $data = [];
        $supp = [];
        for ($i = 0; $i < 20; $i++) {
            $fs = $faker->company();
            while(!empty($supp) && in_array($fs, $supp)){
                $fs = $faker->company();
            }
            $supp[] = $fs;
            $data[] = [
                'name'  => $fs,
                'informations'  => $faker->sentence(),
                'num_street'  => $faker->buildingNumber(),
                'name_street'  => $faker->streetName(),
                'postcode'  => $faker->postcode(),
                'city'  => $faker->city(),
            ];
        }

        $this->table('suppliers')->insert($data)->saveData();
    }
}
