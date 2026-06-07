<div>
@push('styles')
<style>
    /* ═══════════════════════════════════════════
       PPG Login — Brand Panel & Form Panel
       ═══════════════════════════════════════════ */
    :root {
        --em-50: #ecfdf5; --em-100: #d1fae5; --em-200: #a7f3d0;
        --em-300: #6ee7b7; --em-400: #34d399; --em-500: #10b981;
        --em-600: #059669; --em-700: #047857;
        --teal-600: #0d9488;
        --sl-50: #f8fafc; --sl-100: #f1f5f9; --sl-200: #e2e8f0;
        --sl-300: #cbd5e1; --sl-400: #94a3b8; --sl-500: #64748b;
        --sl-600: #475569; --sl-700: #334155; --sl-800: #1e293b;
        --ppg: 'Plus Jakarta Sans', sans-serif;
    }

    /* ── Brand Panel (kiri, desktop) ── */
    .ppg-brand {
        display: none;
        background: linear-gradient(160deg, var(--em-700) 0%, var(--teal-600) 100%);
        padding: 44px 36px;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    @media (min-width: 900px) { .ppg-brand { display: flex; } }

    /* Dot grid overlay */
    .ppg-brand::before {
        content: '';
        position: absolute; inset: 0;
        background-image: radial-gradient(rgba(255,255,255,.07) 1.5px, transparent 1.5px);
        background-size: 24px 24px;
    }
    /* Glow circle */
    .ppg-brand::after {
        content: '';
        position: absolute;
        width: 280px; height: 280px;
        background: radial-gradient(circle, rgba(255,255,255,.09) 0%, transparent 70%);
        bottom: -90px; right: -60px;
        border-radius: 50%;
    }

    .ppg-brand-inner { position: relative; z-index: 1; }

    .ppg-brand-logo {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 36px;
    }
    .ppg-brand-logo img {
        width: 42px; height: 42px;
        border-radius: 10px;
        background: rgba(255,255,255,.15);
        padding: 4px;
        object-fit: contain;
    }
    .ppg-brand-logo-name {
        font-size: 17px; font-weight: 800;
        color: #fff; line-height: 1.1;
        font-family: var(--ppg);
    }
    .ppg-brand-logo-sub {
        display: block; font-size: 12px;
        font-weight: 500; color: rgba(255,255,255,.6);
    }

    .ppg-brand-h1 {
        font-size: clamp(22px, 2.5vw, 30px);
        font-weight: 800; color: #fff;
        line-height: 1.25; margin-bottom: 12px;
        font-family: var(--ppg);
    }
    .ppg-brand-h1 .hl { color: var(--em-300); }

    .ppg-brand-desc {
        font-size: 13.5px; color: rgba(255,255,255,.7);
        line-height: 1.75; max-width: 260px;
        font-family: var(--ppg);
    }

    .ppg-brand-stats {
        display: flex; gap: 12px; flex-wrap: wrap;
        margin-bottom: 16px;
        position: relative; z-index: 1;
    }
    .ppg-brand-stat {
        background: rgba(255,255,255,.12);
        border: 1.5px solid rgba(255,255,255,.18);
        border-radius: 14px; padding: 14px 18px; flex: 1;
    }
    .ppg-brand-stat-num {
        font-size: 20px; font-weight: 800; color: #fff;
        font-family: var(--ppg);
    }
    .ppg-brand-stat-lbl {
        font-size: 11px; font-weight: 600;
        color: rgba(255,255,255,.55); margin-top: 3px;
        font-family: var(--ppg);
    }

    .ppg-brand-quote {
        background: rgba(255,255,255,.1);
        border: 1.5px solid rgba(255,255,255,.15);
        border-radius: 14px; padding: 16px 18px 16px 22px;
        font-size: 13px; color: rgba(255,255,255,.8);
        font-style: italic; line-height: 1.65;
        position: relative; z-index: 1;
        font-family: var(--ppg);
    }
    .ppg-brand-quote::before {
        content: '"';
        position: absolute; top: -8px; left: 14px;
        font-size: 38px; font-style: normal;
        color: rgba(255,255,255,.22);
        font-family: Georgia, serif; line-height: 1;
    }

    /* ── Form Panel (kanan) ── */
    .ppg-form {
        background: #fff;
        padding: 40px 36px;
        display: flex; flex-direction: column; justify-content: center;
    }
    @media (max-width: 899px) {
        .ppg-form {
            border-radius: 24px;
            box-shadow: 0 24px 80px rgba(0,0,0,.14);
        }
    }

    /* Logo mobile */
    .ppg-mobile-logo {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 22px;
    }
    .ppg-mobile-logo img { width: 34px; height: 34px; border-radius: 8px; object-fit: contain; }
    .ppg-mobile-logo-name {
        font-size: 15px; font-weight: 800;
        color: var(--em-700); line-height: 1.1;
        font-family: var(--ppg);
    }
    .ppg-mobile-logo-sub { display: block; font-size: 11px; font-weight: 500; color: var(--sl-400); }
    @media (min-width: 900px) { .ppg-mobile-logo { display: none; } }

    /* Header form */
    .ppg-form-header { margin-bottom: 24px; }
    .ppg-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px; border-radius: 999px;
        font-size: 11px; font-weight: 700;
        letter-spacing: .04em; text-transform: uppercase;
        background: var(--em-100); color: var(--em-700);
        margin-bottom: 12px; font-family: var(--ppg);
    }
    .ppg-form-title {
        font-size: 24px; font-weight: 800; color: var(--sl-800);
        line-height: 1.2; margin-bottom: 4px; font-family: var(--ppg);
    }
    .ppg-form-subtitle {
        font-size: 13.5px; color: var(--sl-500);
        line-height: 1.6; font-family: var(--ppg);
    }

    /* Override Filament form di dalam panel ini */
    .ppg-form label,
    .ppg-form input,
    .ppg-form button,
    .ppg-form select,
    .ppg-form span,
    .ppg-form p,
    .ppg-form a {
        font-family: var(--ppg) !important;
    }

    /* Input */
    .ppg-form input[type="text"],
    .ppg-form input[type="password"],
    .ppg-form input[type="email"] {
        border-radius: 12px !important;
        background-color: var(--sl-50) !important;
        border-color: var(--sl-200) !important;
        transition: border-color .2s, box-shadow .2s !important;
    }
    .ppg-form input[type="text"]:focus,
    .ppg-form input[type="password"]:focus,
    .ppg-form input[type="email"]:focus {
        border-color: var(--em-400) !important;
        box-shadow: 0 0 0 3px rgba(52,211,153,.18) !important;
        background-color: #fff !important;
        outline: none !important;
    }

    /* Tombol submit → emerald */
    .ppg-form .fi-btn-primary {
        background-color: var(--em-600) !important;
        border-color: var(--em-600) !important;
        border-radius: 12px !important;
        font-weight: 700 !important;
        box-shadow: 0 8px 28px rgba(5,150,105,.25) !important;
        transition: all .25s cubic-bezier(.34,1.56,.64,1) !important;
    }
    .ppg-form .fi-btn-primary:hover {
        background-color: var(--em-700) !important;
        border-color: var(--em-700) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 12px 36px rgba(5,150,105,.38) !important;
    }

    /* Checkbox */
    .ppg-form [type="checkbox"]:checked {
        background-color: var(--em-600) !important;
        border-color: var(--em-600) !important;
    }

    /* Sembunyikan default Filament logo/heading di SimplePage jika sempat ter-render */
    .ppg-form .fi-logo,
    .ppg-form .fi-simple-header { display: none !important; }

    /* Link balik */
    .ppg-back {
        display: flex; align-items: center; justify-content: center; gap: 6px;
        margin-top: 22px; font-size: 13px; font-weight: 600;
        color: var(--sl-500); text-decoration: none;
        transition: color .2s; font-family: var(--ppg);
    }
    .ppg-back:hover { color: var(--em-600); }
</style>
@endpush

<div class="ppg-login-wrapper">

{{-- ════ BRAND PANEL (kiri) ════ --}}
<div class="ppg-brand">
    <div class="ppg-brand-inner">
        <div class="ppg-brand-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo PPG">
            <span class="ppg-brand-logo-name">
                PPG Kartim
                <small class="ppg-brand-logo-sub">Karawang Timur</small>
            </span>
        </div>
        <h1 class="ppg-brand-h1">
            Portal Data<br>
            <span class="hl">Generasi Unggul</span><br>
            Karawang Timur
        </h1>
        <p class="ppg-brand-desc">
            Platform digital terintegrasi untuk mengelola data generus, mubaligh, dan laporan PJP secara terukur dan berkelanjutan.
        </p>
    </div>
    <div>
        <div class="ppg-brand-stats">
            <div class="ppg-brand-stat">
                <div class="ppg-brand-stat-num">5</div>
                <div class="ppg-brand-stat-lbl">Kategori Generus</div>
            </div>
            <div class="ppg-brand-stat">
                <div class="ppg-brand-stat-num">24/7</div>
                <div class="ppg-brand-stat-lbl">Akses Data</div>
            </div>
        </div>
        <div class="ppg-brand-quote">
            Membangun generasi berakhlaqul karimah melalui data terintegrasi dan pembinaan yang terstruktur.
        </div>
    </div>
</div>

{{-- ════ FORM PANEL (kanan) ════ --}}
<div class="ppg-form">

    {{-- Logo mobile --}}
    <div class="ppg-mobile-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo PPG">
        <span class="ppg-mobile-logo-name">
            PPG Kartim
            <small class="ppg-mobile-logo-sub">Karawang Timur</small>
        </span>
    </div>

    {{-- Header --}}
    <div class="ppg-form-header">
        <div class="ppg-badge">🌿 Penggerak Pembina Generus</div>
        <h2 class="ppg-form-title">Selamat Datang!</h2>
        <p class="ppg-form-subtitle">Masuk untuk mengelola data generus PPG Karawang Timur.</p>
    </div>

    {{-- Form Filament --}}
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

    {{-- Kembali ke halaman utama --}}
    <a href="{{ url('/') }}" class="ppg-back">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Kembali ke halaman utama
    </a>
</div>

<x-filament-actions::modals />

</div>{{-- .ppg-login-wrapper --}}
</div>{{-- root --}}
