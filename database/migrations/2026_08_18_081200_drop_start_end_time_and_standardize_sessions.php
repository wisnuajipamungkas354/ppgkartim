<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Pindahkan data lama ke struktur JSON 'sessions' yang baru
        $events = DB::table('events')->get();
        foreach ($events as $event) {
            $sessions = json_decode($event->sessions, true);
            
            if (empty($sessions)) {
                // Jika event lama (belum pakai kolom sessions)
                $sessions = [
                    [
                        'date' => $event->date,
                        'sesi' => [
                            [
                                'label' => 'Sesi Tunggal',
                                'start_time' => $event->start_time ? substr($event->start_time, 0, 5) : null,
                                'end_time' => $event->end_time ? substr($event->end_time, 0, 5) : null,
                            ]
                        ]
                    ]
                ];
                DB::table('events')->where('id', $event->id)->update(['sessions' => json_encode($sessions)]);
            } else {
                // Jika event terlanjur tersimpan dengan format multi_session lama (tidak ada bungkus 'date' & 'sesi')
                if (isset($sessions[0]['label'])) {
                    $sessions = [
                        [
                            'date' => $event->date,
                            'sesi' => $sessions
                        ]
                    ];
                    DB::table('events')->where('id', $event->id)->update(['sessions' => json_encode($sessions)]);
                }
            }
        }

        // Hapus kolom start_time dan end_time
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
        });
    }
};
