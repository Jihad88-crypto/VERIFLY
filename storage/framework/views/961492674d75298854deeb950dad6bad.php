<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer API | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f97316; /* Orange for API */
            --secondary: #9945FF; 
            --accent: #00C2FF; 
            --text-main: #1e1e2f;
            --text-muted: #64748b;
            --code-bg: #1e293b;
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

        /* Ambient BG (Orange Tone) */
        .ambient-bg {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -3;
            background: #ffffff; overflow: hidden;
        }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.6;
            animation: floatOrb 25s infinite ease-in-out alternate;
        }
        .orb-1 { width: 900px; height: 900px; background: #f97316; /* Orange */ top: -300px; right: -200px; opacity: 0.5; }
        .orb-2 { width: 700px; height: 700px; background: #f59e0b; /* Amber */ bottom: -200px; left: -200px; animation-duration: 35s; opacity: 0.4; }
        .orb-3 { 
            width: 500px; height: 500px; background: #ea580c; /* Red Orange */ 
            top: 40%; left: 60%; transform: translate(-50%, -50%);
            animation-name: pulseOrb; animation-duration: 20s; opacity: 0.3;
            position: absolute; border-radius: 50%; filter: blur(80px);
        }
        .noise-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -2; opacity: 0.03; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }
        @keyframes floatOrb {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(40px, 40px) rotate(5deg); }
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

        /* GRID LAYOUT */
        .api-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
        @media(max-width: 900px) { .api-grid { grid-template-columns: 1fr; } }

        /* CARDS */
        .api-card {
            background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px);
            border: 1px solid #e2e8f0; border-radius: 20px;
            padding: 30px; box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
            height: 100%;
        }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .card-title { font-weight: 700; font-size: 1.25rem; font-family: 'Space Grotesk'; display: flex; align-items: center; gap: 10px; }
        
        /* API KEY BOX */
        .key-container {
            background: #fff; border: 1px solid #cbd5e1; border-radius: 12px; padding: 16px; 
            display: flex; align-items: center; justify-content: space-between; gap: 10px;
            margin-bottom: 20px;
        }
        .api-key-text {
            font-family: 'Space Mono', monospace; color: #334155; font-size: 1.1rem; letter-spacing: 1px;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap; filter: blur(4px); transition: filter 0.2s; cursor: pointer;
        }
        .api-key-text:hover, .api-key-text.visible { filter: blur(0); }
        
        .action-btn {
            padding: 8px 16px; border-radius: 8px; border: none; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px;
        }
        .btn-copy { background: #f1f5f9; color: #475569; }
        .btn-copy:hover { bgackground: #e2e8f0; color: #1e293b; }
        .btn-regen { background: #fee2e2; color: #b91c1c; }
        .btn-regen:hover { background: #fecaca; }

        /* STATS */
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 20px; }
        .stat-box { background: #fff; padding: 20px; border-radius: 16px; border: 1px solid #f1f5f9; text-align: center; }
        .stat-val { font-size: 2rem; font-weight: 800; font-family: 'Space Grotesk'; color: var(--primary); margin-bottom: 4px; }
        .stat-label { color: var(--text-muted); font-size: 0.9rem; font-weight: 600; }

        /* CODE SNIPPETS */
        .tabs { display: flex; gap: 8px; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; }
        .tab { padding: 6px 16px; border-radius: 99px; font-size: 0.9rem; font-weight: 600; cursor: pointer; color: #64748b; transition: all 0.2s; }
        .tab.active { background: var(--primary); color: white; }
        
        .code-block {
            background: var(--code-bg); color: #e2e8f0; padding: 20px; border-radius: 12px;
            font-family: 'Space Mono', monospace; font-size: 0.9rem; line-height: 1.5; overflow-x: auto;
            position: relative;
        }
        .lang-badge { position: absolute; top: 10px; right: 10px; color: #64748b; font-size: 0.8rem; font-weight: bold; }

        /* DOCS LIST */
        .endpoint-list { list-style: none; }
        .endpoint-item { 
            padding: 16px; border-bottom: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 4px; 
            transition: background 0.2s; border-radius: 8px;
        }
        .endpoint-item:hover { background: #fff; }
        .method { 
            display: inline-block; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; width: fit-content;
        }
        .method.post { background: #dcfce7; color: #15803d; }
        .method.get { background: #dbeafe; color: #1e40af; }
        .url { font-family: 'Space Mono', monospace; font-size: 0.95rem; color: #334155; font-weight: 600; }
        .desc { font-size: 0.9rem; color: #64748b; }

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
                Back
            </a>
            <h1>Developer API <span style="font-size: 0.5em; background: var(--primary); color: white; padding: 4px 12px; border-radius: 99px; vertical-align: middle;">PRO v2.0</span></h1>
            <p id="page-subtitle">Integrate our verification engine directly into your platform.</p>
        </div>

        <div class="api-grid">
            
            <!-- LEFT COLUMN -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                
                <!-- API KEY CARD -->
                <div class="api-card">
                    <div class="card-header">
                        <div class="card-title" id="card-key-title">🔑 API Credentials</div>
                        <div style="font-size: 0.9rem; color: var(--text-muted);">Environment: <strong style="color: #10b981;">Production</strong></div>
                    </div>
                    
                    <p style="margin-bottom: 12px; font-size: 0.95rem; color: #64748b;" id="key-label">Your Secret Key (Click to reveal)</p>
                    <div class="key-container">
                        <div class="api-key-text" id="apiKey" onclick="this.classList.toggle('visible')">sk_live_83749281_xK92mQzLp82</div>
                        <button class="action-btn btn-copy" onclick="copyKey()" id="btn-copy">
                           📋 Copy
                        </button>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button class="action-btn btn-regen" id="btn-regen">🔄 Regenerate Key</button>
                    </div>
                </div>

                <!-- USAGE CHARTS -->
                <div class="api-card">
                    <div class="card-header">
                        <div class="card-title" id="card-stats-title">📊 Usage Statistics</div>
                        <select style="padding: 6px; border-radius: 8px; border: 1px solid #cbd5e1;">
                            <option>Last 30 Days</option>
                            <option>Last 7 Days</option>
                        </select>
                    </div>
                    
                    <!-- Mock Chart Bars -->
                    <div style="display: flex; align-items: flex-end; gap: 8px; height: 150px; margin-bottom: 24px; padding-bottom: 8px; border-bottom: 1px solid #cbd5e1;">
                        <div style="flex:1; background: #cbd5e1; height: 40%; border-radius: 4px;"></div>
                        <div style="flex:1; background: #cbd5e1; height: 60%; border-radius: 4px;"></div>
                        <div style="flex:1; background: #cbd5e1; height: 30%; border-radius: 4px;"></div>
                        <div style="flex:1; background: #cbd5e1; height: 80%; border-radius: 4px;"></div>
                        <div style="flex:1; background: #cbd5e1; height: 50%; border-radius: 4px;"></div>
                        <div style="flex:1; background: var(--primary); height: 90%; border-radius: 4px; position: relative;"></div>
                        <div style="flex:1; background: #cbd5e1; height: 45%; border-radius: 4px;"></div>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="stat-val">1,204</div>
                            <div class="stat-label" id="stat-req">Total Requests</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-val" style="color: #10b981;">99.8%</div>
                            <div class="stat-label" id="stat-succ">Success Rate</div>
                        </div>
                    </div>
                </div>

            </div>

             <!-- RIGHT COLUMN -->
             <div style="display: flex; flex-direction: column; gap: 24px;">
                
                <!-- GUIDE -->
                <div class="api-card">
                    <div class="card-header">
                        <div class="card-title" id="card-guide-title">🚀 Quick Start</div>
                    </div>
                    
                    <div class="tabs">
                        <div class="tab active" onclick="switchTab('curl')">cURL</div>
                        <div class="tab" onclick="switchTab('python')">Python</div>
                        <div class="tab" onclick="switchTab('js')">Node.js</div>
                    </div>

                    <!-- cURL Snippet -->
                    <div class="code-block" id="code-curl">
<span class="lang-badge">BASH</span>
curl -X POST https://api.privasi.id/v1/verify/video \
  -H "Authorization: Bearer sk_live_..." \
  -F "file=@video.mp4"
                    </div>

                    <!-- Python Snippet -->
                    <div class="code-block" id="code-python" style="display: none;">
<span class="lang-badge">PYTHON</span>
import requests

url = "https://api.privasi.id/v1/verify/video"
headers = {"Authorization": "Bearer sk_live_..."}
files = {"file": open("video.mp4", "rb")}

response = requests.post(url, headers=headers, files=files)
print(response.json())
                    </div>

                    <!-- JS Snippet -->
                    <div class="code-block" id="code-js" style="display: none;">
<span class="lang-badge">NODE</span>
const axios = require('axios');
const formData = new FormData();
formData.append('file', fileStream);

await axios.post('https://api.privasi.id/v1/verify/video', formData, {
  headers: { 'Authorization': 'Bearer sk_live_...' }
});
                    </div>

                </div>

                <!-- REFERENCE -->
                 <div class="api-card">
                    <div class="card-header">
                        <div class="card-title" id="card-docs-title">📚 Available Endpoints</div>
                    </div>
                    <ul class="endpoint-list">
                        <li class="endpoint-item">
                            <div><span class="method post">POST</span> <span class="url">/v1/verify/video</span></div>
                            <div class="desc" id="desc-video">Deepfake & artifact analysis</div>
                        </li>
                        <li class="endpoint-item">
                            <div><span class="method post">POST</span> <span class="url">/v1/verify/image</span></div>
                            <div class="desc" id="desc-image">Pixel manipulation check</div>
                        </li>
                        <li class="endpoint-item">
                            <div><span class="method post">POST</span> <span class="url">/v1/verify/audio</span></div>
                            <div class="desc" id="desc-audio">Voice clone detection</div>
                        </li>
                        <li class="endpoint-item">
                            <div><span class="method get">GET</span> <span class="url">/v1/credits</span></div>
                            <div class="desc" id="desc-credits">Check remaining quota</div>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </div>

    <script>
        const translations = {
            en: {
                nav: { dash: "Dashboard", tech: "Technology", dev: "Developers", price: "Pricing", supp: "Support" },
                page: { title: "Developer API", sub: "Integrate our verification engine directly into your platform." },
                back: "Back",
                card: { key: "API Credentials", stats: "Usage Statistics", guide: "Quick Start", docs: "Available Endpoints" },
                key: { label: "Your Secret Key (Click to reveal)", copy: "Copy", regen: "Regenerate Key" },
                stats: { req: "Total Requests", succ: "Success Rate" },
                docs: { vid: "Deepfake & artifact analysis", img: "Pixel manipulation check", aud: "Voice clone detection", cred: "Check remaining quota" }
            },
            id: {
                nav: { dash: "Dasbor", tech: "Teknologi", dev: "Developers", price: "Harga", supp: "Bantuan" },
                page: { title: "API Pengembang", sub: "Integrasikan mesin verifikasi kami langsung ke platform Anda." },
                back: "Kembali",
                card: { key: "Kredensial API", stats: "Statistik Penggunaan", guide: "Mulai Cepat", docs: "Endpoint Tersedia" },
                key: { label: "Kunci Rahasia (Klik untuk melihat)", copy: "Salin", regen: "Kunci Baru" },
                stats: { req: "Total Permintaan", succ: "Tingkat Sukses" },
                docs: { vid: "Analisis deepfake & artefak", img: "Cek manipulasi piksel", aud: "Deteksi kloning suara", cred: "Cek kuota tersisa" }
            },
            es: {
                nav: { dash: "Tablero", tech: "Tecnología", dev: "Desarrolladores", price: "Precios", supp: "Ayuda" },
                page: { title: "API Desarrollador", sub: "Integre nuestro motor de verificación en su plataforma." },
                back: "Volver",
                card: { key: "Credenciales API", stats: "Estadísticas", guide: "Inicio Rápido", docs: "Puntos Finales" },
                key: { label: "Su Clave Secreta (Clic para revelar)", copy: "Copiar", regen: "Regenerar Clave" },
                stats: { req: "Solicitudes Totales", succ: "Tasa de Éxito" },
                docs: { vid: "Análisis de deepfake", img: "Manipulación de píxeles", aud: "Clonación de voz", cred: "Ver cuota" }
            },
            fr: {
                nav: { dash: "Tableau", tech: "Technologie", dev: "Développeurs", price: "Tarifs", supp: "Support" },
                page: { title: "API Développeur", sub: "Intégrez notre moteur de vérification à votre plateforme." },
                back: "Retour",
                card: { key: "Identifiants API", stats: "Statistiques", guide: "Démarrage Rapide", docs: "Points de terminaison" },
                key: { label: "Clé Secrète (Cliquer pour révéler)", copy: "Copier", regen: "Régénérer" },
                stats: { req: "Total des requêtes", succ: "Taux de réussite" },
                docs: { vid: "Analyse deepfake", img: "Manipulation de pixels", aud: "Clonage vocal", cred: "Vérifier quota" }
            },
            de: {
                nav: { dash: "Dashboard", tech: "Technologie", dev: "Entwickler", price: "Preise", supp: "Support" },
                page: { title: "Entwickler-API", sub: "Integrieren Sie unsere Verifizierungs-Engine." },
                back: "Zurück",
                card: { key: "API-Anmeldedaten", stats: "Nutzungsstatistik", guide: "Schnellstart", docs: "Verfügbare Endpunkte" },
                key: { label: "Geheimer Schlüssel (Klicken zum Anzeigen)", copy: "Kopieren", regen: "Neu generieren" },
                stats: { req: "Gesamtanfragen", succ: "Erfolgsrate" },
                docs: { vid: "Deepfake-Analyse", img: "Pixelmanipulation", aud: "Stimmklonen", cred: "Quote prüfen" }
            },
            jp: {
                nav: { dash: "ダッシュボード", tech: "技術", dev: "開発者", price: "価格", supp: "サポート" },
                page: { title: "開発者API", sub: "検証エンジンをプラットフォームに直接統合します。" },
                back: "戻る",
                card: { key: "API認証情報", stats: "利用統計", guide: "クイックスタート", docs: "利用可能なエンドポイント" },
                key: { label: "秘密鍵（クリックして表示）", copy: "コピー", regen: "再生成" },
                stats: { req: "総リクエスト数", succ: "成功率" },
                docs: { vid: "ディープフェイク分析", img: "ピクセル操作チェック", aud: "音声クローン検出", cred: "残量チェック" }
            },
            cn: {
                nav: { dash: "仪表板", tech: "技术", dev: "开发者", price: "价格", supp: "支持" },
                page: { title: "开发者API", sub: "将我们的验证引擎集成到您的平台中。" },
                back: "返回",
                card: { key: "API凭证", stats: "使用统计", guide: "快速开始", docs: "可用端点" },
                key: { label: "您的密钥（点击显示）", copy: "复制", regen: "重新生成" },
                stats: { req: "总请求数", succ: "成功率" },
                docs: { vid: "深度伪造分析", img: "像素操作检查", aud: "语音克隆检测", cred: "检查配额" }
            },
            ru: {
                nav: { dash: "Дашборд", tech: "Технологии", dev: "Разработчики", price: "Цены", supp: "Поддержка" },
                page: { title: "API Разработчика", sub: "Интегрируйте наш движок верификации." },
                back: "Назад",
                card: { key: "Учетные данные API", stats: "Статистика", guide: "Быстрый старт", docs: "Доступные эндпоинты" },
                key: { label: "Ваш секретный ключ", copy: "Копировать", regen: "Перегенерировать" },
                stats: { req: "Всего запросов", succ: "Успешность" },
                docs: { vid: "Анализ дипфейков", img: "Проверка пикселей", aud: "Клонирование голоса", cred: "Проверка квоты" }
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
            document.querySelector('.page-header h1').childNodes[0].nodeValue = t.page.title + " ";
            document.getElementById('page-subtitle').innerText = t.page.sub;
            document.getElementById('btn-back').childNodes[2].nodeValue = " " + t.back;

            // Cards
            document.getElementById('card-key-title').innerText = "🔑 " + t.card.key;
            document.getElementById('card-stats-title').innerText = "📊 " + t.card.stats;
            document.getElementById('card-guide-title').innerText = "🚀 " + t.card.guide;
            document.getElementById('card-docs-title').innerText = "📚 " + t.card.docs;

            // Details
            document.getElementById('key-label').innerText = t.key.label;
            document.getElementById('btn-copy').childNodes[0].nodeValue = "\\n                           📋 " + t.key.copy + "\\n                        ";
            document.getElementById('btn-regen').innerText = "🔄 " + t.key.regen;
            
            document.getElementById('stat-req').innerText = t.stats.req;
            document.getElementById('stat-succ').innerText = t.stats.succ;

            document.getElementById('desc-video').innerText = t.docs.vid;
            document.getElementById('desc-image').innerText = t.docs.img;
            document.getElementById('desc-audio').innerText = t.docs.aud;
            document.getElementById('desc-credits').innerText = t.docs.cred;
        }

        window.addEventListener('languageChanged', (e) => applyLang(e.detail.lang));
        document.addEventListener('DOMContentLoaded', () => {
             const savedLang = localStorage.getItem('privasi_lang') || 'en';
             applyLang(savedLang);
        });

        function copyKey() {
            // Mock copy
            const key = "sk_live_83749281_xK92mQzLp82";
            navigator.clipboard.writeText(key);
            const btn = document.querySelector('.btn-copy');
            const original = btn.innerText; // Use innerText to keep icon if possible, but simplified here
            btn.innerHTML = "✅ Copied!";
            setTimeout(() => {
                // Must restore properly with lang
                const savedLang = localStorage.getItem('privasi_lang') || 'en';
                const t = translations[savedLang] || translations['en'];
                btn.innerHTML = "\\n                           📋 " + t.key.copy + "\\n                        ";
            }, 2000);
        }

        function switchTab(lang) {
            // Hide all
            document.getElementById('code-curl').style.display = 'none';
            document.getElementById('code-python').style.display = 'none';
            document.getElementById('code-js').style.display = 'none';
            
            // Deactivate Tabs
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));

            // Show selected
            document.getElementById('code-' + lang).style.display = 'block';
            
            // Activate Tab
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\privasi-app\resources\views/pages/api-dashboard.blade.php ENDPATH**/ ?>