<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.b
     */
    public function up(): void
    {
        Schema::create('uang_kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('role_id');
            $table->date('tgl_transaksi');
            $table->string('nm_penginput')->nullable();
            $table->enum('jenis_kas', ['PEMASUKAN', 'PENGELUARAN']);
            $table->float('nominal');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uang_kas');
    }
};
