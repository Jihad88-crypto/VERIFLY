<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Analysis | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <!-- EXIF.js Library for Metadata Extraction -->
    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
    <style>
        :root {
            --primary: #14F195; /* Green for Image */
            --secondary: #9945FF; 
            --accent: #00C2FF; 
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
            --text-main: #1e1e2f;
            --text-muted: #64748b;
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        /* Original Background */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, 
                #000814 0%,
                #001d3d 25%,
                #003566 50%,
                #001d3d 75%,
                #000814 100%
            );
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Blue Nebula Glow */
        body::before {
            content: '';
            position: fixed;
            right: -15%;
            top: 50%;
            transform: translateY(-50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, 
                rgba(59, 130, 246, 0.15) 0%, 
                rgba(37, 99, 235, 0.08) 30%,
                transparent 70%
            );
            opacity: 0.6;
            z-index: 0;
            pointer-events: none;
        }
        
        /* Starfield Effect */
        body::after {
            content: '';
            position: fixed;
            left: -10%;
            top: 20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, 
                rgba(147, 197, 253, 0.1) 0%, 
                transparent 50%
            );
            opacity: 0.4;
            z-index: 0;
            pointer-events: none;
            animation: pulse 8s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.1); }
        }

        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            padding: 60px 20px; 
            text-align: center; 
            position: relative; 
            z-index: 1; 
        }

        .page-header h1 { 
            font-family: 'Space Grotesk', sans-serif; 
            font-size: 2.5rem; 
            margin-bottom: 12px; 
            color: #ffffff; 
        }
        
        .page-header p { 
            color: #94a3b8; 
            font-size: 1.1rem; 
            margin-bottom: 40px; 
        }


        /* Upload Zone - Original Style */
        .upload-zone {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(20px);
            border: 2px dashed rgba(20, 241, 149, 0.3);
            border-radius: 24px;
            padding: 80px 40px;
            transition: all 0.4s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        
        .upload-zone:hover {
            border-color: #14F195;
            border-style: solid;
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(20, 241, 149, 0.3);
        }
        
        .upload-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(20, 241, 149, 0.2) 0%, rgba(153, 69, 255, 0.2) 100%);
            border: 3px solid rgba(20, 241, 149, 0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            transition: all 0.3s ease;
        }
        
        .upload-zone:hover .upload-icon {
            transform: scale(1.05);
            box-shadow: 0 8px 24px rgba(20, 241, 149, 0.4);
        }
        
        .upload-icon svg {
            width: 50px;
            height: 50px;
        }
        
        .upload-title {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 12px;
            color: #ffffff;
        }
        
        .upload-desc {
            color: #94a3b8;
            font-size: 1rem;
            margin-bottom: 28px;
        }
        
        .file-types {
            display: inline-block;
            padding: 10px 24px;
            background: linear-gradient(135deg, rgba(20, 241, 149, 0.1) 0%, rgba(153, 69, 255, 0.1) 100%);
            border: 1px solid rgba(20, 241, 149, 0.3);
            border-radius: 24px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #14F195;
        }
        
        input[type="file"] { display: none; }

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
        .stage-badge {
            display: inline-block; padding: 6px 16px; border-radius: 99px; 
            font-size: 0.75rem; font-weight: 700; letter-spacing: 1px;
            background: rgba(255,255,255,0.1); color: #94a3b8; margin-bottom: 24px;
            position: relative; z-index: 2; transition: all 0.3s ease;
        }

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

        /* DEBUG LOG PANEL - USER-FRIENDLY DESIGN */
        .debug-panel {
            display: none;
            background: transparent;
            border: none;
            border-radius: 0;
            padding: 0;
            margin-top: 32px;
        }
        
        /* Result card styles */
        .result-main-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 40px;
            text-align: center;
            margin-bottom: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        
        /* Verification layer card */
        .verification-layer-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }
        
        .verification-layer-card:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }
        
        /* Summary card */
        .summary-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 28px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 20px;
            border-radius: 24px;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        
        .status-badge.pass {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1.5px solid #10b981;
        }
        
        .status-badge.fail {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1.5px solid #ef4444;
        }
        
        /* Clean up old styles */
        .debug-title,
        .debug-section,
        .debug-section-title,
        .debug-row,
        .debug-label,
        .debug-value,
        .debug-score-item,
        .debug-score-badge,
        .debug-score-text,
        .debug-final-score {
            /* Removed - using new inline styles */
        }
            font-weight: 600;
            font-size: 0.9rem;
            margin-top: 8px;
        }
        .debug-result-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 99px;
            font-weight: 700;
            font-size: 0.95rem;
            margin-top: 16px;
        }
        .debug-result-badge.authentic {
            background: #10b981;
            color: white;
        }
        .debug-result-badge.suspicious {
            background: #ef4444;
            color: white;
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

        <!-- DEMO DISCLAIMER -->
        <div style="background: rgba(251, 191, 36, 0.1); border: 2px solid #fbbf24; border-radius: 16px; padding: 20px; margin-bottom: 32px; text-align: center;">
            <div style="font-size: 1.5rem; margin-bottom: 8px;">⚠️</div>
            <div style="font-weight: 700; color: #d97706; margin-bottom: 8px; font-size: 1.05rem;">DEMONSTRATION MODE</div>
            <div style="color: #92400e; font-size: 0.9rem; line-height: 1.6;">
                This is a <strong>simulation for UI demonstration purposes only</strong>. Results are randomly generated and do not reflect actual AI detection. Real AI detection requires advanced neural networks and specialized APIs.
            </div>
        </div>

        <!-- 1. Upload Zone -->
        <div class="upload-zone" id="uploadZone" onclick="triggerUpload()">
            <div class="upload-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 15V3M12 3L8 7M12 3L16 7" stroke="#14F195" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L2 19C2 20.1046 2.89543 21 4 21L20 21C21.1046 21 22 20.1046 22 19V17" stroke="#14F195" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="upload-title">Click to Upload or Drag Image</div>
            <div class="upload-desc">Maximum file size: 20MB</div>
            <div class="file-types">JPG, PNG, WEBP</div>
            <input type="file" id="fileInput" accept="image/png,image/jpeg,image/webp" onchange="if(this.files[0]) handleFile(this.files)">
        </div>

        <!-- 2. Scanning Animation (Pixel Grid) -->
        <div class="scanning-state" id="scanState">
            <div id="stageBadge" class="stage-badge">READY TO SCAN</div>
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

        <!-- DEBUG LOG PANEL -->
        <div class="debug-panel" id="debugPanel">
            <div class="debug-title">🔍 Debug Log</div>
            <div id="debugContent"></div>
        </div>

    </div>

    <script>
        const uploadZone = document.getElementById('uploadZone');
        const fileInput = document.getElementById('fileInput');
        const scanState = document.getElementById('scanState');
        const resultCard = document.getElementById('resultCard');
        const pixelGrid = document.getElementById('pixelGrid');

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


        // Translations
        const scenarios = {
            ai: {
                isFake: true,
                metaStatus: "⚠️ AI Signature Detected",
                metaDetail: "Source: Midjourney v6 / Stable Diffusion",
                metaColor: "#fbbf24", // Amber
                title: "AI GENERATED",
                verdictIcon: "🤖",
                confidence: "99.8%",
                headerClass: "verdict-header", // Red/Orange
                anomalies: [
                    "Metadata: Missing camera sensor information.",
                    "Noise: Uniform generative noise pattern detected.",
                    "ELA: No compression artifacts typical of cameras."
                ]
            },
            edit: {
                isFake: true,
                metaStatus: "⚠️ Metadata Modified",
                metaDetail: "Software: Adobe Photoshop 2024 (Win)",
                metaColor: "#fbbf24", 
                title: "EDITING DETECTED",
                verdictIcon: "⚠️",
                confidence: "94.2%",
                headerClass: "verdict-header",
                anomalies: [
                    "Metadata: Software tag shows photo editor usage.",
                    "Pixel Grid: Cloning artifacts found in region (120, 450).",
                    "ELA: High variance in compressed regions."
                ]
            },
            real_iphone: {
                isFake: false,
                metaStatus: "✅ Device Verified",
                metaDetail: "Captured on: iPhone 15 Pro Max (ISO 80)",
                metaColor: "#34d399", // Green
                title: "AUTHENTIC PHOTO",
                verdictIcon: "✅",
                confidence: "99.1%",
                headerClass: "verdict-header safe",
                anomalies: [
                    "Metadata: Consistent Apple HEIC maker notes.",
                    "ELA: Uniform compression across all regions."
                ]
            },
            real_sony: {
                isFake: false,
                metaStatus: "✅ Device Verified",
                metaDetail: "Captured on: Sony Alpha A7III (f/2.8)",
                metaColor: "#34d399",
                title: "AUTHENTIC PHOTO",
                verdictIcon: "✅",
                confidence: "98.5%",
                headerClass: "verdict-header safe",
                anomalies: [
                    "Metadata: Original ARW/JPG headers intact.",
                    "Sensor: Bayer pattern matches Sony specs."
                ]
            }
        };

        function handleFile(files) {
            if (files.length > 0) {
                resetPage(false);
                const file = files[0];
                
                // File size validation (max 10MB)
                const maxSize = 10 * 1024 * 1024; // 10MB in bytes
                if (file.size > maxSize) {
                    alert('⚠️ File terlalu besar!\n\nMaksimal ukuran: 10MB\nUkuran file Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB\n\nSilakan compress foto terlebih dahulu.');
                    resetPage(true);
                    return;
                }
                
                console.log('=== IMAGE VERIFICATION DEBUG LOG ===');
                console.log('File Name:', file.name);
                console.log('File Size:', (file.size / 1024).toFixed(2), 'KB');
                console.log('File Type:', file.type);
                
                // Create canvas for pixel analysis
                const canvas = document.createElement('canvas');
                
                // Read file for EXIF extraction
                const reader = new FileReader();
                
                // Error handling for file reading
                reader.onerror = function() {
                    alert('❌ Gagal membaca file!\n\nKemungkinan penyebab:\n- File corrupt\n- Format tidak didukung\n- File terlalu besar\n\nSilakan coba foto lain.');
                    resetPage(true);
                };
                
                reader.onload = function(e) {
                    const img = new Image();
                    
                    // Error handling for image loading
                    img.onerror = function() {
                        alert('❌ Gagal memuat gambar!\n\nFile mungkin bukan gambar yang valid.\nSilakan coba foto lain.');
                        resetPage(true);
                    };
                    
                    img.onload = function() {
                        console.log('Image Dimensions:', img.width, 'x', img.height);
                        
                        // Extract EXIF data
                        try {
                            EXIF.getData(img, function() {
                                const exifData = EXIF.getAllTags(this);
                                analyzeWithLogging(file, img, exifData, canvas);
                            });
                        } catch (error) {
                            console.error('EXIF extraction error:', error);
                            // Continue without EXIF if extraction fails
                            analyzeWithLogging(file, img, {}, canvas);
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // ============================================
        // PIXEL ANALYSIS FUNCTIONS
        // ============================================
        
        /**
         * Main Pixel Analysis Function
         * Analyzes image pixels for AI detection indicators
         */
        function analyzePixels(img, canvas) {
            try {
                const ctx = canvas.getContext('2d');
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
                
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const pixels = imageData.data;
                
                let pixelScore = 0;
                let pixelLogs = [];
                
                // 1. Noise Pattern Analysis
                try {
                    const noiseScore = analyzeNoise(pixels, canvas.width, canvas.height);
                    pixelScore += noiseScore.score;
                    pixelLogs.push(noiseScore.log);
                } catch (e) {
                    console.error('Noise analysis error:', e);
                    pixelLogs.push('Noise: Analysis failed');
                }
                
                // 2. JPEG Compression Artifacts
                try {
                    const compressionScore = analyzeCompression(pixels, canvas.width, canvas.height);
                    pixelScore += compressionScore.score;
                    pixelLogs.push(compressionScore.log);
                } catch (e) {
                    console.error('Compression analysis error:', e);
                    pixelLogs.push('Compression: Analysis failed');
                }
                
                // 3. Color Histogram Analysis
                try {
                    const colorScore = analyzeColorHistogram(pixels);
                    pixelScore += colorScore.score;
                    pixelLogs.push(colorScore.log);
                } catch (e) {
                    console.error('Color analysis error:', e);
                    pixelLogs.push('Color: Analysis failed');
                }
                
                // 4. Edge Detection
                try {
                    const edgeScore = analyzeEdges(pixels, canvas.width, canvas.height);
                    pixelScore += edgeScore.score;
                    pixelLogs.push(edgeScore.log);
                } catch (e) {
                    console.error('Edge analysis error:', e);
                    pixelLogs.push('Edge: Analysis failed');
                }
                
                return {
                    score: pixelScore,
                    logs: pixelLogs
                };
            } catch (error) {
                console.error('Pixel analysis failed:', error);
                // Return neutral score if analysis fails
                return {
                    score: 0,
                    logs: ['Pixel analysis unavailable (image too large or format issue)']
                };
            }
        }
        
        /**
         * 1. Noise Pattern Analysis
         * Real cameras have sensor noise, AI images are too smooth
         */
        function analyzeNoise(pixels, width, height) {
            // Sample dark areas (where noise is most visible)
            let darkPixels = [];
            
            for (let i = 0; i < pixels.length; i += 4) {
                const r = pixels[i];
                const g = pixels[i + 1];
                const b = pixels[i + 2];
                const brightness = (r + g + b) / 3;
                
                // Collect dark pixels (brightness < 50)
                if (brightness < 50) {
                    darkPixels.push({r, g, b});
                }
                
                // Sample only 1000 pixels for performance
                if (darkPixels.length >= 1000) break;
            }
            
            if (darkPixels.length < 100) {
                return {score: 0, log: 'Noise: Insufficient dark areas'};
            }
            
            // Calculate variance (noise level)
            let variance = 0;
            for (let i = 0; i < darkPixels.length - 1; i++) {
                const diff = Math.abs(darkPixels[i].r - darkPixels[i + 1].r);
                variance += diff;
            }
            variance /= darkPixels.length;
            
            // Real camera: variance > 3 (natural noise)
            // AI image: variance < 1.5 (too smooth)
            if (variance > 3) {
                return {score: -10, log: `-10: Natural noise detected (variance: ${variance.toFixed(2)})`};
            } else if (variance < 1.5) {
                return {score: +15, log: `+15: Suspiciously smooth (variance: ${variance.toFixed(2)})`};
            } else {
                return {score: 0, log: `Noise: Moderate (variance: ${variance.toFixed(2)})`};
            }
        }
        
        /**
         * 2. JPEG Compression Artifacts Analysis
         * Real photos have 8x8 block artifacts, AI may have different patterns
         */
        function analyzeCompression(pixels, width, height) {
            // Sample 8x8 blocks and check for blocking artifacts
            let blockVariance = 0;
            let blockCount = 0;
            
            // Check 10 random 8x8 blocks
            for (let n = 0; n < 10; n++) {
                const x = Math.floor(Math.random() * (width - 8));
                const y = Math.floor(Math.random() * (height - 8));
                
                // Get average color of block
                let blockSum = 0;
                for (let by = 0; by < 8; by++) {
                    for (let bx = 0; bx < 8; bx++) {
                        const idx = ((y + by) * width + (x + bx)) * 4;
                        blockSum += (pixels[idx] + pixels[idx + 1] + pixels[idx + 2]) / 3;
                    }
                }
                const blockAvg = blockSum / 64;
                
                // Check variance within block
                let variance = 0;
                for (let by = 0; by < 8; by++) {
                    for (let bx = 0; bx < 8; bx++) {
                        const idx = ((y + by) * width + (x + bx)) * 4;
                        const brightness = (pixels[idx] + pixels[idx + 1] + pixels[idx + 2]) / 3;
                        variance += Math.abs(brightness - blockAvg);
                    }
                }
                variance /= 64;
                blockVariance += variance;
                blockCount++;
            }
            
            blockVariance /= blockCount;
            
            // Real JPEG: block variance 5-15
            // AI: block variance < 3 or > 20
            if (blockVariance >= 5 && blockVariance <= 15) {
                return {score: -10, log: `-10: JPEG artifacts detected (${blockVariance.toFixed(2)})`};
            } else {
                return {score: +10, log: `+10: Abnormal compression (${blockVariance.toFixed(2)})`};
            }
        }
        
        /**
         * 3. Color Histogram Analysis
         * Real photos have natural color distribution, AI may be too perfect
         */
        function analyzeColorHistogram(pixels) {
            // Build histogram for each channel
            const rHist = new Array(256).fill(0);
            const gHist = new Array(256).fill(0);
            const bHist = new Array(256).fill(0);
            
            // Sample every 10th pixel for performance
            for (let i = 0; i < pixels.length; i += 40) {
                rHist[pixels[i]]++;
                gHist[pixels[i + 1]]++;
                bHist[pixels[i + 2]]++;
            }
            
            // Check for spikes (unnatural distribution)
            let spikes = 0;
            const threshold = Math.max(...rHist) * 0.5;
            
            for (let i = 0; i < 256; i++) {
                if (rHist[i] > threshold || gHist[i] > threshold || bHist[i] > threshold) {
                    spikes++;
                }
            }
            
            // Real photo: smooth distribution (spikes < 10)
            // AI: too many spikes or too flat
            if (spikes < 10) {
                return {score: -10, log: `-10: Natural color distribution (${spikes} spikes)`};
            } else {
                return {score: +10, log: `+10: Unnatural color distribution (${spikes} spikes)`};
            }
        }
        
        /**
         * 4. Edge Detection Analysis
         * Real photos have natural edge blur, AI may be too sharp or too smooth
         */
        function analyzeEdges(pixels, width, height) {
            // Simple Sobel edge detection on sample area
            let edgeStrength = 0;
            let edgeCount = 0;
            
            // Sample 100x100 area in center
            const startX = Math.floor(width / 2) - 50;
            const startY = Math.floor(height / 2) - 50;
            
            for (let y = startY; y < startY + 100 && y < height - 1; y++) {
                for (let x = startX; x < startX + 100 && x < width - 1; x++) {
                    const idx = (y * width + x) * 4;
                    const idxRight = (y * width + (x + 1)) * 4;
                    const idxDown = ((y + 1) * width + x) * 4;
                    
                    const current = (pixels[idx] + pixels[idx + 1] + pixels[idx + 2]) / 3;
                    const right = (pixels[idxRight] + pixels[idxRight + 1] + pixels[idxRight + 2]) / 3;
                    const down = (pixels[idxDown] + pixels[idxDown + 1] + pixels[idxDown + 2]) / 3;
                    
                    const gx = Math.abs(right - current);
                    const gy = Math.abs(down - current);
                    const gradient = Math.sqrt(gx * gx + gy * gy);
                    
                    if (gradient > 20) {
                        edgeStrength += gradient;
                        edgeCount++;
                    }
                }
            }
            
            if (edgeCount === 0) {
                return {score: 0, log: 'Edge: No significant edges found'};
            }
            
            const avgEdge = edgeStrength / edgeCount;
            
            // Real photo: moderate edge strength (30-80)
            // AI: too sharp (>100) or too smooth (<20)
            if (avgEdge >= 30 && avgEdge <= 80) {
                return {score: -10, log: `-10: Natural edge sharpness (${avgEdge.toFixed(2)})`};
            } else {
                return {score: +10, log: `+10: Abnormal edge sharpness (${avgEdge.toFixed(2)})`};
            }
        }
        
        /**
         * AI DETECTION API (REAL HUGGING FACE API)
         * Sends image to Hugging Face AI-image-detector model
         * Returns AI probability (0-100%)
         */
        async function analyzeWithAI(metadataScore, pixelScore, img) {
            try {
                // Convert image to base64
                const canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                
                // Get image as blob
                const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.95));
                
                // Call Hugging Face API
                const API_KEY = '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';
                const API_URL = 'https://api-inference.huggingface.co/models/umm-maybe/AI-image-detector';
                
                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${API_KEY}`,
                        'Content-Type': 'application/octet-stream'
                    },
                    body: blob
                });
                
                if (!response.ok) {
                    throw new Error(`API error: ${response.status}`);
                }
                
                const result = await response.json();
                console.log('Hugging Face API Response:', result);
                
                // Parse result
                // Hugging Face returns array like: [{label: "artificial", score: 0.92}, {label: "human", score: 0.08}]
                let aiProbability = 50; // Default fallback
                
                if (Array.isArray(result) && result.length > 0) {
                    console.log('Response is array, processing...');
                    
                    // Look for "artificial" or "ai" or "fake" label
                    const artificialResult = result.find(r => 
                        r.label && (
                            r.label.toLowerCase().includes('artificial') || 
                            r.label.toLowerCase().includes('ai') ||
                            r.label.toLowerCase().includes('fake')
                        )
                    );
                    
                    if (artificialResult) {
                        aiProbability = artificialResult.score * 100;
                        console.log('Found artificial label:', artificialResult.label, 'Score:', aiProbability);
                    } else {
                        // If no "artificial" label, check for "human" or "real" and invert
                        const humanResult = result.find(r => 
                            r.label && (
                                r.label.toLowerCase().includes('human') || 
                                r.label.toLowerCase().includes('real') ||
                                r.label.toLowerCase().includes('authentic')
                            )
                        );
                        
                        if (humanResult) {
                            aiProbability = (1 - humanResult.score) * 100; // Invert score
                            console.log('Found human label:', humanResult.label, 'Inverted to AI probability:', aiProbability);
                        } else {
                            // Fallback: assume first result is AI probability
                            aiProbability = result[0].score * 100;
                            console.log('Using first result:', result[0].label, 'Score:', aiProbability);
                        }
                    }
                } else if (result.score !== undefined) {
                    aiProbability = result.score * 100;
                    console.log('Response has direct score:', aiProbability);
                } else {
                    console.error('Unexpected API response format:', result);
                    throw new Error('Unexpected API response format');
                }
                
                console.log('Final AI Probability:', aiProbability);
                
                // Convert probability to score
                // 0-30% = authentic (-30 to -10)
                // 30-70% = uncertain (-10 to +10)
                // 70-100% = AI (+10 to +30)
                let apiScore = 0;
                if (aiProbability < 30) {
                    apiScore = -30 + (aiProbability / 30) * 20;
                } else if (aiProbability < 70) {
                    apiScore = -10 + ((aiProbability - 30) / 40) * 20;
                } else {
                    apiScore = 10 + ((aiProbability - 70) / 30) * 20;
                }
                
                return {
                    score: Math.round(apiScore),
                    probability: aiProbability.toFixed(1),
                    confidence: 95,
                    log: `AI Probability: ${aiProbability.toFixed(1)}% (Score: ${Math.round(apiScore)})`,
                    mockMode: false
                };
                
            } catch (error) {
                console.error('AI API error:', error);
                return {
                    score: 0,
                    probability: 50,
                    confidence: 0,
                    log: `AI API error: ${error.message} - using metadata + pixel only`,
                    mockMode: false,
                    error: true
                };
            }
        }
        
        // Helper function for delay
        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        async function analyzeWithLogging(file, img, exif, canvas) {
            let metadataScore = 0;
            let logLines = [];
            let hasEditingSoftware = false;
            let hasCameraInfo = false;
            
            // Add to UI log
            function addLog(text, type = 'info') {
                logLines.push({text, type});
                console.log(text);
            }
            
            addLog('=== IMAGE VERIFICATION DEBUG LOG ===', 'header');
            addLog(`File: ${file.name}`, 'info');
            addLog(`Size: ${(file.size / 1024).toFixed(2)} KB`, 'info');
            addLog(`Dimensions: ${img.width} x ${img.height}`, 'info');
            addLog('', 'info');
            
            addLog('--- EXIF METADATA ---', 'header');
            addLog(`Software: ${exif.Software || 'NOT FOUND'}`, 'info');
            addLog(`Make: ${exif.Make || 'NOT FOUND'}`, 'info');
            addLog(`Model: ${exif.Model || 'NOT FOUND'}`, 'info');
            addLog(`DateTime: ${exif.DateTime || exif.DateTimeOriginal || 'NOT FOUND'}`, 'info');
            addLog(`LensModel: ${exif.LensModel || 'NOT FOUND'}`, 'info');
            addLog(`ISO: ${exif.ISOSpeedRatings || 'NOT FOUND'}`, 'info');
            addLog(`Aperture: ${exif.FNumber || exif.ApertureValue || 'NOT FOUND'}`, 'info');
            addLog(`Shutter Speed: ${exif.ExposureTime || 'NOT FOUND'}`, 'info');
            addLog(`GPS: ${exif.GPSLatitude && exif.GPSLongitude ? 'FOUND' : 'NOT FOUND'}`, 'info');
            addLog('', 'info');
            
            addLog('--- METADATA ANALYSIS SCORING ---', 'header');
            
            // Check 1: Software
            const software = exif.Software;
            if (software) {
                const sw = software.toLowerCase();
                if (sw.includes('photoshop') || sw.includes('gimp') || sw.includes('lightroom') || sw.includes('affinity')) {
                    metadataScore += 25;
                    hasEditingSoftware = true;
                    addLog(`+25: Editing software detected (${software})`, 'negative');
                } else if (sw.includes('camera') || sw.includes('samsung') || sw.includes('xiaomi') || sw.includes('huawei') || sw.includes('oppo')) {
                    metadataScore -= 20;
                    addLog(`-20: Phone camera detected (${software})`, 'positive');
                } else {
                    metadataScore -= 10;
                    addLog(`-10: Safe software (${software})`, 'positive');
                }
            } else {
                metadataScore += 35;
                addLog('+35: No software tag (highly suspicious - AI indicator)', 'negative');
            }
            
            // Check 2: Camera Make/Model
            if (exif.Make && exif.Model) {
                metadataScore -= 30;
                hasCameraInfo = true;
                addLog(`-30: Camera found (${exif.Make} ${exif.Model})`, 'positive');
            } else {
                metadataScore += 35;
                addLog('+35: No camera info (highly suspicious - AI indicator)', 'negative');
            }
            
            // Check 3: Lens Information (DSLR/Mirrorless indicator)
            if (exif.LensModel) {
                metadataScore -= 15;
                addLog(`-15: Lens info found (${exif.LensModel})`, 'positive');
            }
            
            // Check 4: GPS Data (Real photos often have GPS)
            if (exif.GPSLatitude && exif.GPSLongitude) {
                metadataScore -= 10;
                addLog(`-10: GPS data found (real location)`, 'positive');
            }
            
            // Check 5: Camera Parameters (ISO, Aperture, Shutter)
            let cameraParamsCount = 0;
            if (exif.ISOSpeedRatings) {
                cameraParamsCount++;
                addLog(`-5: ISO found (${exif.ISOSpeedRatings})`, 'positive');
                metadataScore -= 5;
            }
            if (exif.FNumber || exif.ApertureValue) {
                cameraParamsCount++;
                const aperture = exif.FNumber || exif.ApertureValue;
                addLog(`-5: Aperture found (f/${aperture})`, 'positive');
                metadataScore -= 5;
            }
            if (exif.ExposureTime) {
                cameraParamsCount++;
                addLog(`-5: Shutter speed found (${exif.ExposureTime}s)`, 'positive');
                metadataScore -= 5;
            }
            
            // Bonus if all camera params present
            if (cameraParamsCount === 3) {
                metadataScore -= 10;
                addLog(`-10: Complete camera parameters (strong authentic indicator)`, 'positive');
            }
            
            // Check 6: DateTime Validation
            const dateTime = exif.DateTime || exif.DateTimeOriginal;
            if (dateTime) {
                // Check if date is reasonable (not too old, not in future)
                const dateMatch = dateTime.match(/(\d{4}):(\d{2}):(\d{2})/);
                if (dateMatch) {
                    const year = parseInt(dateMatch[1]);
                    const currentYear = new Date().getFullYear();
                    if (year >= 2000 && year <= currentYear) {
                        metadataScore -= 5;
                        addLog(`-5: Valid timestamp (${dateTime})`, 'positive');
                    } else {
                        metadataScore += 10;
                        addLog(`+10: Suspicious timestamp (${dateTime})`, 'negative');
                    }
                }
            }
            
            // Check 7: Resolution
            const aiRes = [{w:512,h:512},{w:1024,h:1024},{w:768,h:768},{w:1536,h:1536}];
            const isAI = aiRes.some(r => Math.abs(img.width-r.w)<10 && Math.abs(img.height-r.h)<10);
            if (isAI) {
                metadataScore += 30;
                addLog(`+30: AI resolution detected (${img.width}x${img.height})`, 'negative');
            }
            
            addLog('', 'info');
            addLog(`METADATA SCORE: ${metadataScore}`, 'header');
            addLog('', 'info');
            
            // ============================================
            // PIXEL ANALYSIS (AI NATURAL SCAN)
            // ============================================
            addLog('--- PIXEL ANALYSIS (AI NATURAL SCAN) ---', 'header');
            
            let pixelScore = 0;
            let pixelLogs = [];
            
            try {
                const pixelResult = analyzePixels(img, canvas);
                pixelScore = pixelResult.score;
                pixelLogs = pixelResult.logs;
            } catch (error) {
                console.error('Pixel analysis error:', error);
                pixelScore = 0;
                pixelLogs = ['Pixel analysis failed - using metadata only'];
            }
            
            // Add pixel analysis logs
            pixelLogs.forEach(log => {
                const isPositive = log.startsWith('-');
                addLog(log, isPositive ? 'positive' : 'negative');
            });
            
            addLog('', 'info');
            addLog(`PIXEL SCORE: ${pixelScore}`, 'header');
            addLog('', 'info');
            
            // ============================================
            // AI DETECTION API ANALYSIS
            // ============================================
            addLog('--- AI DETECTION API ---', 'header');
            addLog('Calling AI detection model...', 'info');
            
            const aiResult = await analyzeWithAI(metadataScore, pixelScore, img);
            const aiScore = aiResult.score;
            const aiProbability = aiResult.probability;
            
            if (aiResult.mockMode) {
                addLog('⚠️ MOCK MODE: Using simulated AI API', 'info');
            }
            
            if (aiResult.error) {
                addLog(aiResult.log, 'negative');
            } else {
                const isAI = parseFloat(aiProbability) > 50;
                addLog(aiResult.log, isAI ? 'negative' : 'positive');
                
                if (parseFloat(aiProbability) > 70) {
                    addLog(`High AI probability detected`, 'negative');
                } else if (parseFloat(aiProbability) < 30) {
                    addLog(`Low AI probability - likely authentic`, 'positive');
                } else {
                    addLog(`Uncertain - moderate AI probability`, 'info');
                }
            }
            
            addLog('', 'info');
            addLog(`AI API SCORE: ${aiScore}`, 'header');
            addLog('', 'info');
            
            // ============================================
            // COMBINED SCORING (3-LAYER)
            // ============================================
            addLog('--- COMBINED ANALYSIS (3-LAYER) ---', 'header');
            
            // Weight: Metadata 30%, Pixel 30%, AI API 40%
            const hasMetadata = (exif.Make || exif.Model || exif.Software);
            let finalScore = 0;
            
            if (aiResult.error) {
                // AI API failed - fallback to 2-layer
                if (hasMetadata) {
                    finalScore = (metadataScore * 0.6) + (pixelScore * 0.4);
                    addLog(`AI API unavailable - using 2-layer fallback`, 'info');
                    addLog(`Metadata (60%): ${metadataScore} × 0.6 = ${(metadataScore * 0.6).toFixed(1)}`, 'info');
                    addLog(`Pixel (40%): ${pixelScore} × 0.4 = ${(pixelScore * 0.4).toFixed(1)}`, 'info');
                } else {
                    finalScore = pixelScore;
                    addLog(`No metadata + AI API unavailable - using Pixel only`, 'info');
                    addLog(`Pixel (100%): ${pixelScore}`, 'info');
                }
            } else {
                // AI API available - use 3-layer
                if (hasMetadata) {
                    finalScore = (metadataScore * 0.3) + (pixelScore * 0.3) + (aiScore * 0.4);
                    addLog(`Metadata (30%): ${metadataScore} × 0.3 = ${(metadataScore * 0.3).toFixed(1)}`, 'info');
                    addLog(`Pixel (30%): ${pixelScore} × 0.3 = ${(pixelScore * 0.3).toFixed(1)}`, 'info');
                    addLog(`AI API (40%): ${aiScore} × 0.4 = ${(aiScore * 0.4).toFixed(1)}`, 'info');
                } else {
                    // No metadata: Pixel 40% + AI API 60%
                    finalScore = (pixelScore * 0.4) + (aiScore * 0.6);
                    addLog(`No metadata found - using Pixel + AI API`, 'info');
                    addLog(`Pixel (40%): ${pixelScore} × 0.4 = ${(pixelScore * 0.4).toFixed(1)}`, 'info');
                    addLog(`AI API (60%): ${aiScore} × 0.6 = ${(aiScore * 0.6).toFixed(1)}`, 'info');
                }
            }
            
            finalScore = Math.round(finalScore);
            
            // ============================================
            // CONFIDENCE & CONFLICT DETECTION
            // ============================================
            addLog('', 'info');
            addLog('--- CONFIDENCE ANALYSIS ---', 'header');
            
            // Determine direction of each analysis
            const metadataDirection = metadataScore < 0 ? 'authentic' : 'suspicious';
            const pixelDirection = pixelScore < 0 ? 'authentic' : 'suspicious';
            const aiDirection = aiScore < 0 ? 'authentic' : 'suspicious';
            
            let confidence = 0;
            let conflictDetected = false;
            let confidenceLevel = '';
            let conflictMessage = '';
            
            if (!aiResult.error) {
                // 3-layer analysis available
                const directions = [metadataDirection, pixelDirection, aiDirection];
                const authenticCount = directions.filter(d => d === 'authentic').length;
                const suspiciousCount = directions.filter(d => d === 'suspicious').length;
                
                if (authenticCount === 3 || suspiciousCount === 3) {
                    // Perfect agreement (3/3)
                    const agreement = Math.abs(metadataScore) + Math.abs(pixelScore) + Math.abs(aiScore);
                    confidence = Math.min(98, 85 + (agreement / 10));
                    confidenceLevel = 'HIGH';
                    addLog(`✅ Perfect Agreement: All 3 analyses point to ${metadataDirection}`, 'positive');
                    addLog(`Confidence: ${confidence.toFixed(0)}% (HIGH)`, 'positive');
                } else if (authenticCount === 2 || suspiciousCount === 2) {
                    // Majority agreement (2/3)
                    const majorityDirection = authenticCount === 2 ? 'authentic' : 'suspicious';
                    confidence = Math.min(85, 65 + Math.abs(finalScore) / 2);
                    confidenceLevel = 'MEDIUM';
                    conflictDetected = true;
                    addLog(`⚠️ Majority Agreement: 2 out of 3 analyses point to ${majorityDirection}`, 'info');
                    addLog(`Confidence: ${confidence.toFixed(0)}% (MEDIUM)`, 'info');
                    
                    // Find which one disagrees
                    if (metadataDirection !== majorityDirection) {
                        conflictMessage = 'Metadata disagrees with Pixel + AI API. Metadata may be missing or manipulated.';
                    } else if (pixelDirection !== majorityDirection) {
                        conflictMessage = 'Pixel analysis disagrees with Metadata + AI API. Image may have unusual characteristics.';
                    } else {
                        conflictMessage = 'AI API disagrees with Metadata + Pixel. This is unusual - manual review recommended.';
                    }
                    addLog(`⚠️ ${conflictMessage}`, 'negative');
                } else {
                    // Complete disagreement (1/1/1) - very rare
                    confidence = 40;
                    confidenceLevel = 'LOW';
                    conflictDetected = true;
                    addLog(`❌ COMPLETE DISAGREEMENT: All 3 analyses conflict!`, 'negative');
                    addLog(`Confidence: ${confidence.toFixed(0)}% (LOW)`, 'negative');
                    conflictMessage = 'Complete disagreement between all analyses. Manual expert review required.';
                    addLog(`⚠️ ${conflictMessage}`, 'negative');
                }
            } else if (hasMetadata) {
                // Both analyses available - check for conflict
                if (metadataDirection === pixelDirection) {
                    // AGREEMENT: Both point same direction
                    const agreement = Math.abs(metadataScore) + Math.abs(pixelScore);
                    confidence = Math.min(95, 70 + (agreement / 5));
                    confidenceLevel = 'HIGH';
                    addLog(`✅ Agreement: Both analyses point to ${metadataDirection}`, 'positive');
                    addLog(`Confidence: ${confidence.toFixed(0)}% (HIGH)`, 'positive');
                } else {
                    // CONFLICT: Analyses disagree
                    conflictDetected = true;
                    const metadataStrength = Math.abs(metadataScore);
                    const pixelStrength = Math.abs(pixelScore);
                    const totalStrength = metadataStrength + pixelStrength;
                    
                    // Confidence based on strength difference
                    const strengthDiff = Math.abs(metadataStrength - pixelStrength);
                    confidence = Math.min(70, 30 + strengthDiff);
                    
                    if (confidence >= 50) {
                        confidenceLevel = 'MEDIUM';
                    } else {
                        confidenceLevel = 'LOW';
                    }
                    
                    addLog(`⚠️ CONFLICT DETECTED!`, 'negative');
                    addLog(`Metadata says: ${metadataDirection.toUpperCase()} (${metadataScore})`, 'info');
                    addLog(`Pixel says: ${pixelDirection.toUpperCase()} (${pixelScore})`, 'info');
                    addLog(`Confidence: ${confidence.toFixed(0)}% (${confidenceLevel})`, 'negative');
                    
                    // Generate conflict message
                    if (metadataStrength > pixelStrength) {
                        conflictMessage = 'Metadata analysis is stronger. Possible fake metadata or heavy editing.';
                    } else {
                        conflictMessage = 'Pixel analysis is stronger. Metadata may be manipulated.';
                    }
                    addLog(`⚠️ ${conflictMessage}`, 'negative');
                }
            } else {
                // No metadata - pixel only
                const pixelStrength = Math.abs(pixelScore);
                confidence = Math.min(75, 40 + pixelStrength);
                confidenceLevel = confidence >= 60 ? 'MEDIUM' : 'LOW';
                addLog(`ℹ️ No metadata available - relying on pixel analysis only`, 'info');
                addLog(`Confidence: ${confidence.toFixed(0)}% (${confidenceLevel})`, 'info');
            }
            
            // Determine classification (3-tier system)
            let classification = '';
            let classColor = '';
            let classIcon = '';
            
            // Adjust classification based on conflict
            if (conflictDetected && confidenceLevel === 'LOW') {
                // Low confidence conflict = UNCERTAIN
                classification = 'UNCERTAIN';
                classColor = 'warning'; // Orange
                classIcon = '❓';
                addLog('', 'info');
                addLog('CLASSIFICATION: UNCERTAIN (Conflicting signals detected)', 'warning');
            } else if (hasCameraInfo && hasEditingSoftware) {
                // Has camera info BUT has editing software = EDITED
                classification = 'EDITED';
                classColor = 'warning'; // Yellow
                classIcon = '⚠️';
                addLog('', 'info');
                addLog('CLASSIFICATION: EDITED (Real photo with modifications)', 'warning');
            } else if (finalScore >= 40) {
                // High suspicion score = AI GENERATED
                classification = 'AI GENERATED';
                classColor = 'danger'; // Red
                classIcon = '🤖';
                addLog('', 'info');
                addLog('CLASSIFICATION: AI GENERATED (Synthetic image)', 'negative');
            } else {
                // Low score = AUTHENTIC
                classification = 'AUTHENTIC';
                classColor = 'success'; // Green
                classIcon = '✅';
                addLog('', 'info');
                addLog('CLASSIFICATION: AUTHENTIC (Natural photo)', 'positive');
            }
            
            addLog('', 'info');
            addLog(`FINAL COMBINED SCORE: ${finalScore}`, 'header');
            addLog(`CONFIDENCE: ${confidence.toFixed(0)}% (${confidenceLevel})`, 'header');
            addLog('Threshold: <40 = Authentic, 40+ = AI, Has Editing Software = Edited', 'info');
            addLog(`Result: ${classification}`, classColor === 'success' ? 'positive' : 'negative');
            
            if (conflictDetected) {
                addLog('', 'info');
                addLog('⚠️ CONFLICT WARNING ⚠️', 'negative');
                addLog(conflictMessage, 'negative');
                addLog('Recommendation: Manual expert review suggested', 'negative');
            }
            
            addLog('', 'info');
            
            // Use random for demo
            const keys = Object.keys(scenarios);
            const randomKey = keys[Math.floor(Math.random() * keys.length)];
            const scenario = scenarios[randomKey];
            
            addLog(`DEMO MODE: Using random scenario - ${randomKey}`, 'info');
            
            // Display logs in UI with classification and confidence
            displayDebugLog(logLines, classification, classColor, classIcon, finalScore, confidence, confidenceLevel, conflictDetected, conflictMessage, aiProbability, aiResult.mockMode);
            
            startStage1(scenario);
        }

        function displayDebugLog(logLines, classification, classColor, classIcon, finalScore, confidence, confidenceLevel, conflictDetected, conflictMessage, aiProbability, aiMockMode) {
            const debugPanel = document.getElementById('debugPanel');
            const debugContent = document.getElementById('debugContent');
            
            // Parse log data
            const fileInfo = {
                name: logLines.find(l => l.text.startsWith('File:'))?.text.replace('File: ', '') || '',
                size: logLines.find(l => l.text.startsWith('Size:'))?.text.replace('Size: ', '') || '',
                dimensions: logLines.find(l => l.text.startsWith('Dimensions:'))?.text.replace('Dimensions: ', '') || ''
            };
            
            const exifInfo = {
                software: logLines.find(l => l.text.startsWith('Software:'))?.text.replace('Software: ', '') || '',
                make: logLines.find(l => l.text.startsWith('Make:'))?.text.replace('Make: ', '') || '',
                model: logLines.find(l => l.text.startsWith('Model:'))?.text.replace('Model: ', '') || '',
                dateTime: logLines.find(l => l.text.startsWith('DateTime:'))?.text.replace('DateTime: ', '') || '',
                lens: logLines.find(l => l.text.startsWith('LensModel:'))?.text.replace('LensModel: ', '') || '',
                iso: logLines.find(l => l.text.startsWith('ISO:'))?.text.replace('ISO: ', '') || '',
                aperture: logLines.find(l => l.text.startsWith('Aperture:'))?.text.replace('Aperture: ', '') || '',
                shutter: logLines.find(l => l.text.startsWith('Shutter Speed:'))?.text.replace('Shutter Speed: ', '') || '',
                gps: logLines.find(l => l.text.startsWith('GPS:'))?.text.replace('GPS: ', '') || ''
            };
            
            const scores = logLines.filter(l => l.text.match(/^[+-]\d+:/));
            const demoMode = logLines.find(l => l.text.startsWith('DEMO MODE:'))?.text || '';
            
            // Determine badge color based on classification
            let badgeClass = 'authentic';
            let badgeColor = '#10b981'; // Green
            if (classColor === 'warning') {
                badgeClass = 'edited';
                badgeColor = '#f59e0b'; // Yellow/Orange
            } else if (classColor === 'danger') {
                badgeClass = 'ai-generated';
                badgeColor = '#ef4444'; // Red
            }
            
            
            // Extract scores from logs
            const metadataScore = parseInt(logLines.find(l => l.text.includes('METADATA SCORE:'))?.text.match(/-?\d+/)?.[0] || '0');
            const pixelScore = parseInt(logLines.find(l => l.text.includes('PIXEL SCORE:'))?.text.match(/-?\d+/)?.[0] || '0');
            const aiScore = parseInt(logLines.find(l => l.text.includes('AI API SCORE:'))?.text.match(/-?\d+/)?.[0] || '0');
            
            // Determine status for each layer
            const metadataStatus = metadataScore < 0 ? 'PASS' : 'FAIL';
            const pixelStatus = pixelScore < 0 ? 'PASS' : 'FAIL';
            const aiStatus = parseFloat(aiProbability) < 50 ? 'PASS' : 'FAIL';
            
            // Generate compact explanations
            let whyText = '';
            if (classColor === 'success') {
                whyText = `• No signs of artificial manipulation or generation were found<br>• Image metadata is consistent with authentic capture devices<br>• Pixel-level analysis shows natural camera characteristics`;
            } else if (classColor === 'warning' && classification === 'UNCERTAIN') {
                whyText = `• Conflicting signals from different analysis layers<br>• Some indicators suggest authenticity, others suggest AI<br>• Manual expert review recommended`;
            } else if (classColor === 'warning') {
                whyText = `• Original photo detected but with post-processing<br>• ${exifInfo.software ? `Edited with ${exifInfo.software}` : 'Editing software detected'}<br>• Not in original form`;
            } else {
                whyText = `• Multiple indicators point to AI generation<br>• ${!exifInfo.make ? 'No camera metadata found' : 'Suspicious metadata patterns'}<br>• Artificial pixel patterns detected`;
            }
            
            // Icon for result
            let shieldIcon = classColor === 'success' ? '✓' : classColor === 'warning' ? '⚠' : '✗';
            
            // Build Reflect-style result HTML
            let html = `
                <!-- Reflect-Style Result Card -->
                <div style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 40px; text-align: center; margin-bottom: 24px; box-shadow: 0 8px 32px rgba(0,0,0,0.2);">
                    <!-- Icon -->
                    <div style="width: 80px; height: 80px; margin: 0 auto 20px; background: linear-gradient(135deg, ${badgeColor}15 0%, ${badgeColor}05 100%); border: 2px solid ${badgeColor}40; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2L4 6v6c0 5.55 3.84 10.74 8 12 4.16-1.26 8-6.45 8-12V6l-8-4z" fill="${badgeColor}" opacity="0.2"/>
                            <path d="M12 2L4 6v6c0 5.55 3.84 10.74 8 12 4.16-1.26 8-6.45 8-12V6l-8-4z" stroke="${badgeColor}" stroke-width="2" fill="none"/>
                            ${classColor === 'success' ? '<path d="M9 12l2 2 4-4" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>' : classColor === 'warning' ? '<circle cx="12" cy="12" r="1.5" fill="white"/><path d="M12 8v3" stroke="white" stroke-width="2" stroke-linecap="round"/>' : '<path d="M9 9l6 6M15 9l-6 6" stroke="white" stroke-width="2.5" stroke-linecap="round"/>'}
                        </svg>
                    </div>
                    
                    <!-- Title -->
                    <div style="font-family: 'Space Grotesk', sans-serif; font-size: 1.75rem; font-weight: 700; color: white; margin-bottom: 8px; letter-spacing: -0.02em;">
                        ${classification}
                    </div>
                    <div style="font-size: 0.9375rem; color: rgba(255,255,255,0.6); font-weight: 500;">
                        ${confidence.toFixed(0)}% Confidence
                    </div>
                </div>
                
                <!-- Accordion Sections -->
                <div style="margin-bottom: 24px;">
                    <!-- Metadata Analysis -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; margin-bottom: 8px; overflow: hidden; transition: all 0.2s ease;">
                        <div onclick="toggleAccordion('acc1')" style="padding: 16px 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="${metadataStatus === 'PASS' ? '#10b981' : '#ef4444'}" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <div style="flex: 1;">
                                    <span style="color: rgba(255,255,255,0.95); font-weight: 600; font-size: 0.9375rem;">Metadata Analysis</span>
                                    <span style="color: ${metadataStatus === 'PASS' ? '#10b981' : '#ef4444'}; font-weight: 600; font-size: 0.8125rem; margin-left: 10px;">- ${metadataStatus}</span>
                                </div>
                            </div>
                            <svg id="chevron-acc1" width="20" height="20" viewBox="0 0 20 20" fill="none" style="transition: transform 0.3s;">
                                <path d="M6 8l4 4 4-4" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div id="acc1" style="max-height: 0; overflow: hidden; transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                            <div style="padding: 0 20px 16px 54px; color: rgba(255,255,255,0.7); font-size: 0.875rem; line-height: 1.7; border-top: 1px solid rgba(255,255,255,0.05);">
                                ${exifInfo.make ? `
                                    <div style="margin-top: 12px; display: flex; gap: 8px;"><span style="color: #10b981;">✓</span><span>Camera: ${exifInfo.make} ${exifInfo.model}</span></div>
                                    ${exifInfo.software ? `<div style="display: flex; gap: 8px; margin-top: 6px;"><span style="color: #10b981;">✓</span><span>Software: ${exifInfo.software}</span></div>` : ''}
                                    <div style="display: flex; gap: 8px; margin-top: 6px;"><span style="color: #10b981;">✓</span><span>Natural lighting ${exifInfo.iso ? `(ISO ${exifInfo.iso})` : ''}</span></div>
                                ` : `
                                    <div style="margin-top: 12px; display: flex; gap: 8px;"><span style="color: #ef4444;">✗</span><span style="color: rgba(255,255,255,0.6);">No camera metadata</span></div>
                                    <div style="color: rgba(255,255,255,0.5); font-size: 0.8125rem; margin-top: 6px; margin-left: 24px;">Common for screenshots, social media, or AI images</div>
                                `}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pixel Analysis -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; margin-bottom: 8px; overflow: hidden;">
                        <div onclick="toggleAccordion('acc2')" style="padding: 16px 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <circle cx="11" cy="11" r="8" stroke="${pixelStatus === 'PASS' ? '#10b981' : '#ef4444'}" stroke-width="2"/>
                                    <path d="M21 21l-4.35-4.35" stroke="${pixelStatus === 'PASS' ? '#10b981' : '#ef4444'}" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <div style="flex: 1;">
                                    <span style="color: rgba(255,255,255,0.95); font-weight: 600; font-size: 0.9375rem;">Pixel Analysis</span>
                                    <span style="color: ${pixelStatus === 'PASS' ? '#10b981' : '#ef4444'}; font-weight: 600; font-size: 0.8125rem; margin-left: 10px;">- ${pixelStatus}</span>
                                </div>
                            </div>
                            <svg id="chevron-acc2" width="20" height="20" viewBox="0 0 20 20" fill="none" style="transition: transform 0.3s;">
                                <path d="M6 8l4 4 4-4" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div id="acc2" style="max-height: 0; overflow: hidden; transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                            <div style="padding: 0 20px 16px 54px; color: rgba(255,255,255,0.7); font-size: 0.875rem; line-height: 1.7; border-top: 1px solid rgba(255,255,255,0.05);">
                                <div style="margin-top: 12px; display: flex; gap: 8px;"><span style="color: ${pixelScore < 0 ? '#10b981' : '#ef4444'};">${pixelScore < 0 ? '✓' : '✗'}</span><span>Artifacts: ${pixelScore < 0 ? 'None detected' : 'Unusual patterns'}</span></div>
                                <div style="display: flex; gap: 8px; margin-top: 6px;"><span style="color: ${pixelScore < 0 ? '#10b981' : '#ef4444'};">${pixelScore < 0 ? '✓' : '✗'}</span><span>Lighting: ${pixelScore < 0 ? 'Natural' : 'Inconsistent'}</span></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- AI Detection -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; margin-bottom: 8px; overflow: hidden;">
                        <div onclick="toggleAccordion('acc3')" style="padding: 16px 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke="${aiStatus === 'PASS' ? '#10b981' : '#ef4444'}" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <div style="flex: 1;">
                                    <span style="color: rgba(255,255,255,0.95); font-weight: 600; font-size: 0.9375rem;">AI Detection</span>
                                    <span style="color: ${aiStatus === 'PASS' ? '#10b981' : '#ef4444'}; font-weight: 600; font-size: 0.8125rem; margin-left: 10px;">- ${aiStatus}</span>
                                </div>
                            </div>
                            <svg id="chevron-acc3" width="20" height="20" viewBox="0 0 20 20" fill="none" style="transition: transform 0.3s;">
                                <path d="M6 8l4 4 4-4" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div id="acc3" style="max-height: 0; overflow: hidden; transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                            <div style="padding: 0 20px 16px 54px; color: rgba(255,255,255,0.7); font-size: 0.875rem; line-height: 1.7; border-top: 1px solid rgba(255,255,255,0.05);">
                                <div style="margin-top: 12px; margin-bottom: 12px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span style="font-weight: 500;">AI Probability</span>
                                        <span style="font-weight: 700; color: ${parseFloat(aiProbability) > 70 ? '#ef4444' : (parseFloat(aiProbability) < 30 ? '#10b981' : '#f59e0b')};">${aiProbability}%</span>
                                    </div>
                                    <div style="background: rgba(255,255,255,0.08); height: 6px; border-radius: 3px; overflow: hidden;">
                                        <div style="background: ${parseFloat(aiProbability) > 70 ? '#ef4444' : (parseFloat(aiProbability) < 30 ? '#10b981' : '#f59e0b')}; height: 100%; width: ${aiProbability}%; transition: width 0.5s ease;"></div>
                                    </div>
                                </div>
                                <div style="color: rgba(255,255,255,0.6); font-size: 0.8125rem;">
                                    ${parseFloat(aiProbability) < 30 ? '✓ Low probability - likely authentic' : parseFloat(aiProbability) > 70 ? '✗ High probability - likely AI' : '⚠ Moderate probability'}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Summary -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; overflow: hidden;">
                        <div onclick="toggleAccordion('acc4')" style="padding: 16px 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="10" stroke="#8b5cf6" stroke-width="2"/>
                                    <path d="M12 16v-4m0-4h.01" stroke="#8b5cf6" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <span style="color: rgba(255,255,255,0.95); font-weight: 600; font-size: 0.9375rem;">Why this result?</span>
                            </div>
                            <svg id="chevron-acc4" width="20" height="20" viewBox="0 0 20 20" fill="none" style="transition: transform 0.3s;">
                                <path d="M6 8l4 4 4-4" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div id="acc4" style="max-height: 0; overflow: hidden; transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                            <div style="padding: 0 20px 16px 54px; color: rgba(255,255,255,0.7); font-size: 0.875rem; line-height: 1.8; border-top: 1px solid rgba(255,255,255,0.05);">
                                <div style="margin-top: 12px;">${whyText}</div>
                                ${conflictDetected ? `
                                    <div style="margin-top: 14px; padding: 12px; background: rgba(239,68,68,0.1); border-left: 3px solid #ef4444; border-radius: 6px;">
                                        <div style="color: #fca5a5; font-weight: 600; font-size: 0.8125rem; margin-bottom: 4px;">⚠ Conflict</div>
                                        <div style="color: rgba(255,255,255,0.7); font-size: 0.8125rem;">${conflictMessage}</div>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
                <div style="margin-bottom: 20px;">
                    <!-- Accordion 1: Metadata Analysis -->
                    <div style="background: linear-gradient(135deg, rgba(15,23,42,0.9) 0%, rgba(30,41,59,0.9) 100%); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; margin-bottom: 10px; overflow: hidden; transition: all 0.3s ease;">
                        <div onclick="toggleAccordion('acc1')" style="padding: 16px 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: background 0.2s;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <!-- SVG Icon -->
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" style="flex-shrink: 0;">
                                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="${metadataStatus === 'PASS' ? '#10b981' : '#ef4444'}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div style="flex: 1;">
                                    <span style="color: rgba(255,255,255,0.95); font-weight: 600; font-size: 0.95rem;">Metadata Analysis</span>
                                    <span style="color: ${metadataStatus === 'PASS' ? '#10b981' : '#ef4444'}; font-weight: 700; font-size: 0.85rem; margin-left: 10px;">- ${metadataStatus}</span>
                                </div>
                            </div>
                            <svg id="chevron-acc1" width="20" height="20" viewBox="0 0 20 20" fill="none" style="transition: transform 0.3s; flex-shrink: 0;">
                                <path d="M6 8l4 4 4-4" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div id="acc1" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                            <div style="padding: 0 20px 16px 56px; color: rgba(255,255,255,0.75); font-size: 0.875rem; line-height: 1.7; border-top: 1px solid rgba(255,255,255,0.05);">
                                ${exifInfo.make ? `
                                    <div style="margin-top: 12px; display: flex; align-items: start; gap: 8px;">
                                        <span style="color: #10b981; flex-shrink: 0;">✓</span>
                                        <span>Camera metadata detected: ${exifInfo.make} ${exifInfo.model}</span>
                                    </div>
                                    ${exifInfo.software ? `<div style="display: flex; align-items: start; gap: 8px; margin-top: 6px;"><span style="color: #10b981; flex-shrink: 0;">✓</span><span>Software: ${exifInfo.software}</span></div>` : ''}
                                    <div style="display: flex; align-items: start; gap: 8px; margin-top: 6px;">
                                        <span style="color: #10b981; flex-shrink: 0;">✓</span>
                                        <span>Natural camera lighting ${exifInfo.iso ? `(ISO ${exifInfo.iso})` : ''}</span>
                                    </div>
                                ` : `
                                    <div style="margin-top: 12px; display: flex; align-items: start; gap: 8px;">
                                        <span style="color: #ef4444; flex-shrink: 0;">✗</span>
                                        <span style="color: rgba(255,255,255,0.6);">No camera metadata detected</span>
                                    </div>
                                    <div style="color: rgba(255,255,255,0.5); font-size: 0.8rem; margin-top: 6px; margin-left: 24px;">Common for screenshots, social media images, or AI-generated content</div>
                                `}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Accordion 2: Pixel Analysis -->
                    <div style="background: linear-gradient(135deg, rgba(15,23,42,0.9) 0%, rgba(30,41,59,0.9) 100%); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; margin-bottom: 10px; overflow: hidden;">
                        <div onclick="toggleAccordion('acc2')" style="padding: 16px 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" style="flex-shrink: 0;">
                                    <circle cx="11" cy="11" r="8" stroke="${pixelStatus === 'PASS' ? '#10b981' : '#ef4444'}" stroke-width="2"/>
                                    <path d="M21 21l-4.35-4.35" stroke="${pixelStatus === 'PASS' ? '#10b981' : '#ef4444'}" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <div style="flex: 1;">
                                    <span style="color: rgba(255,255,255,0.95); font-weight: 600; font-size: 0.95rem;">Pixel Analysis</span>
                                    <span style="color: ${pixelStatus === 'PASS' ? '#10b981' : '#ef4444'}; font-weight: 700; font-size: 0.85rem; margin-left: 10px;">- ${pixelStatus}</span>
                                </div>
                            </div>
                            <svg id="chevron-acc2" width="20" height="20" viewBox="0 0 20 20" fill="none" style="transition: transform 0.3s;">
                                <path d="M6 8l4 4 4-4" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div id="acc2" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                            <div style="padding: 0 20px 16px 56px; color: rgba(255,255,255,0.75); font-size: 0.875rem; line-height: 1.7; border-top: 1px solid rgba(255,255,255,0.05);">
                                <div style="margin-top: 12px; display: flex; align-items: start; gap: 8px;">
                                    <span style="color: ${pixelScore < 0 ? '#10b981' : '#ef4444'}; flex-shrink: 0;">${pixelScore < 0 ? '✓' : '✗'}</span>
                                    <span>Artifact detection: ${pixelScore < 0 ? 'No common artifacts found' : 'Unusual patterns detected'}</span>
                                </div>
                                <div style="display: flex; align-items: start; gap: 8px; margin-top: 6px;">
                                    <span style="color: ${pixelScore < 0 ? '#10b981' : '#ef4444'}; flex-shrink: 0;">${pixelScore < 0 ? '✓' : '✗'}</span>
                                    <span>Shadow & lighting: ${pixelScore < 0 ? 'Natural patterns' : 'Inconsistencies found'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Accordion 3: AI Detection -->
                    <div style="background: linear-gradient(135deg, rgba(15,23,42,0.9) 0%, rgba(30,41,59,0.9) 100%); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; margin-bottom: 10px; overflow: hidden;">
                        <div onclick="toggleAccordion('acc3')" style="padding: 16px 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" style="flex-shrink: 0;">
                                    <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke="${aiStatus === 'PASS' ? '#10b981' : '#ef4444'}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div style="flex: 1;">
                                    <span style="color: rgba(255,255,255,0.95); font-weight: 600; font-size: 0.95rem;">AI Detection</span>
                                    <span style="color: ${aiStatus === 'PASS' ? '#10b981' : '#ef4444'}; font-weight: 700; font-size: 0.85rem; margin-left: 10px;">- ${aiStatus}</span>
                                </div>
                            </div>
                            <svg id="chevron-acc3" width="20" height="20" viewBox="0 0 20 20" fill="none" style="transition: transform 0.3s;">
                                <path d="M6 8l4 4 4-4" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div id="acc3" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                            <div style="padding: 0 20px 16px 56px; color: rgba(255,255,255,0.75); font-size: 0.875rem; line-height: 1.7; border-top: 1px solid rgba(255,255,255,0.05);">
                                <div style="margin-top: 12px; margin-bottom: 12px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span style="font-weight: 500;">AI Probability</span>
                                        <span style="font-weight: 700; color: ${parseFloat(aiProbability) > 70 ? '#ef4444' : (parseFloat(aiProbability) < 30 ? '#10b981' : '#f59e0b')};">${aiProbability}%</span>
                                    </div>
                                    <div style="background: rgba(255,255,255,0.08); height: 6px; border-radius: 3px; overflow: hidden;">
                                        <div style="background: ${parseFloat(aiProbability) > 70 ? '#ef4444' : (parseFloat(aiProbability) < 30 ? '#10b981' : '#f59e0b')}; height: 100%; width: ${aiProbability}%; transition: width 0.5s ease;"></div>
                                    </div>
                                </div>
                                <div style="color: rgba(255,255,255,0.6); font-size: 0.8rem;">
                                    ${parseFloat(aiProbability) < 30 ? '✓ Low probability - likely authentic' : parseFloat(aiProbability) > 70 ? '✗ High probability - likely AI-generated' : '⚠ Moderate probability - uncertain'}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Accordion 4: Why this result? -->
                    <div style="background: linear-gradient(135deg, rgba(15,23,42,0.9) 0%, rgba(30,41,59,0.9) 100%); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; overflow: hidden;">
                        <div onclick="toggleAccordion('acc4')" style="padding: 16px 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" style="flex-shrink: 0;">
                                    <circle cx="12" cy="12" r="10" stroke="#9945FF" stroke-width="2"/>
                                    <path d="M12 16v-4m0-4h.01" stroke="#9945FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span style="color: rgba(255,255,255,0.95); font-weight: 600; font-size: 0.95rem;">Why this result?</span>
                            </div>
                            <svg id="chevron-acc4" width="20" height="20" viewBox="0 0 20 20" fill="none" style="transition: transform 0.3s;">
                                <path d="M6 8l4 4 4-4" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div id="acc4" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                            <div style="padding: 0 20px 16px 56px; color: rgba(255,255,255,0.75); font-size: 0.875rem; line-height: 1.8; border-top: 1px solid rgba(255,255,255,0.05);">
                                <div style="margin-top: 12px;">${whyText}</div>
                                ${conflictDetected ? `
                                    <div style="margin-top: 14px; padding: 12px; background: rgba(239,68,68,0.1); border-left: 3px solid #ef4444; border-radius: 6px;">
                                        <div style="color: #fca5a5; font-weight: 600; font-size: 0.8rem; margin-bottom: 4px;">⚠ Conflict Detected</div>
                                        <div style="color: rgba(255,255,255,0.7); font-size: 0.8rem;">${conflictMessage}</div>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            debugContent.innerHTML = html;
            debugPanel.style.display = 'block';
        }
        
        // Accordion toggle function
        function toggleAccordion(id) {
            const content = document.getElementById(id);
            const chevron = document.getElementById('chevron-' + id);
            const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';
            
            if (isOpen) {
                content.style.maxHeight = '0';
                chevron.style.transform = 'rotate(0deg)';
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
                chevron.style.transform = 'rotate(180deg)';
            }
        }

        // --- STAGE 1: METADATA ---
        function startStage1(scenario) {
            uploadZone.style.display = 'none';
            scanState.style.display = 'block';
            resultCard.style.display = 'none'; // Ensure result is hidden

            const stageBadge = document.getElementById('stageBadge');
            const statusText = document.getElementById('scanStatus');
            const detailText = document.getElementById('scanDetail');
            
            // Set Visuals for Stage 1
            stageBadge.innerText = "STAGE 1 / 2 : METADATA";
            stageBadge.style.background = "rgba(59, 130, 246, 0.2)";
            stageBadge.style.color = "#60a5fa";
            
            statusText.innerText = "Reading Device Info...";
            detailText.innerText = "Extracting EXIF, XMP, and IPTC headers...";
            statusText.style.color = "white"; // Reset color

            // Slow pixel animation
            const anim = setInterval(() => { randomizePixels(3); }, 300);

            // Simulate Metadata Process Duration (2.0s)
            setTimeout(() => {
                clearInterval(anim);
                
                // Show Metadata Result based on Scenario
                statusText.innerText = scenario.metaStatus;
                detailText.innerText = scenario.metaDetail;
                statusText.style.color = scenario.metaColor;

                // Proceed to Stage 2
                setTimeout(() => {
                    startStage2(scenario);
                }, 2000);
                
            }, 2000);
        }

        // --- STAGE 2: AI DEEP SCAN ---
        function startStage2(scenario) {
            const stageBadge = document.getElementById('stageBadge');
            const statusText = document.getElementById('scanStatus');
            const detailText = document.getElementById('scanDetail');
            const pixelGrid = document.getElementById('pixelGrid');

            // Set Visuals for Stage 2
            stageBadge.innerText = "STAGE 2 / 2 : AI NEURAL SCAN";
            stageBadge.style.background = "rgba(147, 51, 234, 0.2)";
            stageBadge.style.color = "#d8b4fe";
            statusText.style.color = "white"; // Reset to white for scanning

            statusText.innerText = "Analyzing Pixel Patterns...";
            detailText.innerText = "Detecting generative noise & compression artifacts...";
            
            // Fast pixel animation
            pixelGrid.style.opacity = '1';
            const anim = setInterval(() => { randomizePixels(25); }, 80);

            // Simulate AI Process Duration (3.0s)
            setTimeout(() => {
                clearInterval(anim);
                showResult(scenario);
            }, 3000);
        }

        function randomizePixels(count) {
            const pixels = document.querySelectorAll('.pixel');
            pixels.forEach(p => p.classList.remove('active'));
            for(let i=0; i<count; i++) {
                const r = Math.floor(Math.random() * 100);
                pixels[r].classList.add('active');
            }
        }

        function showResult(scenario) {
            scanState.style.display = 'none';
            resultCard.style.display = 'block';
            
            const header = document.getElementById('verdictHeader');
            const icon = document.getElementById('verdictIcon');
            const title = document.getElementById('verdictTitle');
            const score = document.getElementById('verdictScore');
            
            // Populate Data from Scenario
            header.className = scenario.headerClass;
            icon.innerText = scenario.verdictIcon;
            title.innerText = scenario.title;
            score.innerText = 'Confidence: ' + scenario.confidence;

            // Animate Bars Randomly for effect
            setTimeout(() => {
                document.querySelectorAll('.ai-bar').forEach(bar => {
                    const width = scenario.isFake ? (Math.random() * 40 + 50) + '%' : (Math.random() * 10 + 5) + '%';
                    bar.style.width = width;
                    // Color Logic
                    bar.style.background = scenario.isFake ? '#ef4444' : '#10b981';
                });
            }, 100);

            // Populate Anomalies List dynamically
            const anomList = document.querySelector('.anom-list');
            anomList.innerHTML = ''; // Clear old list
            
            scenario.anomalies.forEach(text => {
                const li = document.createElement('li');
                li.innerText = text;
                // Style adjustment for Real vs Fake text
                li.style.color = scenario.isFake ? '#7f1d1d' : '#047857'; // Red vs Green text
                anomList.appendChild(li);
            });

            // Adjust Anomalies Box Style
            const anomBox = document.querySelector('.anomalies-box');
            if (scenario.isFake) {
                anomBox.style.background = '#fef2f2';
                anomBox.style.borderColor = '#fecaca';
                document.querySelector('.anom-title').innerText = "🚩 Anomalies Found";
                document.querySelector('.anom-title').style.color = "#b91c1c";
            } else {
                anomBox.style.background = '#ecfdf5';
                anomBox.style.borderColor = '#d1fae5';
                document.querySelector('.anom-title').innerText = "✔️ Validation Succesful";
                document.querySelector('.anom-title').style.color = "#059669";
            }
        }

        function resetPage(showUpload = true) {
            resultCard.style.display = 'none';
            scanState.style.display = 'none';
            document.getElementById('debugPanel').style.display = 'none';
            
            // Clean up text to defaults
            document.getElementById('scanStatus').innerText = "Initializing...";
            document.getElementById('scanStatus').style.color = "white";
            document.querySelectorAll('.ai-bar').forEach(bar => bar.style.width = '0%');
            
            if(showUpload) {
                uploadZone.style.display = 'block';
                document.getElementById('fileInput').value = '';
            }
        }
    </script>
</body>
</html>
