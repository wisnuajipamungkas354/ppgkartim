<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan FGD - {{ $session->name }}</title>
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
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
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
            font-size: 14px;
            color: #7f8c8d;
        }

        .note-container {
            margin-bottom: 40px;
            page-break-inside: auto;
        }

        .note-header {
            background-color: #2c3e50;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            page-break-after: avoid;
        }

        .note-notulis {
            font-style: italic;
            margin-bottom: 15px;
            color: #555;
            page-break-after: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: auto;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 13px;
            color: #2980b9;
            margin-top: 15px;
            margin-bottom: 5px;
            border-bottom: 1px solid #2980b9;
            display: inline-block;
            page-break-after: avoid; /* Prevent break after title */
        }

        thead {
            page-break-inside: avoid;
            page-break-after: avoid;
        }

        tbody {
            page-break-inside: auto;
            page-break-before: auto;
        }

        tr {
            page-break-inside: auto;
            page-break-after: auto;
        }

        th, td {
            border: 1px solid #bdc3c7;
            padding: 8px;
            vertical-align: top;
            page-break-inside: auto;
        }

        th {
            background-color: #ecf0f1;
            font-weight: bold;
            color: #2c3e50;
            text-align: left;
            width: 33.33%;
        }

        .ap-th {
            width: 20%;
        }

        /* Prevent empty p tags in rich text from taking too much space */
        p { margin-top: 0; margin-bottom: 8px; page-break-inside: auto; }
    </style>
</head>
<body>

    <header>
        <table style="width: 100%; border: none; margin-bottom: 0;">
            <tr>
                <td style="width: 15%; text-align: left; border: none; padding: 0; vertical-align: middle;">
                    @if(!empty($logoCai))
                        <img src="{{ $logoCai }}" alt="Logo CAI" style="max-height: 65px;">
                    @endif
                </td>
                <td style="width: 70%; text-align: center; border: none; padding: 0; vertical-align: middle;">
                    <h2>Laporan Hasil Focus Group Discussion (FGD)</h2>
                    <p>Sesi: <strong>{{ $session->name }}</strong> | Tanggal: {{ \Carbon\Carbon::parse($session->date)->translatedFormat('d F Y') }}</p>
                </td>
                <td style="width: 15%; text-align: right; border: none; padding: 0; vertical-align: middle;">
                    @if(!empty($logoPpg))
                        <img src="{{ $logoPpg }}" alt="Logo PPG" style="max-height: 65px;">
                    @endif
                </td>
            </tr>
        </table>
    </header>

    @if($notes->isEmpty())
        <p style="text-align: center; color: #7f8c8d; margin-top: 50px;">Belum ada catatan FGD untuk sesi ini.</p>
    @else
        @foreach($notes as $note)
            <div class="note-container">
                <div class="note-header">
                    Grup: {{ $note->group->name ?? '-' }} &nbsp;&nbsp;|&nbsp;&nbsp; Tema: {{ $note->theme->title ?? '-' }}
                </div>
                <div class="note-notulis">
                    Notulis: {{ $note->notulis_name ?? 'Tidak ada nama' }}
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Problem</th>
                            <th>Penyebab</th>
                            <th>Solusi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{!! $note->problem ?: '-' !!}</td>
                            <td>{!! $note->penyebab ?: '-' !!}</td>
                            <td>{!! $note->solusi ?: '-' !!}</td>
                        </tr>
                    </tbody>
                </table>

                @if($note->ap_deskripsi || $note->ap_nama_kegiatan || $note->ap_peserta || $note->ap_waktu || $note->ap_dana)
                    <div class="section-title">Action Plan</div>
                    <table>
                        <thead>
                            <tr>
                                <th class="ap-th">Deskripsi</th>
                                <th class="ap-th">Nama Kegiatan</th>
                                <th class="ap-th">Peserta</th>
                                <th class="ap-th">Waktu</th>
                                <th class="ap-th">Dana</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{!! $note->ap_deskripsi ?: '-' !!}</td>
                                <td>{!! $note->ap_nama_kegiatan ?: '-' !!}</td>
                                <td>{!! $note->ap_peserta ?: '-' !!}</td>
                                <td>{!! $note->ap_waktu ?: '-' !!}</td>
                                <td>{!! $note->ap_dana ?: '-' !!}</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>

            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach
    @endif

</body>
</html>
