<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('allowed_id_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allowed_student_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('used_at');
            $table->string('action')->default('used'); // used, restored, etc.
            $table->string('note')->nullable();
            $table->timestamps();
            $table->foreign('allowed_student_id')->references('id')->on('allowed_student_ids');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
    public function down() {
        Schema::dropIfExists('allowed_id_audit_logs');
    }
};
