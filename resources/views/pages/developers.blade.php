<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developers API | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #9945FF;
            --secondary: #14F195;
            --accent: #00C2FF; 
            --text-main: #1e1e2f;
            --text-muted: #64748b;
            --glass-border: rgba(255, 255, 255, 0.5);
            --code-bg: #1e1e2e;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F7FF;
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Reusing Aurora Background */
        .ambient-bg {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -3;
            background: #ffffff; overflow: hidden;
        }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.6;
            animation: floatOrb 25s infinite ease-in-out alternate;
        }
        .orb-1 { width: 900px; height: 900px; background: #C084FC; top: -300px; right: -200px; opacity: 0.4; }
        .orb-2 { width: 700px; height: 700px; background: #2DD4BF; bottom: -200px; left: -200px; animation-duration: 35s; }
        .noise-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -2;
            opacity: 0.03; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }
        @keyframes floatOrb {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(40px, 40px) rotate(5deg); }
        }

        /* Layout */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 40px; }
        
        /* Hero Split */
        .dev-hero {
            display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;
            padding: 80px 0 100px;
        }

        .hero-text h1 {
            font-family: 'Space Grotesk', sans-serif; font-size: 3.5rem; line-height: 1.1; font-weight: 800; color: #1a1b2e; margin-bottom: 24px;
        }
        .hero-text h1 span {
            background: linear-gradient(135deg, var(--primary), #4F46E5); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .hero-text p {
            font-size: 1.1rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 32px; max-width: 500px;
        }

        .cta-group { display: flex; gap: 16px; align-items: center; }
        .btn-primary {
            background: #1a1b2e; color: white; padding: 14px 28px; border-radius: 12px;
            font-weight: 600; text-decoration: none; transition: transform 0.2s;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .btn-secondary {
            background: white; color: var(--text-main); border: 1px solid #e2e8f0; padding: 14px 28px; border-radius: 12px;
            font-weight: 600; text-decoration: none; transition: background 0.2s;
        }
        .btn-secondary:hover { background: #f8fafc; }

        /* Code Terminal Window */
        .terminal-window {
            background: var(--code-bg); border-radius: 16px; 
            box-shadow: 0 30px 60px -10px rgba(0,0,0,0.2), 0 0 0 1px rgba(255,255,255,0.1);
            overflow: hidden; transform: perspective(1000px) rotateY(-5deg) rotateX(2deg);
            transition: transform 0.4s ease;
        }
        .terminal-window:hover { transform: perspective(1000px) rotateY(0deg) rotateX(0deg); }
        
        .terminal-header {
            background: rgba(255,255,255,0.05); padding: 12px 16px; display: flex; align-items: center; gap: 8px;
        }
        .dot { width: 10px; height: 10px; border-radius: 50%; }
        .dot.red { background: #ff5f56; }
        .dot.yellow { background: #ffbd2e; }
        .dot.green { background: #27c93f; }
        .terminal-title { margin-left: auto; margin-right: auto; color: rgba(255,255,255,0.4); font-size: 0.8rem; font-family: monospace; }
        
        .terminal-body { padding: 24px; font-family: 'Fira Code', monospace; font-size: 0.9rem; color: #a9b1d6; line-height: 1.6; }
        .code-line { display: block; margin-bottom: 4px; }
        .c-keyword { color: #bb9af7; } /* purple */
        .c-func { color: #7aa2f7; } /* blue */
        .c-str { color: #9ece6a; } /* green */
        .c-comment { color: #565f89; font-style: italic; }

        /* API Features Grid */
        .api-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 80px; }
        .api-card {
            background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px;
            transition: all 0.3s; cursor: pointer;
        }
        .api-card:hover { border-color: var(--primary); transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }
        .api-card:active { transform: translateY(-3px) scale(0.99); transition: all 0.1s; }
        .api-method { 
            display: inline-block; background: #f0fdf4; color: #16a34a; 
            font-size: 0.75rem; font-weight: 700; padding: 4px 8px; border-radius: 6px; margin-bottom: 12px; border: 1px solid #dcfce7;
        }
        .api-card h3 { font-family: 'Space Grotesk'; font-size: 1.2rem; margin-bottom: 8px; }
        .api-card p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 16px; }
        .api-link { font-size: 0.9rem; color: var(--primary); font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 4px; }
        .api-link:hover { text-decoration: underline; }

        @media (max-width: 900px) {
            .dev-hero { grid-template-columns: 1fr; text-align: center; }
            .terminal-window { display: none; } /* Hide complicated visual on mobile for now */
            .hero-text p { margin-left: auto; margin-right: auto; }
            .cta-group { justify-content: center; }
            .api-grid { grid-template-columns: 1fr; }
        }

        /* Modal Styles */
        .modal-backdrop {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000;
            background: rgba(11, 11, 21, 0.8); backdrop-filter: blur(8px);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
        }
        .modal-backdrop.active { opacity: 1; pointer-events: all; }
        
        .modal-glass {
            background: #fff; width: 90%; max-width: 650px;
            border-radius: 24px; padding: 40px; position: relative;
            box-shadow: 0 50px 100px -20px rgba(0,0,0,0.3);
            transform: scale(0.95) translateY(20px); transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            max-height: 90vh; overflow-y: auto;
        }
        .modal-backdrop.active .modal-glass { transform: scale(1) translateY(0); }

        .close-modal {
            position: absolute; top: 24px; right: 24px; padding: 8px 16px;
            background: #f1f5f9; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; color: #64748b;
        }
        .close-modal:hover { background: #e2e8f0; color: #ef4444; }

        .modal-title { font-family: 'Space Grotesk'; font-size: 1.5rem; margin-bottom: 8px; color: #1e1e2f; }
        .modal-desc { color: var(--text-muted); margin-bottom: 24px; }
        
        .code-block {
            background: #1e1e2e; padding: 20px; border-radius: 12px; font-family: 'Fira Code', monospace; font-size: 0.85rem; color: #a9b1d6; margin-bottom: 16px;
            overflow-x: auto;
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
        
        <!-- Hero Section -->
        <div class="dev-hero">
            <div class="hero-text">
                <h1 id="dev-hero-title">Integrate Trust <br><span>Into Your App</span></h1>
                <p id="dev-hero-desc">Powerful REST APIs for deepfake detection and media forensics. Built for scale, security, and mili-second latency.</p>
                <div class="cta-group">
                    <a href="{{ route('dashboard') }}" class="btn-primary" id="btn-get-key">
                        Get API Key 
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12h-6M12 5l7 7-7 7"/></svg>
                    </a>
                    <a href="#endpoints-title" class="btn-secondary" id="btn-docs">Read Docs</a>
                </div>
            </div>

            <!-- Terminal Visual -->
            <div class="terminal-window">
                <div class="terminal-header">
                    <div class="dot red"></div><div class="dot yellow"></div><div class="dot green"></div>
                    <div class="terminal-title">curl_request.sh</div>
                </div>
                <div class="terminal-body">
                    <span class="code-line"><span class="c-comment"># Analyze a video for manipulation</span></span>
                    <span class="code-line"><span class="c-keyword">curl</span> -X POST \</span>
                    <span class="code-line">  https://api.privasi.ai/v1/detect/video \</span>
                    <span class="code-line">  -H <span class="c-str">"Authorization: Bearer sk_live_..."</span> \</span>
                    <span class="code-line">  -F <span class="c-str">"file=@suspect_video.mp4"</span></span>
                    <br>
                    <span class="code-line"><span class="c-comment"># Response</span></span>
                    <span class="code-line">{</span>
                    <span class="code-line">  <span class="c-str">"authenticity_score"</span>: <span class="c-func">0.02</span>,</span>
                    <span class="code-line">  <span class="c-str">"verdict"</span>: <span class="c-str">"DEEPFAKE"</span>,</span>
                    <span class="code-line">  <span class="c-str">"confidence"</span>: <span class="c-func">99.8</span></span>
                    <span class="code-line">}</span>
                </div>
            </div>
        </div>

        <!-- API Capabilities -->
        <!-- API Capabilities -->
        <h2 id="endpoints-title" style="font-family: 'Space Grotesk'; font-size: 1.5rem; margin-bottom: 24px; color: #1e1e2f;">Available Endpoints</h2>
        <div class="api-grid">
            <div class="api-card" onclick="openModal('video')">
                <span class="api-method">POST</span>
                <h3>/v1/detect/video</h3>
                <p id="ep-video-desc">Frame-by-frame analysis for temporal inconsistencies and face swap artifacts.</p>
                <a href="javascript:void(0)" class="api-link" id="ep-video-link">View Docs →</a>
            </div>
            <div class="api-card" onclick="openModal('image')">
                <span class="api-method">POST</span>
                <h3>/v1/detect/image</h3>
                <p id="ep-image-desc">ELA and noise analysis to detect generative fill and pixel manipulation.</p>
                <a href="javascript:void(0)" class="api-link" id="ep-image-link">View Docs →</a>
            </div>
            <div class="api-card" onclick="openModal('audio')">
                <span class="api-method">POST</span>
                <h3>/v1/detect/audio</h3>
                <p id="ep-audio-desc">Spectral voice analysis to identify synthetic speech patterns.</p>
                <a href="javascript:void(0)" class="api-link" id="ep-audio-link">View Docs →</a>
            </div>
        </div>

        <!-- MODAL SYSTEM (Ported & Adapted) -->
        <div class="modal-backdrop" id="techModal" onclick="closeModal(event)">
            <div class="modal-glass">
                <button class="close-modal" onclick="closeModal(event)">Close</button>
                
                <!-- Video API Modal -->
                <div id="modal-content-video" class="modal-body" style="display: none;">
                    <div class="terminal-header" style="margin-bottom: 20px; border-radius: 8px;">
                        <div class="dot red"></div><div class="dot yellow"></div><div class="dot green"></div>
                        <div class="terminal-title">POST /v1/detect/video</div>
                    </div>
                    <h3 class="modal-title">Video Forensics API</h3>
                    <p class="modal-desc">Detects deepfakes using frame-by-frame analysis.</p>
                    
                    <div class="code-block">
<span class="c-comment"># Request Example</span>
<span class="c-keyword">curl</span> -X POST https://api.privasi.ai/v1/detect/video \
  -H <span class="c-str">"Authorization: Bearer sk_test_..."</span> \
  -F <span class="c-str">"file=@video.mp4"</span>
                    </div>

                    <div class="code-block">
<span class="c-comment"># Response (200 OK)</span>
{
  <span class="c-str">"id"</span>: <span class="c-str">"req_123xyz"</span>,
  <span class="c-str">"verdict"</span>: <span class="c-str">"FAKE"</span>,
  <span class="c-str">"confidence"</span>: <span class="c-func">0.998</span>,
  <span class="c-str">"artifacts"</span>: [<span class="c-str">"temporal_jitter"</span>, <span class="c-str">"face_warping"</span>]
}
                    </div>
                </div>

                <!-- Image API Modal -->
                <div id="modal-content-image" class="modal-body" style="display: none;">
                    <div class="terminal-header" style="margin-bottom: 20px; border-radius: 8px;">
                        <div class="dot red"></div><div class="dot yellow"></div><div class="dot green"></div>
                        <div class="terminal-title">POST /v1/detect/image</div>
                    </div>
                    <h3 class="modal-title">Deep Pixel API</h3>
                    <p class="modal-desc">Analyzes distinctive noise patterns and ELA.</p>
                    
                    <div class="code-block">
<span class="c-comment"># Request Example</span>
<span class="c-keyword">curl</span> -X POST https://api.privasi.ai/v1/detect/image \
  -H <span class="c-str">"Authorization: Bearer sk_test_..."</span> \
  -F <span class="c-str">"file=@image.jpg"</span>
                    </div>

                    <div class="code-block">
<span class="c-comment"># Response (200 OK)</span>
{
  <span class="c-str">"id"</span>: <span class="c-str">"req_img_999"</span>,
  <span class="c-str">"verdict"</span>: <span class="c-str">"REAL"</span>,
  <span class="c-str">"analysis"</span>: {
     <span class="c-str">"ela_score"</span>: <span class="c-func">0.05</span>,
     <span class="c-str">"metadata_integrity"</span>: <span class="c-func">true</span>
  }
}
                    </div>
                </div>

                <!-- Audio API Modal -->
                <div id="modal-content-audio" class="modal-body" style="display: none;">
                    <div class="terminal-header" style="margin-bottom: 20px; border-radius: 8px;">
                        <div class="dot red"></div><div class="dot yellow"></div><div class="dot green"></div>
                        <div class="terminal-title">POST /v1/detect/audio</div>
                    </div>
                    <h3 class="modal-title">Voice Analysis API</h3>
                    <p class="modal-desc">Identifies synthetic speech patterns.</p>
                    
                    <div class="code-block">
<span class="c-comment"># Request Example</span>
<span class="c-keyword">curl</span> -X POST https://api.privasi.ai/v1/detect/audio \
  -H <span class="c-str">"Authorization: Bearer sk_test_..."</span> \
  -F <span class="c-str">"file=@voice.wav"</span>
                    </div>

                    <div class="code-block">
<span class="c-comment"># Response (200 OK)</span>
{
  <span class="c-str">"id"</span>: <span class="c-str">"req_aud_777"</span>,
  <span class="c-str">"verdict"</span>: <span class="c-str">"SYNTHETIC"</span>,
  <span class="c-str">"voice_match"</span>: <span class="c-str">"elevenlabs_v2"</span>
}
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        const translations = {
            en: {
                nav: { dash: "Dashboard", tech: "Technology", dev: "Developers", price: "Pricing", supp: "Support" },
                hero: {
                    title: "Integrate Trust <br><span>Into Your App</span>",
                    desc: "Powerful REST APIs for deepfake detection and media forensics. Built for scale, security, and mili-second latency.",
                    btnKey: "Get API Key",
                    btnDocs: "Read Docs"
                },
                ep: {
                    title: "Available Endpoints",
                    video: "Frame-by-frame analysis for temporal inconsistencies and face swap artifacts.",
                    image: "ELA and noise analysis to detect generative fill and pixel manipulation.",
                    audio: "Spectral voice analysis to identify synthetic speech patterns.",
                    link: "View Docs →"
                },
                modal: {
                    video: { title: "Video Forensics API", desc: "Detects deepfakes using frame-by-frame analysis.", req: "Request Example", res: "Response (200 OK)" },
                    image: { title: "Deep Pixel API", desc: "Analyzes distinctive noise patterns and ELA.", req: "Request Example", res: "Response (200 OK)" },
                    audio: { title: "Voice Analysis API", desc: "Identifies synthetic speech patterns.", req: "Request Example", res: "Response (200 OK)" }
                }
            },
            id: {
                nav: { dash: "Dasbor", tech: "Teknologi", dev: "Developers", price: "Harga", supp: "Bantuan" },
                hero: {
                    title: "Integrasi Kepercayaan <br><span>Ke Aplikasi Anda</span>",
                    desc: "REST API canggih untuk deteksi deepfake dan forensik media. Dibangun untuk skala besar, keamanan, dan latensi mili-detik.",
                    btnKey: "Dapatkan API Key",
                    btnDocs: "Baca Dokumen"
                },
                ep: {
                    title: "Endpoint Tersedia",
                    video: "Analisis frame-by-frame untuk inkonsistensi temporal dan artefak face swap.",
                    image: "Analisis ELA dan noise untuk mendeteksi generative fill dan manipulasi piksel.",
                    audio: "Analisis spektral suara untuk mengidentifikasi pola bicara sintetis.",
                    link: "Lihat Dokumen →"
                },
                modal: {
                    video: { title: "API Forensik Video", desc: "Mendeteksi deepfake menggunakan analisis frame-by-frame.", req: "Contoh Request", res: "Respons (200 OK)" },
                    image: { title: "API Deep Pixel", desc: "Menganalisis pola noise khas dan ELA.", req: "Contoh Request", res: "Respons (200 OK)" },
                    audio: { title: "API Analisis Suara", desc: "Mengidentifikasi pola bicara sintetis.", req: "Contoh Request", res: "Respons (200 OK)" }
                }
            },
            es: {
                nav: { dash: "Tablero", tech: "Tecnología", dev: "Desarrolladores", price: "Precios", supp: "Ayuda" },
                hero: {
                    title: "Integra Confianza <br><span>En Tu App</span>",
                    desc: "APIs REST potentes para detección de deepfakes y forense de medios. Escala, seguridad y latencia de milisegundos.",
                    btnKey: "Obtener Clave API",
                    btnDocs: "Leer Documentos"
                },
                ep: {
                    title: "Endpoints Disponibles",
                    video: "Análisis cuadro por cuadro para inconsistencias temporales.",
                    image: "Análisis ELA y ruido para detectar relleno generativo.",
                    audio: "Análisis espectral de voz para identificar patrones sintéticos.",
                    link: "Ver Docs →"
                },
                modal: {
                    video: { title: "API Forense de Video", desc: "Detecta deepfakes usando análisis cuadro por cuadro.", req: "Ejemplo de Solicitud", res: "Respuesta (200 OK)" },
                    image: { title: "API Deep Pixel", desc: "Analiza patrones de ruido distintivos y ELA.", req: "Ejemplo de Solicitud", res: "Respuesta (200 OK)" },
                    audio: { title: "API de Análisis de Voz", desc: "Identifica patrones de voz sintéticos.", req: "Ejemplo de Solicitud", res: "Respuesta (200 OK)" }
                }
            },
            fr: {
                nav: { dash: "Tableau de bord", tech: "Technologies", dev: "Développeurs", price: "Tarifs", supp: "Support" },
                hero: {
                    title: "Intégrez la Confiance <br><span>Dans Votre App</span>",
                    desc: "APIs REST puissantes pour la détection de deepfakes. Conçu pour l'échelle et la sécurité.",
                    btnKey: "Obtenir Clé API",
                    btnDocs: "Lire la Documentation"
                },
                ep: {
                    title: "Endpoints Disponibles",
                    video: "Analyse image par image pour les incohérences temporelles.",
                    image: "Analyse ELA et bruit pour détecter le remplissage génératif.",
                    audio: "Analyse spectrale de la voix pour identifier les motifs synthétiques.",
                    link: "Voir Docs →"
                },
                modal: {
                    video: { title: "API Forensique Vidéo", desc: "Détecte les deepfakes grâce à l'analyse image par image.", req: "Exemple de Requête", res: "Réponse (200 OK)" },
                    image: { title: "API Deep Pixel", desc: "Analyse les modèles de bruit distinctifs et ELA.", req: "Exemple de Requête", res: "Réponse (200 OK)" },
                    audio: { title: "API d'Analyse Vocale", desc: "Identifie les modèles de parole synthétiques.", req: "Exemple de Requête", res: "Réponse (200 OK)" }
                }
            },
            de: {
                nav: { dash: "Dashboard", tech: "Technologien", dev: "Entwickler", price: "Preise", supp: "Support" },
                hero: {
                    title: "Integrieren Sie Vertrauen <br><span>In Ihre App</span>",
                    desc: "Leistungsstarke REST-APIs zur Deepfake-Erkennung. Gebaut für Skalierung und Sicherheit.",
                    btnKey: "API-Schlüssel erhalten",
                    btnDocs: "Dokumentation lesen"
                },
                ep: {
                    title: "Verfügbare Endpunkte",
                    video: "Bild-für-Bild-Analyse auf zeitliche Inkonsistenzen.",
                    image: "ELA- und Rauschanalyse zur Erkennung generativer Füllungen.",
                    audio: "Spektrale Stimmanalyse zur Identifizierung synthetischer Muster.",
                    link: "Docs ansehen →"
                },
                modal: {
                    video: { title: "Video-Forensik-API", desc: "Erkennt Deepfakes mittels Bild-für-Bild-Analyse.", req: "Anfragebeispiel", res: "Antwort (200 OK)" },
                    image: { title: "Deep Pixel API", desc: "Analysiert charakteristische Rauschmuster und ELA.", req: "Anfragebeispiel", res: "Antwort (200 OK)" },
                    audio: { title: "Sprachanalyse-API", desc: "Identifiziert synthetische Sprachmuster.", req: "Anfragebeispiel", res: "Antwort (200 OK)" }
                }
            },
            jp: {
                nav: { dash: "ダッシュボード", tech: "技術", dev: "開発者", price: "価格", supp: "サポート" },
                hero: {
                    title: "信頼をアプリに<br><span>統合する</span>",
                    desc: "ディープフェイク検出とメディアフォレンジックのための強力なREST API。スケール、セキュリティ、ミリ秒単位のレイテンシを実現。",
                    btnKey: "APIキーを取得",
                    btnDocs: "ドキュメントを読む"
                },
                ep: {
                    title: "利用可能なエンドポイント",
                    video: "一時的な不整合や顔の入れ替えアーティファクトのフレームごとの分析。",
                    image: "生成的な塗りつぶしやピクセル操作を検出するためのELAおよびノイズ分析。",
                    audio: "合成音声パターンを特定するためのスペクトル音声分析。",
                    link: "ドキュメントを見る →"
                },
                modal: {
                    video: { title: "ビデオフォレンジックAPI", desc: "フレームごとの分析を使用してディープフェイクを検出します。", req: "リクエスト例", res: "レスポンス (200 OK)" },
                    image: { title: "Deep Pixel API", desc: "特徴的なノイズパターンとELAを分析します。", req: "リクエスト例", res: "レスポンス (200 OK)" },
                    audio: { title: "音声分析API", desc: "合成音声パターンを特定します。", req: "リクエスト例", res: "レスポンス (200 OK)" }
                }
            },
            cn: {
                nav: { dash: "仪表板", tech: "技术", dev: "开发者", price: "价格", supp: "支持" },
                hero: {
                    title: "将信任集成<br><span>到您的应用中</span>",
                    desc: "用于Deepfake检测和媒体取证的强大REST API。专为规模、安全性和毫秒级延迟而构建。",
                    btnKey: "获取API密钥",
                    btnDocs: "阅读文档"
                },
                ep: {
                    title: "可用端点",
                    video: "针对时间不一致和换脸伪影的逐帧分析。",
                    image: "用于检测生成填充和像素操作的ELA和噪声分析。",
                    audio: "用于识别合成语音模式的频谱语音分析。",
                    link: "查看文档 →"
                },
                modal: {
                    video: { title: "视频取证API", desc: "使用逐帧分析检测Deepfake。", req: "请求示例", res: "响应 (200 OK)" },
                    image: { title: "Deep Pixel API", desc: "分析独特的噪声模式和ELA。", req: "请求示例", res: "响应 (200 OK)" },
                    audio: { title: "语音分析API", desc: "识别合成语音模式。", req: "请求示例", res: "响应 (200 OK)" }
                }
            },
            ru: {
                nav: { dash: "Дашборд", tech: "Технологии", dev: "Разработчики", price: "Цены", supp: "Поддержка" },
                hero: {
                    title: "Интегрируйте доверие <br><span>В ваше приложение</span>",
                    desc: "Мощные REST API для обнаружения дипфейков. Создано для масштабируемости и безопасности.",
                    btnKey: "Получить ключ API",
                    btnDocs: "Читать документацию"
                },
                ep: {
                    title: "Доступные эндпоинты",
                    video: "Покадровый анализ для выявления временных несоответствий.",
                    image: "Анализ ELA и шума для обнаружения генеративного заполнения.",
                    audio: "Спектральный анализ голоса для идентификации синтетических паттернов.",
                    link: "См. документы →"
                },
                modal: {
                    video: { title: "API Видеокриминалистики", desc: "Обнаруживает дипфейки с помощью покадрового анализа.", req: "Пример запроса", res: "Ответ (200 OK)" },
                    image: { title: "API Deep Pixel", desc: "Анализирует характерные шумы и ELA.", req: "Пример запроса", res: "Ответ (200 OK)" },
                    audio: { title: "API Анализа Голоса", desc: "Определяет синтетические речевые паттерны.", req: "Пример запроса", res: "Ответ (200 OK)" }
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

            // Hero
            if(document.getElementById('dev-hero-title')) document.getElementById('dev-hero-title').innerHTML = t.hero.title;
            if(document.getElementById('dev-hero-desc')) document.getElementById('dev-hero-desc').innerText = t.hero.desc;
            if(document.getElementById('btn-get-key')) {
                document.getElementById('btn-get-key').innerHTML = t.hero.btnKey + ` <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12h-6M12 5l7 7-7 7"/></svg>`;
            }
            if(document.getElementById('btn-docs')) document.getElementById('btn-docs').innerText = t.hero.btnDocs;

            // Endpoints
            if(document.getElementById('endpoints-title')) document.getElementById('endpoints-title').innerText = t.ep.title;
            
            if(document.getElementById('ep-video-desc')) document.getElementById('ep-video-desc').innerText = t.ep.video;
            if(document.getElementById('ep-image-desc')) document.getElementById('ep-image-desc').innerText = t.ep.image;
            if(document.getElementById('ep-audio-desc')) document.getElementById('ep-audio-desc').innerText = t.ep.audio;

            const links = document.querySelectorAll('.api-link');
            links.forEach(l => l.innerText = t.ep.link);

            // Modal Translations
            if(t.modal) {
                // Video Modal
                if(document.querySelector('#modal-content-video .modal-title')) document.querySelector('#modal-content-video .modal-title').innerText = t.modal.video.title;
                if(document.querySelector('#modal-content-video .modal-desc')) document.querySelector('#modal-content-video .modal-desc').innerText = t.modal.video.desc;

                // Image Modal
                if(document.querySelector('#modal-content-image .modal-title')) document.querySelector('#modal-content-image .modal-title').innerText = t.modal.image.title;
                if(document.querySelector('#modal-content-image .modal-desc')) document.querySelector('#modal-content-image .modal-desc').innerText = t.modal.image.desc;

                // Audio Modal
                if(document.querySelector('#modal-content-audio .modal-title')) document.querySelector('#modal-content-audio .modal-title').innerText = t.modal.audio.title;
                if(document.querySelector('#modal-content-audio .modal-desc')) document.querySelector('#modal-content-audio .modal-desc').innerText = t.modal.audio.desc;
            }
        }

        function openModal(type) {
            const modal = document.getElementById('techModal');
            const contents = document.querySelectorAll('.modal-body');
            contents.forEach(el => el.style.display = 'none');
            
            const target = document.getElementById('modal-content-' + type);
            if(target) target.style.display = 'block';

            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Basic scroll lock
        }

        function closeModal(e) {
            if (e.target === document.getElementById('techModal') || e.target.classList.contains('close-modal') || e.target.tagName === 'BUTTON') {
                document.getElementById('techModal').classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Check LocalStorage set by Dashboard
            const savedLang = localStorage.getItem('privasi_lang') || 'en';
            applyLang(savedLang);
        });
    </script>
</body>
</html>
