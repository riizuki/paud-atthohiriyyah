<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insert([
            ['key' => 'pmb_status', 'value' => '1'], // 1 = open, 0 = closed
            ['key' => 'pmb_year', 'value' => '2026/2027'],
        ]);
    }
}
