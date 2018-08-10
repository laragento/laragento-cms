<?php

namespace Laragento\Cms\Database\Seeders;

use Illuminate\Database\Seeder;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Page;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Homepage

           factory(Page::class)->create([
               'title' => 'Home',
               'slug' => str_slug('Home'),
               'url_path' => '/'
           ]);


    }
}
