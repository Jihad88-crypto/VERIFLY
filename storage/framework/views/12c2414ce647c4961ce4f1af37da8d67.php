<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Authenticity | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00C2FF; /* Blue for Audio */
            --secondary: #9945FF; 
            --accent: #14F195; 
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

        /* Ambient BG (Blue Tone for Audio) */
        .ambient-bg {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -3;
            background: #ffffff; overflow: hidden;
        }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.6;
            animation: floatOrb 25s infinite ease-in-out alternate;
        }
        .orb-1 { width: 900px; height: 900px; background: #0ea5e9; /* Sky */ top: -300px; right: -200px; opacity: 0.4; }
        .orb-2 { width: 700px; height: 700px; background: #3b82f6; /* Blue */ bottom: -200px; left: -200px; animation-duration: 35s; opacity: 0.4; }
        .orb-3 { 
            width: 500px; height: 500px; background: #6366f1; /* Indigo */ 
            top: 30%; right: 20%; transform: translate(50%, -50%);
            animation: pulseOrb 18s infinite ease-in-out; opacity: 0.3;
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

        .container { max-width: 800px; margin: 0 auto; padding: 60px 20px; text-align: center; }

        .page-header h1 { font-family: 'Space Grotesk', sans-serif; font-size: 2.5rem; margin-bottom: 12px; color: #1a1b2e; }
        .page-header p { color: var(--text-muted); font-size: 1.1rem; margin-bottom: 40px; }

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
            transform: translateY(-8px); box-shadow: 0 25px 50px -12px rgba(56, 189, 248, 0.15); 
        }
        .upload-zone::before {
            content: ''; position: absolute; inset: 0; 
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 24px 24px; opacity: 0.3; pointer-events: none;
        }
        
        .upload-icon {
            width: 80px; height: 80px; background: #e0f2fe; color: var(--primary);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin: 0 auto 20px;
        }
        .upload-title { font-weight: 700; font-size: 1.2rem; margin-bottom: 8px; }
        .upload-desc { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 24px; }
        .file-types { display: inline-block; padding: 6px 16px; background: #f1f5f9; border-radius: 99px; font-size: 0.8rem; font-weight: 600; color: #64748b; }

        /* SCANNING STATE (SOUNDWAVE) */
        .scanning-state {
            display: none; background: rgba(15, 23, 42, 0.95); border-radius: 24px; padding: 60px 40px;
            box-shadow: 0 30px 60px -15px rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1);
            position: relative; overflow: hidden; color: white;
            backdrop-filter: blur(20px); min-height: 400px;
        }
        
        .soundwave {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            height: 120px; margin-bottom: 40px;
        }
        .bar {
            width: 8px; background: var(--primary); border-radius: 99px;
            animation: wave 1s ease-in-out infinite;
        }
        @keyframes wave {
            0%, 100% { height: 20%; opacity: 0.3; }
            50% { height: 100%; opacity: 1; box-shadow: 0 0 20px var(--primary); }
        }

        .scan-status { color: #fff; text-transform: uppercase; letter-spacing: 2px; font-size: 1rem; position: relative; z-index: 2; margin-top: 20px; }
        .scan-detail { color: #94a3b8; font-family: 'Space Grotesk'; position: relative; z-index: 2; margin-top: 8px; }

        /* RESULT CARD */
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
            background: linear-gradient(135deg, #ef4444, #be123c);
        }
        .verdict-header.safe { background: linear-gradient(135deg, #10b981, #064e3b); }
        
        .verdict-title { font-size: 2.5rem; text-shadow: 0 2px 10px rgba(0,0,0,0.2); margin-bottom: 4px; font-weight: 800; font-family: 'Space Grotesk'; }
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
        .anom-list li::before { content: '🎙️'; }

        .btn-retry {
            display: block; width: 100%; padding: 16px; background: #f8fafc; color: #64748b; font-weight: 600;
            text-align: center; border: none; border-top: 1px solid #e2e8f0; cursor: pointer; transition: all 0.2s;
        }
        .btn-retry:hover { background: #f1f5f9; color: #1e1e2f; }

        input[type="file"] { display: none; }
    </style>
</head>
<body>

    <div class="ambient-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="noise-overlay"></div>
    </div>

    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container">
        
        <!-- Header -->
        <div class="page-header">
            <a href="<?php echo e(route('dashboard')); ?>" class="btn-back" id="btn-back">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span id="btn-back-text">Back</span>
            </a>
            <h1><span id="page-title-text">Audio Authenticity</span> <span style="font-size: 0.5em; background: var(--primary); color: #082f49; padding: 4px 12px; border-radius: 99px; vertical-align: middle;">PRO v2.0</span></h1>
            <p>Detect voice cloning, synthetic speech, and deepfake audio patterns.</p>
        </div>

        <!-- 1. Upload Zone -->
        <div class="upload-zone" id="uploadZone" onclick="triggerUpload()">
            <div class="upload-icon">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
            </div>
            <div class="upload-title">Click to Upload or Drag Audio</div>
            <div class="upload-desc">Maximum file size: 20MB</div>
            <div class="file-types">MP3, WAV, M4A</div>
            <input type="file" id="fileInput" accept="audio/*" onchange="handleFile(this.files)">
        </div>

        <!-- 2. Scanning Animation (Soundwave) -->
        <div class="scanning-state" id="scanState">
            <div class="soundwave" id="soundWave">
                <!-- JS will fill bars -->
            </div>
            <div class="scan-status" id="scanStatus">Initializing...</div>
            <div class="scan-detail" id="scanDetail">Decompressing audio stream...</div>
        </div>

        <!-- 3. Result Card -->
        <div class="result-card" id="resultCard">
            <!-- Verdict Header -->
            <div class="verdict-header" id="verdictHeader">
                <span class="verdict-icon" id="verdictIcon">🤖</span>
                <div class="verdict-title" id="verdictTitle">SYNTHETIC DETECTED</div>
                <div class="verdict-score" id="verdictScore">Confidence: 98.1%</div>
            </div>

            <div class="result-body">
                <div class="breakdown-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span id="bd-text">Audio Spectrum Analysis</span>
                </div>

                <div class="analysis-item">
                    <div class="ai-label"><span>Voice Cloning Signature</span> <span>Critical</span></div>
                    <div class="ai-bar-track"><div class="ai-bar" style="width: 0%; background: #ef4444;" data-width="96%"></div></div>
                </div>

                <div class="analysis-item">
                    <div class="ai-label"><span>Background Noise Floor</span> <span>Unnatural</span></div>
                    <div class="ai-bar-track"><div class="ai-bar" style="width: 0%; background: #f97316;" data-width="82%"></div></div>
                </div>

                <div class="analysis-item">
                    <div class="ai-label"><span>Spectral Continuity</span> <span>Irregular</span></div>
                    <div class="ai-bar-track"><div class="ai-bar" style="width: 0%; background: #ef4444;" data-width="78%"></div></div>
                </div>

                <div class="anomalies-box">
                    <div class="anom-title">🚩 Anomalies Found</div>
                    <ul class="anom-list">
                        <li>Frequencies above 16kHz are perfectly flattened (ElevenLabs signature).</li>
                        <li>Breathing patterns are mathematically consistent.</li>
                        <li>Zero background noise detected between words.</li>
                    </ul>
                </div>
            </div>

            <button class="btn-retry" onclick="resetPage()">Analyze Another Audio</button>
        </div>

    </div>

    <script>
        const uploadZone = document.getElementById('uploadZone');
        const fileInput = document.getElementById('fileInput');
        const scanState = document.getElementById('scanState');
        const resultCard = document.getElementById('resultCard');
        const soundWave = document.getElementById('soundWave');

        // Translations (Similar to others)
        const translations = {
            en: {
                nav: { dash: "Dashboard", tech: "Technology", dev: "Developers", price: "Pricing", supp: "Support" },
                header: { title: "Audio Authenticity", sub: "Detect voice cloning, synthetic speech, and deepfake audio patterns." },
                back: "Back",
                upload: { title: "Click to Upload or Drag Audio", desc: "Maximum file size: 20MB" },
                scan: { init: "Initializing...", d1: "Decompressing audio stream..." },
                res: { 
                    fake: "SYNTHETIC DETECTED", real: "AUTHENTIC AUDIO", 
                    bd: "Audio Spectrum Analysis", 
                    a1: "Voice Cloning Signature", a2: "Background Noise Floor", a3: "Spectral Continuity",
                    anom: "Anomalies Found", safe: "Natural Voice",
                    btn: "Analyze Another Audio"
                }
            },
            id: {
                nav: { dash: "Dasbor", tech: "Teknologi", dev: "Developers", price: "Harga", supp: "Bantuan" },
                header: { title: "Keaslian Audio", sub: "Deteksi kloning suara, ucapan sintetis, dan audio deepfake." },
                back: "Kembali",
                upload: { title: "Klik untuk Unggah atau Tarik Audio", desc: "Ukuran file maksimum: 20MB" },
                scan: { init: "Memulai...", d1: "Mengekstrak stream audio..." },
                res: { 
                    fake: "SUARA SINTETIS", real: "AUDIO ASLI", 
                    bd: "Analisis Spektrum Audio", 
                    a1: "Tanda Kloning Suara", a2: "Noise Floor Latar Belakang", a3: "Kontinuitas Spektral",
                    anom: "Anomali Ditemukan", safe: "Suara Alami",
                    btn: "Analisis Audio Lain"
                }
            },
            es: {
                nav: { dash: "Tablero", tech: "Tecnología", dev: "Desarrolladores", price: "Precios", supp: "Ayuda" },
                header: { title: "Autenticidad de Audio", sub: "Detecte clonación de voz y patrones sintéticos." },
                back: "Volver",
                upload: { title: "Haz clic para subir o arrastrar", desc: "Tamaño máx: 20MB" },
                scan: { init: "Iniciando...", d1: "Descomprimiendo..." },
                res: { 
                    fake: "VOZ SINTÉTICA", real: "AUDIO AUTÉNTICO", 
                    bd: "Análisis de Espectro", 
                    a1: "Firma de Clonación", a2: "Ruido de Fondo", a3: "Continuidad Espectral",
                    anom: "Anomalías Encontradas", safe: "Voz Natural",
                    btn: "Analizar Otro Audio"
                }
            },
            fr: {
                nav: { dash: "Tableau", tech: "Technologie", dev: "Développeurs", price: "Tarifs", supp: "Support" },
                header: { title: "Authenticité Audio", sub: "Détectez le clonage vocal et la synthèse vocale." },
                back: "Retour",
                upload: { title: "Cliquez ou glissez un fichier", desc: "Taille max : 20MB" },
                scan: { init: "Initialisation...", d1: "Décompression..." },
                res: { 
                    fake: "SYNTHÈSE DÉTECTÉE", real: "AUDIO AUTHENTIQUE", 
                    bd: "Analyse Spectrale", 
                    a1: "Signature de Clonage", a2: "Bruit de Fond", a3: "Continuité Spectrale",
                    anom: "Anomalies Trouvées", safe: "Voix Naturelle",
                    btn: "Analyser un autre audio"
                }
            },
            de: {
                nav: { dash: "Dashboard", tech: "Technologie", dev: "Entwickler", price: "Preise", supp: "Support" },
                header: { title: "Audio-Echtheit", sub: "Stimmklonen und synthetische Sprache erkennen." },
                back: "Zurück",
                upload: { title: "Klicken oder Datei ziehen", desc: "Max. Größe: 20MB" },
                scan: { init: "Initialisierung...", d1: "Dekomprimierung..." },
                res: { 
                    fake: "SYNTHETISCH ERKANNT", real: "AUTHENTISCH", 
                    bd: "Spektralanalyse", 
                    a1: "Stimmklon-Signatur", a2: "Hintergrundrauschen", a3: "Spektrale Kontinuität",
                    anom: "Anomalien gefunden", safe: "Natürliche Stimme",
                    btn: "Anderes Audio analysieren"
                }
            },
            jp: {
                nav: { dash: "ダッシュボード", tech: "技術", dev: "開発者", price: "価格", supp: "サポート" },
                header: { title: "音声の真正性", sub: "音声クローン、合成音声、ディープフェイク音声を検出します。" },
                back: "戻る",
                upload: { title: "クリックまたは音声をドラッグ", desc: "最大ファイルサイズ: 20MB" },
                scan: { init: "初期化中...", d1: "オーディオストリームを展開中..." },
                res: { 
                    fake: "合成音声を検出", real: "本物の音声", 
                    bd: "音声スペクトル分析", 
                    a1: "音声クローン署名", a2: "背景ノイズフロア", a3: "スペクトル連続性",
                    anom: "異常を発見", safe: "自然な音声",
                    btn: "別の音声を分析"
                }
            },
            cn: {
                nav: { dash: "仪表板", tech: "技术", dev: "开发者", price: "价格", supp: "支持" },
                header: { title: "音频真实性", sub: "检测语音克隆、合成语音和Deepfake音频模式。" },
                back: "返回",
                upload: { title: "点击上传或拖拽音频", desc: "最大文件大小: 20MB" },
                scan: { init: "初始化...", d1: "解压音频流..." },
                res: { 
                    fake: "检测到合成", real: "真实音频", 
                    bd: "音频频谱分析", 
                    a1: "语音克隆特征", a2: "背景噪声底", a3: "频谱连续性",
                    anom: "发现异常", safe: "自然语音",
                    btn: "分析另一个音频"
                }
            },
            ru: {
                nav: { dash: "Дашборд", tech: "Технологии", dev: "Разработчики", price: "Цены", supp: "Поддержка" },
                header: { title: "Аутентичность аудио", sub: "Обнаружение клонирования голоса и синтеза речи." },
                back: "Назад",
                upload: { title: "Нажмите или перетащите", desc: "Макс. размер: 20MB" },
                scan: { init: "Инициализация...", d1: "Распаковка..." },
                res: { 
                    fake: "СИНТЕЗ ОБНАРУЖЕН", real: "ПОДЛИННОЕ АУДИО", 
                    bd: "Спектральный анализ", 
                    a1: "Подпись клонирования", a2: "Фоновый шум", a3: "Спектральная непрерывность",
                    anom: "Найдены аномалии", safe: "Естественный голос",
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
            
            // Result
            const bdText = document.getElementById('bd-text');
            if(bdText) bdText.innerText = t.res.bd;
            
            const btnRetry = document.querySelector('.btn-retry');
            if(btnRetry) btnRetry.innerText = t.res.btn;
            
            const labels = document.querySelectorAll('.ai-label span:first-child');
            if(labels.length > 0) labels[0].innerText = t.res.a1;
            if(labels.length > 1) labels[1].innerText = t.res.a2;
            if(labels.length > 2) labels[2].innerText = t.res.a3;
        }

        window.addEventListener('languageChanged', (e) => applyLang(e.detail.lang));
        document.addEventListener('DOMContentLoaded', () => {
             const savedLang = localStorage.getItem('privasi_lang') || 'en';
             applyLang(savedLang);
        });

        // Initialize Soundwave
        for(let i=0; i<20; i++) {
            let b = document.createElement('div');
            b.className = 'bar';
            b.style.animationDelay = (i * 0.1) + 's';
            soundWave.appendChild(b);
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
                const isFake = Math.random() < 0.6; 
                startScanning(isFake);
            }
        }

        function startScanning(isFake) {
            uploadZone.style.display = 'none';
            scanState.style.display = 'block';

            const statusText = document.getElementById('scanStatus');
            const detailText = document.getElementById('scanDetail');

            const steps = [
                { t: 800, s: "Scanning Frequencies...", d: "Analyzing 20Hz - 20kHz range..." },
                { t: 1800, s: "Matching Voiceprint...", d: "Comparing against known AI models (ElevenLabs, Coqui)..." },
                { t: 2800, s: "Checking Noise Floor...", d: "Looking for digital silence artifacts..." },
                { t: 3500, s: "Finalizing...", d: "Generating spectrogram report..." }
            ];

            steps.forEach(step => {
                setTimeout(() => {
                    statusText.innerText = step.s;
                    detailText.innerText = step.d;
                }, step.t);
            });

            setTimeout(() => {
                showResult(isFake);
            }, 4200);
        }

        function showResult(isFake) {
            scanState.style.display = 'none';
            resultCard.style.display = 'block';
            
            const header = document.getElementById('verdictHeader');
            const icon = document.getElementById('verdictIcon');
            const title = document.getElementById('verdictTitle');
            const score = document.getElementById('verdictScore');

            const lang = localStorage.getItem('privasi_lang') || 'en';
            const t = translations[lang] || translations['en'];
            
            setTimeout(() => {
                document.querySelectorAll('.ai-bar').forEach(bar => {
                    bar.style.width = bar.getAttribute('data-width');
                });
            }, 100);

            if (isFake) {
                // Fake
                header.className = 'verdict-header'; 
                icon.innerText = '🤖';
                title.innerText = t.res.fake;
                score.innerText = 'Confidence: 98.1%';
                
                // Add analysis logic/text update here if needed
                document.querySelectorAll('.analysis-item').forEach(item => item.style.opacity = '1');
            } else {
                // Real
                header.className = 'verdict-header safe'; 
                icon.innerText = '✅';
                title.innerText = t.res.real;
                score.innerText = 'Confidence: 99.4%';
                
                document.querySelectorAll('.analysis-item').forEach(item => item.style.opacity = '0.5');
                document.querySelector('.anomalies-box').innerHTML = `
                    <div class="anom-title" style="color: #059669;">✔️ ${t.res.safe}</div>
                    <ul class="anom-list">
                        <li style="color: #047857;">Noise Floor: Reliable analog background noise detected.</li>
                        <li style="color: #047857;">Breathing: Natural irregularity in breath patterns.</li>
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
<?php /**PATH C:\laragon\www\privasi-app\resources\views/pages/verify-audio.blade.php ENDPATH**/ ?>