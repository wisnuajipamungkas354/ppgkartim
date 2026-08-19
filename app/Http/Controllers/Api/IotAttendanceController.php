<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IotDevice;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class IotAttendanceController extends Controller
{
    /**
     * Authenticate device and get it from Bearer Token
     */
    private function getDevice(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return null;
        }

        return IotDevice::where('api_token', $token)->where('is_active', true)->first();
    }

    /**
     * Menormalisasi format sesi dari berbagai tipe acara menjadi satu format array baku.
     */
    private function normalizeEventSessions($event)
    {
        $eventSessions = [];

        if ($event->event_type === 'single') {
            $eventSessions[] = [
                'label' => 'Sesi Tunggal',
                'date' => $event->date,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
            ];
        } elseif ($event->event_type === 'multi_session') {
            foreach ($event->sessions ?? [] as $sesi) {
                $eventSessions[] = [
                    'label' => $sesi['label'] ?? 'Unknown Session',
                    'date' => $event->date,
                    'start_time' => $sesi['start_time'] ?? null,
                    'end_time' => $sesi['end_time'] ?? null,
                ];
            }
        } elseif ($event->event_type === 'multi_day') {
            foreach ($event->sessions ?? [] as $day) {
                $date = $day['date'] ?? null;
                foreach ($day['sesi'] ?? [] as $sesi) {
                    $eventSessions[] = [
                        'label' => $sesi['label'] ?? 'Unknown Session',
                        'date' => $date,
                        'start_time' => $sesi['start_time'] ?? null,
                        'end_time' => $sesi['end_time'] ?? null,
                    ];
                }
            }
        }

        return $eventSessions;
    }

    /**
     * Memparsing string waktu sesi menjadi instance Carbon dengan zona waktu Asia/Jakarta.
     * Mengembalikan array berisi [$startCarbon, $endCarbon, $sessionDateString].
     */
    private function getSessionTimeBounds($sesi, $fallbackDate)
    {
        $sessionDate = \Carbon\Carbon::parse($sesi['date'] ?? $fallbackDate)->format('Y-m-d');
        
        $startTimeStr = is_object($sesi['start_time']) ? $sesi['start_time']->format('H:i') : $sesi['start_time'];
        $endTimeStr = is_object($sesi['end_time']) ? $sesi['end_time']->format('H:i') : $sesi['end_time'];

        $start = $startTimeStr ? Carbon::parse($sessionDate . ' ' . $startTimeStr, 'Asia/Jakarta') : null;
        $end = $endTimeStr ? Carbon::parse($sessionDate . ' ' . $endTimeStr, 'Asia/Jakarta') : null;
        
        // Jika jam selesai lebih kecil dari jam mulai, berarti sesinya lewat tengah malam (ganti hari)
        if ($start && $end && $end->lt($start)) {
            $end->addDay();
        }

        return [$start, $end, $sessionDate];
    }
    
    /**
     * API 1: Mengecek acara dan sesi yang sedang berlangsung
     * Endpoint: GET /api/v1/device/status
     */
    public function checkActiveSession(Request $request)
    {
        $device = $this->getDevice($request);
        if (!$device) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized device'], 401);
        }

        // Cari event milik user perangkat yang sedang aktif
        $activeEvents = Event::where('user_id', $device->user_id)
            ->where('is_active', true)
            ->get();

        // Gunakan timezone spesifik untuk menghindari masalah server production yang berjalan di UTC
        $now = Carbon::now('Asia/Jakarta');
        $todayStr = $now->format('Y-m-d');
        
        $currentSession = null;
        $currentEvent = null;

        // Iterasi semua event yang aktif untuk mencari sesi yang berlangsung HARI INI
        // dan jamnya mencakup waktu sekarang
        foreach ($activeEvents as $event) {
            $eventSessions = $this->normalizeEventSessions($event);

            foreach ($eventSessions as $sesi) {
                [$start, $end, $sessionDate] = $this->getSessionTimeBounds($sesi, $todayStr);
                
                if ($start && $end) {
                    // Cek apakah jam saat ini berada di dalam rentang sesi
                    // Diberi toleransi: start - 30 menit s/d end + 60 menit
                    $validStart = $start->copy()->subMinutes(30);
                    $validEnd = $end->copy()->addMinutes(60);
                    
                    if ($now->between($validStart, $validEnd)) {
                        $currentSession = $sesi;
                        $currentSession['date'] = $sessionDate;
                        $currentEvent = $event;
                        break 2; // Langsung keluar dari pencarian event & sesi
                    }
                }
            }
        }

        if (!$currentEvent || !$currentSession) {
            return response()->json(['status' => 'idle', 'message' => 'Tidak ada acara yang sedang berlangsung']);
        }

        // Cari tahu key mana di data_json yang menyimpan nama peserta
        $nameField = 'nama';
        foreach ($currentEvent->column_config ?? [] as $config) {
            $label = strtolower($config['label'] ?? '');
            if (str_contains($label, 'nama') || str_contains($label, 'name')) {
                $nameField = $config['field'];
                break;
            }
        }

        // Ambil data peserta untuk event ini
        $participants = EventParticipant::where('event_id', $currentEvent->id)
            ->get()
            ->map(function ($p) use ($nameField) {
                $nama = $p->data_json[$nameField] ?? null;
                
                // Fallback: ambil value pertama jika key tidak ditemukan
                if (!$nama && is_array($p->data_json) && count($p->data_json) > 0) {
                    $nama = reset($p->data_json);
                }
                
                return [
                    'rfid_tag' => $p->rfid_tag,
                    'nama' => $nama ?? 'Peserta'
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'event_id' => $currentEvent->id,
                'event_name' => $currentEvent->name,
                'session_date' => $currentSession['date'],
                'session_label' => $currentSession['label'] ?? 'Unknown Session',
                'start_time' => $currentSession['start_time'],
                'end_time' => $currentSession['end_time'],
                'participants' => $participants
            ]
        ]);
    }

    /**
     * API 2: Menerima data presensi batch dari ESP32
     * Endpoint: POST /api/v1/device/attendances
     */
    public function submitBatchAttendances(Request $request)
    {
        $device = $this->getDevice($request);
        if (!$device) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized device'], 401);
        }

        $eventId = $request->input('event_id');
        $sessionDate = $request->input('session_date');
        $sessionLabel = $request->input('session_label');
        $scans = $request->input('scans', []); // Array of ['rfid_tag' => '...', 'scanned_at' => 'Y-m-d H:i:s']

        if (!$eventId || empty($scans)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
        }

        $event = Event::where('id', $eventId)->where('user_id', $device->user_id)->first();
        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Event not found or unauthorized'], 404);
        }

        // Ambil waktu mulai/selesai sesi untuk kalkulasi keterlambatan
        $start = null;
        $end = null;
        
        $eventSessions = $this->normalizeEventSessions($event);
        foreach ($eventSessions as $sesi) {
            [$sesiStart, $sesiEnd, $sessionDateParsed] = $this->getSessionTimeBounds($sesi, $sessionDate);
            
            // Cocokkan berdasarkan tanggal dan label sesi
            if ($sessionDateParsed === $sessionDate && ($sesi['label'] ?? '') === $sessionLabel) {
                $start = $sesiStart;
                $end = $sesiEnd;
                break;
            }
        }

        $lateTime = $start ? $start->copy()->addMinutes(10) : null;
        $insertedCount = 0;

        foreach ($scans as $scan) {
            $rfidTag = $scan['rfid_tag'] ?? null;
            $scannedAtStr = $scan['scanned_at'] ?? null;
            
            if (!$rfidTag || !$scannedAtStr) continue;

            $participant = EventParticipant::where('event_id', $eventId)
                ->where('rfid_tag', $rfidTag)
                ->first();

            if (!$participant) continue;

            // Cek apakah sudah presensi
            $already = Attendance::where('event_id', $eventId)
                ->where('participant_id', $participant->id)
                ->where('date', $sessionDate)
                ->where('session_label', $sessionLabel)
                ->exists();

            if ($already) continue;

            try {
                // Parse timestamp dari alat dengan timezone yang sama
                $scannedAt = Carbon::parse($scannedAtStr, 'Asia/Jakarta');
            } catch (\Exception $e) {
                $scannedAt = Carbon::now('Asia/Jakarta');
            }

            // Hitung status kedatangan
            $status = null;
            if ($start && $end) {
                if ($scannedAt->lt($start)) {
                    $status = 'in_time';
                } elseif ($scannedAt->between($start, $lateTime)) {
                    $status = 'on_time';
                } else {
                    $status = 'over_time';
                }
            }

            Attendance::create([
                'event_id' => $eventId,
                'participant_id' => $participant->id,
                'date' => $sessionDate,
                'session_label' => $sessionLabel,
                'check_in_at' => $scannedAt,
                'arrival_status' => $status,
            ]);

            $insertedCount++;
        }

        return response()->json([
            'status' => 'success',
            'message' => "Berhasil menyimpan $insertedCount data presensi."
        ]);
    }
}
