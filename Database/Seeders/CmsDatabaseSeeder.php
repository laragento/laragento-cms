<?php

namespace Laragento\Cms\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CmsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
        //$this->call(PageTableSeeder::class);

        $this->call(ElementTableSeeder::class);
        //$this->call(UpdateElementTableSeeder::class);
    }
}
