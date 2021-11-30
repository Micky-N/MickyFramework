<?php


use App\Models\Product;
use App\Models\Stock;
use Phinx\Seed\AbstractSeed;

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
        $data = [];
        $ps = [];
        for ($i = 0; $i < 100; $i++) {
            $faker = Product::shuffleId();
            while(!empty($ps) && in_array($faker, $ps)){
                $faker = Product::shuffleId();
            }
            $ps[] = $faker;
            $data[] = [
                'code_product'  => $faker,
                'quantity' => rand(50, 250)
            ];
        }

        $this->table('stocks')->insert($data)->saveData();
    }
}
