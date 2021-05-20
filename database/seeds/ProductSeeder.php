<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<20;$i++)
        {
            DB::table('products')->insert([
                'name' => Str::random(10),
                'description' => Str::random(10),
                'image' => Str::random(10),
                'color' => Str::random(10),
                'price' => $i,
            ]);
        }
    }
}
