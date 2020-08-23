<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		DB::table('products')->insert([
            'id' => 1,
            'name' => 'Product # 1',
            'stock' => '50',
        ]);

        DB::table('products')->insert([
            'id' => 2,
            'name' => 'Product # 2',
            'stock' => '100',
        ]);

        DB::table('products')->insert([
            'id' => 3,
            'name' => 'Product # 3',
            'stock' => '88',
        ]);
		
		DB::table('products')->insert([
            'id' => 4,
            'name' => 'Product # 4',
            'stock' => '100',
        ]);
        // $this->call(UserSeeder::class);
    }
}
