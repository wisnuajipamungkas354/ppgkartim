<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PPG Karawang Timur - Portal Data Generasi Unggul. Sistem pendataan dan pengelolaan generus berbasis digital untuk komunitas Karawang Timur.">
    <title>PPG Karawang Timur — Portal Data Generasi Unggul</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --em-50:#ecfdf5; --em-100:#d1fae5; --em-200:#a7f3d0; --em-300:#6ee7b7;
            --em-400:#34d399; --em-500:#10b981; --em-600:#059669; --em-700:#047857;
            --em-800:#065f46; --em-900:#064e3b;
            --teal-100:#ccfbf1; --teal-400:#2dd4bf; --teal-500:#14b8a6; --teal-600:#0d9488;
            --amber-100:#fef3c7; --amber-400:#fbbf24; --amber-500:#f59e0b;
            --sl-50:#f8fafc; --sl-100:#f1f5f9; --sl-200:#e2e8f0; --sl-300:#cbd5e1;
            --sl-400:#94a3b8; --sl-500:#64748b; --sl-600:#475569; --sl-700:#334155;
            --sl-800:#1e293b; --sl-900:#0f172a;
            --white:#ffffff;
            --font:'Plus Jakarta Sans',sans-serif;
            --r-sm:12px; --r-md:16px; --r-lg:24px; --r-xl:32px;
            --sh-sm:0 2px 8px rgba(0,0,0,.06);
            --sh-md:0 4px 20px rgba(0,0,0,.08);
            --sh-lg:0 8px 40px rgba(0,0,0,.12);
            --sh-col:0 8px 32px rgba(5,150,105,.22);
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth}
        body{font-family:var(--font);background:var(--white);color:var(--sl-800);line-height:1.6;overflow-x:hidden}
        img{display:block;max-width:100%}
        a{text-decoration:none;color:inherit}

        /* ── UTILITY ── */
        .container{width:100%;max-width:1100px;margin:0 auto;padding:0 20px}
        .badge{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:999px;font-size:12px;font-weight:700;letter-spacing:.04em;text-transform:uppercase}
        .badge-em{background:var(--em-100);color:var(--em-700)}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:13px 26px;border-radius:var(--r-sm);font-size:15px;font-weight:700;cursor:pointer;border:none;transition:all .25s cubic-bezier(.34,1.56,.64,1);font-family:var(--font)}
        .btn:hover{transform:translateY(-2px)}
        .btn:active{transform:translateY(0)}
        .btn-primary{background:var(--em-600);color:var(--white);box-shadow:var(--sh-col)}
        .btn-primary:hover{background:var(--em-700);box-shadow:0 12px 40px rgba(5,150,105,.38)}
        .btn-outline{background:transparent;color:var(--em-700);border:2px solid var(--em-200)}
        .btn-outline:hover{background:var(--em-50);border-color:var(--em-400)}
        .btn-sm{padding:8px 18px;font-size:13px;border-radius:10px}
        .btn-amber{background:var(--amber-400);color:var(--sl-900)}
        .btn-amber:hover{background:var(--amber-500);box-shadow:0 10px 30px rgba(251,191,36,.38)}
        .btn-ghost{background:rgba(255,255,255,.15);color:var(--white);border:1.5px solid rgba(255,255,255,.3)}
        .btn-ghost:hover{background:rgba(255,255,255,.25)}
        .sec-label{font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--em-600);margin-bottom:10px}
        .sec-title{font-size:clamp(22px,5vw,36px);font-weight:800;color:var(--sl-800);line-height:1.2}
        .sec-desc{font-size:15px;color:var(--sl-500);line-height:1.75;margin-top:10px}

        /* ── NAVBAR ── */
        .navbar{position:fixed;top:0;left:0;right:0;z-index:100;padding:16px 0;transition:all .3s ease}
        .navbar.scrolled{background:rgba(255,255,255,.95);backdrop-filter:blur(16px);box-shadow:0 1px 0 var(--sl-200);padding:11px 0}
        .navbar-inner{display:flex;align-items:center;justify-content:space-between}
        .nav-logo{display:flex;align-items:center;gap:10px}
        .nav-logo img{width:36px;height:36px;object-fit:contain;border-radius:8px}
        .nav-logo-text{font-size:16px;font-weight:800;color:var(--em-700);line-height:1.1}
        .nav-logo-sub{font-size:11px;font-weight:500;color:var(--sl-400);display:block}
        .nav-links{display:none;align-items:center;gap:28px}
        @media(min-width:768px){.nav-links{display:flex}}
        .nav-links a:hover{color:var(--em-600)}
        .nav-right{display:flex;align-items:center;gap:10px}
        .nav-right .btn{display:none}
        @media(min-width:768px){.nav-right .btn{display:inline-flex}}
        .hamburger{display:flex;flex-direction:column;gap:5px;cursor:pointer;padding:6px;background:none;border:none}
        @media(min-width:768px){.hamburger{display:none}}
        .hamburger span{display:block;width:22px;height:2px;background:var(--sl-700);border-radius:999px;transition:all .3s}
        .hamburger.open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
        .hamburger.open span:nth-child(2){opacity:0}
        .hamburger.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}

        /* Mobile menu */
        .mob-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.5);z-index:99}
        .mob-overlay.open{display:flex;align-items:flex-start}
        .mob-panel{background:var(--white);width:280px;padding:72px 24px 24px;height:100%;display:flex;flex-direction:column;gap:4px;box-shadow:var(--sh-lg)}
        .mob-panel a{font-size:16px;font-weight:600;color:var(--sl-700);padding:12px 8px;border-bottom:1px solid var(--sl-100);display:block}
        .mob-panel .btn{margin-top:16px;text-align:center;width:100%;justify-content:center}

        /* ── HERO ── */
        .hero{min-height:100dvh;padding:100px 0 64px;background:linear-gradient(160deg,var(--em-50) 0%,#f0fdfa 50%,var(--white) 100%);position:relative;overflow:hidden;display:flex;align-items:center}
        .hero::before{content:'';position:absolute;width:480px;height:480px;background:radial-gradient(circle,rgba(52,211,153,.14) 0%,transparent 70%);top:-80px;right:-120px;border-radius:50%;pointer-events:none}
        .hero::after{content:'';position:absolute;width:280px;height:280px;background:radial-gradient(circle,rgba(251,191,36,.1) 0%,transparent 70%);bottom:-40px;left:-60px;border-radius:50%;pointer-events:none}
        .hero-inner{display:grid;grid-template-columns:1fr;gap:48px;align-items:center;position:relative;z-index:1}
        @media(min-width:768px){.hero-inner{grid-template-columns:1fr 1fr;gap:56px}}
        .hero-title{font-size:clamp(30px,7vw,52px);font-weight:800;color:var(--sl-800);line-height:1.15;margin:16px 0 18px}
        .hero-title .hi{color:var(--em-600);position:relative;display:inline-block}
        .hero-title .hi::after{content:'';position:absolute;bottom:2px;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--em-400),var(--teal-400));border-radius:999px;opacity:.35}
        .hero-desc{font-size:15px;color:var(--sl-500);line-height:1.8;margin-bottom:28px;max-width:460px}
        .hero-btns{display:flex;flex-wrap:wrap;gap:12px;margin-bottom:40px}
        .hero-stats{display:flex;flex-wrap:wrap;gap:24px;padding-top:22px;border-top:1px solid var(--sl-200)}
        .hero-stat-num{font-size:24px;font-weight:800;color:var(--sl-800);line-height:1}
        .hero-stat-lbl{font-size:12px;font-weight:500;color:var(--sl-400);margin-top:3px}
        .hero-img-wrap{display:flex;justify-content:center}
        .hero-img-card{background:var(--white);border-radius:var(--r-xl);padding:14px;box-shadow:var(--sh-lg);width:100%;max-width:440px;position:relative}
        .hero-img-card img{width:100%;border-radius:var(--r-lg)}
        .float-badge{position:absolute;background:var(--white);border-radius:var(--r-sm);padding:10px 14px;box-shadow:var(--sh-md);display:flex;align-items:center;gap:10px;font-size:13px;font-weight:700;color:var(--sl-700);animation:floatY 3s ease-in-out infinite}
        .float-badge .fi{width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center}
        .float-badge .fi svg{width:18px;height:18px}
        .fb1{top:-14px;left:-10px} .fb2{bottom:10px;right:-18px;animation-delay:1.2s}
        @media(max-width:767px){.fb1{top:-8px;left:2px}.fb2{bottom:4px;right:2px}}
        @keyframes floatY{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}

        /* ── SENSUS GENERUS ── */
        .sensus{padding:72px 0;background:var(--em-800)}
        .sensus-header{text-align:center;margin-bottom:40px}
        .sensus-header .sec-label{color:var(--em-300)}
        .sensus-header .sec-title{color:var(--white)}
        .sensus-header .sec-desc{color:rgba(255,255,255,.6)}
        .sensus-total{display:flex;align-items:center;justify-content:center;gap:16px;margin-bottom:40px}
        .sensus-total-num{font-size:56px;font-weight:800;color:var(--white);line-height:1}
        .sensus-total-info{text-align:left}
        .sensus-total-info strong{display:block;font-size:15px;font-weight:700;color:var(--em-300)}
        .sensus-total-info span{font-size:13px;color:rgba(255,255,255,.5)}
        .sensus-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}
        @media(min-width:640px){.sensus-grid{grid-template-columns:repeat(5,1fr)}}
        .sensus-card{background:rgba(255,255,255,.08);border:1.5px solid rgba(255,255,255,.12);border-radius:var(--r-md);padding:20px 14px;text-align:center;transition:all .3s}
        .sensus-card:hover{background:rgba(255,255,255,.14);transform:translateY(-3px)}
        .sensus-card-icon{display:flex;align-items:center;justify-content:center;height:48px;margin-bottom:14px;color:var(--em-300)}
        .sensus-card-icon svg{width:36px;height:36px;stroke-width:1.5px}
        .sensus-card-num{font-size:28px;font-weight:800;color:var(--white);line-height:1}
        .sensus-card-label{font-size:12px;font-weight:600;color:rgba(255,255,255,.55);margin-top:5px}

        /* ── SLIDER KEPENGURUSAN ── */
        .kepengurusan{padding:72px 0;background:var(--sl-50)}
        .kepengurusan-header{text-align:center;max-width:520px;margin:0 auto 40px}
        .slider-wrap{position:relative;overflow:hidden}
        .slider-track{display:flex;gap:20px;transition:transform .4s cubic-bezier(.25,.46,.45,.94);will-change:transform;padding-bottom:4px}
        .pengurus-card{flex:0 0 calc(50% - 10px);background:var(--white);border-radius:var(--r-md);padding:24px 20px;box-shadow:var(--sh-sm);border:1.5px solid var(--sl-200);text-align:center;transition:all .3s;min-width:0}
        @media(min-width:640px){.pengurus-card{flex:0 0 calc(33.333% - 14px)}}
        @media(min-width:900px){.pengurus-card{flex:0 0 calc(25% - 15px)}}
        .pengurus-card:hover{box-shadow:var(--sh-md);border-color:var(--em-300);transform:translateY(-4px)}
        .avatar{width:72px;height:72px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;color:var(--white);margin:0 auto 14px;flex-shrink:0}
        .pengurus-nama{font-size:14px;font-weight:700;color:var(--sl-800);line-height:1.3;margin-bottom:5px}
        .pengurus-dapukan{font-size:12px;font-weight:500;color:var(--em-600);background:var(--em-50);padding:3px 10px;border-radius:999px;display:inline-block}
        .slider-controls{display:flex;align-items:center;justify-content:center;gap:16px;margin-top:24px}
        .slider-btn{width:40px;height:40px;border-radius:50%;border:1.5px solid var(--sl-200);background:var(--white);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;transition:all .2s;color:var(--sl-600)}
        .slider-btn:hover{border-color:var(--em-400);color:var(--em-600);background:var(--em-50)}
        .slider-btn:disabled{opacity:.4;cursor:not-allowed}
        .slider-dots{display:flex;gap:6px}
        .dot{width:7px;height:7px;border-radius:50%;background:var(--sl-300);cursor:pointer;transition:all .2s}
        .dot.active{width:20px;border-radius:999px;background:var(--em-500)}

        /* ── FEATURE ICONS ── */
        .feature-icon svg{width:22px;height:22px}

        /* ── CHART STATISTIK ── */
        .chartstat{padding:72px 0;background:var(--white)}
        .chartstat-header{text-align:center;max-width:560px;margin:0 auto 36px}
        .filter-panel{background:var(--sl-50);border-radius:var(--r-lg);padding:20px 20px 24px;margin-bottom:32px;border:1.5px solid var(--sl-200)}
        .filter-level{margin-top:18px;padding-top:18px;border-top:1px solid var(--sl-200)}
        .filter-level:first-child{margin-top:0;padding-top:0;border-top:none}
        .filter-level-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px}
        .filter-level-label{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--sl-400);display:flex;align-items:center;gap:6px}
        .filter-level-label svg{width:13px;height:13px;opacity:.7}
        .filter-clear{font-size:12px;font-weight:600;color:var(--sl-400);background:none;border:none;cursor:pointer;padding:3px 8px;border-radius:6px;font-family:var(--font);transition:all .2s;display:flex;align-items:center;gap:4px}
        .filter-clear:hover{background:var(--sl-200);color:var(--sl-700)}
        .filter-chips{display:flex;flex-wrap:wrap;gap:8px}
        .chip{padding:7px 14px;border-radius:999px;font-size:13px;font-weight:600;cursor:pointer;border:1.5px solid var(--sl-200);background:var(--white);color:var(--sl-600);transition:all .2s;font-family:var(--font)}
        .chip.active{border-color:var(--em-500);background:var(--em-50);color:var(--em-700)}
        .chip:hover{border-color:var(--em-400);color:var(--em-700)}
        .filter-breadcrumb{display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:16px}
        .bc-btn{padding:5px 12px;border-radius:999px;font-size:12px;font-weight:700;border:1.5px solid var(--sl-200);background:var(--white);color:var(--sl-500);cursor:pointer;font-family:var(--font);transition:all .2s;white-space:nowrap}
        .bc-btn.active{background:var(--em-600);border-color:var(--em-600);color:var(--white)}
        .bc-btn:hover:not(.active){background:var(--sl-100)}
        .bc-sep{color:var(--sl-300);font-size:14px;font-weight:600}
        .chart-state-label{font-size:13px;font-weight:600;color:var(--sl-600);margin-bottom:0;padding:10px 14px;background:var(--em-50);border:1.5px solid var(--em-200);border-radius:var(--r-sm);display:flex;align-items:center;gap:8px}
        .chart-state-label svg{width:14px;height:14px;color:var(--em-600)}
        .chart-container{background:var(--sl-50);border-radius:var(--r-lg);padding:20px 20px 16px;border:1.5px solid var(--sl-200);position:relative;min-height:320px;height:380px}
        .chart-container.loading{display:flex;align-items:center;justify-content:center}
        .chart-empty{text-align:center;color:var(--sl-400);font-size:14px;width:100%}
        .chart-empty svg{width:36px;height:36px;margin:0 auto 10px;opacity:.35}
        #chartCanvas{width:100%!important;height:100%!important}

        /* ── MODAL ── */
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.55);z-index:200;align-items:flex-end;padding:0}
        @media(min-width:640px){.modal-overlay{align-items:center;padding:20px}}
        .modal-overlay.open{display:flex}
        .modal-box{background:var(--white);border-radius:var(--r-lg) var(--r-lg) 0 0;width:100%;max-width:660px;margin:0 auto;max-height:90dvh;display:flex;flex-direction:column;overflow:hidden}
        @media(min-width:640px){.modal-box{border-radius:var(--r-lg)}}
        .modal-head{padding:20px 24px 16px;border-bottom:1px solid var(--sl-100);display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
        .modal-head h3{font-size:17px;font-weight:700;color:var(--sl-800)}
        .modal-head p{font-size:13px;color:var(--sl-400);margin-top:2px}
        .modal-close{width:36px;height:36px;border-radius:50%;border:none;background:var(--sl-100);cursor:pointer;font-size:17px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .2s}
        .modal-close:hover{background:var(--sl-200)}
        .modal-filter-chips{padding:14px 24px;border-bottom:1px solid var(--sl-100);display:flex;gap:8px;flex-wrap:wrap;flex-shrink:0}
        .modal-body{padding:0;overflow-y:auto;flex:1}
        .modal-table{width:100%;border-collapse:collapse}
        .modal-table th{position:sticky;top:0;background:var(--sl-50);padding:11px 16px;text-align:left;font-size:12px;font-weight:700;color:var(--sl-500);letter-spacing:.04em;text-transform:uppercase;border-bottom:1px solid var(--sl-200)}
        .modal-table td{padding:11px 16px;font-size:14px;color:var(--sl-700);border-bottom:1px solid var(--sl-100)}
        .modal-table tr:last-child td{border-bottom:none}
        .modal-table tr:hover td{background:var(--sl-50)}
        .modal-empty{padding:48px;text-align:center;color:var(--sl-400)}
        .kat-badge{padding:3px 9px;border-radius:999px;font-size:11px;font-weight:700}
        .kat-PAUD{background:#fef9ee;color:#92400e}
        .kat-CABERAWIT{background:var(--em-50);color:var(--em-700)}
        .kat-PRA_REMAJA{background:#eff6ff;color:#1d4ed8}
        .kat-REMAJA{background:#fdf4ff;color:#7e22ce}
        .kat-PRA_NIKAH{background:#fff7ed;color:#c2410c}
        .jk-L{color:#2563eb;font-weight:600}
        .jk-P{color:#db2777;font-weight:600}
        .loading-spinner{width:36px;height:36px;border:3px solid var(--sl-200);border-top-color:var(--em-500);border-radius:50%;animation:spin .7s linear infinite;margin:48px auto}
        @keyframes spin{to{transform:rotate(360deg)}}

        /* ── CTA ── */
        .cta{padding:72px 0;background:linear-gradient(135deg,var(--em-700) 0%,var(--teal-600) 100%);position:relative;overflow:hidden}
        .cta::before{content:'';position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.06) 1px,transparent 1px);background-size:24px 24px}
        .cta-inner{position:relative;z-index:1;text-align:center;max-width:580px;margin:0 auto}
        .cta-title{font-size:clamp(24px,5vw,40px);font-weight:800;color:var(--white);line-height:1.2;margin-bottom:14px}
        .cta-desc{font-size:15px;color:rgba(255,255,255,.72);margin-bottom:32px;line-height:1.7}
        .cta-btns{display:flex;flex-wrap:wrap;gap:12px;justify-content:center}

        /* ── FOOTER ── */
        footer{background:var(--sl-900);padding:48px 0 24px}
        .footer-inner{display:flex;flex-direction:column;gap:32px}
        @media(min-width:768px){.footer-inner{flex-direction:row;justify-content:space-between;align-items:flex-start}}
        .footer-logo-text{font-size:16px;font-weight:800;color:var(--em-400);line-height:1.1}
        .footer-logo-sub{font-size:11px;color:rgba(255,255,255,.3)}
        .footer-desc{font-size:13px;color:rgba(255,255,255,.38);margin-top:12px;max-width:260px;line-height:1.7}
        .footer-links-wrap{display:flex;gap:40px;flex-wrap:wrap}
        .footer-links-group h5{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.35);margin-bottom:14px}
        .footer-links-group ul{list-style:none;display:flex;flex-direction:column;gap:9px}
        .footer-links-group a{font-size:14px;color:rgba(255,255,255,.6);font-weight:500;transition:color .2s}
        .footer-links-group a:hover{color:var(--em-400)}
        .footer-bottom{padding-top:24px;margin-top:24px;border-top:1px solid rgba(255,255,255,.07);display:flex;flex-direction:column;gap:6px;align-items:center;text-align:center}
        @media(min-width:768px){.footer-bottom{flex-direction:row;justify-content:space-between}}
        .footer-bottom p{font-size:13px;color:rgba(255,255,255,.28)}

        /* ── REVEAL ANIMATION ── */
        .reveal{opacity:0;transform:translateY(22px);transition:opacity .55s ease,transform .55s ease}
        .reveal.visible{opacity:1;transform:translateY(0)}
        .reveal-d1{transition-delay:.1s}.reveal-d2{transition-delay:.2s}.reveal-d3{transition-delay:.3s}
        .reveal-d4{transition-delay:.4s}.reveal-d5{transition-delay:.5s}
    </style>
</head>
<body>

{{-- ════ NAVBAR ════ --}}
<nav class="navbar" id="navbar">
    <div class="container navbar-inner">
        <a href="#" class="nav-logo">
            <img src="{{ asset('images/logo.png') }}" alt="PPG Logo">
            <span class="nav-logo-text">PPG Kartim <small class="nav-logo-sub">Karawang Timur</small></span>
        </a>
        <div class="nav-links">
            <a href="#profil">Profil</a>
            <a href="#generus">Statistik</a>
            <a href="#kepengurusan">Kepengurusan</a>
            <a href="#chart">Chart</a>
        </div>
        <div class="nav-right">
            @auth
                <a href="{{ url('/admin') }}" class="btn btn-primary btn-sm" id="nav-dash-btn">Dashboard →</a>
            @else
                <a href="{{ url('/admin/login') }}" class="btn btn-outline btn-sm" id="nav-login-btn">Masuk</a>
            @endauth
            <button class="hamburger" id="ham-btn" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile Menu --}}
<div class="mob-overlay" id="mob-menu">
    <div class="mob-panel">
        <a href="#profil" class="mob-link">Profil</a>
        <a href="#generus" class="mob-link">Statistik Generus</a>
        <a href="#kepengurusan" class="mob-link">Kepengurusan</a>
        <a href="#chart" class="mob-link">Chart Wilayah</a>
        @auth
            <a href="{{ url('/admin') }}" class="btn btn-primary">Dashboard</a>
        @else
            <a href="{{ url('/admin/login') }}" class="btn btn-primary">Masuk</a>
        @endauth
    </div>
</div>


{{-- ════ HERO ════ --}}
<section class="hero" id="profil">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-text">
                <span class="badge badge-em reveal">🌿 Penggerak Pembina Generus</span>
                <h1 class="hero-title reveal">
                    Portal Data<br>
                    <span class="hi">PPG Karawang</span><br>
                    Timur
                </h1>
                <p class="hero-desc reveal">
                    Platform digital terintegrasi untuk mengelola data generus, mubaligh, dan laporan PJP. Mewujudkan pembinaan yang terukur, terdata, dan berkelanjutan.
                </p>
                <div class="hero-btns reveal">
                    @auth
                        <a href="{{ url('/admin') }}" class="btn btn-primary" id="hero-dash-btn">Ke Dashboard →</a>
                    @else
                        <a href="{{ url('/admin/login') }}" class="btn btn-primary" id="hero-login-btn">Masuk</a>
                        <a href="#generus" class="btn btn-outline" id="hero-stat-btn">Lihat Statistik</a>
                    @endauth
                </div>
                <div class="hero-stats reveal">
                    @php $totalSemua = array_sum(array_column($totals, 'total')); @endphp
                    <div>
                        <div class="hero-stat-num">{{ number_format($totalSemua) }}</div>
                        <div class="hero-stat-lbl">Total Generus</div>
                    </div>
                    <div>
                        <div class="hero-stat-num">5</div>
                        <div class="hero-stat-lbl">Kategori</div>
                    </div>
                    <div>
                        <div class="hero-stat-num">24/7</div>
                        <div class="hero-stat-lbl">Akses Data</div>
                    </div>
                </div>
            </div>

            <div class="hero-img-wrap reveal">
                <div class="hero-img-card">
                    <img src="{{ asset('images/hero-illustration.png') }}" alt="Generasi Muda PPG">
                    <div class="float-badge fb1">
                        <div class="fi" style="background:var(--em-100);color:var(--em-700)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
                        </div>
                        <div>
                            <div>Data Terstruktur</div>
                            <div style="font-size:11px;font-weight:500;color:var(--sl-400)">Real-time & akurat</div>
                        </div>
                    </div>
                    <div class="float-badge fb2">
                        <div class="fi" style="background:var(--amber-100);color:#b45309">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                        </div>
                        <div>
                            <div>100% Digital</div>
                            <div style="font-size:11px;font-weight:500;color:var(--sl-400)">Kelola dari mana saja</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ════ SENSUS GENERUS ════ --}}
<section class="sensus" id="generus">
    <div class="container">
        <div class="sensus-header reveal">
            <div class="sec-label">Sensus Generus</div>
            <h2 class="sec-title" style="color:var(--white)">Total Generus Karawang Timur</h2>
            <p class="sec-desc">Data jumlah generus berdasarkan kategori pembinaan, diperbarui secara real-time.</p>
        </div>

        <div class="sensus-total reveal">
            <div class="sensus-total-num count-up" data-target="{{ $totalSemua }}">0</div>
            <div class="sensus-total-info">
                <strong>Total Generus</strong>
                <span>Seluruh Karawang Timur</span>
            </div>
        </div>

        @php
            $sensusIcons = [
                'PAUD'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>',
                'CABERAWIT'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>',
                'PRA_REMAJA' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2Z"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/><path d="M8 22v-6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v6"/><path d="M8 10h8"/></svg>',
                'REMAJA'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>',
                'PRA_NIKAH'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>',
            ];
            $delayClasses = ['reveal-d1','reveal-d2','reveal-d3','reveal-d4','reveal-d5'];
            $i = 0;
        @endphp
        <div class="sensus-grid">
            @foreach($totals as $key => $val)
            <div class="sensus-card reveal {{ $delayClasses[$i] }}">
                <div class="sensus-card-icon">{!! $sensusIcons[$key] !!}</div>
                <div class="sensus-card-num count-up" data-target="{{ $val['total'] }}">0</div>
                <div class="sensus-card-label">{{ $val['label'] }}</div>
            </div>
            @php $i++; @endphp
            @endforeach
        </div>
    </div>
</section>


{{-- ════ SLIDER KEPENGURUSAN ════ --}}
<section class="kepengurusan" id="kepengurusan">
    <div class="container">
        <div class="kepengurusan-header reveal">
            <div class="sec-label">Kepengurusan PPG</div>
            <h2 class="sec-title">Pengurus PPG Karawang Timur</h2>
            <p class="sec-desc">Tim pengurus yang berdedikasi menggerakkan pembinaan generus di wilayah Karawang Timur.</p>
        </div>

        @php
        $pengurusList = [
            ['nama'=>'H. Ahmad Fauzi, S.Ag', 'dapukan'=>'Ketua PPG', 'inisial'=>'AF', 'color'=>'#059669'],
            ['nama'=>'Siti Rahmawati', 'dapukan'=>'Sekretaris', 'inisial'=>'SR', 'color'=>'#0d9488'],
            ['nama'=>'Muhammad Ridwan', 'dapukan'=>'Bendahara', 'inisial'=>'MR', 'color'=>'#7c3aed'],
            ['nama'=>'Fatimah Azzahra', 'dapukan'=>'Bid. PAUD & CBR', 'inisial'=>'FA', 'color'=>'#db2777'],
            ['nama'=>'Hasan Basri', 'dapukan'=>'Bid. Pra Remaja', 'inisial'=>'HB', 'color'=>'#d97706'],
            ['nama'=>'Nurul Hidayah', 'dapukan'=>'Bid. Remaja', 'inisial'=>'NH', 'color'=>'#0284c7'],
            ['nama'=>'Abdul Mukti', 'dapukan'=>'Bid. Pra Nikah', 'inisial'=>'AM', 'color'=>'#16a34a'],
            ['nama'=>'Dewi Anggraeni', 'dapukan'=>'Bid. Pendidikan', 'inisial'=>'DA', 'color'=>'#9333ea'],
        ];
        @endphp

        <div class="slider-wrap reveal">
            <div class="slider-track" id="sliderTrack">
                @foreach($pengurusList as $p)
                <div class="pengurus-card">
                    <div class="avatar" style="background:{{ $p['color'] }}">{{ $p['inisial'] }}</div>
                    <div class="pengurus-nama">{{ $p['nama'] }}</div>
                    <span class="pengurus-dapukan">{{ $p['dapukan'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="slider-controls">
            <button class="slider-btn" id="sliderPrev" aria-label="Sebelumnya">&#8592;</button>
            <div class="slider-dots" id="sliderDots"></div>
            <button class="slider-btn" id="sliderNext" aria-label="Berikutnya">&#8594;</button>
        </div>
    </div>
</section>


{{-- ════ CHART STATISTIK ════ --}}
<section class="chartstat" id="chart">
    <div class="container">
        <div class="chartstat-header reveal">
            <div class="sec-label">Statistik Wilayah</div>
            <h2 class="sec-title">Sebaran Generus per Wilayah</h2>
            <p class="sec-desc">Lihat total, lalu saring per Desa, lalu per Kelompok. Klik batang chart untuk melihat daftar generus.</p>
        </div>

        <div class="filter-panel reveal">
            {{-- Breadcrumb state --}}
            <div class="filter-breadcrumb" id="filterBreadcrumb">
                <button class="bc-btn active" id="bcTotal">Karawang Timur</button>
                <span class="bc-sep" id="bcSep1" style="display:none">›</span>
                <button class="bc-btn" id="bcDesa" style="display:none"></button>
                <span class="bc-sep" id="bcSep2" style="display:none">›</span>
                <button class="bc-btn" id="bcKel" style="display:none"></button>
            </div>

            {{-- Level 1: Filter Desa --}}
            <div class="filter-level">
                <div class="filter-level-head">
                    <span class="filter-level-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Filter per Desa
                    </span>
                    <button class="filter-clear" id="clearDesa" style="display:none">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Hapus filter
                    </button>
                </div>
                <div class="filter-chips" id="desaChips">
                    @foreach($desas as $desa)
                        <button class="chip" data-id="{{ $desa->id }}" data-nama="{{ $desa->nm_desa }}">{{ $desa->nm_desa }}</button>
                    @endforeach
                </div>
            </div>

            {{-- Level 2: Filter Kelompok (hidden until desa selected) --}}
            <div class="filter-level" id="kelompokLevel" style="display:none">
                <div class="filter-level-head">
                    <span class="filter-level-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Filter per Kelompok
                        <span id="kelInDesaLabel" style="font-size:11px;color:var(--em-600);font-weight:700;text-transform:none;letter-spacing:0"></span>
                    </span>
                    <button class="filter-clear" id="clearKel">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Hapus filter
                    </button>
                </div>
                <div class="filter-chips" id="kelompokChips"></div>
            </div>
        </div>

        <div class="chart-container reveal" id="chartContainer">
            <canvas id="chartCanvas"></canvas>
        </div>

        {{-- Data for JS --}}
        <script id="desaData" type="application/json">@json($desas)</script>
        <script id="kelompokData" type="application/json">@json($kelompoks)</script>
    </div>
</section>


{{-- ════ CTA ════ --}}
<section class="cta">
    <div class="cta-inner reveal">
        <div class="badge" style="background:rgba(255,255,255,.15);color:white;margin-bottom:18px">
            🚀 Mulai Sekarang
        </div>
        <h2 class="cta-title">Siap Mengelola Data Generus dengan Lebih Mudah?</h2>
        <p class="cta-desc">Masuk ke portal dan manfaatkan semua fitur pengelolaan data PPG Karawang Timur secara digital.</p>
        <div class="cta-btns">
            @auth
                <a href="{{ url('/admin') }}" class="btn btn-amber" id="cta-dash-btn">Ke Dashboard →</a>
            @else
                <a href="{{ url('/admin/login') }}" class="btn btn-amber" id="cta-login-btn">Masuk</a>
                <a href="#profil" class="btn btn-ghost" id="cta-back-btn">Pelajari Lebih Lanjut</a>
            @endauth
        </div>
    </div>
</section>


{{-- ════ FOOTER ════ --}}
<footer>
    <div class="container">
        <div class="footer-inner">
            <div>
                <div class="nav-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="PPG Logo">
                    <span class="footer-logo-text">PPG Kartim <small class="footer-logo-sub">Karawang Timur</small></span>
                </div>
                <p class="footer-desc">Penggerak Pembina Generus (PPG) Karawang Timur — Membangun generasi berakhlaqul karimah melalui data terintegrasi.</p>
            </div>
            <div class="footer-links-wrap">
                <div class="footer-links-group">
                    <h5>Navigasi</h5>
                    <ul>
                        <li><a href="#profil">Profil</a></li>
                        <li><a href="#generus">Statistik</a></li>
                        <li><a href="#kepengurusan">Kepengurusan</a></li>
                        <li><a href="#chart">Chart Wilayah</a></li>
                    </ul>
                </div>
                <div class="footer-links-group">
                    <h5>Akses</h5>
                    <ul>
                        @auth
                            <li><a href="{{ url('/admin') }}">Dashboard</a></li>
                        @else
                            <li><a href="{{ url('/admin/login') }}">Masuk</a></li>
                        @endauth
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


{{-- ════ MODAL DETAIL GENERUS ════ --}}
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-box" id="modalBox">
        <div class="modal-head">
            <div>
                <h3 id="modalTitle">Detail Generus</h3>
                <p id="modalSubtitle">Memuat data...</p>
            </div>
            <button class="modal-close" id="modalClose">✕</button>
        </div>
        <div class="modal-filter-chips" id="modalKatFilter">
            <button class="chip active" data-kat="">Semua Kategori</button>
            <button class="chip" data-kat="PAUD">PAUD/TK</button>
            <button class="chip" data-kat="CABERAWIT">Caberawit</button>
            <button class="chip" data-kat="PRA_REMAJA">Pra Remaja</button>
            <button class="chip" data-kat="REMAJA">Remaja</button>
            <button class="chip" data-kat="PRA_NIKAH">Pra Nikah</button>
        </div>
        <div class="modal-body">
            <div class="loading-spinner" id="modalLoader"></div>
            <table class="modal-table" id="modalTable" style="display:none">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody id="modalBody"></tbody>
            </table>
            <div class="modal-empty" id="modalEmpty" style="display:none">
                Tidak ada data generus ditemukan.
            </div>
        </div>
    </div>
</div>


<script>
/* ── Navbar scroll ── */
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => navbar.classList.toggle('scrolled', scrollY > 50), {passive:true});

/* ── Hamburger ── */
const hamBtn = document.getElementById('ham-btn');
const mobMenu = document.getElementById('mob-menu');
hamBtn.addEventListener('click', () => {
    hamBtn.classList.toggle('open');
    mobMenu.classList.toggle('open');
});
mobMenu.addEventListener('click', e => { if(e.target===mobMenu){ hamBtn.classList.remove('open'); mobMenu.classList.remove('open'); }});
document.querySelectorAll('.mob-link').forEach(a => a.addEventListener('click', () => {
    hamBtn.classList.remove('open'); mobMenu.classList.remove('open');
}));

/* ── Scroll Reveal ── */
const reveals = document.querySelectorAll('.reveal');
const revealObs = new IntersectionObserver(entries => entries.forEach(e => { if(e.isIntersecting) e.target.classList.add('visible'); }), {threshold:.08});
reveals.forEach(el => revealObs.observe(el));

/* ── Count-up ── */
function countUp(el) {
    const target = parseInt(el.dataset.target, 10);
    if(!target) { el.textContent = '0'; return; }
    const dur = 1600, start = performance.now();
    const tick = now => {
        const p = Math.min((now - start) / dur, 1);
        el.textContent = Math.round((1 - Math.pow(1-p, 3)) * target);
        if(p < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}
const countObs = new IntersectionObserver(entries => entries.forEach(e => {
    if(e.isIntersecting){ countUp(e.target); countObs.unobserve(e.target); }
}), {threshold:.5});
document.querySelectorAll('.count-up').forEach(el => countObs.observe(el));

/* ── Slider Kepengurusan ── */
(function(){
    const track = document.getElementById('sliderTrack');
    const dotsWrap = document.getElementById('sliderDots');
    const prevBtn = document.getElementById('sliderPrev');
    const nextBtn = document.getElementById('sliderNext');
    const cards = track.querySelectorAll('.pengurus-card');

    let current = 0;
    let perView = getPerView();
    const total = cards.length;

    function getPerView() {
        if(window.innerWidth >= 900) return 4;
        if(window.innerWidth >= 640) return 3;
        return 2;
    }

    function maxIndex() { return Math.max(0, total - perView); }

    function buildDots() {
        dotsWrap.innerHTML = '';
        const count = maxIndex() + 1;
        for(let i = 0; i < count; i++) {
            const d = document.createElement('button');
            d.className = 'dot' + (i === current ? ' active' : '');
            d.setAttribute('aria-label', `Slide ${i+1}`);
            d.addEventListener('click', () => go(i));
            dotsWrap.appendChild(d);
        }
    }

    function go(idx) {
        current = Math.max(0, Math.min(idx, maxIndex()));
        const cardW = cards[0].offsetWidth + 20; // gap 20px
        track.style.transform = `translateX(-${current * cardW}px)`;
        dotsWrap.querySelectorAll('.dot').forEach((d,i) => d.classList.toggle('active', i===current));
        prevBtn.disabled = current === 0;
        nextBtn.disabled = current >= maxIndex();
    }

    prevBtn.addEventListener('click', () => go(current - 1));
    nextBtn.addEventListener('click', () => go(current + 1));

    // Touch swipe
    let startX = 0;
    track.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, {passive:true});
    track.addEventListener('touchend', e => {
        const dx = startX - e.changedTouches[0].clientX;
        if(Math.abs(dx) > 40) go(dx > 0 ? current+1 : current-1);
    });

    window.addEventListener('resize', () => {
        const nv = getPerView();
        if(nv !== perView){ perView = nv; buildDots(); go(0); }
    });

    buildDots();
    go(0);

    // Auto-play
    let auto = setInterval(() => { if(current >= maxIndex()) go(0); else go(current+1); }, 4000);
    track.parentElement.addEventListener('mouseenter', () => clearInterval(auto));
    track.parentElement.addEventListener('mouseleave', () => { auto = setInterval(() => { if(current >= maxIndex()) go(0); else go(current+1); }, 4000); });
})();

/* ── Chart & Filter (Cascading Hierarchy) ── */
(function(){
    const desas     = JSON.parse(document.getElementById('desaData').textContent);
    const kelompoks = JSON.parse(document.getElementById('kelompokData').textContent);

    // State
    let state = { level: 'all', desaId: null, desaNama: '', kelId: null, kelNama: '' };
    let chartInst = null;

    // DOM refs
    const chartCanvas   = document.getElementById('chartCanvas');
    const chartContainer= document.getElementById('chartContainer');
    const desaChipsWrap = document.getElementById('desaChips');
    const kelChipsWrap  = document.getElementById('kelompokChips');
    const kelLevel      = document.getElementById('kelompokLevel');
    const kelInDesaLbl  = document.getElementById('kelInDesaLabel');
    const clearDesaBtn  = document.getElementById('clearDesa');
    const clearKelBtn   = document.getElementById('clearKel');
    const bcTotal       = document.getElementById('bcTotal');
    const bcDesa        = document.getElementById('bcDesa');
    const bcKel         = document.getElementById('bcKel');
    const bcSep1        = document.getElementById('bcSep1');
    const bcSep2        = document.getElementById('bcSep2');

    const katColors = {
        'PAUD'       : { bg:'rgba(251,191,36,.8)',  border:'#f59e0b' },
        'CABERAWIT'  : { bg:'rgba(16,185,129,.8)',  border:'#059669' },
        'PRA_REMAJA' : { bg:'rgba(59,130,246,.8)',  border:'#2563eb' },
        'REMAJA'     : { bg:'rgba(139,92,246,.8)',  border:'#7c3aed' },
        'PRA_NIKAH'  : { bg:'rgba(249,115,22,.8)',  border:'#ea580c' },
    };

    /* ─ Breadcrumb updater ─ */
    function updateBreadcrumb() {
        // Total button always active when level=all
        bcTotal.classList.toggle('active', state.level === 'all');

        if(state.level === 'all') {
            bcSep1.style.display = 'none';
            bcDesa.style.display = 'none';
            bcSep2.style.display = 'none';
            bcKel.style.display  = 'none';
        } else if(state.level === 'desa') {
            bcSep1.style.display = '';
            bcDesa.style.display = '';
            bcDesa.textContent = state.desaNama;
            bcDesa.classList.add('active');
            bcSep2.style.display = 'none';
            bcKel.style.display  = 'none';
        } else { // kelompok
            bcSep1.style.display = '';
            bcDesa.style.display = '';
            bcDesa.textContent = state.desaNama;
            bcDesa.classList.remove('active');
            bcSep2.style.display = '';
            bcKel.style.display  = '';
            bcKel.textContent = state.kelNama;
            bcKel.classList.add('active');
        }
    }

    /* ─ Desa chip interactions ─ */
    desaChipsWrap.addEventListener('click', e => {
        const chip = e.target.closest('.chip');
        if(!chip) return;

        const id   = chip.dataset.id;
        const nama = chip.dataset.nama;

        if(state.desaId === id && state.level === 'desa') {
            // Toggle off → go to total
            goTotal();
            return;
        }

        state.level   = 'desa';
        state.desaId  = id;
        state.desaNama= nama;
        state.kelId   = null;
        state.kelNama = '';

        // Highlight chip
        desaChipsWrap.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        clearDesaBtn.style.display = '';

        // Show kelompok chips for this desa
        renderKelChips(id, nama);

        updateBreadcrumb();
        fetchChart('desa', id, nama);
    });

    /* ─ Kelompok chip interactions ─ */
    kelChipsWrap.addEventListener('click', e => {
        const chip = e.target.closest('.chip');
        if(!chip) return;

        const id   = chip.dataset.id;
        const nama = chip.dataset.nama;

        if(state.kelId === id) {
            // Toggle off → go back to desa level
            state.level   = 'desa';
            state.kelId   = null;
            state.kelNama = '';
            kelChipsWrap.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
            updateBreadcrumb();
            fetchChart('desa', state.desaId, state.desaNama);
            return;
        }

        state.level   = 'kelompok';
        state.kelId   = id;
        state.kelNama = nama;

        kelChipsWrap.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
        chip.classList.add('active');

        updateBreadcrumb();
        fetchChart('kelompok', id, nama);
    });

    /* ─ Clear buttons ─ */
    clearDesaBtn.addEventListener('click', () => goTotal());
    clearKelBtn.addEventListener('click', () => {
        state.level   = 'desa';
        state.kelId   = null;
        state.kelNama = '';
        kelChipsWrap.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
        updateBreadcrumb();
        fetchChart('desa', state.desaId, state.desaNama);
    });

    /* ─ Breadcrumb navigation ─ */
    bcTotal.addEventListener('click', () => goTotal());
    bcDesa.addEventListener('click', () => {
        if(state.level === 'kelompok') {
            state.level = 'desa'; state.kelId = null; state.kelNama = '';
            kelChipsWrap.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
            updateBreadcrumb();
            fetchChart('desa', state.desaId, state.desaNama);
        }
    });

    function goTotal() {
        state = { level:'all', desaId:null, desaNama:'', kelId:null, kelNama:'' };
        desaChipsWrap.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
        clearDesaBtn.style.display = 'none';
        kelLevel.style.display = 'none';
        updateBreadcrumb();
        fetchChart('all', null, 'Karawang Timur');
    }

    function renderKelChips(desaId, desaNama) {
        const filtered = kelompoks.filter(k => String(k.desa_id) === String(desaId));
        kelChipsWrap.innerHTML = '';

        if(filtered.length === 0) {
            kelChipsWrap.innerHTML = '<span style="font-size:13px;color:var(--sl-400);padding:6px 0">Belum ada kelompok terdaftar</span>';
        } else {
            filtered.forEach(k => {
                const b = document.createElement('button');
                b.className = 'chip';
                b.dataset.id   = k.id;
                b.dataset.nama = k.nm_kelompok;
                b.textContent  = k.nm_kelompok;
                kelChipsWrap.appendChild(b);
            });
        }

        kelInDesaLbl.textContent = `dalam ${desaNama}`;
        kelLevel.style.display = '';
    }

    /* ─ Fetch & render chart ─ */
    function fetchChart(type, id, nama) {
        chartContainer.classList.add('loading');
        chartCanvas.style.opacity = '0';

        const url = id ? `/api/chart-data?type=${type}&id=${id}` : '/api/chart-data';
        fetch(url)
            .then(r => r.json())
            .then(data => renderChart(data, type, id, nama))
            .catch(() => { chartContainer.classList.remove('loading'); });
    }

    function renderChart(data, type, id, nama) {
        chartContainer.classList.remove('loading');

        const labels = data.map(d => d.label);
        const totals = data.map(d => d.total);
        const kats   = data.map(d => d.kategori);
        const bgs    = kats.map(k => katColors[k]?.bg   || 'rgba(100,116,139,.6)');
        const bords  = kats.map(k => katColors[k]?.border || '#64748b');

        if(chartInst) chartInst.destroy();

        chartInst = new Chart(chartCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: nama,
                    data: totals,
                    backgroundColor: bgs,
                    borderColor: bords,
                    borderWidth: 2,
                    borderRadius: 10,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                onClick: (evt, elems) => {
                    if(elems.length > 0 && id) {
                        openModal(type, id, nama, kats[elems[0].index]);
                    }
                },
                onHover: (evt, elems) => {
                    chartCanvas.style.cursor = (elems.length > 0 && id) ? 'pointer' : 'default';
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: ctx => ctx[0].label,
                            label: ctx => ` ${ctx.raw} generus` + (id ? ' — klik untuk detail' : '')
                        }
                    }
                },
                scales: {
                    x: { grid:{display:false}, ticks:{font:{family:'Plus Jakarta Sans',weight:'600'}} },
                    y: { beginAtZero:true, grid:{color:'rgba(0,0,0,.05)'}, ticks:{stepSize:1,font:{family:'Plus Jakarta Sans'}} }
                }
            }
        });

        chartCanvas.style.opacity = '1';
        chartCanvas.style.transition = 'opacity .3s';
    }

    /* ─ Init: auto-load total Karawang Timur ─ */
    updateBreadcrumb();
    fetchChart('all', null, 'Karawang Timur');

    /* ── Modal ── */
    const modalOverlay = document.getElementById('modalOverlay');
    const modalClose   = document.getElementById('modalClose');
    const modalTitle   = document.getElementById('modalTitle');
    const modalSub     = document.getElementById('modalSubtitle');
    const modalLoader  = document.getElementById('modalLoader');
    const modalTable   = document.getElementById('modalTable');
    const modalBody    = document.getElementById('modalBody');
    const modalEmpty   = document.getElementById('modalEmpty');
    const katFilterWrap= document.getElementById('modalKatFilter');
    let allGenerus = [];

    function openModal(type, id, nama, kategori='') {
        modalTitle.textContent = nama;
        modalSub.textContent = (type==='desa'?'Desa':'Kelompok') + ' · Memuat data...';
        modalLoader.style.display = 'block';
        modalTable.style.display  = 'none';
        modalEmpty.style.display  = 'none';
        katFilterWrap.querySelectorAll('.chip').forEach(c => c.classList.toggle('active', c.dataset.kat===kategori));
        modalOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';

        fetch(`/api/detail-generus?type=${type}&id=${id}`)
            .then(r => r.json())
            .then(data => {
                allGenerus = data;
                renderModalTable(kategori);
                modalSub.textContent = (type==='desa'?'Desa':'Kelompok') + ` · ${data.length} generus`;
            });
    }

    function renderModalTable(kat) {
        const katMap = {'PAUD':'PAUD/TK','CABERAWIT':'Caberawit','PRA_REMAJA':'Pra Remaja','REMAJA':'Remaja','PRA_NIKAH':'Pra Nikah'};
        const filtered = kat ? allGenerus.filter(g => g.kategori === katMap[kat]) : allGenerus;
        modalLoader.style.display = 'none';

        if(filtered.length === 0) {
            modalTable.style.display = 'none';
            modalEmpty.style.display = 'block';
            return;
        }

        modalEmpty.style.display = 'none';
        modalTable.style.display = 'table';

        const katClass = {'PAUD/TK':'PAUD','Caberawit':'CABERAWIT','Pra Remaja':'PRA_REMAJA','Remaja':'REMAJA','Pra Nikah':'PRA_NIKAH'};
        modalBody.innerHTML = filtered.map((g, i) => `
            <tr>
                <td style="color:var(--sl-400)">${i+1}</td>
                <td style="font-family:monospace;font-size:13px">${g.nis || '-'}</td>
                <td style="font-weight:600">${g.nama}</td>
                <td><span class="${g.jk==='Laki-laki'?'jk-L':'jk-P'}">${g.jk==='Laki-laki'?'♂ L':'♀ P'}</span></td>
                <td><span class="kat-badge kat-${katClass[g.kategori]||''}">${g.kategori}</span></td>
            </tr>
        `).join('');
    }

    katFilterWrap.addEventListener('click', e => {
        const chip = e.target.closest('.chip');
        if(!chip) return;
        katFilterWrap.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        modalLoader.style.display = 'block';
        modalTable.style.display  = 'none';
        modalEmpty.style.display  = 'none';
        setTimeout(() => renderModalTable(chip.dataset.kat), 80);
    });

    modalClose.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', e => { if(e.target===modalOverlay) closeModal(); });
    document.addEventListener('keydown', e => { if(e.key==='Escape') closeModal(); });

    function closeModal() {
        modalOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    window.openModal = openModal;
})();
</script>
</body>
</html>
