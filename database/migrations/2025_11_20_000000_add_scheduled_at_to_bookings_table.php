<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dateTime('scheduled_at')->nullable()->after('availability_id');
        });
    }
    public function down() {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('scheduled_at');
        });
    }
};
