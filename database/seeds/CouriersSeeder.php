<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouriersSeeder extends Seeder
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
                'code' => 'jne',
                'title' => 'JNE'
            ],
            [
                'code' => 'pos',
                'title' => 'POS'
            ],
            [
                'code' => 'tiki',
                'title' => 'TIKI'
            ],
        ];

        DB::table('couriers')->insert($data);
    }
}
