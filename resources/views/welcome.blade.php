<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Privasi</title>

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body{
      font-family: Arial, sans-serif;
      min-height: 100vh;
      padding: 24px;

      /* background bergerak */
      background: linear-gradient(120deg, #ffecd2, #fcb69f, #ffecd2);
      background-size: 400% 400%;
      animation: bgMove 12s ease infinite;
    }

    @keyframes bgMove{
      0% { background-position: 0% 50%; }
      50%{ background-position: 100% 50%; }
      100%{ background-position: 0% 50%; }
    }

    /* search bar atas */
    .search-container{
      display: flex;
      justify-content: center;
      margin-top: 16px;
    }

    .search-box{
      width: min(720px, 100%);
      display: flex;
      align-items: center;
      gap: 10px;

      padding: 14px 18px;
      border-radius: 999px;

      background: rgba(255,255,255,0.75);
      backdrop-filter: blur(8px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }

    .search-box input{
      border: none;
      outline: none;
      background: transparent;
      width: 100%;
      font-size: 16px;
    }

    /* konten */
    .container{
      max-width: 980px;
      margin: 30px auto 0;
    }

    .title{
      margin: 18px 0 14px;
      font-size: 28px;
    }

    .subtitle{
      margin-bottom: 18px;
      opacity: 0.8;
    }

    .grid{
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 16px;
    }

    .card{
      background: rgba(255,255,255,0.75);
      border-radius: 16px;
      padding: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.10);
      cursor: pointer;
      transition: transform 0.15s ease;
    }

    .card:hover{
      transform: translateY(-3px);
    }

    .card h3{
      margin-bottom: 8px;
    }

    .card p{
      opacity: 0.85;
      line-height: 1.4;
    }

    @media (max-width: 900px){
      .grid{ grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <!-- SEARCH -->
  <header class="search-container">
    <div class="search-box">
      <span>🔎</span>
      <input id="search" type="text" placeholder="Cari pengaturan privasi..." />
    </div>
  </header>

  <!-- MENU -->
  <main class="container">
    <h1 class="title" id="page-title">Pengaturan Privasi</h1>
    <p class="subtitle" id="page-subtitle">Pilih bagian yang ingin kamu atur.</p>

    <section class="grid" id="cards">
      <div class="card" data-title="Data Pribadi">
        <h3 id="card-1-title">Data Pribadi</h3>
        <p id="card-1-desc">Kelola nama, email, dan informasi akun.</p>
      </div>

      <div class="card" data-title="Keamanan">
        <h3 id="card-2-title">Keamanan</h3>
        <p id="card-2-desc">Password, verifikasi 2 langkah, dan perangkat login.</p>
      </div>

      <div class="card" data-title="Izin Aplikasi">
        <h3 id="card-3-title">Izin Aplikasi</h3>
        <p id="card-3-desc">Kamera, lokasi, mikrofon, dan notifikasi.</p>
      </div>

      <div class="card" data-title="Riwayat Aktivitas">
        <h3 id="card-4-title">Riwayat Aktivitas</h3>
        <p id="card-4-desc">Lihat dan hapus aktivitas yang tersimpan.</p>
      </div>

      <div class="card" data-title="Cookie & Pelacakan">
        <h3 id="card-5-title">Cookie & Pelacakan</h3>
        <p id="card-5-desc">Atur pelacakan, cookie, dan preferensi iklan.</p>
      </div>

      <div class="card" data-title="Kontrol Berbagi">
        <h3 id="card-6-title">Kontrol Berbagi</h3>
        <p id="card-6-desc">Atur siapa yang bisa melihat konten kamu.</p>
      </div>
    </section>
  </main>

  <script>
    // fitur sederhana: filter card saat mengetik
    const input = document.getElementById('search');
    const cards = Array.from(document.querySelectorAll('.card'));

    input.addEventListener('input', () => {
      const q = input.value.toLowerCase().trim();
      cards.forEach(card => {
        const t = (card.dataset.title || '').toLowerCase();
        const show = t.includes(q);
        card.style.display = show ? '' : 'none';
      });
    });

    // Translation Logic
    const translations = {
        id: {
            placeholder: "Cari pengaturan privasi...",
            title: "Pengaturan Privasi",
            subtitle: "Pilih bagian yang ingin kamu atur.",
            c1: { t: "Data Pribadi", d: "Kelola nama, email, dan informasi akun." },
            c2: { t: "Keamanan", d: "Password, verifikasi 2 langkah, dan perangkat login." },
            c3: { t: "Izin Aplikasi", d: "Kamera, lokasi, mikrofon, dan notifikasi." },
            c4: { t: "Riwayat Aktivitas", d: "Lihat dan hapus aktivitas yang tersimpan." },
            c5: { t: "Cookie & Pelacakan", d: "Atur pelacakan, cookie, dan preferensi iklan." },
            c6: { t: "Kontrol Berbagi", d: "Atur siapa yang bisa melihat konten kamu." }
        },
        en: {
            placeholder: "Search privacy settings...",
            title: "Privacy Settings",
            subtitle: "Select the section you want to manage.",
            c1: { t: "Personal Data", d: "Manage name, email, and account info." },
            c2: { t: "Security", d: "Password, 2FA, and login devices." },
            c3: { t: "App Permissions", d: "Camera, location, microphone, and notifications." },
            c4: { t: "Activity History", d: "View and delete saved activity." },
            c5: { t: "Cookies & Tracking", d: "Manage tracking, cookies, and ad prefs." },
            c6: { t: "Sharing Controls", d: "Control who can see your content." }
        },
        jp: {
            placeholder: "プライバシー設定を検索...",
            title: "プライバシー設定",
            subtitle: "管理したいセクションを選択してください。",
            c1: { t: "個人データ", d: "名前、メール、アカウント情報を管理します。" },
            c2: { t: "セキュリティ", d: "パスワード、2要素認証、ログインデバイス。" },
            c3: { t: "アプリの権限", d: "カメラ、位置情報、マイク、通知。" },
            c4: { t: "アクティビティ履歴", d: "保存されたアクティビティを表示および削除します。" },
            c5: { t: "Cookieと追跡", d: "追跡、Cookie、広告設定を管理します。" },
            c6: { t: "共有コントロール", d: "コンテンツを閲覧できるユーザーを制御します。" }
        },
        es: { placeholder: "Buscar configuración...", title: "Configuración de Privacidad", subtitle: "Selecciona una sección.", c1: {t: "Datos Personales", d: "Gestionar cuenta."}, c2: {t: "Seguridad", d: "Contraseña y 2FA."}, c3: {t: "Permisos", d: "Cámara y ubicación."}, c4: {t: "Historial", d: "Ver actividad."}, c5: {t: "Cookies", d: "Gestionar rastreo."}, c6: {t: "Compartir", d: "Controlar visibilidad."} },
        fr: { placeholder: "Rechercher paramètres...", title: "Paramètres de Confidentialité", subtitle: "Sélectionnez une section.", c1: {t: "Données Perso", d: "Gérer compte."}, c2: {t: "Sécurité", d: "Mot de passe et 2FA."}, c3: {t: "Permissions", d: "Caméra et localisation."}, c4: {t: "Historique", d: "Voir activité."}, c5: {t: "Cookies", d: "Gérer suivi."}, c6: {t: "Partage", d: "Contrôler visibilité."} },
        de: { placeholder: "Einstellungen suchen...", title: "Datenschutzeinstellungen", subtitle: "Wählen Sie einen Bereich.", c1: {t: "Persönliche Daten", d: "Konto verwalten."}, c2: {t: "Sicherheit", d: "Passwort & 2FA."}, c3: {t: "Berechtigungen", d: "Kamera & Standort."}, c4: {t: "Verlauf", d: "Aktivität ansehen."}, c5: {t: "Cookies", d: "Tracking verwalten."}, c6: {t: "Teilen", d: "Sichtbarkeit steuern."} },
        cn: { placeholder: "搜索隐私设置...", title: "隐私设置", subtitle: "选择您要管理的部分。", c1: {t: "个人数据", d: "管理帐户信息。"}, c2: {t: "安全", d: "密码和双重验证。"}, c3: {t: "应用权限", d: "相机和位置。"}, c4: {t: "活动历史", d: "查看和删除活动。"}, c5: {t: "Cookies与跟踪", d: "管理跟踪设置。"}, c6: {t: "共享控制", d: "控制谁可以看到内容。"}, },
        ru: { placeholder: "Поиск настроек...", title: "Настройки конфиденциальности", subtitle: "Выберите раздел.", c1: {t: "Личные данные", d: "Управление аккаунтом."}, c2: {t: "Безопасность", d: "Пароль и 2FA."}, c3: {t: "Разрешения", d: "Камера и местоположение."}, c4: {t: "История", d: "Просмотр активности."}, c5: {t: "Cookies", d: "Управление трекингом."}, c6: {t: "Общий доступ", d: "Управление видимостью."} }
    };

    document.addEventListener('DOMContentLoaded', () => {
        const savedLang = localStorage.getItem('privasi_lang') || 'id'; // Default ID for this page originally
        const t = translations[savedLang] || translations['id'];

        if(document.getElementById('search')) document.getElementById('search').placeholder = t.placeholder;
        if(document.getElementById('page-title')) document.getElementById('page-title').innerText = t.title;
        if(document.getElementById('page-subtitle')) document.getElementById('page-subtitle').innerText = t.subtitle;

        if(document.getElementById('card-1-title')) document.getElementById('card-1-title').innerText = t.c1.t;
        if(document.getElementById('card-1-desc')) document.getElementById('card-1-desc').innerText = t.c1.d;

        if(document.getElementById('card-2-title')) document.getElementById('card-2-title').innerText = t.c2.t;
        if(document.getElementById('card-2-desc')) document.getElementById('card-2-desc').innerText = t.c2.d;
        
        if(document.getElementById('card-3-title')) document.getElementById('card-3-title').innerText = t.c3.t;
        if(document.getElementById('card-3-desc')) document.getElementById('card-3-desc').innerText = t.c3.d;

        if(document.getElementById('card-4-title')) document.getElementById('card-4-title').innerText = t.c4.t;
        if(document.getElementById('card-4-desc')) document.getElementById('card-4-desc').innerText = t.c4.d;

        if(document.getElementById('card-5-title')) document.getElementById('card-5-title').innerText = t.c5.t;
        if(document.getElementById('card-5-desc')) document.getElementById('card-5-desc').innerText = t.c5.d;

        if(document.getElementById('card-6-title')) document.getElementById('card-6-title').innerText = t.c6.t;
        if(document.getElementById('card-6-desc')) document.getElementById('card-6-desc').innerText = t.c6.d;
    });
  </script>

</body>
</html>
