<?php


use Faker\Factory;
use Phinx\Seed\AbstractSeed;
use Bezhanov\Faker\Provider\Commerce;

class CategorySeeder extends AbstractSeed
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
        $faker->addProvider(new Commerce($faker));
        $data = [];
        $cate = [];
        for ($i = 0; $i < 5; $i++) {
            $fc = $faker->category;
            while(!empty($cate) && in_array($fc, $cate)){
                $fc = $faker->category;
            }
            $cate[] = $fc;
            $data[] = [
                'name'  => $fc,
                'description'   => $faker->sentence()
            ];
        }

        $this->table('categories')->insert($data)->saveData();
    }
}
