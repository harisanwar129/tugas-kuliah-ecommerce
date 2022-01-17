<?php


use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Power Point',
                'slug' => 'powerpoint',
                'grup_menu' => 'templates'
            ],
            [
                'name' => 'Word',
                'slug' => 'word',
                'grup_menu' => 'templates'
            ],
            [
                'name' => 'Manajemen',
                'slug' => 'manajemen',
                'grup_menu' => 'tools'
            ],
            [
                'name' => 'Programming',
                'slug' => 'programming',
                'grup_menu' => 'tools'
            ],
        ];
    }
}
