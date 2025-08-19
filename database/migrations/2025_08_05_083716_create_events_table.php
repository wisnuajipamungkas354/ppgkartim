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
        Schema::create('events', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('poster_image')->nullable();
            $table->string('name');
            $table->string('place');
            $table->date('date');

            $table->enum('attendance_method', ['manual', 'rfid', 'manual_rfid'])->default('manual');

            $table->time('start_time')->nullable();  // waktu presensi dimulai
            $table->time('end_time')->nullable();    // waktu presensi berakhir

            $table->json('column_config');           // konfigurasi kolom dinamis presensi
            $table->integer('kode_event');
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
