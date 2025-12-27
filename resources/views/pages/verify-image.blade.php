<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Analysis | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #14F195; /* Secondary brand color for Image */
            --secondary: #9945FF; 
            --accent: #00C2FF; 
            --danger: #ef4444;
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

        /* Ambient BG (Green Tone for Image) */
        .ambient-bg {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -3;
            background: #ffffff; overflow: hidden;
        }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.6;
            animation: floatOrb 25s infinite ease-in-out alternate;
        }
        .orb-1 { width: 900px; height: 900px; background: #10b981; /* Emerald */ top: -300px; right: -200px; opacity: 0.4; }
        .orb-2 { width: 700px; height: 700px; background: #22c55e; /* Green */ bottom: -200px; left: -200px; animation-duration: 35s; opacity: 0.4; }
        .orb-3 { 
            width: 600px; height: 600px; background: #14b8a6; /* Teal */ 
            top: 50%; left: 20%; transform: translate(-50%, -50%);
            animation: floatOrb 28s infinite ease-in-out; opacity: 0.3;
            position: absolute; border-radius: 50%; filter: blur(90px);
        }
        .noise-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -2; opacity: 0.03; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }
        @keyframes floatOrb {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(40px, 40px) rotate(5deg); }
        }

        .container { max-width: 800px; margin: 0 auto; padding: 60px 20px; text-align: center; }

        .page-header h1 { font-family: 'Space Grotesk', sans-serif; font-size: 2.5rem; margin-bottom: 12px; color: #1a1b2e; }
        .page-header p { color: var(--text-muted); font-size: 1.1rem; margin-bottom: 40px; }

        /* UPLOAD ZONE */
        .upload-zone {
            background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px);
            border: 2px dashed #cbd5e1; border-radius: 24px;
            padding: 80px 40px; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer;
            position: relative; overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .upload-zone:hover { 
            border-color: var(--primary); background: rgba(255, 255, 255, 0.9); 
            transform: translateY(-8px); box-shadow: 0 25px 50px -12px rgba(20, 241, 149, 0.15); 
        }
        .upload-zone::before {
            content: ''; position: absolute; inset: 0; 
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 24px 24px; opacity: 0.3; pointer-events: none;
        }
        
        .upload-icon {
            width: 80px; height: 80px; background: #ecfdf5; color: var(--primary);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin: 0 auto 20px;
        }
        .upload-title { font-weight: 700; font-size: 1.2rem; margin-bottom: 8px; }
        .upload-desc { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 24px; }
        .file-types { display: inline-block; padding: 6px 16px; background: #f1f5f9; border-radius: 99px; font-size: 0.8rem; font-weight: 600; color: #64748b; }

        /* SCANNING STATE (PIXEL GRID EFFECT) */
        .scanning-state {
            display: none; background: rgba(15, 23, 42, 0.95); border-radius: 24px; padding: 60px 40px;
            box-shadow: 0 30px 60px -15px rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1);
            position: relative; overflow: hidden; color: white;
            backdrop-filter: blur(20px); min-height: 400px;
        }
        
        .pixel-grid {
            display: grid; grid-template-columns: repeat(10, 1fr); gap: 4px;
            width: 200px; height: 200px; margin: 0 auto 32px;
            position: relative; z-index: 2;
        }
        .pixel {
            background: rgba(20, 241, 149, 0.1); border-radius: 4px; transition: all 0.2s;
        }
        .pixel.active {
            background: var(--primary); box-shadow: 0 0 15px var(--primary); transform: scale(1.1);
        }

        .scan-status { color: #fff; text-transform: uppercase; letter-spacing: 2px; font-size: 1rem; position: relative; z-index: 2; margin-top: 20px; }
        .scan-detail { color: #94a3b8; font-family: 'Space Grotesk'; position: relative; z-index: 2; margin-top: 8px; }

        /* GLASS RESULT CARD */
        .result-card {
            display: none; border-radius: 30px; overflow: hidden;
            box-shadow: 0 50px 100px -20px rgba(0,0,0,0.15); 
            border: 1px solid rgba(255,255,255,0.5);
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(24px);
            text-align: left; animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes slideUp { from { transform: translateY(40px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .verdict-header {
            padding: 50px; text-align: center; color: white; position: relative; overflow: hidden;
            background: linear-gradient(135deg, #ef4444, #f97316); /* Orange/Red for Image Alert */
        }
        .verdict-header::before {
            content: ''; position: absolute; inset: 0; 
            background: radial-gradient(circle at top right, rgba(255,255,255,0.3), transparent 60%);
        }
        .verdict-header.safe { background: linear-gradient(135deg, #10b981, #064e3b); }
        
        .verdict-title { font-size: 3rem; text-shadow: 0 2px 10px rgba(0,0,0,0.2); margin-bottom: 4px; }
        .verdict-score { 
            display: inline-block; padding: 6px 16px; background: rgba(0,0,0,0.2); 
            border-radius: 99px; font-weight: 600; font-size: 0.9rem; backdrop-filter: blur(4px); 
        }

        .result-body { padding: 40px; }
        .breakdown-title { font-weight: 700; font-size: 1.1rem; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; }
        
        .analysis-item { margin-bottom: 20px; }
        .ai-label { display: flex; justify-content: space-between; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem; }
        .ai-bar-track { height: 8px; background: #f1f5f9; border-radius: 99px; overflow: hidden; }
        .ai-bar { height: 100%; border-radius: 99px; width: 0; transition: width 1s ease 0.5s; }
        
        .anomalies-box {
            background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px; margin-top: 32px;
        }
        .anom-title { color: #b91c1c; font-weight: 700; margin-bottom: 12px; font-size: 0.95rem; display: flex; align-items: center; gap: 8px; }
        .anom-list li { list-style: none; margin-bottom: 8px; color: #7f1d1d; font-size: 0.9rem; display: flex; gap: 8px; }
        .anom-list li::before { content: '🛑'; }

        .btn-retry {
            display: block; width: 100%; padding: 16px; background: #f8fafc; color: #64748b; font-weight: 600;
            text-align: center; border: none; border-top: 1px solid #e2e8f0; cursor: pointer; transition: all 0.2s;
        }
        .btn-retry:hover { background: #f1f5f9; color: #1e1e2f; }

        input[type="file"] { display: none; }
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
    </style>
</head>
<body>

    <div class="ambient-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="noise-overlay"></div>
    </div>

    @include('components.navbar')

    <div class="container">
        
        <!-- Header -->
        <div class="page-header">
            <a href="{{ route('dashboard') }}" class="btn-back" id="btn-back">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span id="btn-back-text">Back</span>
            </a>
            <h1><span id="page-title-text">Image Analysis</span> <span style="font-size: 0.5em; background: var(--primary); color: #064e3b; padding: 4px 12px; border-radius: 99px; vertical-align: middle;">PRO v2.0</span></h1>
            <p>Scan photos for pixel editing, generative AI fill, and EXIF manipulation.</p>
        </div>

        <!-- 1. Upload Zone -->
        <div class="upload-zone" id="uploadZone" onclick="triggerUpload()">
            <div class="upload-icon">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div class="upload-title">Click to Upload or Drag Image</div>
            <div class="upload-desc">Maximum file size: 20MB</div>
            <div class="file-types">JPG, PNG, WEBP</div>
            <input type="file" id="fileInput" accept="image/png,image/jpeg,image/webp" onchange="handleFile(this.files)">
        </div>

        <!-- 2. Scanning Animation (Pixel Grid) -->
        <div class="scanning-state" id="scanState">
            <div class="pixel-grid" id="pixelGrid">
                <!-- JS will fill 100 pixels here -->
            </div>
            <div class="scan-status" id="scanStatus">Initializing...</div>
            <div class="scan-detail" id="scanDetail">Decompressing bitmap data...</div>
        </div>

        <!-- 3. Result Card -->
        <div class="result-card" id="resultCard">
            <!-- Verdict Header -->
            <div class="verdict-header" id="verdictHeader">
                <span class="verdict-icon" id="verdictIcon">⚠️</span>
                <div class="verdict-title" id="verdictTitle">EDITING DETECTED</div>
                <div class="verdict-score" id="verdictScore">Confidence: 94.2%</div>
            </div>

            <div class="result-body">
                <div class="breakdown-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span id="bd-text">Forensic Breakdown</span>
                </div>

                <div class="analysis-item">
                    <div class="ai-label"><span>Error Level Analysis (ELA)</span> <span>High Variance</span></div>
                    <div class="ai-bar-track"><div class="ai-bar" style="width: 0%; background: #ef4444;" data-width="89%"></div></div>
                </div>

                <div class="analysis-item">
                    <div class="ai-label"><span>AI Generative Noise</span> <span>Possible</span></div>
                    <div class="ai-bar-track"><div class="ai-bar" style="width: 0%; background: #f97316;" data-width="65%"></div></div>
                </div>

                <div class="analysis-item">
                    <div class="ai-label"><span>EXIF Integrity</span> <span>Broken</span></div>
                    <div class="ai-bar-track"><div class="ai-bar" style="width: 0%; background: #ef4444;" data-width="100%"></div></div>
                </div>

                <div class="anomalies-box">
                    <div class="anom-title">🚩 Anomalies Found</div>
                    <ul class="anom-list">
                        <li>Pixel Grid: Inconsistent compression artifacts found.</li>
                        <li>Metadata: Software tag shows "Adobe Photoshop 2024".</li>
                        <li>Region: Generative fill suspected in background layer.</li>
                    </ul>
                </div>
            </div>

            <button class="btn-retry" onclick="resetPage()">Analyze Another Image</button>
        </div>

    </div>

    <script>
        const uploadZone = document.getElementById('uploadZone');
        const fileInput = document.getElementById('fileInput');
        const scanState = document.getElementById('scanState');
        const resultCard = document.getElementById('resultCard');
        const pixelGrid = document.getElementById('pixelGrid');

        // Translations
        const translations = {
            en: {
                nav: { dash: "Dashboard", tech: "Technology", dev: "Developers", price: "Pricing", supp: "Support" },
                header: { title: "Image Analysis", sub: "Scan photos for pixel editing, generative AI fill, and EXIF manipulation." },
                back: "Back",
                upload: { title: "Click to Upload or Drag Image", desc: "Maximum file size: 20MB" },
                scan: { init: "Initializing...", d1: "Decompressing bitmap data..." },
                res: { 
                    fake: "EDITING DETECTED", real: "AUTHENTIC PHOTO", 
                    bd: "Forensic Breakdown", 
                    a1: "Error Level Analysis (ELA)", a2: "AI Generative Noise", a3: "EXIF Integrity",
                    anom: "Anomalies Found", safe: "Metric Validated",
                    btn: "Analyze Another Image"
                }
            },
            id: {
                nav: { dash: "Dasbor", tech: "Teknologi", dev: "Developers", price: "Harga", supp: "Bantuan" },
                header: { title: "Analisis Foto", sub: "Pindai foto untuk edit piksel, AI, dan manipulasi EXIF." },
                back: "Kembali",
                upload: { title: "Klik untuk Unggah atau Tarik Foto", desc: "Ukuran file maksimum: 20MB" },
                scan: { init: "Memulai...", d1: "Mengekstrak data bitmap..." },
                res: { 
                    fake: "EDITAN TERDETEKSI", real: "FOTO ASLI", 
                    bd: "Rincian Forensik", 
                    a1: "Analisis Error Level (ELA)", a2: "Noise Generatif AI", a3: "Integritas EXIF",
                    anom: "Anomali Ditemukan", safe: "Metrik Tervalidasi",
                    btn: "Analisis Foto Lain"
                }
            },
            es: {
                nav: { dash: "Tablero", tech: "Tecnología", dev: "Desarrolladores", price: "Precios", supp: "Ayuda" },
                header: { title: "Análisis de Imagen", sub: "Escanea fotos en busca de edición de píxeles y AI." },
                back: "Volver",
                upload: { title: "Clic para subir o arrastrar", desc: "Tamaño máx: 20MB" },
                scan: { init: "Iniciando...", d1: "Descomprimiendo..." },
                res: { 
                    fake: "EDICIÓN DETECTADA", real: "FOTO AUTÉNTICA", 
                    bd: "Desglose Forense", 
                    a1: "Análisis Nivel de Error (ELA)", a2: "Ruido Generativo AI", a3: "Integridad EXIF",
                    anom: "Anomalías Encontradas", safe: "Métrica Validada",
                    btn: "Analizar Otra Imagen"
                }
            },
            fr: {
                nav: { dash: "Tableau", tech: "Technologie", dev: "Développeurs", price: "Tarifs", supp: "Support" },
                header: { title: "Analyse d'image", sub: "Scannez les photos pour l'édition de pixels et AI." },
                back: "Retour",
                upload: { title: "Cliquez ou glissez une photo", desc: "Taille max : 20MB" },
                scan: { init: "Initialisation...", d1: "Décompression..." },
                res: { 
                    fake: "ÉDITION DÉTECTÉE", real: "PHOTO AUTHENTIQUE", 
                    bd: "Détails Forensiques", 
                    a1: "Analyse Niveau Erreur (ELA)", a2: "Bruit Génératif IA", a3: "Intégrité EXIF",
                    anom: "Anomalies Trouvées", safe: "Métrique Validée",
                    btn: "Analyser une autre image"
                }
            },
            de: {
                nav: { dash: "Dashboard", tech: "Technologie", dev: "Entwickler", price: "Preise", supp: "Support" },
                header: { title: "Bildanalyse", sub: "Scannen Sie Fotos auf Pixelbearbeitung und KI." },
                back: "Zurück",
                upload: { title: "Klicken oder Bild ziehen", desc: "Max. Größe: 20MB" },
                scan: { init: "Initialisierung...", d1: "Dekomprimierung..." },
                res: { 
                    fake: "BEARBEITUNG ERKANNT", real: "AUTHENTISCH", 
                    bd: "Forensische Details", 
                    a1: "Fehlerstufenanalyse (ELA)", a2: "KI-Rauschen", a3: "EXIF-Integrität",
                    anom: "Anomalien gefunden", safe: "Metrik validiert",
                    btn: "Anderes Bild analysieren"
                }
            },
            jp: {
                nav: { dash: "ダッシュボード", tech: "技術", dev: "開発者", price: "価格", supp: "サポート" },
                header: { title: "画像分析", sub: "ピクセル編集やAI操作をスキャンします。" },
                back: "戻る",
                upload: { title: "クリックまたは画像をドラッグ", desc: "最大ファイルサイズ: 20MB" },
                scan: { init: "初期化中...", d1: "ビットマップデータを解凍中..." },
                res: { 
                    fake: "編集を検出", real: "本物の写真", 
                    bd: "フォレンジック内訳", 
                    a1: "エラーレベル分析 (ELA)", a2: "AI生成ノイズ", a3: "EXIF整合性",
                    anom: "異常を発見", safe: "検証済み",
                    btn: "別の画像を分析"
                }
            },
            cn: {
                nav: { dash: "仪表板", tech: "技术", dev: "开发者", price: "价格", supp: "支持" },
                header: { title: "图像分析", sub: "扫描照片以检测像素编辑和AI填充。" },
                back: "返回",
                upload: { title: "点击上传或拖拽图片", desc: "最大文件大小: 20MB" },
                scan: { init: "初始化...", d1: "解压位图数据..." },
                res: { 
                    fake: "检测到编辑", real: "真实照片", 
                    bd: "取证详情", 
                    a1: "错误级别分析 (ELA)", a2: "AI生成噪声", a3: "EXIF完整性",
                    anom: "发现异常", safe: "验证通过",
                    btn: "分析另一张图片"
                }
            },
            ru: {
                nav: { dash: "Дашборд", tech: "Технологии", dev: "Разработчики", price: "Цены", supp: "Поддержка" },
                header: { title: "Анализ изображений", sub: "Сканирование на наличие редактирования и ИИ." },
                back: "Назад",
                upload: { title: "Нажмите или перетащите", desc: "Макс. размер: 20MB" },
                scan: { init: "Инициализация...", d1: "Распаковка..." },
                res: { 
                    fake: "РЕДАКТИРОВАНИЕ ОБНАРУЖЕНО", real: "ПОДЛИННОЕ ФОТО", 
                    bd: "Детали анализа", 
                    a1: "Analisis (ELA)", a2: "Генеративный шум ИИ", a3: "Целостность EXIF",
                    anom: "Найдены аномалии", safe: "Проверено",
                    btn: "Анализировать другое"
                }
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
            const titleText = document.getElementById('page-title-text');
            if(titleText) titleText.innerText = t.header.title;
            
            const sub = document.querySelector('.page-header p');
            if(sub) sub.innerText = t.header.sub;
            
            const backText = document.getElementById('btn-back-text');
            if(backText) backText.innerText = t.back;
            
            document.querySelector('.upload-title').innerText = t.upload.title;
            document.querySelector('.upload-desc').innerText = t.upload.desc;
            
            document.getElementById('scanStatus').innerText = t.scan.init;
            document.getElementById('scanDetail').innerText = t.scan.d1;
            
            // Result Static Text
            const bdText = document.getElementById('bd-text');
            if(bdText) bdText.innerText = t.res.bd;
            
            const btnRetry = document.querySelector('.btn-retry');
            if(btnRetry) btnRetry.innerText = t.res.btn;
            
            const labels = document.querySelectorAll('.ai-label span:first-child');
            if(labels.length > 0) labels[0].innerText = t.res.a1;
            if(labels.length > 1) labels[1].innerText = t.res.a2;
            if(labels.length > 2) labels[2].innerText = t.res.a3;
        }

        // Listen for Navbar Language Change
        window.addEventListener('languageChanged', (e) => {
            applyLang(e.detail.lang);
        });

        document.addEventListener('DOMContentLoaded', () => {
            const savedLang = localStorage.getItem('privasi_lang') || 'en';
            applyLang(savedLang);
        });

        // Initialize Pixel Grid
        for(let i=0; i<100; i++) {
            let p = document.createElement('div');
            p.className = 'pixel';
            pixelGrid.appendChild(p);
        }

        // Drag & Drop
        uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('dragover'); });
        uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            handleFile(e.dataTransfer.files);
        });

        function triggerUpload() { fileInput.click(); }

        function handleFile(files) {
            if (files.length > 0) {
                const isFake = Math.random() < 0.7; 
                startScanning(isFake);
            }
        }

        function startScanning(isFake) {
            uploadZone.style.display = 'none';
            scanState.style.display = 'block';

            const statusText = document.getElementById('scanStatus');
            const detailText = document.getElementById('scanDetail');
            const pixels = document.querySelectorAll('.pixel');

            const steps = [
                { t: 800, s: "Scanning Pixels...", d: "Analyzing 12MP bitmap array..." },
                { t: 1600, s: "Checking ELA...", d: "Calculating compression error levels..." },
                { t: 2400, s: "Verifying EXIF...", d: "Validating camera sensor signature..." },
                { t: 3200, s: "Finalizing...", d: "Generating forensic report..." }
            ];

            // Animate Pixels Randomly
            const pixelInterval = setInterval(() => {
                pixels.forEach(p => p.classList.remove('active'));
                for(let i=0; i<15; i++) {
                    const r = Math.floor(Math.random() * 100);
                    pixels[r].classList.add('active');
                }
            }, 100);

            // Timeline
            steps.forEach(step => {
                setTimeout(() => {
                    statusText.innerText = step.s;
                    detailText.innerText = step.d;
                }, step.t);
            });

            setTimeout(() => {
                clearInterval(pixelInterval);
                showResult(isFake);
            }, 4000);
        }

        function showResult(isFake) {
            scanState.style.display = 'none';
            resultCard.style.display = 'block';
            
            const header = document.getElementById('verdictHeader');
            const icon = document.getElementById('verdictIcon');
            const title = document.getElementById('verdictTitle');
            const score = document.getElementById('verdictScore');
            
            // Get current lang for dynamic verdict text
            const lang = localStorage.getItem('privasi_lang') || 'en';
            const t = translations[lang] || translations['en'];

            setTimeout(() => {
                document.querySelectorAll('.ai-bar').forEach(bar => {
                    bar.style.width = bar.getAttribute('data-width');
                });
            }, 100);

            if (isFake) {
                // Fake State
                header.className = 'verdict-header'; // Danger
                icon.innerText = '⚠️';
                title.innerText = t.res.fake;
                score.innerText = 'Confidence: 94.2%';
            } else {
                // Real State
                header.className = 'verdict-header safe'; // Green
                icon.innerText = '✅';
                title.innerText = t.res.real;
                score.innerText = 'Confidence: 99.1%';
                
                document.querySelectorAll('.analysis-item').forEach(item => item.style.opacity = '0.5');
                document.querySelector('.anomalies-box').innerHTML = `
                    <div class="anom-title" style="color: #059669;">✔️ ${t.res.safe}</div>
                    <ul class="anom-list">
                        <li style="color: #047857;">Metadata: Original Sony A7III tags found.</li>
                        <li style="color: #047857;">ELA: Uniform compression across all regions.</li>
                    </ul>
                `;
                document.querySelector('.anomalies-box').style.background = '#ecfdf5';
                document.querySelector('.anomalies-box').style.borderColor = '#d1fae5';
            }
        }

        function resetPage() {
            resultCard.style.display = 'none';
            uploadZone.style.display = 'block';
            document.getElementById('fileInput').value = '';
            document.querySelectorAll('.ai-bar').forEach(bar => bar.style.width = '0%');
        }
    </script>
</body>
</html>
