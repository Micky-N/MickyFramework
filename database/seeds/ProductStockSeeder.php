<?php


use App\Models\Product;
use App\Models\Stock;
use Phinx\Seed\AbstractSeed;

class ProductStockSeeder extends AbstractSeed
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
            $faker = [Product::shuffleId(), Stock::shuffleId()];
            while(!empty($ps) && in_array($faker, $ps)){
                $faker = [Product::shuffleId(), Stock::shuffleId()];
            }
            $ps[] = $faker;
            $data[] = [
                'code_product'  => $faker[0],
                'code_stock'  => $faker[1],
                'quantity' => rand(50, 250)
            ];
        }

        $this->table('product_stocks')->insert($data)->saveData();
    }
}
