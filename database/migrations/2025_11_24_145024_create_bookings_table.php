<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tutor_id');
            $table->unsignedBigInteger('tutee_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('status')->default('pending');
            $table->dateTime('scheduled_at')->nullable();
            $table->integer('duration')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tutee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
