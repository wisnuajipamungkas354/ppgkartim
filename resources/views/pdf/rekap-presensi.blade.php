<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekap Presensi</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        header h2 {
            margin: 0;
            font-size: 20px;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        header p {
            margin: 5px 0 0 0;
            font-size: 13px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 0 6px rgba(0,0,0,0.1);
        }

        thead {
            background: #2c3e50;
            color: #fff;
        }

        thead th {
            padding: 10px;
            text-align: center;
            font-size: 12px;
        }

        tbody td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        tfoot td {
            padding: 10px;
            font-weight: bold;
            border-top: 2px solid #2c3e50;
            background: #ecf0f1;
        }

        /* Warna status */
        .hadir {
            color: #27ae60;
            font-weight: bold;
        }
        .izin {
            color: #f39c12;
            font-weight: bold;
        }
        .alpha {
            color: #e74c3c;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="text-align: center">Rekapitulasi Presensi {{ $event->name }}</h1>
        <p style="text-align: center">
          {{ Carbon\Carbon::parse($event->date)->format('d/m/Y') }} | 
          Jam {{ Carbon\Carbon::parse($event->start_time)->format('H:i') }} s/d {{ Carbon\Carbon::parse($event->end_time)->format('H:i') }} | 
          {{ $event->place }}
        </p>
    </div>
    <hr>
    <p>Total Peserta  : {{ $totalParticipant }}</p>
    <p>Hadir  : {{ $hadir }}</p>
    <p>Tidak Hadir  : {{ $alfa }}</p>

    <table class="content-table">
        <thead>
            <tr>
              <th>No</th>
              <th>RFID Tag</th>
              @foreach($event->column_config as $column)
                <th>{{ $column['label'] }}</th>
              @endforeach
              <th>Jam</th>
              <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($eventParticipant as $index => $participant)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $participant->rfid_tag }}</td>
                    @foreach($event->column_config as $column)
                        <td>{{ $participant->data_json[$column['field']] }}</td>
                    @endforeach
                    <td>{{ $participant->attendance?->check_in_at != null ? \Carbon\Carbon::parse($participant->attendance->check_in_at)->format('H:i') : '' }}</td>
                    <td>{{ $participant->attendance->arrival_status ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Rekap Presensi dibuat pada: {{ \Carbon\Carbon::now()->locale('id')->format('d F Y H:i') }}</p>
    </div>
</body>
</html>