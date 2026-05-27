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
        Schema::create('penguruses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insan_id')->nullable()->constrained('insans')->nullOnDelete();
            $table->string('dapukanable_type');
            $table->string('dapukanable_id');
            $table->string('jenis_dapukan'); // Organisasi, PPG, Umum
            $table->string('kategori_dapukan'); // LDII, ASAD, SENKOM, PPG, 4S dll
            $table->string('nama_dapukan'); // Kurikulum, Tendik, Ketua Muda/i, Ketua PJP, Wakil Ketua PJP, Pembina
            $table->boolean('is_active')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penguruses');
    }
};
