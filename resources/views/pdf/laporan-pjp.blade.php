<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan PJP {{ $namaLaporan }} - {{ $bulanTahun }}</title>
    <style>
        /* Reset & Base Styles */
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9.5px; color: #333; line-height: 1.3; margin: 0; padding: 0; }
        
        /* Typography & Alignment */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .title { font-size: 13px; font-weight: bold; margin-bottom: 2px; text-transform: uppercase; text-decoration: underline; }
        .subtitle { font-size: 11px; margin-bottom: 10px; }
        
        /* Section Titles */
        .section-title { font-size: 11px; font-weight: bold; margin-top: 10px; margin-bottom: 3px; background-color: #e9ecef; padding: 4px; }
        .sub-section-title { font-size: 10px; font-weight: bold; margin-top: 4px; margin-bottom: 3px; padding-left: 5px; text-transform: uppercase; }
        
        /* Kop Surat Styles (Tabel 3 Kolom) */
        table.kop-surat { width: 100%; border-bottom: 3px solid #000; margin-bottom: 10px; border-collapse: collapse; }
        table.kop-surat td { padding: 4px; vertical-align: middle; border: none; }
        .kop-logo { width: 15%; text-align: left; }
        .kop-logo img { width: 70px; height: auto; }
        .kop-text { width: 70%; text-align: center; } 
        .kop-empty { width: 15%; }
        .kop-title-text { font-size: 16px; font-weight: bold; text-transform: uppercase; margin-bottom: 3px; }
        .kop-contact { font-size: 8.5px; }
        
        /* Tables Data */
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        table.data-table th, table.data-table td { border: 1px solid #000; padding: 3px; vertical-align: top; }
        table.data-table th { background-color: #f8f9fa; text-align: center; font-weight: bold; }
        table.data-table td.center { text-align: center; }
        
        /* Baris Total */
        tr.row-total td { background-color: #f1f3f5; font-weight: bold; }
        
        /* Utility */
        .page-break { page-break-before: always; }
        
        /* Lampiran/Gallery */
        .lampiran-section { margin-bottom: 15px; }
        .lampiran-title { font-weight: bold; margin-bottom: 5px; font-size: 11px; }
        table.gallery-table { width: 100%; border: none; }
        table.gallery-table td { border: none; padding: 5px; text-align: center; width: 50%; }
        .photo-box img { max-width: 100%; max-height: 200px; border: 1px solid #ccc; padding: 3px; background: #fff; }
    </style>
</head>
<body>

    <table class="kop-surat">
        <tr>
            <td class="kop-logo">
                <img src="{{ $logoImg }}">
            </td>
            <td class="kop-text">
                <div class="kop-title-text">Penggerak Pembina Generus (PPG)<br>Karawang Timur</div>
                <div class="kop-contact">
                    Email: ppgkarawangtimur@gmail.com | Instagram: @ppg_kartim<br>
                    Website: www.ppgkartim.org | WhatsApp: 0812-3456-7890
                </div>
            </td>
            <td class="kop-empty"></td>
        </tr>
    </table>

    <div class="text-center">
        <div class="title">LAPORAN PJP {{ strtoupper($namaLaporan) }}</div>
        <div class="subtitle">Bulan: {{ $bulanTahun }}</div>
    </div>

    <div class="section-title">I. KEGIATAN</div>
    
    <div class="sub-section-title">A. RUTIN</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Kegiatan</th>
                <th width="20%">Jumlah Terlaksana</th>
                <th width="40%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kegiatanRutin as $index => $rutin)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ $rutin->nm_kegiatan }}</td>
                <td class="center">{{ $rutin->jml_terlaksana }}</td>
                <td>{{ $rutin->keterangan }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="center">Tidak ada kegiatan rutin bulan ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="sub-section-title">B. KHUSUS</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="25%">Nama Kegiatan</th>
                <th width="25%">Materi</th>
                <th width="15%">Peserta</th>
                <th width="15%">Dokumentasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kegiatanKhusus as $index => $khusus)
            <tr>
                <td class="center">{{ $index+1; }}</td>
                <td class="center">{{ \Carbon\Carbon::parse($khusus->tanggal)->format('d M Y') }}</td>
                <td>{{ $khusus->nm_kegiatan }}</td>
                <td>{{ $khusus->materi }}</td>
                <td class="center">{{ $khusus->peserta }} Org</td>
                <td class="center text-bold">
                    {{ !empty($khusus->dokumentasi) ? 'Lampiran I.B-'.($index + 1) : '-' }}
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="center">Tidak ada kegiatan khusus bulan ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">II. SENSUS GENERUS (TOTAL JIWA)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Kategori Generus</th>
                <th width="20%">Laki-laki</th>
                <th width="20%">Perempuan</th>
                <th width="20%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $kategoriSensus = ['paud' => 'Paud/TK', 'cbr' => 'Caberawit (CBR)', 'pra_remaja' => 'Pra Remaja', 'remaja' => 'Remaja', 'pra_nikah' => 'Pra Nikah'];
                $total_l = 0; $total_p = 0; $no = 1;
            @endphp

            @foreach($kategoriSensus as $key => $label)
                @php
                    $l = $sensus[$key]['l'] ?? 0;
                    $p = $sensus[$key]['p'] ?? 0;
                    $jml = $l + $p;
                    $total_l += $l;
                    $total_p += $p;
                @endphp
                <tr>
                    <td class="center">{{ $no++ }}</td>
                    <td>{{ $label }}</td>
                    <td class="center">{{ $l }}</td>
                    <td class="center">{{ $p }}</td>
                    <td class="center text-bold">{{ $jml }}</td>
                </tr>
            @endforeach
            <tr class="row-total">
                <td colspan="2" class="center">TOTAL</td>
                <td class="center">{{ $total_l }}</td>
                <td class="center">{{ $total_p }}</td>
                <td class="center">{{ $total_l + $total_p }}</td>
            </tr>
        </tbody>
    </table>

    {{-- TODO: Tampilkan kembali jika struktur data arus mutasi sudah siap
    <div class="section-title">III. ARUS GENERUS (MUTASI)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Keterangan</th>
                <th width="11%">Paud/TK</th>
                <th width="11%">Caberawit</th>
                <th width="11%">Pra Remaja</th>
                <th width="11%">Remaja</th>
                <th width="11%">Pra Nikah</th>
                <th width="15%">Total</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $kategoriMutasi = ['pindah' => 'Pindah', 'menikah' => 'Menikah', 'meninggal' => 'Meninggal', 'amar_maruf' => "Amar Ma'ruf"];
                $totalKolom = ['paud' => 0, 'cbr' => 0, 'pra_remaja' => 0, 'remaja' => 0, 'pra_nikah' => 0];
                $no = 1;
            @endphp

            @foreach($kategoriMutasi as $key => $label)
                @php
                    $pd = $arus[$key]['paud'] ?? 0;
                    $cb = $arus[$key]['cbr'] ?? 0;
                    $pr = $arus[$key]['pra_remaja'] ?? 0;
                    $rm = $arus[$key]['remaja'] ?? 0;
                    $pn = $arus[$key]['pra_nikah'] ?? 0;
                    $jml_baris = $pd + $cb + $pr + $rm + $pn;
                    $totalKolom['paud'] += $pd;
                    $totalKolom['cbr'] += $cb;
                    $totalKolom['pra_remaja'] += $pr;
                    $totalKolom['remaja'] += $rm;
                    $totalKolom['pra_nikah'] += $pn;
                @endphp
                <tr>
                    <td class="center">{{ $no++ }}</td>
                    <td>{{ $label }}</td>
                    <td class="center">{{ $pd }}</td>
                    <td class="center">{{ $cb }}</td>
                    <td class="center">{{ $pr }}</td>
                    <td class="center">{{ $rm }}</td>
                    <td class="center">{{ $pn }}</td>
                    <td class="center text-bold">{{ $jml_baris }}</td>
                </tr>
            @endforeach
            <tr class="row-total">
                <td colspan="2" class="center">TOTAL</td>
                <td class="center">{{ $totalKolom['paud'] }}</td>
                <td class="center">{{ $totalKolom['cbr'] }}</td>
                <td class="center">{{ $totalKolom['pra_remaja'] }}</td>
                <td class="center">{{ $totalKolom['remaja'] }}</td>
                <td class="center">{{ $totalKolom['pra_nikah'] }}</td>
                <td class="center">{{ array_sum($totalKolom) }}</td>
            </tr>
        </tbody>
    </table>
    --}}

    <div class="section-title">III. KEPENGURUSAN</div>
    
    @if(isset($isKelompok) && $isKelompok)
        <div class="sub-section-title">A. PENGURUS PJP</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Dapukan</th>
                    <th width="35%">Nama Lengkap</th>
                    <th width="25%">No WA</th>
                </tr>
            </thead>
            <tbody>
                @php $pengurusPjp = $kepengurusan->where('kategori_pengurus', 'PJP')->values(); @endphp
                @forelse($pengurusPjp as $index => $pengurus)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $pengurus->nm_dapukan }}</td>
                    <td>{{ $pengurus->nm_pengurus }}</td>
                    <td class="center">{{ $pengurus->no_telp }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="center">Data pengurus PJP belum diisi.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="sub-section-title">B. PENGURUS 5 UNSUR</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Dapukan</th>
                    <th width="35%">Nama Lengkap</th>
                    <th width="25%">No WA</th>
                </tr>
            </thead>
            <tbody>
                @php $pengurusLimaUnsur = $kepengurusan->where('kategori_pengurus', 'LIMA UNSUR')->values(); @endphp
                @forelse($pengurusLimaUnsur as $index => $pengurus)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $pengurus->nm_dapukan }}</td>
                    <td>{{ $pengurus->nm_pengurus }}</td>
                    <td class="center">{{ $pengurus->no_telp }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="center">Data pengurus 5 unsur belum diisi.</td></tr>
                @endforelse
            </tbody>
        </table>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Dapukan</th>
                    <th width="35%">Nama Lengkap</th>
                    <th width="25%">No WA</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kepengurusan as $index => $pengurus)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $pengurus->nm_dapukan }}</td>
                    <td>{{ $pengurus->nm_pengurus }}</td>
                    <td class="center">{{ $pengurus->no_telp }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="center">Data kepengurusan belum diisi.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="section-title">IV. MUSYAWAROH RUTIN</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Judul Musyawaroh</th>
                <th width="15%">Status</th>
                <th width="15%">Tanggal</th>
                <th width="25%">Keterangan</th>
                <th width="15%">Dokumentasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($musyawaroh as $index => $musy)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ $musy->judul_musyawaroh }}</td>
            <td class="center">{{ $musy->tanggal !== null ? 'Terlaksana' : 'Belum Terlaksana' }}</td>
                <td class="center">{{ $musy->tanggal ? \Carbon\Carbon::parse($musy->tanggal)->format('d M Y') : '-' }}</td>
                <td>{{ $musy->keterangan }}</td>
                <td class="center text-bold">
                    {{ !empty($musy->dokumentasi) ? 'Lampiran IV-'.($index + 1) : '-' }}
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="center">Tidak ada musyawarah rutin bulan ini.</td></tr>
            @endforelse
        </tbody>
    </table>


    @php
        $hasDokumentasiKhusus = !empty($kegiatanKhusus) && $kegiatanKhusus->contains(function($k) { return !empty($k->dokumentasi); });
        $hasDokumentasiMusyawaroh = !empty($musyawaroh) && $musyawaroh->contains(function($m) { return !empty($m->dokumentasi); });
    @endphp

    @if($hasDokumentasiKhusus || $hasDokumentasiMusyawaroh)
        <div class="page-break"></div>
        
        <table class="kop-surat" style="margin-bottom: 15px;">
            <tr>
                <td class="kop-logo">
                    <img src="{{ $logoImg }}" alt="Logo PPG">
                </td>
                <td class="kop-text">
                    <div class="kop-title-text">Penggerak Pembina Generus (PPG)<br>Karawang Timur</div>
                    <div class="kop-contact">
                        Email: ppgkartim@contoh.com | Instagram: @ppg_kartim<br>
                        Website: www.ppgkartim.org | WhatsApp: 0812-3456-7890
                    </div>
                </td>
                <td class="kop-empty"></td>
            </tr>
        </table>

        <div class="text-center">
            <div class="title" style="text-decoration: none;">LAMPIRAN DOKUMENTASI</div>
            <div class="subtitle">Laporan PJP {{ $namaLaporan }} - {{ $bulanTahun }}</div>
        </div>

        @if($hasDokumentasiKhusus)
            <div class="sub-section-title" style="border-bottom:1px solid #000; padding-left:0; font-size: 11px;">B. DOKUMENTASI KEGIATAN KHUSUS</div>
            @foreach($kegiatanKhusus as $index => $khusus)
                @if(!empty($khusus->dokumentasi))
                    <div class="lampiran-section">
                        <div class="lampiran-title">Lampiran I.B-{{ $index + 1 }} : {{ $khusus->nm_kegiatan }} ({{ \Carbon\Carbon::parse($khusus->tanggal)->format('d M Y') }})</div>
                        <table class="gallery-table">
                            <tr>
                            @foreach($khusus->dokumentasi as $foto)
                                <td class="photo-box">
                                    <img src="{{ storage_path('app/public/' . $foto) }}" alt="Dokumentasi">
                                </td>
                                @if($loop->iteration % 2 == 0) </tr><tr> @endif
                            @endforeach
                            </tr>
                        </table>
                    </div>
                @endif
            @endforeach
        @endif

        @if($hasDokumentasiMusyawaroh)
            <div class="sub-section-title" style="border-bottom:1px solid #000; margin-top:20px; padding-left:0; font-size: 11px;">IV. DOKUMENTASI MUSYAWAROH RUTIN</div>
            @foreach($musyawaroh as $index => $musy)
                @if(!empty($musy->dokumentasi))
                    <div class="lampiran-section">
                        <div class="lampiran-title">Lampiran IV-{{ $index + 1 }} : {{ $musy->judul_musyawaroh }} ({{ \Carbon\Carbon::parse($musy->tanggal)->format('d M Y') }})</div>
                        <table class="gallery-table">
                            <tr>
                            @foreach($musy->dokumentasi as $foto)
                                <td class="photo-box">
                                    <img src="{{ storage_path('app/public/' . $foto) }}" alt="Dokumentasi">
                                </td>
                                @if($loop->iteration % 2 == 0) </tr><tr> @endif
                            @endforeach
                            </tr>
                        </table>
                    </div>
                @endif
            @endforeach
        @endif
    @endif

</body>
</html>