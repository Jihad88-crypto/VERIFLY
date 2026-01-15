<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #9945FF; /* Solana Purple */
            --secondary: #14F195; /* Solana Green */
            --accent: #19FB9B; 
            --bg-dark: #000814; /* Deep Space */
            --text-main: #ffffff;
            --text-muted: #94a3b8; 
            --card-bg: rgba(15, 23, 42, 0.85); /* Dark blue cards */
            --card-border: rgba(59, 130, 246, 0.2); /* Blue glow */
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            /* background-color set via url below */
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }


        /* PREMIUM MODERN DASHBOARD BACKGROUND */
        body {
            background: #0a0a0f;
            color: var(--text-main);
            position: relative;
            overflow-x: hidden;
        }
        
        /* Base gradient mesh */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: 
                radial-gradient(ellipse at 20% 30%, rgba(138, 43, 226, 0.3) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 70%, rgba(147, 51, 234, 0.25) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(153, 69, 255, 0.18) 0%, transparent 60%);
            z-index: 0;
            pointer-events: none;
        }
        
        /* Subtle grid pattern */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(rgba(153, 69, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(153, 69, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
            pointer-events: none;
            opacity: 0.5;
        }
        
        /* Animated purple orb - top left */
        .orb-1 {
            position: fixed;
            top: 10%;
            left: 15%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(138, 43, 226, 0.5) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
            animation: floatOrb1 20s ease-in-out infinite;
        }
        
        /* Animated purple orb - bottom right */
        .orb-2 {
            position: fixed;
            bottom: 15%;
            right: 10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(147, 51, 234, 0.45) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(90px);
            z-index: 0;
            pointer-events: none;
            animation: floatOrb2 25s ease-in-out infinite;
        }
        
        /* Accent glow - center */
        .accent-glow {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(153, 69, 255, 0.2) 0%, transparent 60%);
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
            pointer-events: none;
            animation: pulse 15s ease-in-out infinite;
        }
        
        @keyframes floatOrb1 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, -40px); }
        }
        
        @keyframes floatOrb2 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-40px, 30px); }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); }
            50% { opacity: 1; transform: translate(-50%, -50%) scale(1.1); }
        }
        
        /* Dark vignette for depth */
        .vignette {
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse at center, transparent 0%, rgba(0, 0, 0, 0.6) 100%);
            pointer-events: none;
            z-index: 0;
        }
        
        /* Hide old decorative elements */
        .purple-glow-left,
        .purple-glow-right,
        .stars,
        .shape-left,
        .shape-right,
        .chess-pink,
        .chess-clear,
        .chess-blue {
            display: none !important;
        }

        /* REMOVED OLD CANVAS STYLES */

        /* ELEGANT DARK NAVBAR */
        .navbar {
            display: flex; justify-content: space-between; align-items: center; padding: 20px 48px;
            background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.1); position: sticky; top: 0; z-index: 100;
        }
        .brand {
            display: flex; align-items: center; gap: 10px; font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.5rem; letter-spacing: -0.5px;
            background: linear-gradient(to right, #ffffff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .nav-center {
            display: flex; gap: 40px; align-items: center;
        }
        
        .nav-link {
            text-decoration: none; color: var(--text-main); font-weight: 600; font-size: 0.95rem; position: relative; padding: 8px 0; cursor: pointer;
            transition: color 0.2s;
        }
        .nav-link:hover { color: var(--primary); }
        .nav-link::after {
            content: ''; position: absolute; bottom: 0; left: 0; width: 0%; height: 2px; background: var(--primary); transition: width 0.3s ease;
        }
        .nav-link:hover::after { width: 100%; }
        .nav-link.active::after { width: 100%; }

        .nav-right { display: flex; align-items: center; gap: 24px; }

        .user-pill {
            display: flex; align-items: center; gap: 12px; 
            padding: 6px 6px 6px 16px; background: rgba(255,255,255,0.05); border-radius: 99px; border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
        }
        .avatar {
            width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 50%; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem;
        }
        
        .container { max-width: 1280px; margin: 60px auto; padding: 0 40px; }
        .page-header { margin-bottom: 60px; text-align: center; position: relative; }
        .page-header h1 { 
            font-family: 'Space Grotesk', sans-serif; font-size: 3.5rem; line-height: 1.1; font-weight: 800; 
            margin-bottom: 16px; 
            background: linear-gradient(135deg, #e2e8f0 0%, #ffffff 50%, #94a3b8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-shadow: 0 0 40px rgba(255,255,255,0.1);
        }
        .page-header p { font-size: 1.25rem; color: var(--text-muted); max-width: 600px; margin: 0 auto; }

        .grid-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; padding-bottom: 80px; }
        /* GLASSMORPHISM CARDS */
        .card {
            background: rgba(255, 255, 255, 0.05); /* Very transparent */
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-radius: 24px;
            padding: 32px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        /* Glass reflection effect */
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.3), 
                transparent
            );
        }
        
        .card:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(59, 130, 246, 0.4);
            box-shadow: 
                0 16px 48px rgba(59, 130, 246, 0.15),
                0 0 0 1px rgba(59, 130, 246, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }
        
        .card:active { 
            transform: translateY(-4px) scale(0.98); 
        }

        .card-icon-wrapper {
            width: 48px; height: 48px; background: transparent; display: flex; align-items: center; justify-content: flex-start;
            font-size: 2rem; color: #fff; margin-bottom: 12px; border: none; padding: 0;
        }
        .card h3 { font-size: 1.25rem; font-weight: 600; color: #fff; }
        .card p { font-size: 0.95rem; line-height: 1.6; color: var(--text-muted); }
        .card-action { display: none; } /* Minimal elegant style */

        .icon-blue { color: #3b82f6; } .icon-blue::after { background: #3b82f6; }
        .icon-orange { color: #f97316; } .icon-orange::after { background: #f97316; }
        .icon-green { color: #10b981; } .icon-green::after { background: #10b981; }
        .icon-pink { color: #ec4899; } .icon-pink::after { background: #ec4899; }

        .ai-fab {
            position: fixed; bottom: 40px; right: 40px; background: #111; color: #fff; padding: 14px 28px; border-radius: 100px;
            display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); transition: all 0.3s; cursor: pointer; z-index: 200;
        }
        .ai-fab:hover { transform: translateY(-5px); box-shadow: 0 15px 50px rgba(153, 69, 255, 0.4); background: linear-gradient(135deg, #222, #000); }
        
        .btn-logout { padding: 8px 20px; border-radius: 99px; border: none; color: #64748b; background: #f1f5f9; font-weight: 600; cursor: pointer; transition: all 0.2s; font-size: 0.85rem; }
        .btn-logout:hover { background: #fee2e2; color: #ef4444; }

        .lang-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 999; background: rgba(255, 255, 255, 0.3); backdrop-filter: blur(24px); display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: all 0.5s ease; }
        .lang-overlay.active { opacity: 1; pointer-events: all; }
        .lang-card { background: #fff; padding: 60px; border-radius: 40px; text-align: center; box-shadow: 0 40px 100px -20px rgba(0,0,0,0.1); max-width: 1000px; width: 90%; transform: scale(0.9); transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); border: 1px solid rgba(255,255,255,1); }
        .lang-overlay.active .lang-card { transform: scale(1); }
        .lang-title { font-family: 'Space Grotesk', sans-serif; font-size: 3rem; font-weight: 800; margin-bottom: 40px; color: #111; }
        .lang-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
        @media (max-width: 768px) { .lang-grid { grid-template-columns: repeat(2, 1fr); } }
        .lang-option { background: #f8fafc; border: 2px solid transparent; border-radius: 20px; padding: 24px; cursor: pointer; transition: all 0.3s ease; }
        .lang-option:hover { border-color: var(--primary); background: #fdfdfd; box-shadow: 0 10px 40px rgba(153, 69, 255, 0.1); transform: translateY(-5px); }
        .lang-flag { font-size: 3rem; margin-bottom: 12px; display: block; }
        .lang-name { font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; font-weight: 700; color: var(--text-main); }
        .close-lang { margin-top: 40px; background: #e2e8f0; border: none; color: var(--text-main); font-weight: 600; cursor: pointer; font-size: 1rem; padding: 12px 32px; border-radius: 99px; }
        .close-lang:hover { background: #cbd5e1; }
    </style>
</head>
<body>
    
    <!-- Premium Background Effects -->
    <div class="orb-1"></div>
    <div class="orb-2"></div>
    <div class="accent-glow"></div>
    <div class="vignette"></div>

    <!-- Background is now handled via CSS Body -->



    <div class="lang-overlay" id="langOverlay">
        <div class="lang-card">
            <h2 class="lang-title" id="t-lang-title">Choose your Language</h2>
            <div class="lang-grid">
                <div class="lang-option" onclick="changeLang('en')"><span class="lang-flag">🇬🇧</span><span class="lang-name">English</span></div>
                <div class="lang-option" onclick="changeLang('id')"><span class="lang-flag">🇮🇩</span><span class="lang-name">Indonesia</span></div>
                <div class="lang-option" onclick="changeLang('es')"><span class="lang-flag">🇪🇸</span><span class="lang-name">Español</span></div>
                <div class="lang-option" onclick="changeLang('fr')"><span class="lang-flag">🇫🇷</span><span class="lang-name">Français</span></div>
                <div class="lang-option" onclick="changeLang('de')"><span class="lang-flag">🇩🇪</span><span class="lang-name">Deutsch</span></div>
                <div class="lang-option" onclick="changeLang('jp')"><span class="lang-flag">🇯🇵</span><span class="lang-name">日本語</span></div>
                <div class="lang-option" onclick="changeLang('cn')"><span class="lang-flag">🇨🇳</span><span class="lang-name">中文</span></div>
                <div class="lang-option" onclick="changeLang('ru')"><span class="lang-flag">🇷🇺</span><span class="lang-name">Русский</span></div>
            </div>
            <button class="close-lang" onclick="toggleLang()" id="t-close">Close / Tutup</button>
        </div>
    </div>

    <!-- NEW PROFESSIONAL NAVBAR -->
    <!-- NAVBAR -->
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
             <h1 id="heroTitle">Privacy Command Center</h1>
             <p id="heroSub">Manage verifications, API capability, and security settings in one place.</p>
        </div>

        <div class="grid-cards">
            <!-- 1 Video -->
            <div class="card" onclick="window.location.href='{{ route('verify.video') }}'">
                <div class="card-icon-wrapper"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg></div>
                <h3 id="c1-title">Video Verification</h3>
                <p id="c1-desc">Upload video content to detect deepfake artifacts.</p>
                <div class="card-action" id="c1-act">Start Scan <span>→</span></div>
            </div>
            <!-- 2 Photo -->
            <div class="card" onclick="window.location.href='{{ route('verify.image') }}'">
                <div class="card-icon-wrapper icon-green"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                <h3 id="c2-title">Image Analysis</h3>
                <p id="c2-desc">Scan images for pixel-level alterations and AI generation.</p>
                <div class="card-action" style="color: var(--secondary);" id="c2-act">Analyze Now <span>→</span></div>
            </div>
            <!-- 3 Audio -->
            <div class="card" onclick="window.location.href='{{ route('verify.audio') }}'">
                <div class="card-icon-wrapper icon-blue"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg></div>
                <h3 id="c3-title">Audio Authenticity</h3>
                <p id="c3-desc">Detect voice cloning and synthetic speech patterns.</p>
                <div class="card-action" style="color: var(--accent);" id="c3-act">Check Audio <span>→</span></div>
            </div>
            <!-- 4 API -->
            <div class="card" onclick="window.location.href='{{ route('developer.api') }}'">
                <div class="card-icon-wrapper icon-orange"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg></div>
                <h3 id="c4-title">Developer API</h3>
                <p id="c4-desc">Get API keys to integrate verification into your apps.</p>
                <div class="card-action" style="color: #f97316;" id="c4-act">Get Keys <span>→</span></div>
            </div>
            <!-- 5 History -->
            <div class="card" onclick="window.location.href='{{ route('activity.logs') }}'">
                <div class="card-icon-wrapper" style="color: #64748b;"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                <h3 id="c5-title">Activity Logs</h3>
                <p id="c5-desc">Review past verifications and download signed certificates.</p>
                <div class="card-action" style="color: #64748b;" id="c5-act">View History <span>→</span></div>
            </div>
            <!-- 6 Billing -->
            <div class="card" onclick="window.location.href='{{ route('billing') }}'">
                <div class="card-icon-wrapper icon-pink"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg></div>
                <h3 id="c6-title">Billing & Plans</h3>
                <p id="c6-desc">Manage subscription, payment methods, and invoices.</p>
                <div class="card-action" style="color: #ec4899;" id="c6-act">Manage Plan <span>→</span></div>
            </div>
            <!-- 7 Settings -->
            <div class="card" onclick="window.location.href='{{ route('settings') }}'">
                <div class="card-icon-wrapper" style="color: #8b5cf6;"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></div>
                <h3 id="c7-title">Settings</h3>
                <p id="c7-desc">Update profile, security, and notification preferences.</p>
                <div class="card-action" style="color: #8b5cf6;" id="c7-act">Configure <span>→</span></div>
            </div>
            <!-- 8 Help -->
            <div class="card" onclick="window.location.href='{{ route('support') }}'">
                <div class="card-icon-wrapper" style="color: #14b8a6;"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></div>
                <h3 id="c8-title">Help Center</h3>
                <p id="c8-desc">Get support, read documentation, and find answers.</p>
                <div class="card-action" style="color: #14b8a6;" id="c8-act">Get Help <span>→</span></div>
            </div>
        </div>
    </div>

    <div class="ai-fab"><span>✨</span><span style="font-weight: 600;" id="ai-btn">Ask AI</span></div>

    <script>
        const translations = {
            en: {
                title: "Privacy Command Center", sub: "Manage verifications, API capability, and security settings in one place.",
                nav: ["Dashboard", "Technology", "Developers", "Pricing", "Support"],
                c1: ["Video Verification", "Upload video content to detect deepfake artifacts.", "Start Scan"],
                c2: ["Image Analysis", "Scan images for pixel-level alterations and AI generation.", "Analyze Now"],
                c3: ["Audio Authenticity", "Detect voice cloning and synthetic speech patterns.", "Check Audio"],
                c4: ["Developer API", "Get API keys to integrate verification into your apps.", "Get Keys"],
                c5: ["Activity Logs", "Review past verifications and download signed certificates.", "View History"],
                c6: ["Billing & Plans", "Manage subscription, payment methods, and invoices.", "Manage Plan"],
                c7: ["Settings", "Update profile, security, and notification preferences.", "Configure"],
                c8: ["Help Center", "Get support, read documentation, and find answers.", "Get Help"],
                ai: "Ask AI", close: "Close"
            },
            id: {
                title: "Pusat Komando Privasi", sub: "Kelola verifikasi, kemampuan API, dan pengaturan keamanan di satu tempat.",
                nav: ["Dasbor", "Teknologi", "Pengembang", "Harga", "Bantuan"],
                c1: ["Verifikasi Video", "Unggah video untuk mendeteksi artefak deepfake.", "Mulai Pindai"],
                c2: ["Analisis Gambar", "Pindai gambar untuk mendeteksi perubahan piksel dan AI.", "Analisis Sekarang"],
                c3: ["Keaslian Audio", "Deteksi kloning suara dan pola ucapan sintetis.", "Cek Audio"],
                c4: ["API Pengembang", "Dapatkan kunci API untuk integrasi aplikasi Anda.", "Dapatkan Kunci"],
                c5: ["Log Aktivitas", "Lihat riwayat verifikasi dan unduh sertifikat.", "Lihat Riwayat"],
                c6: ["Tagihan & Paket", "Kelola langganan, metode pembayaran, dan faktur.", "Kelola Paket"],
                c7: ["Pengaturan", "Perbarui profil, keamanan, dan preferensi notifikasi.", "Konfigurasi"],
                c8: ["Pusat Bantuan", "Dapatkan dukungan, baca dokumentasi, dan temukan jawaban.", "Dapatkan Bantuan"],
                ai: "Tanya AI", close: "Tutup"
            },
            es: {
                title: "Centro de Comando", sub: "Gestione verificaciones, API y configuración de seguridad en un solo lugar.",
                nav: ["Tablero", "Tecnología", "Desarrolladores", "Precios", "Soporte"],
                c1: ["Verificación de Video", "Suba video para detectar artefactos deepfake.", "Iniciar"],
                c2: ["Análisis de Imagen", "Escanee imágenes para detectar alteraciones.", "Analizar"],
                c3: ["Autenticidad de Audio", "Detecte clonación de voz y patrones sintéticos.", "Revisar Audio"],
                c4: ["API de Desarrollador", "Obtenga claves API para integrar en sus apps.", "Obtener Claves"],
                c5: ["Registros", "Revise verificaciones pasadas y descargue certificados.", "Ver Historial"],
                c6: ["Facturación", "Gestione suscripciones y métodos de pago.", "Gestionar"],
                c7: ["Configuración", "Actualice perfil, seguridad y notificaciones.", "Configurar"],
                c8: ["Centro de Ayuda", "Obtenga soporte y lea la documentación.", "Ayuda"],
                ai: "Preguntar a AI", close: "Cerrar"
            },
            fr: {
                title: "Centre de Commande", sub: "Gérez les vérifications, l'API et la sécurité en un seul endroit.",
                nav: ["Tableau de bord", "Technologie", "Développeurs", "Tarifs", "Support"],
                c1: ["Vérification Vidéo", "Téléchargez une vidéo pour détecter les deepfakes.", "Scanner"],
                c2: ["Analyse d'Image", "Scannez les images pour détecter les altérations.", "Analyser"],
                c3: ["Authenticité Audio", "Détectez le clonage vocal et la synthèse.", "Vérifier Audio"],
                c4: ["API Développeur", "Obtenez des clés API pour vos applications.", "Obtenir Clés"],
                c5: ["Journaux", "Consultez l'historique et les certificats.", "Voir Historique"],
                c6: ["Facturation", "Gérez les abonnements et les factures.", "Gérer Plan"],
                c7: ["Paramètres", "Mettez à jour le profil et la sécurité.", "Configurer"],
                c8: ["Centre d'Aide", "Obtenez de l'aide et de la documentation.", "Aide"],
                ai: "Demander à l'IA", close: "Fermer"
            },
            de: {
                title: "Datenschutz Zentrale", sub: "Verwalten Sie Verifizierungen, API und Sicherheit an einem Ort.",
                nav: ["Dashboard", "Technologie", "Entwickler", "Preise", "Support"],
                c1: ["Video-Verifizierung", "Video hochladen, um Deepfakes zu erkennen.", "Starten"],
                c2: ["Bildanalyse", "Bilder auf Pixeländerungen scannen.", "Analysieren"],
                c3: ["Audio-Echtheit", "Stimmklonen und synthetische Sprache erkennen.", "Prüfen"],
                c4: ["Entwickler-API", "API-Schlüssel für Ihre Apps erhalten.", "Schlüssel holen"],
                c5: ["Aktivitätsprotokolle", "Verlauf prüfen und Zertifikate herunterladen.", "Verlauf ansehen"],
                c6: ["Abrechnung", "Abonnements und Rechnungen verwalten.", "Verwalten"],
                c7: ["Einstellungen", "Profil und Sicherheit aktualisieren.", "Konfigurieren"],
                c8: ["Hilfezentrum", "Support erhalten und Dokumentation lesen.", "Hilfe"],
                ai: "KI Fragen", close: "Schließen"
            },
            jp: {
                title: "プライバシーコマンドセンター", sub: "検証、API機能、セキュリティ設定を一か所で管理します。",
                nav: ["ダッシュボード", "テクノロジー", "開発者", "価格", "サポート"],
                c1: ["ビデオ検証", "ビデオをアップロードしてディープフェイクを検出します。", "スキャン開始"],
                c2: ["画像分析", "画像をスキャンしてAI生成や修正を検出します。", "今すぐ分析"],
                c3: ["音声の真正性", "音声のクローン作成や合成パターンを検出します。", "音声をチェック"],
                c4: ["開発者API", "アプリに統合するためのAPIキーを取得します。", "キーを取得"],
                c5: ["活動ログ", "過去の検証を確認し、証明書をダウンロードします。", "履歴を表示"],
                c6: ["請求とプラン", "サブスクリプションと請求書を管理します。", "プラン管理"],
                c7: ["設定", "プロフィール、セキュリティ、通知を更新します。", "設定する"],
                c8: ["ヘルプセンター", "サポートを受け、ドキュメントを読みます。", "ヘルプ"],
                ai: "AIに聞く", close: "閉じる"
            },
            cn: {
                title: "隐私控制中心", sub: "在一个地方管理验证、API功能和安全设置。",
                nav: ["仪表板", "技术", "开发者", "定价", "支持"],
                c1: ["视频验证", "上传视频以检测Deepfake伪造。", "开始扫描"],
                c2: ["图像分析", "扫描图像以检测像素级更改和AI生成。", "立即分析"],
                c3: ["音频真实性", "检测语音克隆和合成语音模式。", "检查音频"],
                c4: ["开发者API", "获取API密钥以集成到您的应用中。", "获取密钥"],
                c5: ["活动日志", "查看过去的验证并下载证书。", "查看历史"],
                c6: ["账单与计划", "管理订阅、付款方式和发票。", "管理计划"],
                c7: ["设置", "更新个人资料、安全性和通知首选项。", "配置"],
                c8: ["帮助中心", "获取支持，阅读文档并查找答案。", "获取帮助"],
                ai: "询问AI", close: "关闭"
            },
            ru: {
                title: "Центр управления", sub: "Управляйте проверками, API и безопасностью в одном месте.",
                nav: ["Дашборд", "Технологии", "Разработчики", "Цены", "Поддержка"],
                c1: ["Видео проверка", "Загрузите видео для поиска дипфейков.", "Сканировать"],
                c2: ["Анализ изображений", "Сканирование изображений на наличие изменений.", "Анализ"],
                c3: ["Аутентичность аудио", "Обнаружение клонирования голоса.", "Проверить"],
                c4: ["API разработчика", "Получите ключи API для интеграции.", "Получить ключи"],
                c5: ["Журналы", "Просмотр истории и загрузка сертификатов.", "История"],
                c6: ["Биллинг", "Управление подпиской и счетами.", "Управление"],
                c7: ["Настройки", "Обновление профиля и безопасности.", "Настроить"],
                c8: ["Центр помощи", "Получите поддержку и документацию.", "Помощь"],
                ai: "Спросить ИИ", close: "Закрыть"
            }
        };

        // Check for saved language on load
        document.addEventListener('DOMContentLoaded', () => {
            const savedLang = localStorage.getItem('privasi_lang') || 'en';
            // We need to apply the language without toggling the overlay if it's just loading
            // But reuse changeLang logic is easiest, just ensure overlay handling is correct
            // Ideally we split "apply text" from "toggle overlay". for now let's reuse changeLang
            // and force overlay closed if it opens.
            // Actually, changeLang calls toggleLang() at the end. We should modify changeLang.
            // Let's rewrite the functions slightly to be cleaner.
            
            const t = translations[savedLang];
            if(t) {
                updateText(t);
            }
        });

        function toggleLang() {
            const overlay = document.getElementById('langOverlay');
            overlay.classList.toggle('active');
        }

        function changeLang(lang) {
            const t = translations[lang];
            if(!t) return;

            // Save preference
            localStorage.setItem('privasi_lang', lang);

            updateText(t);
            toggleLang(); // Close overlay
            
            // Dispatch Global Event for other views (e.g. Services/Technology Modal)
            window.dispatchEvent(new CustomEvent('languageChanged', { detail: { lang: lang } }));
        }

        function updateText(t) {
             // Header //
            const heroTitle = document.getElementById('heroTitle');
            if(heroTitle) heroTitle.innerHTML = t.title;
            const heroSub = document.getElementById('heroSub');
            if(heroSub) heroSub.innerText = t.sub;

            // Nav //
            // Nav //
            const navDash = document.getElementById('nav-dash'); if(navDash) navDash.innerText = t.nav[0];
            const navTech = document.getElementById('nav-tech'); if(navTech) navTech.innerText = t.nav[1];
            const navDev = document.getElementById('nav-dev'); if(navDev) navDev.innerText = t.nav[2];
            const navPrice = document.getElementById('nav-price'); if(navPrice) navPrice.innerText = t.nav[3];
            const navSupport = document.getElementById('nav-support'); if(navSupport) navSupport.innerText = t.nav[4];

            // Cards //
            if(document.getElementById('c1-title')) {
                document.getElementById('c1-title').innerText = t.c1[0]; document.getElementById('c1-desc').innerText = t.c1[1]; document.querySelector('#c1-act').childNodes[0].nodeValue = t.c1[2] + " ";
                document.getElementById('c2-title').innerText = t.c2[0]; document.getElementById('c2-desc').innerText = t.c2[1]; document.querySelector('#c2-act').childNodes[0].nodeValue = t.c2[2] + " ";
                document.getElementById('c3-title').innerText = t.c3[0]; document.getElementById('c3-desc').innerText = t.c3[1]; document.querySelector('#c3-act').childNodes[0].nodeValue = t.c3[2] + " ";
                document.getElementById('c4-title').innerText = t.c4[0]; document.getElementById('c4-desc').innerText = t.c4[1]; document.querySelector('#c4-act').childNodes[0].nodeValue = t.c4[2] + " ";
                document.getElementById('c5-title').innerText = t.c5[0]; document.getElementById('c5-desc').innerText = t.c5[1]; document.querySelector('#c5-act').childNodes[0].nodeValue = t.c5[2] + " ";
                document.getElementById('c6-title').innerText = t.c6[0]; document.getElementById('c6-desc').innerText = t.c6[1]; document.querySelector('#c6-act').childNodes[0].nodeValue = t.c6[2] + " ";
                document.getElementById('c7-title').innerText = t.c7[0]; document.getElementById('c7-desc').innerText = t.c7[1]; document.querySelector('#c7-act').childNodes[0].nodeValue = t.c7[2] + " ";
                document.getElementById('c8-title').innerText = t.c8[0]; document.getElementById('c8-desc').innerText = t.c8[1]; document.querySelector('#c8-act').childNodes[0].nodeValue = t.c8[2] + " ";
            }

            // Others //
            const aiBtn = document.getElementById('ai-btn'); if(aiBtn) aiBtn.innerText = t.ai;
            const tClose = document.getElementById('t-close'); if(tClose) tClose.innerText = t.close;

        }

    <script>
        // Language Script Only (Canvas Removed)
    </script>
</body>
</html>
