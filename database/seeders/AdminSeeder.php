<?php
namespace Database\Seeders; use Illuminate\Database\Seeder; use App\Models\User;
class AdminSeeder extends Seeder{ public function run(){ User::create(['student_id'=>'ADMIN001','name'=>'System Admin','email'=>'admin@dwcsj.edu.ph','role'=>'admin','password'=>'password123']); } }
