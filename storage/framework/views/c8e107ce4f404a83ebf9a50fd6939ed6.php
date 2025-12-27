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
    <h1 class="title">Pengaturan Privasi</h1>
    <p class="subtitle">Pilih bagian yang ingin kamu atur.</p>

    <section class="grid" id="cards">
      <div class="card" data-title="Data Pribadi">
        <h3>Data Pribadi</h3>
        <p>Kelola nama, email, dan informasi akun.</p>
      </div>

      <div class="card" data-title="Keamanan">
        <h3>Keamanan</h3>
        <p>Password, verifikasi 2 langkah, dan perangkat login.</p>
      </div>

      <div class="card" data-title="Izin Aplikasi">
        <h3>Izin Aplikasi</h3>
        <p>Kamera, lokasi, mikrofon, dan notifikasi.</p>
      </div>

      <div class="card" data-title="Riwayat Aktivitas">
        <h3>Riwayat Aktivitas</h3>
        <p>Lihat dan hapus aktivitas yang tersimpan.</p>
      </div>

      <div class="card" data-title="Cookie & Pelacakan">
        <h3>Cookie & Pelacakan</h3>
        <p>Atur pelacakan, cookie, dan preferensi iklan.</p>
      </div>

      <div class="card" data-title="Kontrol Berbagi">
        <h3>Kontrol Berbagi</h3>
        <p>Atur siapa yang bisa melihat konten kamu.</p>
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
  </script>

</body>
</html>
<?php /**PATH C:\laragon\www\privasi-app\resources\views/welcome.blade.php ENDPATH**/ ?>