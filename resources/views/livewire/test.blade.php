<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PPG Karawang Timur - Portal Data Generasi Unggul. Sistem pendataan dan pengelolaan generus berbasis digital untuk komunitas Karawang Timur.">
    <title>PPG Karawang Timur — Portal Data Generasi Unggul</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ===========================
           CSS VARIABLES & RESET
        =========================== */
        :root {
            --emerald-50: #ecfdf5;
            --emerald-100: #d1fae5;
            --emerald-200: #a7f3d0;
            --emerald-400: #34d399;
            --emerald-500: #10b981;
            --emerald-600: #059669;
            --emerald-700: #047857;
            --emerald-800: #065f46;
            --emerald-900: #064e3b;
            --teal-50: #f0fdfa;
            --teal-100: #ccfbf1;
            --teal-400: #2dd4bf;
            --teal-500: #14b8a6;
            --teal-600: #0d9488;
            --amber-400: #fbbf24;
            --amber-500: #f59e0b;
            --amber-50: #fffbeb;
            --amber-100: #fef3c7;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
            --white: #ffffff;
            --font: 'Plus Jakarta Sans', sans-serif;
            --radius-sm: 12px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 40px rgba(0,0,0,0.12);
            --shadow-colored: 0 8px 32px rgba(5, 150, 105, 0.25);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font);
            background: var(--white);
            color: var(--slate-800);
            line-height: 1.6;
            overflow-x: hidden;
        }

        img { display: block; max-width: 100%; }
        a { text-decoration: none; color: inherit; }

        /* ===========================
           UTILITY
        =========================== */
        .container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .badge-emerald {
            background: var(--emerald-100);
            color: var(--emerald-700);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: var(--radius-sm);
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .btn:hover { transform: translateY(-2px); }
        .btn:active { transform: translateY(0); }

        .btn-primary {
            background: var(--emerald-600);
            color: var(--white);
            box-shadow: var(--shadow-colored);
        }

        .btn-primary:hover {
            background: var(--emerald-700);
            box-shadow: 0 12px 40px rgba(5, 150, 105, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: var(--emerald-700);
            border: 2px solid var(--emerald-200);
        }

        .btn-outline:hover {
            background: var(--emerald-50);
            border-color: var(--emerald-400);
        }

        .btn-white {
            background: var(--white);
            color: var(--emerald-700);
            box-shadow: var(--shadow-md);
        }

        .btn-white:hover {
            box-shadow: var(--shadow-lg);
        }

        .section-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--emerald-600);
            margin-bottom: 10px;
        }

        .section-title {
            font-size: clamp(24px, 5vw, 38px);
            font-weight: 800;
            color: var(--slate-800);
            line-height: 1.2;
        }

        .section-desc {
            font-size: 16px;
            color: var(--slate-500);
            line-height: 1.7;
            margin-top: 12px;
        }

        /* ===========================
           NAVBAR
        =========================== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 16px 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(16px);
            box-shadow: 0 1px 0 var(--slate-200);
            padding: 12px 0;
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-logo img {
            width: 38px;
            height: 38px;
            object-fit: contain;
            border-radius: 8px;
        }

        .navbar-logo span {
            font-size: 17px;
            font-weight: 800;
            color: var(--emerald-700);
        }

        .navbar-logo span small {
            display: block;
            font-size: 11px;
            font-weight: 500;
            color: var(--slate-400);
            line-height: 1;
        }

        .navbar-links {
            display: none;
            align-items: center;
            gap: 32px;
        }

        @media (min-width: 768px) {
            .navbar-links { display: flex; }
        }

        .navbar-links a {
            font-size: 14px;
            font-weight: 600;
            color: var(--slate-600);
            transition: color 0.2s;
        }

        .navbar-links a:hover { color: var(--emerald-600); }

        .navbar-cta {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-cta .btn {
            padding: 10px 20px;
            font-size: 14px;
        }

        /* ===========================
           HERO
        =========================== */
        .hero {
            min-height: 100dvh;
            padding: 100px 0 60px;
            background: linear-gradient(160deg, var(--emerald-50) 0%, var(--teal-50) 50%, var(--white) 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        /* Decorative blobs */
        .hero::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(52, 211, 153, 0.15) 0%, transparent 70%);
            top: -100px;
            right: -150px;
            border-radius: 50%;
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(251, 191, 36, 0.12) 0%, transparent 70%);
            bottom: -50px;
            left: -80px;
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-inner {
            display: grid;
            grid-template-columns: 1fr;
            gap: 48px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        @media (min-width: 768px) {
            .hero-inner {
                grid-template-columns: 1fr 1fr;
                gap: 60px;
            }
        }

        .hero-text .badge { margin-bottom: 20px; }

        .hero-title {
            font-size: clamp(32px, 7vw, 52px);
            font-weight: 800;
            color: var(--slate-800);
            line-height: 1.15;
            margin-bottom: 20px;
        }

        .hero-title .highlight {
            color: var(--emerald-600);
            position: relative;
            display: inline-block;
        }

        .hero-title .highlight::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--emerald-400), var(--teal-400));
            border-radius: 999px;
            opacity: 0.4;
        }

        .hero-desc {
            font-size: 16px;
            color: var(--slate-500);
            line-height: 1.75;
            margin-bottom: 32px;
            max-width: 480px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 48px;
        }

        .hero-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--slate-200);
        }

        .hero-stat-item {
            display: flex;
            flex-direction: column;
        }

        .hero-stat-num {
            font-size: 26px;
            font-weight: 800;
            color: var(--slate-800);
            line-height: 1;
        }

        .hero-stat-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--slate-400);
            margin-top: 4px;
        }

        .hero-image-wrap {
            position: relative;
            display: flex;
            justify-content: center;
        }

        .hero-image-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            padding: 16px;
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 440px;
            position: relative;
        }

        .hero-image-card img {
            width: 100%;
            border-radius: var(--radius-lg);
        }

        /* Floating badge on hero image */
        .hero-float-badge {
            position: absolute;
            background: var(--white);
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 700;
            color: var(--slate-700);
            animation: float 3s ease-in-out infinite;
        }

        .hero-float-badge .icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .hero-float-1 {
            top: -16px;
            left: -12px;
        }

        .hero-float-2 {
            bottom: 12px;
            right: -20px;
            animation-delay: 1s;
        }

        @media (max-width: 767px) {
            .hero-float-1 { top: -10px; left: 0; }
            .hero-float-2 { bottom: 4px; right: 0; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        /* ===========================
           STATS STRIP
        =========================== */
        .stats-strip {
            background: var(--emerald-800);
            padding: 48px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        @media (min-width: 640px) {
            .stats-grid { grid-template-columns: repeat(4, 1fr); }
        }

        .stat-card {
            text-align: center;
        }

        .stat-card-num {
            font-size: 36px;
            font-weight: 800;
            color: var(--white);
            line-height: 1;
        }

        .stat-card-num span {
            color: var(--amber-400);
        }

        .stat-card-label {
            font-size: 13px;
            font-weight: 500;
            color: rgba(255,255,255,0.6);
            margin-top: 6px;
        }

        .stat-divider {
            display: none;
            width: 1px;
            background: rgba(255,255,255,0.15);
            align-self: stretch;
        }

        @media (min-width: 640px) {
            .stat-divider { display: block; }
        }

        /* ===========================
           FEATURES / FITUR UTAMA
        =========================== */
        .features {
            padding: 80px 0;
            background: var(--slate-50);
        }

        .features-header {
            text-align: center;
            max-width: 520px;
            margin: 0 auto 48px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 640px) {
            .features-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (min-width: 1024px) {
            .features-grid { grid-template-columns: repeat(3, 1fr); }
        }

        .feature-card {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 28px;
            border: 1.5px solid var(--slate-200);
            transition: all 0.3s ease;
            cursor: default;
        }

        .feature-card:hover {
            border-color: var(--emerald-300);
            box-shadow: var(--shadow-md);
            transform: translateY(-4px);
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 18px;
        }

        .feature-icon-emerald { background: var(--emerald-100); }
        .feature-icon-teal { background: var(--teal-100); }
        .feature-icon-amber { background: var(--amber-100); }

        .feature-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--slate-800);
            margin-bottom: 8px;
        }

        .feature-desc {
            font-size: 14px;
            color: var(--slate-500);
            line-height: 1.65;
        }

        /* ===========================
           GENERUS KATEGORI
        =========================== */
        .kategori {
            padding: 80px 0;
            background: var(--white);
        }

        .kategori-header {
            text-align: center;
            max-width: 560px;
            margin: 0 auto 48px;
        }

        .kategori-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        @media (min-width: 768px) {
            .kategori-grid { grid-template-columns: repeat(5, 1fr); }
        }

        .kategori-card {
            border-radius: var(--radius-md);
            padding: 24px 16px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .kategori-card:hover { transform: scale(1.04); }

        .kategori-card-icon {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .kategori-card-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--slate-800);
            margin-bottom: 4px;
        }

        .kategori-card-range {
            font-size: 12px;
            color: var(--slate-400);
            font-weight: 500;
        }

        .kat-paud { background: #fef9ee; }
        .kat-cbr { background: #ecfdf5; }
        .kat-prr { background: #eff6ff; }
        .kat-rem { background: #fdf4ff; }
        .kat-prn { background: #fff7ed; }

        /* ===========================
           CTA / LOGIN
        =========================== */
        .cta {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--emerald-700) 0%, var(--teal-600) 100%);
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='30' cy='30' r='1' fill='rgba(255,255,255,0.06)'/%3E%3C/svg%3E") repeat;
        }

        .cta-inner {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .cta-title {
            font-size: clamp(26px, 5vw, 42px);
            font-weight: 800;
            color: var(--white);
            line-height: 1.2;
            margin-bottom: 16px;
        }

        .cta-desc {
            font-size: 16px;
            color: rgba(255,255,255,0.75);
            margin-bottom: 36px;
            line-height: 1.7;
        }

        .cta-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
        }

        .btn-amber {
            background: var(--amber-400);
            color: var(--slate-900);
        }

        .btn-amber:hover {
            background: var(--amber-500);
            box-shadow: 0 10px 30px rgba(251, 191, 36, 0.4);
        }

        /* ===========================
           FOOTER
        =========================== */
        footer {
            background: var(--slate-900);
            padding: 48px 0 28px;
        }

        .footer-inner {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        @media (min-width: 768px) {
            .footer-inner {
                flex-direction: row;
                justify-content: space-between;
                align-items: flex-start;
            }
        }

        .footer-brand .navbar-logo span {
            color: var(--emerald-400);
        }

        .footer-brand .navbar-logo span small {
            color: rgba(255,255,255,0.3);
        }

        .footer-desc {
            font-size: 13px;
            color: rgba(255,255,255,0.4);
            margin-top: 12px;
            max-width: 280px;
            line-height: 1.7;
        }

        .footer-links-wrap {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
        }

        .footer-links-group h5 {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-bottom: 14px;
        }

        .footer-links-group ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-links-group a {
            font-size: 14px;
            color: rgba(255,255,255,0.65);
            font-weight: 500;
            transition: color 0.2s;
        }

        .footer-links-group a:hover { color: var(--emerald-400); }

        .footer-bottom {
            padding-top: 28px;
            margin-top: 28px;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: center;
            text-align: center;
        }

        @media (min-width: 768px) {
            .footer-bottom {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .footer-bottom p {
            font-size: 13px;
            color: rgba(255,255,255,0.3);
        }

        /* ===========================
           HAMBURGER MOBILE MENU
        =========================== */
        .hamburger {
            display: flex;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 6px;
        }

        @media (min-width: 768px) {
            .hamburger { display: none; }
        }

        .hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--slate-700);
            border-radius: 999px;
            transition: all 0.3s;
        }

        .mobile-menu {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            z-index: 99;
        }

        .mobile-menu.open { display: flex; align-items: flex-start; }

        .mobile-menu-panel {
            background: var(--white);
            width: 100%;
            max-width: 320px;
            padding: 72px 28px 28px;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 24px;
            box-shadow: var(--shadow-lg);
        }

        .mobile-menu-panel a {
            font-size: 17px;
            font-weight: 600;
            color: var(--slate-700);
            padding: 10px 0;
            border-bottom: 1px solid var(--slate-100);
        }

        /* ===========================
           SCROLL REVEAL ANIMATION
        =========================== */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }
        .reveal-delay-5 { transition-delay: 0.5s; }

        /* ===========================
           COUNTER ANIMATION
        =========================== */
        .count-up { display: inline-block; }

    </style>
</head>
<body>

    <!-- ============================
         NAVBAR
    ============================ -->
    <nav class="navbar" id="navbar">
        <div class="container navbar-inner">
            <a href="#" class="navbar-logo">
                <img src="{{ asset('images/logo.png') }}" alt="PPG Kartim Logo">
                <span>
                    PPG Kartim
                    <small>Karawang Timur</small>
                </span>
            </a>

            <div class="navbar-links">
                <a href="#profil">Profil</a>
                <a href="#fitur">Fitur</a>
                <a href="#generus">Generus</a>
                <a href="#masuk">Masuk</a>
            </div>

            <div class="navbar-cta">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/admin') }}" class="btn btn-primary" id="nav-dashboard-btn">Dashboard →</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline" id="nav-login-btn">Masuk</a>
                    @endauth
                @endif
                <button class="hamburger" id="hamburger-btn" aria-label="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobile-menu">
        <div class="mobile-menu-panel">
            <a href="#profil" class="mobile-nav-link">Profil</a>
            <a href="#fitur" class="mobile-nav-link">Fitur</a>
            <a href="#generus" class="mobile-nav-link">Generus</a>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/admin') }}" class="btn btn-primary" style="text-align:center;">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary" style="text-align:center;">Masuk Sekarang</a>
                @endauth
            @endif
        </div>
    </div>


    <!-- ============================
         HERO
    ============================ -->
    <section class="hero" id="profil">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-text">
                    <span class="badge badge-emerald reveal">
                        <span>🌿</span> Untuk Generasi Unggul
                    </span>
                    <h1 class="hero-title reveal">
                        Sistem Data<br>
                        <span class="highlight">PPG Karawang</span><br>
                        Timur
                    </h1>
                    <p class="hero-desc reveal">
                        Platform digital terintegrasi untuk mengelola data generus, mubaligh, dan laporan PJP. Mewujudkan pembinaan yang terukur dan terdata.
                    </p>
                    <div class="hero-actions reveal">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/admin') }}" class="btn btn-primary" id="hero-dashboard-btn">
                                    Ke Dashboard →
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary" id="hero-login-btn">
                                    Masuk ke Portal
                                </a>
                                <a href="#fitur" class="btn btn-outline" id="hero-fitur-btn">Lihat Fitur</a>
                            @endauth
                        @endif
                    </div>
                    <div class="hero-stats reveal">
                        <div class="hero-stat-item">
                            <span class="hero-stat-num count-up" data-target="5">0</span>
                            <span class="hero-stat-label">Kategori Generus</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-num count-up" data-target="3">0</span>
                            <span class="hero-stat-label">Desa Cakupan</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-num">24/7</span>
                            <span class="hero-stat-label">Akses Data</span>
                        </div>
                    </div>
                </div>

                <div class="hero-image-wrap reveal">
                    <div class="hero-image-card">
                        <img src="{{ asset('images/hero-illustration.png') }}" alt="Generasi Muda PPG">

                        <div class="hero-float-badge hero-float-1">
                            <div class="icon" style="background: var(--emerald-100);">📊</div>
                            <div>
                                <div>Data Terstruktur</div>
                                <div style="font-size:11px;font-weight:500;color:var(--slate-400);">Real-time & akurat</div>
                            </div>
                        </div>

                        <div class="hero-float-badge hero-float-2">
                            <div class="icon" style="background: var(--amber-100);">🏆</div>
                            <div>
                                <div>100% Digital</div>
                                <div style="font-size:11px;font-weight:500;color:var(--slate-400);">Kelola dari mana saja</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================
         STATS STRIP
    ============================ -->
    <section class="stats-strip">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card reveal reveal-delay-1">
                    <div class="stat-card-num"><span class="count-up" data-target="500">0</span>+</div>
                    <div class="stat-card-label">Total Generus Terdaftar</div>
                </div>
                <div class="stat-card reveal reveal-delay-2">
                    <div class="stat-card-num"><span class="count-up" data-target="30">0</span>+</div>
                    <div class="stat-card-label">Mubaligh Aktif</div>
                </div>
                <div class="stat-card reveal reveal-delay-3">
                    <div class="stat-card-num"><span class="count-up" data-target="12">0</span></div>
                    <div class="stat-card-label">Kelompok Binaan</div>
                </div>
                <div class="stat-card reveal reveal-delay-4">
                    <div class="stat-card-num"><span class="count-up" data-target="100">0</span>%</div>
                    <div class="stat-card-label">Data Berbasis Digital</div>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================
         FITUR UTAMA
    ============================ -->
    <section class="features" id="fitur">
        <div class="container">
            <div class="features-header reveal">
                <div class="section-label">Fitur Platform</div>
                <h2 class="section-title">Semua yang Kamu Butuhkan dalam Satu Platform</h2>
                <p class="section-desc">Didesain khusus untuk kebutuhan pembinaan generus yang modern dan efisien.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card reveal reveal-delay-1">
                    <div class="feature-icon feature-icon-emerald">👥</div>
                    <div class="feature-title">Data Generus Terintegrasi</div>
                    <p class="feature-desc">Kelola data seluruh generus dari PAUD hingga Pra Nikah dalam satu database terpusat yang mudah diakses.</p>
                </div>

                <div class="feature-card reveal reveal-delay-2">
                    <div class="feature-icon feature-icon-teal">📋</div>
                    <div class="feature-title">Laporan PJP Digital</div>
                    <p class="feature-desc">Buat, kelola, dan ekspor laporan PJP bulanan dengan mudah lengkap dengan dokumentasi foto dan kepengurusan.</p>
                </div>

                <div class="feature-card reveal reveal-delay-3">
                    <div class="feature-icon feature-icon-amber">🗓️</div>
                    <div class="feature-title">Manajemen Event</div>
                    <p class="feature-desc">Kelola presensi dan rekap kehadiran kegiatan dengan sistem QR Code yang cepat dan praktis.</p>
                </div>

                <div class="feature-card reveal reveal-delay-1">
                    <div class="feature-icon feature-icon-teal">📊</div>
                    <div class="feature-title">Statistik Real-time</div>
                    <p class="feature-desc">Pantau pertumbuhan dan perkembangan generus secara langsung dengan visualisasi data yang informatif.</p>
                </div>

                <div class="feature-card reveal reveal-delay-2">
                    <div class="feature-icon feature-icon-emerald">📄</div>
                    <div class="feature-title">Ekspor PDF Otomatis</div>
                    <p class="feature-desc">Generate laporan dalam format PDF siap cetak secara otomatis dengan kop surat dan format resmi PPG.</p>
                </div>

                <div class="feature-card reveal reveal-delay-3">
                    <div class="feature-icon feature-icon-amber">🔐</div>
                    <div class="feature-title">Hak Akses Berjenjang</div>
                    <p class="feature-desc">Sistem role-based access control yang membedakan akses Super Admin, Daerah, Desa, hingga Kelompok.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================
         KATEGORI GENERUS
    ============================ -->
    <section class="kategori" id="generus">
        <div class="container">
            <div class="kategori-header reveal">
                <div class="section-label">Kategori Pembinaan</div>
                <h2 class="section-title">Dari PAUD Hingga Pra Nikah</h2>
                <p class="section-desc">PPG Karawang Timur membina generus dalam 5 kategori usia yang berjenjang dan terstruktur.</p>
            </div>

            <div class="kategori-grid">
                <div class="kategori-card kat-paud reveal reveal-delay-1">
                    <div class="kategori-card-icon">🌱</div>
                    <div class="kategori-card-name">PAUD / TK</div>
                    <div class="kategori-card-range">0 – 6 tahun</div>
                </div>
                <div class="kategori-card kat-cbr reveal reveal-delay-2">
                    <div class="kategori-card-icon">📚</div>
                    <div class="kategori-card-name">Caberawit</div>
                    <div class="kategori-card-range">SD (7 – 12 thn)</div>
                </div>
                <div class="kategori-card kat-prr reveal reveal-delay-3">
                    <div class="kategori-card-icon">🎒</div>
                    <div class="kategori-card-name">Pra Remaja</div>
                    <div class="kategori-card-range">SMP (13 – 15 thn)</div>
                </div>
                <div class="kategori-card kat-rem reveal reveal-delay-4">
                    <div class="kategori-card-icon">🌟</div>
                    <div class="kategori-card-name">Remaja</div>
                    <div class="kategori-card-range">SMA/K (16 – 21 thn)</div>
                </div>
                <div class="kategori-card kat-prn reveal reveal-delay-5">
                    <div class="kategori-card-icon">💎</div>
                    <div class="kategori-card-name">Pra Nikah</div>
                    <div class="kategori-card-range">21 tahun ke atas</div>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================
         CTA
    ============================ -->
    <section class="cta" id="masuk">
        <div class="cta-inner reveal">
            <div class="badge" style="background:rgba(255,255,255,0.15);color:white;margin-bottom:20px;display:inline-flex;">
                🚀 Mulai Sekarang
            </div>
            <h2 class="cta-title">Siap Mengelola Data Generus dengan Lebih Mudah?</h2>
            <p class="cta-desc">Masuk ke portal dan mulai manfaatkan semua fitur pengelolaan data PPG Karawang Timur secara digital.</p>
            <div class="cta-actions">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/admin') }}" class="btn btn-amber" id="cta-dashboard-btn">Ke Dashboard →</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-amber" id="cta-login-btn">Masuk ke Portal</a>
                        <a href="#profil" class="btn btn-white" id="cta-back-btn" style="color:var(--emerald-700);">Pelajari Lebih</a>
                    @endauth
                @endif
            </div>
        </div>
    </section>


    <!-- ============================
         FOOTER
    ============================ -->
    <footer>
        <div class="container">
            <div class="footer-inner">
                <div class="footer-brand">
                    <div class="navbar-logo" style="margin-bottom:0">
                        <img src="{{ asset('images/logo.png') }}" alt="PPG Logo">
                        <span>
                            PPG Kartim
                            <small>Karawang Timur</small>
                        </span>
                    </div>
                    <p class="footer-desc">
                        Penggerak Pembina Generus (PPG) Karawang Timur — Membangun generasi yang berakhlaqul karimah melalui data yang terintegrasi.
                    </p>
                </div>

                <div class="footer-links-wrap">
                    <div class="footer-links-group">
                        <h5>Platform</h5>
                        <ul>
                            <li><a href="#profil">Profil</a></li>
                            <li><a href="#fitur">Fitur</a></li>
                            <li><a href="#generus">Kategori Generus</a></li>
                        </ul>
                    </div>
                    <div class="footer-links-group">
                        <h5>Akses</h5>
                        <ul>
                            @if (Route::has('login'))
                                @auth
                                    <li><a href="{{ url('/admin') }}">Dashboard</a></li>
                                @else
                                    <li><a href="{{ route('login') }}">Masuk</a></li>
                                @endauth
                            @endif
                            <li><a href="/registrasi-generus-form">Registrasi Generus</a></li>
                            <li><a href="/events">Cek Event</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2026 PPG Karawang Timur. Seluruh hak dilindungi.</p>
                <p>Dibuat dengan ❤️ untuk generasi unggul Indonesia</p>
            </div>
        </div>
    </footer>


    <!-- ============================
         SCRIPTS
    ============================ -->
    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Hamburger menu
        const hamburger = document.getElementById('hamburger-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
        });

        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) mobileMenu.classList.remove('open');
        });

        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', () => mobileMenu.classList.remove('open'));
        });

        // Scroll reveal observer
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        reveals.forEach(el => observer.observe(el));

        // Count-up animation
        function animateCount(el) {
            const target = parseInt(el.dataset.target, 10);
            const duration = 1600;
            const start = performance.now();

            function step(now) {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                // Ease out
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(eased * target);
                if (progress < 1) requestAnimationFrame(step);
            }

            requestAnimationFrame(step);
        }

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    animateCount(e.target);
                    counterObserver.unobserve(e.target);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.count-up').forEach(el => counterObserver.observe(el));
    </script>

</body>
</html>