<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllowedStudentIdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        DB::table('allowed_student_ids')->insert([
            ['student_id' => 'DWC001', 'education_level' => 'college', 'used' => false, 'created_at' => $now, 'updated_at' => $now],
            ['student_id' => 'DWC002', 'education_level' => 'college', 'used' => false, 'created_at' => $now, 'updated_at' => $now],
            ['student_id' => 'DWC003', 'education_level' => 'basic', 'used' => false, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
