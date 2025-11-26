<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('allowed_student_ids', function (Blueprint $table) {
            $table->dropColumn('education_level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('allowed_student_ids', function (Blueprint $table) {
            $table->string('education_level')->nullable();
        });
    }
};
