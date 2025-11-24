<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        if (!Schema::hasTable('bookings')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'scheduled_at')) {
                $table->dateTime('scheduled_at')->nullable();
            }
        });
    }
    public function down() {
        if (!Schema::hasTable('bookings')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'scheduled_at')) {
                $table->dropColumn('scheduled_at');
            }
        });
    }
};
