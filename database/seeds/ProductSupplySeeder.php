<?php

use App\Models\Supply;
use App\Models\Product;
use Phinx\Seed\AbstractSeed;

class ProductSupplySeeder extends AbstractSeed
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
        $data = [];
        $ps = [];
        for ($i = 0; $i < 200; $i++) {
            $faker = [Product::shuffleId(), Supply::shuffleId()];
            while(!empty($ps) && in_array($faker, $ps)){
                $faker = [Product::shuffleId(), Supply::shuffleId()];
            }
            $ps[] = $faker;
            $data[] = [
                'code_product'  => $faker[0],
                'code_supply'  => $faker[1],
                'price' => rand(20000, 60000)/100
            ];
        }

        $this->table('product_supplies')->insert($data)->saveData();
    }
}
