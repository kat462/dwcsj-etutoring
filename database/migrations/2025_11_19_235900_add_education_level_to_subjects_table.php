<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        if (!Schema::hasTable('subjects')) {
            return;
        }

        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'education_level')) {
                $table->string('education_level')->nullable();
            }
        });
    }
    public function down() {
        if (!Schema::hasTable('subjects')) {
            return;
        }

        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'education_level')) {
                $table->dropColumn('education_level');
            }
        });
    }
};
