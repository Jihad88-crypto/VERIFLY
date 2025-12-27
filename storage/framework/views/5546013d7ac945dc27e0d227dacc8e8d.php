<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8b5cf6; /* Violet for Settings */
            --secondary: #a855f7; 
            --accent: #6366f1; 
            --text-main: #1e1e2f;
            --text-muted: #64748b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F7FF;
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient BG (Dominant Violet Theme) */
        .ambient-bg {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -3;
            background: #ffffff; overflow: hidden;
        }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.6;
            animation: floatOrb 25s infinite ease-in-out alternate;
        }
        /* Violet + Purple + Indigo */
        .orb-1 { width: 900px; height: 900px; background: #8b5cf6; /* Violet */ top: -300px; right: -200px; opacity: 0.5; }
        .orb-2 { width: 700px; height: 700px; background: #a855f7; /* Purple */ bottom: -200px; left: -200px; animation-duration: 35s; opacity: 0.4; }
        .orb-3 { 
            width: 500px; height: 500px; background: #6366f1; /* Indigo */ 
            top: 40%; left: 40%; transform: translate(-50%, -50%);
            animation-name: pulseOrb; animation-duration: 15s; opacity: 0.3;
        }
        .noise-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -2; opacity: 0.03; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }
        @keyframes floatOrb {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(40px, 40px) rotate(5deg); }
        }
        @keyframes pulseOrb {
            0% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.1); }
            100% { transform: translate(-50%, -50%) scale(1); }
        }

        .container { max-width: 1000px; margin: 0 auto; padding: 40px 20px 80px; }

        .page-header { text-align: center; margin-bottom: 50px; }
        .page-header h1 { font-family: 'Space Grotesk', sans-serif; font-size: 2.5rem; margin-bottom: 12px; color: #1a1b2e; }
        .page-header p { color: var(--text-muted); font-size: 1.1rem; }

        .btn-back {
            display: inline-flex; align-items: center; gap: 8px; 
            padding: 10px 24px; border-radius: 99px;
            background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.8);
            color: var(--text-main); font-weight: 600; text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); font-size: 0.95rem;
        }
        .btn-back:hover {
            transform: translateX(-4px); background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08); color: var(--primary);
        }

        /* SETTINGS LAYOUT */
        .settings-layout { display: grid; grid-template-columns: 240px 1fr; gap: 32px; background: rgba(255,255,255,0.7); backdrop-filter: blur(20px); border-radius: 20px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05); }
        @media(max-width: 800px) { .settings-layout { grid-template-columns: 1fr; } }

        /* SIDEBAR */
        .settings-sidebar { background: rgba(255,255,255,0.5); border-right: 1px solid #e2e8f0; padding: 24px; }
        @media(max-width: 800px) { .settings-sidebar { border-right: none; border-bottom: 1px solid #e2e8f0; overflow-x: auto; display: flex; gap: 10px; padding: 16px; } }
        
        .nav-item {
            display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 12px;
            color: #64748b; font-weight: 600; cursor: pointer; transition: all 0.2s; margin-bottom: 4px;
        }
        .nav-item:hover { background: #fff; color: var(--primary); }
        .nav-item.active { background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3); }

        /* CONTENT AREA */
        .settings-content { padding: 32px; }
        .tab-pane { display: none; animation: fadeIn 0.3s ease; }
        .tab-pane.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        .section-title { font-size: 1.25rem; font-weight: 700; color: #1e1e2f; margin-bottom: 8px; }
        .section-desc { font-size: 0.95rem; color: #64748b; margin-bottom: 24px; border-bottom: 1px solid #e2e8f0; padding-bottom: 16px; }

        /* FORMS */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-weight: 600; color: #475569; margin-bottom: 8px; font-size: 0.9rem; }
        .form-input {
            width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid #cbd5e1;
            font-family: inherit; font-size: 0.95rem; outline: none; transition: all 0.2s;
        }
        .form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1); }
        
        .btn-save {
            padding: 10px 24px; background: #1e1e2f; color: white; border: none; border-radius: 8px;
            font-weight: 600; cursor: pointer; transition: all 0.2s;
        }
        /* AVATAR UPLOAD */
        .avatar-section { display: flex; align-items: center; gap: 24px; margin-bottom: 32px; }
        .avatar-preview { 
            width: 80px; height: 80px; border-radius: 50%; 
            background: #e2e8f0; overflow: hidden; 
            border: 4px solid rgba(255,255,255,0.2);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }
        .avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
        .btn-upload {
            padding: 10px 20px; border: 1px solid #cbd5e1; border-radius: 8px; background: white;
            cursor: pointer; font-size: 0.9rem; font-weight: 600; color: #475569; transition: all 0.2s;
        }
        .btn-upload:hover { border-color: #94a3b8; color: #1e293b; background: #f8fafc; }

        /* SESSIONS - Clean List */
        .session-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 12px; background: #fff;
        }
        .device-info { display: flex; align-items: center; gap: 12px; }
        .device-icon { width: 40px; height: 40px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .revoke-link { color: #ef4444; font-weight: 600; cursor: pointer; font-size: 0.9rem; }

        /* NOTIFICATION TOGGLE */
        .toggle-item { display: flex; justify-content: space-between; align-items: center; padding: 16px 0; border-bottom: 1px solid #f1f5f9; }
        .toggle-switch {
            width: 44px; height: 24px; background: #cbd5e1; border-radius: 99px; position: relative; cursor: pointer; transition: background 0.2s;
        }
        .toggle-switch::after {
            content: ''; position: absolute; left: 2px; top: 2px; width: 20px; height: 20px; background: white; border-radius: 50%; transition: transform 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .toggle-switch.active { background: var(--primary); }
        .toggle-switch.active::after { transform: translateX(20px); }

        /* PLAN BADGES */
        .status-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px; border-radius: 99px;
            font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;
            margin-left: 12px; vertical-align: middle;
        }

        /* Free: Simple & Clean */
        .badge-free {
            background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;
        }

        /* PROFILE CARD THEMES */
        .profile-card {
            border-radius: 20px; padding: 32px; margin-bottom: 24px;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative; overflow: hidden;
        }

        /* 1. FREE: Biasa Saja (Clean & Simple) */
        .profile-card.free {
            background: #f8fafc; border: 1px solid #e2e8f0;
        }
        .profile-card.free .card-header-badge { display: none; }

        /* 2. PRO: Mewah (Vibrant Gradient Card) */
        .profile-card.pro {
            background: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%); /* Indigo to Purple */
            color: white;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 15px 35px -5px rgba(79, 70, 229, 0.4);
            position: relative; overflow: hidden;
        }
        .profile-card.pro::before {
            content: ''; position: absolute; top: 0; right: 0; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            transform: translate(20%, -20%); pointer-events: none;
        }
        .profile-card.pro .section-title { color: white; }
        .profile-card.pro .section-desc { color: rgba(255,255,255,0.8); border-color: rgba(255,255,255,0.2); }
        .profile-card.pro .form-label { color: rgba(255,255,255,0.9); }
        .profile-card.pro .form-input {
            background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.3); color: white;
            backdrop-filter: blur(5px);
        }
        .profile-card.pro .form-input:focus {
            background: rgba(255,255,255,0.25); border-color: white; box-shadow: 0 0 0 2px rgba(255,255,255,0.2);
        }
        .profile-card.pro .btn-save {
            background: white; color: #4f46e5; border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2); font-weight: 700;
        }
        .profile-card.pro .btn-upload {
            background: rgba(255,255,255,0.2); color: white; border-color: rgba(255,255,255,0.4);
        }
        .profile-card.pro .btn-upload:hover { background: rgba(255,255,255,0.3); }

        /* 3. ENTERPRISE: Lebih Mewah (Unified Dark Theme) */
        .profile-card.enterprise {
            background: #0f172a; /* Slate 900 */
            color: white;
            border: 1px solid #1e293b; /* Slate 800 */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        /* Harmonize Badge inside Enterprise Card */
        .profile-card.enterprise .badge-enterprise {
            background: rgba(251, 191, 36, 0.1); /* Glassy Gold */
            border-color: rgba(251, 191, 36, 0.5);
            box-shadow: none;
            overflow: hidden; /* Ensure shine stays inside */
        }
        /* Restore Shine Animation for Badge inside Card */
        .profile-card.enterprise .badge-enterprise::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(251, 191, 36, 0.4), transparent);
            transform: skewX(-20deg);
            animation: shine 3s infinite ease-in-out;
            display: block; /* Force display */
        }
        .profile-card.enterprise .form-label { color: #94a3b8; }
        .profile-card.enterprise .form-input {
            background: #1e293b; /* Slate 800 */
            border-color: #334155; color: #f8fafc;
        }
        .profile-card.enterprise .form-input:focus {
            border-color: #fbbf24; background: #0f172a;
            box-shadow: 0 0 0 2px rgba(251, 191, 36, 0.2);
        }
        .profile-card.enterprise .section-title { color: white; }
        .profile-card.enterprise .section-desc { color: #cbd5e1; border-color: #1e293b; }
        .profile-card.enterprise .btn-save {
            background: linear-gradient(135deg, #fbbf24, #b45309); /* Gold to Bronze */
            color: #fff; font-weight: 700; text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .profile-card.enterprise::after {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.03), transparent);
            transform: rotate(45deg); animation: shimmer 8s infinite linear; pointer-events: none;
        }
        @keyframes shimmer { 0% { transform: translateY(-100%) rotate(45deg); } 100% { transform: translateY(100%) rotate(45deg); } }

        /* Switcher for Demo */
        .theme-switcher { display: flex; gap: 8px; margin-bottom: 20px; }
        .theme-btn { padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; cursor: pointer; font-size: 0.85rem; }
        .theme-btn.active { background: #1e1e2f; color: white; border-color: #1e1e2f; }
    </style>
</head>
<body>

    <div class="ambient-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="noise-overlay"></div>
    </div>

    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container">
        
        <div class="page-header">
            <a href="<?php echo e(route('dashboard')); ?>" class="btn-back" id="btn-back">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span id="btn-back-text">Back</span>
            </a>
            <h1><span id="page-title">Settings</span></h1>
            <p id="page-sub">Manage your profile, security, and preferences.</p>
        </div>

        <div class="settings-layout">
            
            <!-- SIDEBAR -->
            <div class="settings-sidebar">
                <div class="nav-item active" onclick="switchTab('profile', this)" id="nav-prof">👤 Profile</div>
                <div class="nav-item" onclick="switchTab('security', this)" id="nav-sec">🛡️ Security</div>
                <div class="nav-item" onclick="switchTab('notif', this)" id="nav-notif">🔔 Notifications</div>
            </div>

            <!-- CONTENT -->
            <div class="settings-content">
                
                <!-- PROFILE TAB -->
                <div id="tab-profile" class="tab-pane active">
                    <!-- DEMO SWITCHER -->
                    <div class="theme-switcher">
                        <button class="theme-btn" onclick="setProfileTheme('free')">See Free</button>
                        <button class="theme-btn" onclick="setProfileTheme('pro')">See Pro</button>
                        <button class="theme-btn active" onclick="setProfileTheme('enterprise')">See Enterprise</button>
                    </div>

                    <!-- PROFILE CARD CONTAINER -->
                    <div class="profile-card enterprise" id="profile-card">
                        
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div>
                                <h2 class="section-title" id="t-prof-title">Public Profile</h2>
                                <p class="section-desc" id="t-prof-desc" style="border: none; padding: 0; margin-bottom: 24px;">Manage your public appearance.</p>
                            </div>
                            <span class="status-badge badge-enterprise" id="status-badge" style="margin:0;">ENTERPRISE 👑</span>
                        </div>

                        <div class="avatar-section" style="background: transparent; border: none; padding: 0; box-shadow: none;">
                            <!-- Reverted to Original Image Avatar -->
                            <div class="avatar-preview">
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(Auth::user()->name)); ?>&background=8b5cf6&color=fff&size=128" alt="Avatar" id="user-avatar-img">
                            </div>
                            <div>
                                <button class="btn-upload" id="btn-change-av">Change Avatar</button>
                                <div style="font-size: 0.8rem; opacity: 0.7; margin-top: 4px; color: inherit;">JPG, GIF or PNG. Max 1MB.</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" id="lbl-name">Full Name</label>
                            <input type="text" class="form-input" value="<?php echo e(Auth::user()->name); ?>" id="in-name">
                        </div>
                        <div class="form-group">
                            <label class="form-label" id="lbl-email">Email Address</label>
                            <input type="email" class="form-input" value="<?php echo e(Auth::user()->email); ?>" id="in-email">
                        </div>
                        
                        <button class="btn-save" id="btn-save-prof">Save Changes</button>
                    </div>
                </div>

                <!-- SECURITY TAB -->
                <div id="tab-security" class="tab-pane">
                    <h2 class="section-title" id="t-sec-title">Security Settings</h2>
                    <p class="section-desc" id="t-sec-desc">Keep your account safe with secure authentication.</p>

                    <div style="margin-bottom: 32px;">
                        <h3 style="font-size: 1rem; margin-bottom: 16px; color: #334155;" id="t-change-pass">Change Password</h3>
                        <div class="form-group">
                            <input type="password" class="form-input" placeholder="Current Password" id="in-curr-pass">
                        </div>
                        <div class="form-group" style="display: flex; gap: 16px;">
                            <input type="password" class="form-input" placeholder="New Password" id="in-new-pass">
                            <input type="password" class="form-input" placeholder="Confirm Password" id="in-conf-pass">
                        </div>
                        <button class="btn-save" id="btn-upd-pass">Update Password</button>
                    </div>

                    <h3 style="font-size: 1rem; margin-bottom: 16px; color: #334155; border-top: 1px solid #e2e8f0; padding-top: 24px;" id="t-sessions">Active Sessions</h3>
                    
                    <div class="session-item">
                        <div class="device-info">
                            <div class="device-icon">💻</div>
                            <div>
                                <div style="font-weight: 600; color: #334155;">Windows 11 PRO • Chrome</div>
                                <div style="font-size: 0.85rem; color: #10b981;">● Active now • Jakarta, ID</div>
                            </div>
                        </div>
                    </div>

                    <div class="session-item">
                        <div class="device-info">
                            <div class="device-icon">📱</div>
                            <div>
                                <div style="font-weight: 600; color: #334155;">iPhone 14 • Safari</div>
                                <div style="font-size: 0.85rem; color: #64748b;">Last seen 2 hours ago • Singapore</div>
                            </div>
                        </div>
                        <div class="revoke-link" id="btn-revoke">Revoke</div>
                    </div>

                </div>

                <!-- NOTIFICATIONS TAB -->
                <div id="tab-notif" class="tab-pane">
                    <h2 class="section-title" id="t-notif-title">Notifications</h2>
                    <p class="section-desc" id="t-notif-desc">Manage how you receive updates and alerts.</p>

                    <div class="toggle-item">
                        <div>
                            <div style="font-weight: 600; color: #334155;" id="t-notif-news">Deepfake News & Alerts</div>
                            <div style="font-size: 0.85rem; color: #64748b;">Get notified about new AI detection models.</div>
                        </div>
                        <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
                    </div>

                    <div class="toggle-item">
                        <div>
                            <div style="font-weight: 600; color: #334155;" id="t-notif-acc">Account Activity</div>
                            <div style="font-size: 0.85rem; color: #64748b;">Notify me when a new device logs in.</div>
                        </div>
                        <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
                    </div>

                    <div class="toggle-item">
                        <div>
                            <div style="font-weight: 600; color: #334155;" id="t-notif-quota">Quota Low Warning</div>
                            <div style="font-size: 0.85rem; color: #64748b;">Alert me when credits are below 10%.</div>
                        </div>
                        <div class="toggle-switch" onclick="this.classList.toggle('active')"></div>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <script>
        function switchTab(tabId, el) {
            // Content
            document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');

            // Nav
            document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
            el.classList.add('active');
        }

        const translations = {
            en: {
                nav: { dash: "Dashboard", tech: "Technology", dev: "Developers", price: "Pricing", supp: "Support" },
                page: { title: "Settings", sub: "Manage your profile, security, and preferences." },
                back: "Back",
                tabs: { prof: "👤 Profile", sec: "🛡️ Security", notif: "🔔 Notifications" },
                prof: { title: "Public Profile", desc: "This information will be displayed on your certificates.", name: "Full Name", email: "Email Address", save: "Save Changes", chg: "Change Avatar", 
                        badge_free: "FREE PLAN", badge_pro: "PRO MEMBER", badge_ent: "ENTERPRISE 👑",
                        btn_free: "See Free", btn_pro: "See Pro", btn_ent: "See Enterprise" },
                sec: { title: "Security Settings", desc: "Keep your account safe with secure authentication.", pass: "Change Password", up: "Update Password", sess: "Active Sessions", rev: "Revoke" },
                notif: { title: "Notifications", desc: "Manage how you receive updates and alerts.", news: "Deepfake News & Alerts", acc: "Account Activity", quota: "Quota Low Warning" }
            },
            id: {
                nav: { dash: "Dasbor", tech: "Teknologi", dev: "Developers", price: "Harga", supp: "Bantuan" },
                page: { title: "Pengaturan", sub: "Kelola profil, keamanan, dan preferensi Anda." },
                back: "Kembali",
                tabs: { prof: "👤 Profil", sec: "🛡️ Keamanan", notif: "🔔 Notifikasi" },
                prof: { title: "Profil Publik", desc: "Informasi ini akan ditampilkan di sertifikat Anda.", name: "Nama Lengkap", email: "Alamat Email", save: "Simpan Perubahan", chg: "Ganti Foto",
                        badge_free: "PAKET GRATIS", badge_pro: "ANGGOTA PRO", badge_ent: "ENTERPRISE 👑",
                        btn_free: "Lihat Free", btn_pro: "Lihat Pro", btn_ent: "Lihat Enterprise" },
                sec: { title: "Pengaturan Keamanan", desc: "Jaga akun Anda tetap aman dengan autentikasi kuat.", pass: "Ganti Kata Sandi", up: "Perbarui Sandi", sess: "Sesi Aktif", rev: "Cabut" },
                notif: { title: "Notifikasi", desc: "Kelola cara Anda menerima pembaruan.", news: "Berita & Peringatan Deepfake", acc: "Aktivitas Akun", quota: "Peringatan Kuota Rendah" }
            },
            es: {
                nav: { dash: "Tablero", tech: "Tecnología", dev: "Desarrolladores", price: "Precios", supp: "Ayuda" },
                page: { title: "Configuración", sub: "Gestione su perfil y seguridad." },
                back: "Volver",
                tabs: { prof: "👤 Perfil", sec: "🛡️ Seguridad", notif: "🔔 Notificaciones" },
                prof: { title: "Perfil Público", desc: "Esta información aparecerá en sus certificados.", name: "Nombre Completo", email: "Correo", save: "Guardar", chg: "Cambiar Foto",
                        badge_free: "PLAN GRATIS", badge_pro: "MIEMBRO PRO", badge_ent: "EMPRESA 👑",
                        btn_free: "Ver Free", btn_pro: "Ver Pro", btn_ent: "Ver Empresa" },
                sec: { title: "Seguridad", desc: "Mantenga su cuenta segura.", pass: "Cambiar Contraseña", up: "Actualizar", sess: "Sesiones Activas", rev: "Revocar" },
                notif: { title: "Notificaciones", desc: "Gestione alertas.", news: "Noticias Deepfake", acc: "Actividad Cuenta", quota: "Alerta Cuota Baja" }
            },
            fr: {
                nav: { dash: "Tableau", tech: "Technologie", dev: "Développeurs", price: "Tarifs", supp: "Support" },
                page: { title: "Paramètres", sub: "Gérez votre profil et sécurité." },
                back: "Retour",
                tabs: { prof: "👤 Profil", sec: "🛡️ Sécurité", notif: "🔔 Notifications" },
                prof: { title: "Profil Public", desc: "Info affichée sur vos certificats.", name: "Nom Complet", email: "Email", save: "Enregistrer", chg: "Changer Avatar",
                        badge_free: "PLAN GRATUIT", badge_pro: "MEMBRE PRO", badge_ent: "ENTREPRISE 👑",
                        btn_free: "Voir Free", btn_pro: "Voir Pro", btn_ent: "Voir Entreprise" },
                sec: { title: "Sécurité", desc: "Protégez votre compte.", pass: "Mot de passe", up: "Mettre à jour", sess: "Sessions actives", rev: "Révoquer" },
                notif: { title: "Notifications", desc: "Gérez vos alertes.", news: "Actualités Deepfake", acc: "Activité Compte", quota: "Alerte Quota" }
            },
            de: {
                nav: { dash: "Dashboard", tech: "Technologie", dev: "Entwickler", price: "Preise", supp: "Support" },
                page: { title: "Einstellungen", sub: "Verwalten Sie Profil und Sicherheit." },
                back: "Zurück",
                tabs: { prof: "👤 Profil", sec: "🛡️ Sicherheit", notif: "🔔 Benachrichtigungen" },
                prof: { title: "Öffentliches Profil", desc: "Wird auf Zertifikaten angezeigt.", name: "Vollständiger Name", email: "E-Mail", save: "Speichern", chg: "Avatar Ändern",
                        badge_free: "KOSTENLOS", badge_pro: "PRO MITGLIED", badge_ent: "ENTERPRISE 👑",
                        btn_free: "Siehe Free", btn_pro: "Siehe Pro", btn_ent: "Siehe Enterprise" },
                sec: { title: "Sicherheit", desc: "Schützen Sie Ihr Konto.", pass: "Passwort Ändern", up: "Aktualisieren", sess: "Aktive Sitzungen", rev: "Widerrufen" },
                notif: { title: "Benachrichtigungen", desc: "Verwalten Sie Warnungen.", news: "Deepfake News", acc: "Kontoaktivität", quota: "Niedrige Quote" }
            },
            jp: {
                nav: { dash: "ダッシュボード", tech: "技術", dev: "開発者", price: "価格", supp: "サポート" },
                page: { title: "設定", sub: "プロフィールとセキュリティを管理します。" },
                back: "戻る",
                tabs: { prof: "👤 プロフィール", sec: "🛡️ セキュリティ", notif: "🔔 通知" },
                prof: { title: "公開プロフィール", desc: "この情報は証明書に表示されます。", name: "氏名", email: "メール", save: "保存", chg: "アバター変更",
                        badge_free: "無料プラン", badge_pro: "プロメンバー", badge_ent: "エンタープライズ 👑",
                        btn_free: "無料を見る", btn_pro: "プロを見る", btn_ent: "企業を見る" },
                sec: { title: "セキュリティ", desc: "アカウントを安全に保ちます。", pass: "パスワード変更", up: "更新", sess: "アクティブなセッション", rev: "取り消し" },
                notif: { title: "通知", desc: "アラートを管理します。", news: "ディープフェイクニュース", acc: "アカウント活動", quota: "残量警告" }
            },
            cn: {
                nav: { dash: "仪表板", tech: "技术", dev: "开发者", price: "价格", supp: "支持" },
                page: { title: "设置", sub: "管理您的个人资料和安全。" },
                back: "返回",
                tabs: { prof: "👤 个人资料", sec: "🛡️ 安全", notif: "🔔 通知" },
                prof: { title: "公开资料", desc: "此信息将显示在您的证书上。", name: "全名", email: "电子邮件", save: "保存更改", chg: "更改头像",
                        badge_free: "免费计划", badge_pro: "专业会员", badge_ent: "企业版 👑",
                        btn_free: "查看免费", btn_pro: "查看专业", btn_ent: "查看企业" },
                sec: { title: "安全", desc: "保护您的帐户安全。", pass: "更改密码", up: "更新密码", sess: "活动会话", rev: "撤销" },
                notif: { title: "通知", desc: "管理警报。", news: "深度伪造新闻", acc: "帐户活动", quota: "配额警告" }
            },
            ru: {
                nav: { dash: "Дашборд", tech: "Технологии", dev: "Разработчики", price: "Цены", supp: "Поддержка" },
                page: { title: "Настройки", sub: "Управление профилем и безопасностью." },
                back: "Назад",
                tabs: { prof: "👤 Профиль", sec: "🛡️ Безопасность", notif: "🔔 Уведомления" },
                prof: { title: "Публичный профиль", desc: "Информация для сертификатов.", name: "Полное имя", email: "Email", save: "Сохранить", chg: "Сменить аватар",
                        badge_free: "БЕСПЛАТНО", badge_pro: "PRO АККАУНТ", badge_ent: "ENTERPRISE 👑",
                        btn_free: "См. Free", btn_pro: "См. Pro", btn_ent: "См. Ent" },
                sec: { title: "Безопасность", desc: "Защита аккаунта.", pass: "Сменить пароль", up: "Обновить", sess: "Активные сессии", rev: "Отозвать" },
                notif: { title: "Уведомления", desc: "Управление оповещениями.", news: "Новости Deepfake", acc: "Активность", quota: "Квота" }
            }
        };

        function applyLang(lang) {
            let t = translations[lang];
            if (!t) t = translations['en'];

            // Navbar
            if(document.getElementById('nav-dash')) document.getElementById('nav-dash').innerText = t.nav.dash;
            if(document.getElementById('nav-tech')) document.getElementById('nav-tech').innerText = t.nav.tech;
            if(document.getElementById('nav-dev')) document.getElementById('nav-dev').innerText = t.nav.dev;
            if(document.getElementById('nav-price')) document.getElementById('nav-price').innerText = t.nav.price;
            if(document.getElementById('nav-support')) document.getElementById('nav-support').innerText = t.nav.supp;

            // Page
            document.getElementById('page-title').innerText = t.page.title;
            document.getElementById('page-sub').innerText = t.page.sub;
            document.getElementById('btn-back-text').innerText = t.back;

            // Tabs
            document.getElementById('nav-prof').innerText = t.tabs.prof;
            document.getElementById('nav-sec').innerText = t.tabs.sec;
            document.getElementById('nav-notif').innerText = t.tabs.notif;

            // Profile
            document.getElementById('t-prof-title').innerText = t.prof.title;
            document.getElementById('t-prof-desc').innerText = t.prof.desc;
            document.getElementById('lbl-name').innerText = t.prof.name;
            document.getElementById('lbl-email').innerText = t.prof.email;
            document.getElementById('btn-save-prof').innerText = t.prof.save;
            document.getElementById('btn-change-av').innerText = t.prof.chg;

            // Sec
            document.getElementById('t-sec-title').innerText = t.sec.title;
            document.getElementById('t-sec-desc').innerText = t.sec.desc;
            document.getElementById('t-change-pass').innerText = t.sec.pass;
            document.getElementById('btn-upd-pass').innerText = t.sec.up;
            document.getElementById('t-sessions').innerText = t.sec.sess;
            if(document.getElementById('btn-revoke')) document.getElementById('btn-revoke').innerText = t.sec.rev;

            // Notif
            document.getElementById('t-notif-title').innerText = t.notif.title;
            document.getElementById('t-notif-desc').innerText = t.notif.desc;
            document.getElementById('t-notif-news').innerText = t.notif.news;
            document.getElementById('t-notif-acc').innerText = t.notif.acc;
            document.getElementById('t-notif-quota').innerText = t.notif.quota;

            // Theme Buttons (Need IDs) - Or select by class / order
            const btns = document.querySelectorAll('.theme-btn');
            if(btns.length >= 3) {
                btns[0].innerText = t.prof.btn_free;
                btns[1].innerText = t.prof.btn_pro;
                btns[2].innerText = t.prof.btn_ent;
            }
            
            // Status Badge (Dynamic based on current theme)
            const card = document.getElementById('profile-card');
            const badge = document.getElementById('status-badge');
            if (card.classList.contains('free')) badge.innerText = t.prof.badge_free;
            if (card.classList.contains('pro')) badge.innerText = t.prof.badge_pro;
            if (card.classList.contains('enterprise')) badge.innerText = t.prof.badge_ent;
        }

        function setProfileTheme(theme) {
            const card = document.getElementById('profile-card');
            const badge = document.getElementById('status-badge');
            
            // Removing old classes
            card.classList.remove('free', 'pro', 'enterprise');
            badge.classList.remove('badge-free', 'badge-pro', 'badge-enterprise');
            
            // Adding new
            card.classList.add(theme);
            badge.classList.add('badge-' + theme);

            // Update Text
            if(theme === 'free') { badge.innerText = "FREE PLAN"; badge.style.color = "#64748b"; }
            if(theme === 'pro') { badge.innerText = translations[localStorage.getItem('privasi_lang') || 'en'].prof.badge_pro; badge.style.color = "white"; }
            if(theme === 'enterprise') { badge.innerText = translations[localStorage.getItem('privasi_lang') || 'en'].prof.badge_ent; badge.style.color = "#fbbf24"; }

            // Highlight Buttons
            document.querySelectorAll('.theme-btn').forEach(btn => btn.classList.remove('active'));
            if(event) event.target.classList.add('active');
        }

        // Add missing translations to the dictionary above first (in next step) or merge them here?
        // Actually, let's just make sure applyLang is called.
        
        window.addEventListener('languageChanged', (e) => applyLang(e.detail.lang));
        document.addEventListener('DOMContentLoaded', () => {
             const savedLang = localStorage.getItem('privasi_lang') || 'en';
             applyLang(savedLang);
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\privasi-app\resources\views/pages/settings.blade.php ENDPATH**/ ?>