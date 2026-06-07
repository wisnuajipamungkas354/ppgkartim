<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Masuk ke Portal Data PPG Karawang Timur">
    <title>Masuk — PPG Karawang Timur</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Filament styles (WAJIB) --}}
    @filamentStyles
    {{ filament()->getTheme()->getHtml() }}
    {{ filament()->getFontHtml() }}

    {{-- CSS variable wajib Filament agar dark mode tidak aktif --}}
    <style>
        :root {
            --font-family: 'Plus Jakarta Sans';
            --sidebar-width: 20rem;
            --collapsed-sidebar-width: 4rem;
            --default-theme-mode: light;
        }
        /* Paksa selalu light mode di halaman ini */
        html { color-scheme: light !important; }
        html.dark { color-scheme: light !important; }
    </style>

    {{-- Paksa light mode (cegah dark mode Filament) --}}
    <script>localStorage.setItem('theme', 'light')</script>

    {{-- Cegah flash dark mode --}}
    <style>
        [x-cloak], [x-cloak='x-cloak'], [x-cloak='1'] { display: none !important; }
    </style>

    @stack('styles')

    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --em-50: #ecfdf5; --em-100: #d1fae5; --em-200: #a7f3d0;
            --em-300: #6ee7b7; --em-400: #34d399; --em-500: #10b981;
            --em-600: #059669; --em-700: #047857; --em-800: #065f46;
            --teal-400: #2dd4bf; --teal-600: #0d9488;
            --sl-50: #f8fafc; --sl-100: #f1f5f9; --sl-200: #e2e8f0;
            --sl-300: #cbd5e1; --sl-400: #94a3b8; --sl-500: #64748b;
            --sl-600: #475569; --sl-700: #334155; --sl-800: #1e293b;
            --ppg: 'Plus Jakarta Sans', sans-serif;
        }

        html, body {
            min-height: 100vh;
            font-family: var(--ppg);
        }

        body {
            background: linear-gradient(160deg, var(--em-50) 0%, #f0fdfa 45%, var(--sl-100) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Dot grid */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: radial-gradient(rgba(5,150,105,.065) 1.5px, transparent 1.5px);
            background-size: 28px 28px;
            pointer-events: none;
            z-index: 0;
        }

        /* Blob kanan atas */
        body::after {
            content: '';
            position: fixed;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(52,211,153,.18) 0%, transparent 70%);
            top: -120px; right: -140px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        /* Blob kiri bawah */
        .blob-br {
            position: fixed;
            width: 340px; height: 340px;
            background: radial-gradient(circle, rgba(45,212,191,.12) 0%, transparent 70%);
            bottom: -80px; left: -80px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Floating deco badges ── */
        .deco {
            position: fixed;
            background: white;
            border-radius: 14px;
            padding: 10px 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 700;
            color: var(--sl-700);
            font-family: var(--ppg);
            z-index: 1;
            animation: floatY 3s ease-in-out infinite;
        }
        .deco-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .deco-sub { font-size: 11px; font-weight: 500; color: var(--sl-400); display: block; margin-top: 1px; }
        .deco-1 { top: 12%; left: 4%; }
        .deco-2 { bottom: 16%; right: 4%; animation-delay: 1.5s; }
        @media (max-width: 1100px) { .deco-1, .deco-2 { display: none; } }
        @keyframes floatY { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-9px)} }

        /* ── Main wrapper ── */
        .login-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 440px;
            margin: 28px 20px;
        }
        @media (min-width: 900px) {
            .login-wrapper {
                max-width: 880px;
                border-radius: 28px;
                overflow: hidden;
                box-shadow: 0 24px 80px rgba(0,0,0,.15);
            }
        }

        /* Grid dua kolom di dalam wrapper */
        .ppg-login-wrapper {
            display: grid;
            grid-template-columns: 1fr;
            width: 100%;
        }
        @media (min-width: 900px) {
            .ppg-login-wrapper {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* ── Animate in ── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .anim { animation: slideUp .45s cubic-bezier(.34,1.56,.64,1) both; }
        .anim-1 { animation-delay: .05s; }
        .anim-2 { animation-delay: .12s; }

        /* ── Filament Notifications (perlu ada di DOM) ── */
        [x-cloak], [x-cloak='x-cloak'], [x-cloak='1'] { display: none !important; }
    </style>
</head>
<body>

    {{-- Dekorasi --}}
    <div class="blob-br"></div>

    <div class="deco deco-1">
        <div class="deco-icon" style="background:var(--em-100);color:var(--em-700)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div>Data Terstruktur <span class="deco-sub">Real-time & akurat</span></div>
    </div>

    <div class="deco deco-2">
        <div class="deco-icon" style="background:#ccfbf1;color:#0d9488">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>
        <div>Login Aman <span class="deco-sub">Akses terlindungi</span></div>
    </div>

    {{-- Login Card --}}
    <div class="login-wrapper anim anim-1">
        {{ $slot }}
    </div>

    {{-- Filament Notifications --}}
    @livewire(Filament\Livewire\Notifications::class)

    {{-- Filament Scripts (WAJIB) --}}
    @filamentScripts(withCore: true)
    @stack('scripts')

</body>
</html>
