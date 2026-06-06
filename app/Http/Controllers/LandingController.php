<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Generus;
use App\Models\Kelompok;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    // Mapping kategori ke label yang ditampilkan
    private array $kategoriLabel = [
        'PAUD'      => 'PAUD/TK',
        'CABERAWIT' => 'Caberawit',
        'PRA_REMAJA'=> 'Pra Remaja',
        'REMAJA'    => 'Remaja',
        'PRA_NIKAH' => 'Pra Nikah',
    ];

    /**
     * Halaman landing page
     */
    public function index()
    {
        // Total generus per kategori
        $totals = $this->getTotals();

        // List desa (untuk filter chart)
        $desas = Desa::orderBy('nm_desa')->get(['id', 'nm_desa']);

        // List kelompok (untuk filter chart)
        $kelompoks = Kelompok::with('desa:id,nm_desa')->orderBy('nm_kelompok')->get(['id', 'nm_kelompok', 'desa_id']);

        return view('landing', compact('totals', 'desas', 'kelompoks'));
    }

    /**
     * API: Statistik generus per kategori untuk chart (filter desa/kelompok)
     */
    public function chartData(Request $request)
    {
        $type = $request->input('type'); // 'desa' | 'kelompok'
        $id   = $request->input('id');

        $query = Generus::query()->withoutGlobalScopes()->whereNull('generuses.deleted_at');

        if ($type === 'desa' && $id) {
            $query->whereHas('insan', fn($q) => $q->where('desa_id', $id));
        } elseif ($type === 'kelompok' && $id) {
            $query->whereHas('insan', fn($q) => $q->where('kelompok_id', $id));
        }

        $counts = $query
            ->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        $data = [];
        foreach ($this->kategoriLabel as $key => $label) {
            $data[] = [
                'kategori' => $key,
                'label'    => $label,
                'total'    => $counts[$key] ?? 0,
            ];
        }

        return response()->json($data);
    }

    /**
     * API: Detail list generus untuk modal (nis, nama, jk, kategori)
     */
    public function detailGenerus(Request $request)
    {
        $type     = $request->input('type');
        $id       = $request->input('id');
        $kategori = $request->input('kategori');

        $query = Generus::withoutGlobalScopes()->whereNull('generuses.deleted_at')->with('insan:id,nama,jk')->where('is_verified', true);

        if ($type === 'desa' && $id) {
            $query->whereHas('insan', fn($q) => $q->where('desa_id', $id));
        } elseif ($type === 'kelompok' && $id) {
            $query->whereHas('insan', fn($q) => $q->where('kelompok_id', $id));
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $generus = $query->get()->map(fn($g) => [
            'nis'      => $g->nis,
            'nama'     => $g->insan?->nama ?? '-',
            'jk'       => $g->insan?->jk === 'L' ? 'Laki-laki' : 'Perempuan',
            'kategori' => $this->kategoriLabel[$g->kategori] ?? $g->kategori,
        ]);

        return response()->json($generus);
    }

    /**
     * Helper: total generus per kategori (semua wilayah)
     */
    private function getTotals(): array
    {
        $counts = Generus::withoutGlobalScopes()->whereNull('generuses.deleted_at')
            ->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        $result = [];
        foreach ($this->kategoriLabel as $key => $label) {
            $result[$key] = [
                'label' => $label,
                'total' => $counts[$key] ?? 0,
            ];
        }

        return $result;
    }
}
