<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
                'username' => 'admin',
                'name' => 'Admin Aplikasi',
                'email' => 'proyekdadin@gmail.com',
                'password' => Hash::make('admin'),
                'phone' => NULL,
                'role' => 'ADMIN',
                'shop_name' => NULL,
                'shop_desc' => NULL
            ],
            [
                'username' => 'm_dadinz',
                'name' => 'Muhamad Ahmadin',
                'email' => 'dadhinz@gmail.com',
                'password' => Hash::make('m_dadinz'),
                'phone' => '089661030388',
                'role' => 'SELLER',
                'shop_name' => 'Dadin Shop',
                'shop_desc' => 'Kami menjual template baru yang elegan dan modern'
            ],
            [
                'username' => 'inggit',
                'name' => 'Inggit Marghita',
                'email' => 'inggitmarg@gmail.com',
                'password' => Hash::make('inggit'),
                'phone' => '085295405635',
                'role' => 'MEMBER',
                'shop_name' => NULL,
                'shop_desc' => NULL
            ],
        ];

        DB::table('users')->insert($data);
    }
}
