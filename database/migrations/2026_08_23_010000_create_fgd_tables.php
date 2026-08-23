<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fgd_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('fgd_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fgd_session_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('fgd_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fgd_session_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('fgd_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fgd_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fgd_theme_id')->constrained()->cascadeOnDelete();
            $table->string('notulis_name')->nullable();
            
            // Problem - Penyebab - Solusi
            $table->text('problem')->nullable();
            $table->text('penyebab')->nullable();
            $table->text('solusi')->nullable();

            // Action Plan
            $table->text('ap_deskripsi')->nullable();
            $table->text('ap_nama_kegiatan')->nullable();
            $table->text('ap_peserta')->nullable();
            $table->text('ap_waktu')->nullable();
            $table->text('ap_dana')->nullable();

            // Peran 5 Unsur
            $table->text('peran_keimaman')->nullable();
            $table->text('peran_pengurus')->nullable();
            $table->text('peran_orang_tua')->nullable();
            $table->text('peran_mubaligh')->nullable();
            $table->text('peran_ahli_pendidik')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fgd_notes');
        Schema::dropIfExists('fgd_themes');
        Schema::dropIfExists('fgd_groups');
        Schema::dropIfExists('fgd_sessions');
    }
};
