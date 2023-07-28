<?php

use App\Product;
// use Database\Seeds\CategorySeeder;
// use CategorySeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategorySeeder::class);
        factory(App\Product::class, 50)->create();


        DB::table('users')->insert([
            'name' => 'Nazal Gusti Prastya',
            'email' => 'nazalprastya@gmail.com',
            'password' => bcrypt('password'),
            'address' => 'Ciomas',
            'phone' => '089516439498'
        ]);

    }

}
