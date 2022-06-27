<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::create([
            'name' => 'Laptops', 'slug' => 'laptops',
        ]);
        Group::create([
            'name' => 'Desktops', 'slug' => 'desktops',
        ]);
        Group::create([
            'name' => 'Mobile Phones', 'slug' => 'mobile-phones',
        ]);
        Group::create([
            'name' => 'Tablets', 'slug' => 'tablets',
        ]);
        Group::create([
            'name' => 'TVs', 'slug' => 'tvs',
        ]);
        Group::create([
            'name' => 'Digital Cameras', 'slug' => 'digital-cameras',
        ]);
        Group::create([
            'name' => 'Appliances', 'slug' => 'appliances',
        ]);
    }
}
