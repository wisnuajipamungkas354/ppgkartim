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
            margin-bottom: 5px;
        }

        .note-notulis {
            font-style: italic;
            color: #555;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: auto;
            table-layout: fixed; /* Helps dompdf calculate breaks better */
        }

        .section-title {
            font-weight: bold;
            font-size: 13px;
            color: #2980b9;
            margin-top: 15px;
            margin-bottom: 5px;
            border-bottom: 1px solid #2980b9;
            display: inline-block;
        }

        thead {
            display: table-header-group; /* Ensures thead repeats on new pages and sticks to tbody */
        }

        tbody {
            display: table-row-group;
        }

        tr {
            page-break-inside: auto;
        }

        th, td {
            border: 1px solid #bdc3c7;
            padding: 8px;
            vertical-align: top;
            word-wrap: break-word; /* Prevents overflow */
        }

        th {
            background-color: #ecf0f1;
            font-weight: bold;
            color: #2c3e50;
            text-align: left;
        }

        .ap-th {
            width: 20%;
        }

        .vertical-list {
            margin-bottom: 15px;
            border: 1px solid #bdc3c7;
            padding: 10px;
            page-break-inside: auto;
        }

        .list-item {
            margin-bottom: 12px;
            page-break-inside: auto;
        }

        .list-item:last-child {
            margin-bottom: 0;
        }

        .list-label {
            font-weight: bold;
            color: #2c3e50;
            background-color: #ecf0f1;
            padding: 4px 8px;
            border-left: 3px solid #2980b9;
            margin-bottom: 5px;
            page-break-after: avoid; /* Keep label attached to its content */
        }

        .list-content {
            padding: 0 8px;
            page-break-inside: auto;
        }

        /* Prevent empty p tags in rich text from taking too much space */
        p { margin-top: 0; margin-bottom: 8px; }
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
                    <h2 style="margin: 0; font-size: 20px; color: #2c3e50; text-transform: uppercase; letter-spacing: 1px;">Laporan Hasil Focus Group Discussion (FGD)</h2>
                    <p style="margin: 5px 0 0 0; font-size: 14px; color: #7f8c8d;">Sesi: <strong>{{ $session->name }}</strong> | Tanggal: {{ \Carbon\Carbon::parse($session->date)->translatedFormat('d F Y') }}</p>
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
        @foreach($notes as $index => $note)
            <div class="note-container">
                <table>
                    <thead>
                        <tr>
                            <td colspan="3" style="border: none; padding: 0;">
                                <div class="note-header">
                                    Grup: {{ $note->group->name ?? '-' }} &nbsp;&nbsp;|&nbsp;&nbsp; Tema: {{ $note->theme->title ?? '-' }}
                                </div>
                                <div class="note-notulis">
                                    Notulis: {{ $note->notulis_name ?? 'Tidak ada nama' }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 33.33%;">Problem</th>
                            <th style="width: 33.33%;">Penyebab</th>
                            <th style="width: 33.33%;">Solusi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="height: 0; border: none; padding: 0;">
                            <td colspan="3" style="height: 0; border: none; padding: 0; line-height: 0;"></td>
                        </tr>
                        <tr>
                            <td>{!! $note->problem ?: '-' !!}</td>
                            <td>{!! $note->penyebab ?: '-' !!}</td>
                            <td>{!! $note->solusi ?: '-' !!}</td>
                        </tr>
                    </tbody>
                </table>

                @if($note->ap_deskripsi || $note->ap_nama_kegiatan || $note->ap_peserta || $note->ap_waktu || $note->ap_dana)
                    <div class="section-title">Action Plan</div>
                    <div class="vertical-list">
                        @if($note->ap_deskripsi)
                            <div class="list-item">
                                <div class="list-label">Deskripsi</div>
                                <div class="list-content">{!! $note->ap_deskripsi !!}</div>
                            </div>
                        @endif
                        @if($note->ap_nama_kegiatan)
                            <div class="list-item">
                                <div class="list-label">Nama Kegiatan</div>
                                <div class="list-content">{!! $note->ap_nama_kegiatan !!}</div>
                            </div>
                        @endif
                        @if($note->ap_peserta)
                            <div class="list-item">
                                <div class="list-label">Peserta</div>
                                <div class="list-content">{!! $note->ap_peserta !!}</div>
                            </div>
                        @endif
                        @if($note->ap_waktu)
                            <div class="list-item">
                                <div class="list-label">Waktu</div>
                                <div class="list-content">{!! $note->ap_waktu !!}</div>
                            </div>
                        @endif
                        @if($note->ap_dana)
                            <div class="list-item">
                                <div class="list-label">Dana</div>
                                <div class="list-content">{!! $note->ap_dana !!}</div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach
    @endif

</body>
</html>
