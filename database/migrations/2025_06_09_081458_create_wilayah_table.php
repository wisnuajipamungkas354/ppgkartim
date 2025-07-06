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
        // Desa Table
        Schema::create('desas', function (Blueprint $table) {
            $table->id();
            $table->string('nm_desa');
            $table->string('alias')->nullable(); // Nama PC
            $table->timestamps();
        });

        // Kelompok Table
        Schema::create('kelompoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas', 'id');
            $table->string('nm_kelompok');
            $table->string('alias')->nullable(); // Nama PAC
            $table->string('nm_masjid')->nullable(); // Nama masjid PAC
            $table->boolean('is_desa'); // Masjid Desa atau bukan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desas');
        Schema::dropIfExists('kelompoks');
    }
};
