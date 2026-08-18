<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Jenis kegiatan: satu sesi, multi sesi dalam 1 hari, atau multi hari
            $table->string('event_type')->default('single')->after('place');

            // Menyimpan hari & sesi secara nested (untuk multi_session & multi_day)
            // Struktur: [{ date, sesi: [{ label, start_time, end_time }] }]
            $table->json('sessions')->nullable()->after('event_type');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['event_type', 'sessions']);
        });
    }
};
