<?php


use Faker\Factory;
use Phinx\Seed\AbstractSeed;
use Bezhanov\Faker\Provider\Commerce;

class ProductSeeder extends AbstractSeed
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
        $prod = [];
        for ($i = 0; $i < 100; $i++) {
            $fp = $faker->productName;
            while(!empty($prod) && in_array($fp, $prod)){
                $fp = $faker->productName;
            }
            $prod[] = $fp;
            $data[] = [
                'code_category' => rand(1, 5),
                'name'  => $fp,
                'selling_price' => rand(20000, 80000)/100,
                'photo'   => $faker->imageUrl(640, 480, 'product', true, $fp)
            ];
        }

        $this->table('products')->insert($data)->saveData();
    }
}
