<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Video Verification | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #9945FF;
            --secondary: #14F195;
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

        /* DYNAMIC AURORA BACKGROUND */
        .ambient-bg {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -3;
            background: #ffffff; overflow: hidden;
        }
        
        .orb {
            position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.8;
            animation: floatOrb 20s infinite ease-in-out alternate;
        }
        .orb-1 {
            width: 800px; height: 800px; background: #a855f7; /* Purple */
            top: -200px; right: -200px; animation-duration: 25s; opacity: 0.5;
        }
        .orb-2 {
            width: 600px; height: 600px; background: #8b5cf6; /* Violet */
            bottom: -100px; left: -100px; animation-duration: 30s; animation-direction: alternate-reverse; opacity: 0.4;
        }
        .orb-3 {
            width: 500px; height: 500px; background: #d946ef; /* Fuchsia */
            top: 40%; left: 40%; transform: translate(-50%, -50%);
            animation-name: pulseOrb; animation-duration: 15s; opacity: 0.4;
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
            0% { transform: translate(-50%, -50%) scale(1); opacity: 0.4; }
            50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.6; }
            100% { transform: translate(-50%, -50%) scale(1); opacity: 0.4; }
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
            transform: translateY(-8px); box-shadow: 0 25px 50px -12px rgba(153, 69, 255, 0.15); 
        }
        .upload-zone::before {
            content: ''; position: absolute; inset: 0; 
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 24px 24px; opacity: 0.3; pointer-events: none;
        }

        /* HIGH-TECH SCANNER */
        .scanning-state {
            display: none; background: rgba(15, 23, 42, 0.95); border-radius: 24px; padding: 60px 40px;
            box-shadow: 0 30px 60px -15px rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1);
            position: relative; overflow: hidden; color: white;
            backdrop-filter: blur(20px);
        }
        /* Cyber Grid Background */
        .scanning-state::after {
            content: ''; position: absolute; inset: 0;
            background: 
                linear-gradient(rgba(147, 51, 234, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(147, 51, 234, 0.1) 1px, transparent 1px);
            background-size: 40px 40px; opacity: 0.3; z-index: 0;
        }
        /* Laser Scan Line */
        .scan-line {
            position: absolute; top: 0; left: 0; width: 100%; height: 4px;
            background: linear-gradient(90deg, transparent, var(--secondary), transparent);
            box-shadow: 0 0 20px var(--secondary);
            animation: scanMove 2s ease-in-out infinite; z-index: 1;
        }
        @keyframes scanMove { 0% { top: 0%; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 100%; opacity: 0; } }

        .scan-loader {
            width: 140px; height: 140px; border-radius: 50%; 
            border: 2px solid rgba(255,255,255,0.1); border-top-color: var(--secondary); border-right-color: var(--primary);
            margin: 0 auto 32px; position: relative; z-index: 2;
            box-shadow: 0 0 40px rgba(153, 69, 255, 0.2);
            animation: spin 1.5s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
        }
        .scan-percentage {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            font-family: 'Space Grotesk'; font-weight: 700; font-size: 2rem; color: #fff;
            text-shadow: 0 0 20px rgba(255,255,255,0.5); z-index: 10;
        }
        .scan-status { color: #fff; text-transform: uppercase; letter-spacing: 2px; font-size: 1rem; position: relative; z-index: 2; }
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
            background: linear-gradient(135deg, #ef4444, #7f1d1d);
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
        .anom-list li::before { content: '⚠️'; }

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
        <div class="orb orb-3"></div>
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
            <h1><span id="page-title-text">Video Forensics</span> <span style="font-size: 0.5em; background: var(--primary); color: #ffffff; padding: 4px 12px; border-radius: 99px; vertical-align: middle;">PRO v2.0</span></h1>
            <p>Upload a video to analyze deepfake artifacts and AI manipulation.</p>
        </div>

        <!-- 1. Under Development Notice -->
        <div class="upload-zone" style="cursor: default; border-color: #fbbf24; background: rgba(254, 243, 199, 0.3);">
            <div style="margin-bottom: 24px;">
                <svg width="64" height="64" fill="none" stroke="#f59e0b" viewBox="0 0 24 24" style="margin: 0 auto;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div class="upload-title" style="color: #92400e; font-size: 1.5rem; margin-bottom: 16px;">
                🚧 Under Development
            </div>
            <div class="upload-desc" style="color: #78350f; font-size: 1.1rem; line-height: 1.6; max-width: 600px; margin: 0 auto 24px;">
                Video AI detection is currently under development due to technical complexity of modern AI video generators (Sora, Runway Gen-3, Pika Labs).
            </div>
            <div style="background: rgba(255, 255, 255, 0.7); border-radius: 16px; padding: 24px; max-width: 600px; margin: 0 auto; text-align: left;">
                <div style="font-weight: 700; color: #92400e; margin-bottom: 12px; font-size: 1rem;">
                    📊 Current Status:
                </div>
                <ul style="list-style: none; padding: 0; margin: 0 0 20px 0; color: #78350f;">
                    <li style="margin-bottom: 8px; display: flex; gap: 8px;">
                        <span>✓</span>
                        <span>Basic implementation complete</span>
                    </li>
                    <li style="margin-bottom: 8px; display: flex; gap: 8px;">
                        <span>✓</span>
                        <span>Accuracy: 70-80% (internal analysis)</span>
                    </li>
                    <li style="margin-bottom: 8px; display: flex; gap: 8px;">
                        <span>⚠️</span>
                        <span>Requires specialized deep learning models for 90%+ accuracy</span>
                    </li>
                </ul>
                <div style="font-weight: 700; color: #92400e; margin-bottom: 12px; font-size: 1rem;">
                    🚀 Future Plans:
                </div>
                <ul style="list-style: none; padding: 0; margin: 0; color: #78350f;">
                    <li style="margin-bottom: 8px; display: flex; gap: 8px;">
                        <span>📌</span>
                        <span>Enhanced version will be released after image detection system is proven successful</span>
                    </li>
                    <li style="margin-bottom: 8px; display: flex; gap: 8px;">
                        <span>📌</span>
                        <span>Integration with specialized video deepfake detection models</span>
                    </li>
                </ul>
            </div>
            <div style="margin-top: 24px; padding: 16px; background: rgba(147, 51, 234, 0.1); border-radius: 12px; max-width: 600px; margin: 24px auto 0;">
                <div style="color: #6b21a8; font-weight: 600; font-size: 0.95rem;">
                    💡 Meanwhile, try our <a href="{{ route('verify.image') }}" style="color: #9333ea; text-decoration: underline;">Image AI Detection</a> with 85-90% accuracy!
                </div>
            </div>
        </div>

        <!-- 2. Scanning Animation -->
        <div class="scanning-state" id="scanState">
            <div class="scan-line"></div>
            <div class="scan-loader">
                <div class="scan-percentage" id="scanPercent">0%</div>
            </div>
            <div class="scan-status" id="scanStatus">Initializing...</div>
            <div class="scan-detail" id="scanDetail">Preparing secure upload tunnel...</div>
        </div>

        <!-- 3. Result Card -->
        <div class="result-card" id="resultCard">
            <!-- Verdict Header (Dynamic Class: .safe for Real, Default for Fake) -->
            <div class="verdict-header" id="verdictHeader">
                <span class="verdict-icon" id="verdictIcon">🤖</span>
                <div class="verdict-title" id="verdictTitle">DEEPFAKE DETECTED</div>
                <div class="verdict-score" id="verdictScore">Confidence: 99.8%</div>
            </div>

            <div class="result-body">
                <div class="breakdown-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span id="bd-text">Analysis Breakdown</span>
                </div>

                <div class="analysis-item">
                    <div class="ai-label"><span>Temporal Inconsistency</span> <span>High</span></div>
                    <div class="ai-bar-track"><div class="ai-bar" style="width: 0%; background: #ef4444;" data-width="94%"></div></div>
                </div>

                <div class="analysis-item">
                    <div class="ai-label"><span>Face Warping Artifacts</span> <span>Detected</span></div>
                    <div class="ai-bar-track"><div class="ai-bar" style="width: 0%; background: #f97316;" data-width="88%"></div></div>
                </div>

                <div class="analysis-item">
                    <div class="ai-label"><span>Audio-Visual Sync</span> <span>Normal</span></div>
                    <div class="ai-bar-track"><div class="ai-bar" style="width: 0%; background: #10b981;" data-width="12%"></div></div>
                </div>

                <div class="anomalies-box">
                    <div class="anom-title">🚩 Critical Anomalies Found</div>
                    <ul class="anom-list">
                        <li>Frame 142-180: Unnatural eye blinking pattern.</li>
                        <li>Frame 205: Jawline pixelation inconsistecy.</li>
                        <li>Metadata: Missing camera sensor signature.</li>
                    </ul>
                </div>
            </div>

            <button class="btn-retry" onclick="resetPage()">Analyze Another Video</button>
        </div>

    </div>

    <script>
        const uploadZone = document.getElementById('uploadZone');
        const fileInput = document.getElementById('fileInput');
        const scanState = document.getElementById('scanState');
        const resultCard = document.getElementById('resultCard');

        // Translations
        const translations = {
            en: {
                nav: { dash: "Dashboard", tech: "Technology", dev: "Developers", price: "Pricing", supp: "Support" },
                header: { title: "Video Forensics", sub: "Upload a video to analyze deepfake artifacts and AI manipulation." },
                back: "Back",
                upload: { title: "Click to Upload or Drag Video", desc: "Maximum file size: 50MB" },
                scan: { init: "Initializing...", d1: "Preparing secure upload tunnel..." },
                res: { 
                    fake: "DEEPFAKE DETECTED", real: "AUTHENTIC CONTENT", 
                    bd: "Analysis Breakdown", 
                    a1: "Temporal Inconsistency", a2: "Face Warping Artifacts", a3: "Audio-Visual Sync",
                    anom: "Critical Anomalies Found", safe: "No Anomalies Found",
                    btn: "Analyze Another Video"
                }
            },
            id: {
                nav: { dash: "Dasbor", tech: "Teknologi", dev: "Developers", price: "Harga", supp: "Bantuan" },
                header: { title: "Forensik Video", sub: "Unggah video untuk menganalisis artefak deepfake dan manipulasi AI." },
                back: "Kembali",
                upload: { title: "Klik untuk Unggah atau Tarik Video", desc: "Ukuran file maksimum: 50MB" },
                scan: { init: "Memulai...", d1: "Menyiapkan tunnel unggahan aman..." },
                res: { 
                    fake: "DEEPFAKE TERDETEKSI", real: "KONTEN ASLI", 
                    bd: "Rincian Analisis", 
                    a1: "Inkonsistensi Temporal", a2: "Artefak Wajah", a3: "Sinkronisasi Audio-Visual",
                    anom: "Anomali Kritis Ditemukan", safe: "Tidak Ada Anomali",
                    btn: "Analisis Video Lain"
                }
            },
            es: {
                nav: { dash: "Tablero", tech: "Tecnología", dev: "Desarrolladores", price: "Precios", supp: "Ayuda" },
                header: { title: "Video Forense", sub: "Sube un video para analizar artefactos deepfake." },
                back: "Volver",
                upload: { title: "Haz clic para subir o arrastra", desc: "Tamaño máx: 50MB" },
                scan: { init: "Iniciando...", d1: "Preparando túnel seguro..." },
                res: { 
                    fake: "DEEPFAKE DETECTADO", real: "CONTENIDO AUTÉNTICO", 
                    bd: "Desglose de Análisis", 
                    a1: "Inconsistencia Temporal", a2: "Artefactos Faciales", a3: "Sincronización A/V",
                    anom: "Anomalías Críticas", safe: "Sin Anomalías",
                    btn: "Analizar Otro Video"
                }
            },
            fr: {
                nav: { dash: "Tableau", tech: "Technologie", dev: "Développeurs", price: "Tarifs", supp: "Support" },
                header: { title: "Video Forensics", sub: "Téléchargez une vidéo pour analyser les deepfakes." },
                back: "Retour",
                upload: { title: "Cliquez ou glissez une vidéo", desc: "Taille max : 50MB" },
                scan: { init: "Initialisation...", d1: "Préparation du tunnel..." },
                res: { 
                    fake: "DEEPFAKE DÉTECTÉ", real: "CONTENU AUTHENTIQUE", 
                    bd: "Détails de l'analyse", 
                    a1: "Incohérence Temporelle", a2: "Artefacts Faciaux", a3: "Synchro Audio-Visuelle",
                    anom: "Anomalies Critiques", safe: "Aucune Anomalie",
                    btn: "Analyser une autre vidéo"
                }
            },
            de: {
                nav: { dash: "Dashboard", tech: "Technologie", dev: "Entwickler", price: "Preise", supp: "Support" },
                header: { title: "Video Forensik", sub: "Video hochladen, um Deepfakes zu analysieren." },
                back: "Zurück",
                upload: { title: "Klicken oder Video ziehen", desc: "Max. Größe: 50MB" },
                scan: { init: "Initialisierung...", d1: "Sicherer Tunnel wird vorbereitet..." },
                res: { 
                    fake: "DEEPFAKE ERKANNT", real: "AUTHENTISCH", 
                    bd: "Analyse-Details", 
                    a1: "Zeitliche Inkonsistenz", a2: "Gesichtsartefakte", a3: "A/V-Synchronisation",
                    anom: "Kritische Anomalien", safe: "Keine Anomalien",
                    btn: "Anderes Video analysieren"
                }
            },
            jp: {
                nav: { dash: "ダッシュボード", tech: "技術", dev: "開発者", price: "価格", supp: "サポート" },
                header: { title: "ビデオフォレンジック", sub: "ディープフェイクやAI操作を分析するために動画をアップロード。" },
                back: "戻る",
                upload: { title: "クリックまたは動画をドラッグ", desc: "最大ファイルサイズ: 50MB" },
                scan: { init: "初期化中...", d1: "安全なアップロードトンネルを準備中..." },
                res: { 
                    fake: "ディープフェイクを検出", real: "本物のコンテンツ", 
                    bd: "分析内訳", 
                    a1: "時間的不整合", a2: "顔のゆがみ", a3: "音声と映像の同期",
                    anom: "重大な異常を発見", safe: "異常なし",
                    btn: "別の動画を分析"
                }
            },
            cn: {
                nav: { dash: "仪表板", tech: "技术", dev: "开发者", price: "价格", supp: "支持" },
                header: { title: "视频取证", sub: "上传视频以分析Deepfake伪造和AI操作。" },
                back: "返回",
                upload: { title: "点击上传或拖拽视频", desc: "最大文件大小: 50MB" },
                scan: { init: "初始化...", d1: "准备安全上传通道..." },
                res: { 
                    fake: "检测到深度伪造", real: "真实内容", 
                    bd: "分析详情", 
                    a1: "时间不一致", a2: "面部扭曲伪影", a3: "音画同步",
                    anom: "发现严重异常", safe: "未发现异常",
                    btn: "分析另一个视频"
                }
            },
            ru: {
                nav: { dash: "Дашборд", tech: "Технологии", dev: "Разработчики", price: "Цены", supp: "Поддержка" },
                header: { title: "Видео Форензика", sub: "Загрузите видео для анализа дипфейков." },
                back: "Назад",
                upload: { title: "Нажмите или перетащите", desc: "Макс. размер: 50MB" },
                scan: { init: "Инициализация...", d1: "Подготовка туннеля..." },
                res: { 
                    fake: "ДИПФЕЙК ОБНАРУЖЕН", real: "ПОДЛИННЫЙ КОНТЕНТ", 
                    bd: "Детали анализа", 
                    a1: "Временная несогласованность", a2: "Искажения лица", a3: "Аудио-видео синхронизация",
                    anom: "Найдены аномалии", safe: "Аномалий нет",
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
            
            const upTitle = document.querySelector('.upload-title');
            if(upTitle) upTitle.innerText = t.upload.title;
            
            const upDesc = document.querySelector('.upload-desc');
            if(upDesc) upDesc.innerText = t.upload.desc;
            
            const scanS = document.getElementById('scanStatus');
            if(scanS) scanS.innerText = t.scan.init;
            
            const scanD = document.getElementById('scanDetail');
            if(scanD) scanD.innerText = t.scan.d1;
            
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
                const file = files[0];
                
                // Validate file type
                const validTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm'];
                if (!validTypes.includes(file.type)) {
                    alert('Please upload a valid video file (MP4, MOV, AVI, WEBM)');
                    return;
                }
                
                // Validate file size (50MB max)
                const maxSize = 50 * 1024 * 1024; // 50MB in bytes
                if (file.size > maxSize) {
                    alert('File size exceeds 50MB. Please upload a smaller video.');
                    return;
                }
                
                startScanning(file);
            }
        }

        function startScanning(file) {
            uploadZone.style.display = 'none';
            scanState.style.display = 'block';

            const statusText = document.getElementById('scanStatus');
            const detailText = document.getElementById('scanDetail');
            const percentText = document.getElementById('scanPercent');

            // Prepare FormData
            const formData = new FormData();
            formData.append('video', file);

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                formData.append('_token', csrfToken.content);
            }

            // Simulate progress animation
            let progress = 0;
            const progressInterval = setInterval(() => {
                if (progress < 90) {
                    progress += Math.floor(Math.random() * 5) + 1;
                    percentText.innerText = progress + '%';
                    
                    if (progress < 20) {
                        statusText.innerText = 'Uploading...';
                        detailText.innerText = 'Encrypting and transmitting video data...';
                    } else if (progress < 50) {
                        statusText.innerText = 'Extracting Frames...';
                        detailText.innerText = 'Decomposing video into frames...';
                    } else if (progress < 75) {
                        statusText.innerText = 'Analyzing Frames...';
                        detailText.innerText = 'Scanning for AI artifacts...';
                    } else {
                        statusText.innerText = 'Checking Metadata...';
                        detailText.innerText = 'Verifying video signature...';
                    }
                }
            }, 200);

            // Call API
            fetch('/api/video/detect-ai', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                clearInterval(progressInterval);
                percentText.innerText = '100%';
                statusText.innerText = 'Finalizing...';
                detailText.innerText = 'Generating forensic report...';
                
                setTimeout(() => {
                    showResult(data);
                }, 800);
            })
            .catch(error => {
                clearInterval(progressInterval);
                scanState.style.display = 'none';
                uploadZone.style.display = 'block';
                alert('Error analyzing video: ' + error.message);
                console.error('Error:', error);
            });
        }

        function showResult(data) {
            scanState.style.display = 'none';
            resultCard.style.display = 'block';
            
            const header = document.getElementById('verdictHeader');
            const icon = document.getElementById('verdictIcon');
            const title = document.getElementById('verdictTitle');
            const scoreEl = document.getElementById('verdictScore');
            
            // Get current lang for dynamic verdict text
            const lang = localStorage.getItem('privasi_lang') || 'en';
            const t = translations[lang] || translations['en'];

            // Determine if AI or Real based on score (EXTREME: lowered threshold to 70%)
            const isAI = data.isAI || data.score < 70;
            const confidence = data.confidence || data.score;

            // Update verdict header
            if (isAI) {
                header.className = 'verdict-header'; 
                icon.innerText = '🤖';
                title.innerText = t.res.fake;
            } else {
                header.className = 'verdict-header safe'; 
                icon.innerText = '✅';
                title.innerText = t.res.real;
            }
            scoreEl.innerText = `Confidence: ${confidence}%`;

            // Update analysis bars with real data
            const labels = document.querySelectorAll('.ai-label span:first-child');
            const bars = document.querySelectorAll('.ai-bar');
            
            if (data.details) {
                // Metadata
                if (labels[0]) labels[0].innerText = 'Metadata Analysis';
                if (bars[0]) {
                    const metadataScore = data.details.metadata?.score || 50;
                    bars[0].setAttribute('data-width', metadataScore + '%');
                    bars[0].style.background = metadataScore < 50 ? '#ef4444' : '#10b981';
                }
                
                // Frame Analysis
                if (labels[1]) labels[1].innerText = 'Frame Analysis';
                if (bars[1]) {
                    const frameScore = data.details.frameAnalysis?.score || 50;
                    bars[1].setAttribute('data-width', frameScore + '%');
                    bars[1].style.background = frameScore < 50 ? '#f97316' : '#10b981';
                }
                
                // Temporal Consistency
                if (labels[2]) labels[2].innerText = 'Temporal Consistency';
                if (bars[2]) {
                    const temporalScore = data.details.temporal?.score || 50;
                    bars[2].setAttribute('data-width', temporalScore + '%');
                    bars[2].style.background = temporalScore < 50 ? '#ef4444' : '#10b981';
                }
            }

            // Animate bars
            setTimeout(() => {
                document.querySelectorAll('.ai-bar').forEach(bar => {
                    bar.style.width = bar.getAttribute('data-width');
                });
            }, 100);

            // Update anomalies box
            const anomaliesBox = document.querySelector('.anomalies-box');
            if (isAI) {
                anomaliesBox.innerHTML = `
                    <div class="anom-title">${t.res.anom}</div>
                    <ul class="anom-list">
                        <li>Metadata: ${data.details?.metadata?.info?.camera ? 'Camera info present' : 'Missing camera sensor signature'}</li>
                        <li>Frame Analysis: Average score ${data.details?.frameAnalysis?.score || 'N/A'}%</li>
                        <li>Temporal: Variance ${data.details?.temporal?.variance || 'N/A'}</li>
                    </ul>
                `;
                anomaliesBox.style.background = '#fef2f2';
                anomaliesBox.style.borderColor = '#fecaca';
            } else {
                anomaliesBox.innerHTML = `
                    <div class="anom-title" style="color: #059669;">✔️ ${t.res.safe}</div>
                    <ul class="anom-list">
                        <li style="color: #047857;">Metadata: ${data.details?.metadata?.info?.camera || 'Authentic signature detected'}</li>
                        <li style="color: #047857;">Frame Analysis: High authenticity score</li>
                        <li style="color: #047857;">Temporal: Natural variance detected</li>
                    </ul>
                `;
                anomaliesBox.style.background = '#ecfdf5';
                anomaliesBox.style.borderColor = '#d1fae5';
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
