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
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->string('event_id');
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('rfid_tag')->nullable()->unique(); // bisa kosong jika manual
            $table->json('data_json');                         // fleksibel simpan data peserta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participants');
    }
};
