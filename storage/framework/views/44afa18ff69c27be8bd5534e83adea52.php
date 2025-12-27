<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #9945FF;
            --secondary: #14F195;
            --accent: #00C2FF; 
            --text-main: #1e1e2f;
            --text-muted: #64748b;
            --glass-border: rgba(255, 255, 255, 0.5);
            --glass-bg: rgba(255, 255, 255, 0.65);
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
            width: 800px; height: 800px; background: #C084FC; /* Stronger Purple */
            top: -200px; right: -200px; animation-duration: 25s;
        }
        .orb-2 {
            width: 600px; height: 600px; background: #2DD4BF; /* Stronger Teal */
            bottom: -100px; left: -100px; animation-duration: 30s; animation-direction: alternate-reverse;
        }
        .orb-3 {
            width: 500px; height: 500px; background: #38BDF8; /* Stronger Blue */
            top: 40%; left: 40%; transform: translate(-50%, -50%);
            animation-name: pulseOrb; animation-duration: 15s;
        }

        /* Subtle Noise Texture */
        .noise-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -2;
            opacity: 0.03; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        @keyframes floatOrb {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(50px, 50px) rotate(10deg); }
        }
        @keyframes pulseOrb {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 0.4; }
            50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.6; }
            100% { transform: translate(-50%, -50%) scale(1); opacity: 0.4; }
        }

        /* Navbar styles removed (using component) */

        /* Hero */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 40px; }
        .hero { padding: 80px 0 60px; text-align: center; }
        .hero h1 {
            font-family: 'Space Grotesk', sans-serif; font-size: 4rem; line-height: 1; font-weight: 800; color: #1a1b2e; margin-bottom: 24px;
            background: linear-gradient(135deg, #1a1b2e, #4338ca); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .hero span { color: var(--primary); -webkit-text-fill-color: var(--primary); background: none; }
        .hero p { font-size: 1.25rem; color: var(--text-muted); max-width: 700px; margin: 0 auto; line-height: 1.6; }

        /* 3-Column Grid Cards (Enhanced) */
        .services-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 100px;
        }
        
        .service-card {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 24px; padding: 32px;
            text-align: center; transition: all 0.4s ease;
            position: relative; overflow: hidden; backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.03), inset 0 0 20px rgba(255,255,255,0.5);
            display: flex; flex-direction: column; height: 100%;
        }

        /* Hover Effects & Active State */
        .service-card:hover {
            transform: translateY(-12px);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1);
        }
        .service-card:active {
            transform: translateY(-6px) scale(0.98);
            transition: all 0.1s;
        }

        /* Accent Colors per Card Type */
        .card-video { --card-accent: #9945FF; }
        .card-image { --card-accent: #14F195; }
        .card-audio { --card-accent: #00C2FF; }

        .service-card:hover { border-color: var(--card-accent); }

        /* Internal Glow Blob */
        .card-blob {
            position: absolute; width: 200px; height: 200px; border-radius: 50%;
            background: var(--card-accent); filter: blur(80px); opacity: 0;
            top: -100px; right: -100px; transition: opacity 0.5s ease; z-index: 0;
            pointer-events: none;
        }
        .service-card:hover .card-blob { opacity: 0.15; }

        /* Decorative Corners (Tech Feel) */
        .close-modal {
            position: absolute; top: 32px; right: 32px; left: auto; /* Moved to Right */
            background: transparent; border: 1px solid #e2e8f0;
            padding: 8px 16px; border-radius: 20px; 
            display: flex; align-items: center; gap: 8px;
            font-family: 'Space Grotesk', sans-serif; font-size: 0.9rem; font-weight: 500; color: #64748b;
            cursor: pointer; transition: all 0.2s; z-index: 20;
        }
        .close-modal:hover { background: #fee2e2; border-color: #fecaca; color: #ef4444; padding-right: 20px; }
        .close-modal svg { width: 16px; height: 16px; transition: transform 0.2s; }
        .close-modal:hover svg { transform: rotate(90deg); }
        .card-corner {
            position: absolute; width: 20px; height: 20px; z-index: 1;
            border-color: var(--card-accent); border-style: solid; opacity: 0; transition: all 0.3s;
        }
        .card-corner.tl { top: 16px; left: 16px; border-width: 2px 0 0 2px; border-radius: 4px 0 0 0; transform: translate(5px, 5px); }
        .card-corner.tr { top: 16px; right: 16px; border-width: 2px 2px 0 0; border-radius: 0 4px 0 0; transform: translate(-5px, 5px); }
        .card-corner.bl { bottom: 16px; left: 16px; border-width: 0 0 2px 2px; border-radius: 0 0 0 4px; transform: translate(5px, -5px); }
        .card-corner.br { bottom: 16px; right: 16px; border-width: 0 2px 2px 0; border-radius: 0 0 4px 0; transform: translate(-5px, -5px); }

        .service-card:hover .card-corner { opacity: 1; transform: translate(0, 0); }

        /* Icon Circle */
        .icon-wrapper {
            width: 80px; height: 80px; margin: 0 auto 24px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 2.5rem;
            position: relative; background: #fff; z-index: 1;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.03);
            transition: transform 0.3s;
        }
        .service-card:hover .icon-wrapper { transform: scale(1.1) rotate(5deg); }
        
        /* Content z-index adjustment */
        .service-card h3, .service-card p, .specs-mini { position: relative; z-index: 1; }

        .service-card h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; color: #1e1e2f; margin-bottom: 12px; font-weight: 700; }
        .service-card p { color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 24px; flex-grow: 1; }

        /* Mini Specs List inside Card */
        .specs-mini { text-align: left; background: rgba(255,255,255,0.5); padding: 16px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.6); }
        .specs-mini li { 
            list-style: none; display: flex; align-items: center; gap: 8px; 
            font-size: 0.85rem; color: #475569; margin-bottom: 8px; font-weight: 500;
        }
        .specs-mini li:last-child { margin-bottom: 0; }
        .specs-mini li::before { content: '•'; color: var(--secondary); font-size: 1.2rem; line-height: 0; }

        /* CTA */
        .cta-box {
            background: linear-gradient(135deg, var(--primary), #4F46E5);
            border-radius: 32px; padding: 60px; text-align: center; position: relative; overflow: hidden;
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.2);
        }
        .cta-btn {
            background: #fff; color: var(--primary); padding: 16px 40px; border-radius: 100px;
            text-decoration: none; font-weight: 700; display: inline-block; margin-top: 24px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1); transition: transform 0.2s;
        }
        .cta-btn:hover { transform: scale(1.05); }

    </style>
</head>
<body>

    <div class="ambient-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>
    <div class="noise-overlay"></div>

    <!-- NAVBAR -->
    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container">
        
        <div class="hero">
            <h1 id="page-title">Advanced <br><span>Verification</span> Tech</h1>
            <p id="page-desc">Secure, Private, and 99.8% Accurate. Our AI models analyze content invisible to the naked eye.</p>
        </div>

        <div class="services-grid">
            <!-- Card 1: Video -->
            <div class="service-card card-video" onclick="openModal('video')" style="cursor: pointer;">
                <div class="card-blob"></div>
                <div class="card-corner tl"></div><div class="card-corner tr"></div>
                <div class="card-corner bl"></div><div class="card-corner br"></div>
                
                <div class="icon-wrapper">
                    <div class="bg-glow" style="background: var(--card-accent);"></div>
                    <!-- Custom SVG: Video Lens -->
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 10L19.5528 7.72361C19.8153 7.59238 20.0899 7.82287 20.0263 8.10926L19.3496 11.1542C19.1672 11.9752 19.1672 12.8315 19.3496 13.6525L20.0263 16.6974C20.0899 16.9838 19.8153 17.2143 19.5528 17.0831L15 14.8066" stroke="url(#gradVideo)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="2" y="6" width="13" height="12" rx="3" stroke="url(#gradVideo)" stroke-width="2"/>
                        <circle cx="8.5" cy="12" r="2" fill="url(#gradVideo)"/>
                        <defs>
                            <linearGradient id="gradVideo" x1="0" y1="0" x2="24" y2="24" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#9945FF"/>
                                <stop offset="1" stop-color="#7C3AED"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <h3 id="s1-title">Video Forensics</h3>
                <p id="s1-desc">Frame-by-frame analysis to detect deepfake inconsistencies and temporal anomalies.</p>
                <ul class="specs-mini">
                    <li id="s1-t1">Frame Inspection</li>
                    <li id="s1-t2">Face Tracking</li>
                    <li id="s1-t3">Light Consistency</li>
                </ul>
            </div>

            <!-- Card 2: Image -->
            <div class="service-card card-image" onclick="openModal('image')" style="cursor: pointer;">
                <div class="card-blob"></div>
                <div class="card-corner tl"></div><div class="card-corner tr"></div>
                <div class="card-corner bl"></div><div class="card-corner br"></div>

                <div class="icon-wrapper">
                    <div class="bg-glow" style="background: var(--card-accent);"></div>
                    <!-- Custom SVG: Image Layers -->
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 9L13.1112 11.6663C12.8795 12.3615 11.8841 12.3828 11.6234 11.698L11 10.0606" stroke="url(#gradImage)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="3" y="5" width="18" height="14" rx="3" stroke="url(#gradImage)" stroke-width="2"/>
                        <circle cx="8" cy="9" r="1.5" fill="url(#gradImage)"/>
                        <path d="M3 16L7 13L10 16L13 13L18 17" stroke="url(#gradImage)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <defs>
                            <linearGradient id="gradImage" x1="0" y1="0" x2="24" y2="24" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#14F195"/>
                                <stop offset="1" stop-color="#10B981"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <h3 id="s2-title">Deep Pixel Scan</h3>
                <p id="s2-desc">Detects generative fill, metadata tampering, and noise patterns in images.</p>
                <ul class="specs-mini">
                    <li id="s2-t1">ELA Analysis</li>
                    <li id="s2-t2">Noise Pattern</li>
                    <li id="s2-t3">Metadata Check</li>
                </ul>
            </div>

            <!-- Card 3: Audio -->
            <div class="service-card card-audio" onclick="openModal('audio')" style="cursor: pointer;">
                <div class="card-blob"></div>
                <div class="card-corner tl"></div><div class="card-corner tr"></div>
                <div class="card-corner bl"></div><div class="card-corner br"></div>

                <div class="icon-wrapper">
                    <div class="bg-glow" style="background: var(--card-accent);"></div>
                    <!-- Custom SVG: Audio Wave -->
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4V20M16 7V17M8 7V17M4 11V13M20 11V13" stroke="url(#gradAudio)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <defs>
                            <linearGradient id="gradAudio" x1="0" y1="0" x2="24" y2="24" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#00C2FF"/>
                                <stop offset="1" stop-color="#3B82F6"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <h3 id="s3-title">Voice Detection</h3>
                <p id="s3-desc">Identifies synthetic voice frequencies and lack of natural micro-tremors.</p>
                <ul class="specs-mini">
                    <li id="s3-t1">Spectral Analysis</li>
                    <li id="s3-t2">Breath Pattern</li>
                    <li id="s3-t3">Replay Detect</li>
                </ul>
            </div>
        </div>

        <div class="cta-box">
            <h2 id="cta-title" style="color:white; font-family: 'Space Grotesk'; font-size: 2.5rem; margin-bottom: 16px;">Ready to Integrate?</h2>
            <p id="cta-desc" style="color: #cbd5e1; max-width: 500px; margin: 0 auto;">Join developers building the future of content authenticity.</p>
            <a href="<?php echo e(route('developers')); ?>" class="cta-btn" id="cta-btn">Get API Access</a>
        </div>

    </div>

    <!-- TECH MODAL SYSTEM -->
    <div class="modal-backdrop" id="techModal" onclick="closeModal(event)">
        <div class="modal-glass">
            <button class="close-modal" onclick="closeModal(event)">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="w-4 h-4" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Close
            </button>
            
            <!-- Video Content -->
            <div id="modal-content-video" class="modal-body" style="display: none;">
                <div class="modal-header">
                    <div class="modal-icon">
                        <!-- Custom SVG: Video Lens (Matching Main Card) -->
                        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 10L19.5528 7.72361C19.8153 7.59238 20.0899 7.82287 20.0263 8.10926L19.3496 11.1542C19.1672 11.9752 19.1672 12.8315 19.3496 13.6525L20.0263 16.6974C20.0899 16.9838 19.8153 17.2143 19.5528 17.0831L15 14.8066" stroke="url(#gradVideoModal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <rect x="2" y="6" width="13" height="12" rx="3" stroke="url(#gradVideoModal)" stroke-width="2"/>
                            <circle cx="8.5" cy="12" r="2" fill="url(#gradVideoModal)"/>
                            <defs>
                                <linearGradient id="gradVideoModal" x1="0" y1="0" x2="24" y2="24" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#9945FF"/>
                                    <stop offset="1" stop-color="#7C3AED"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div>
                        <h2>Video Forensics Engine</h2>
                        <p>Deepfake & Manipulation Detection</p>
                    </div>
                </div>
                
                <div class="modal-grid">
                    <div class="modal-stat">
                        <span class="stat-val">99.8%</span>
                        <span class="stat-label">Accuracy Rate</span>
                    </div>
                    <div class="modal-stat">
                        <span class="stat-val">30ms</span>
                        <span class="stat-label">Processing / Frame</span>
                    </div>
                    <div class="modal-stat">
                        <span class="stat-val">24</span>
                        <span class="stat-label">Inspection Layers</span>
                    </div>
                </div>

                <div class="modal-section">
                    <h3>How it Works</h3>
                    <p>Our AI decomposes video into individual frames and analyzes them across three dimensions:</p>
                    <ul class="modal-list">
                        <li><strong>Temporal Consistency:</strong> Detects unnatural movements or "jitter" between frames often found in deepfakes.</li>
                        <li><strong>Biological Signals:</strong> Monitors micro-changes in skin color (photoplethysmography) that indicate a real heartbeat.</li>
                        <li><strong>Lighting Physics:</strong> Verifies that light reflections in eyes and on skin align perfectly with the scene's light sources.</li>
                    </ul>
                </div>
                
                <div class="modal-chart-placeholder aesthetic-vis">
                    <div class="vis-header">
                        <span class="vis-dot pulse"></span>
                        <span class="vis-title">AI NEURAL LAYERS</span>
                    </div>
                    
                    <div class="vis-stack">
                        <!-- Layer 1 -->
                        <div class="vis-row">
                            <span class="vis-label">Input Frame</span>
                            <div class="vis-bar-track"><div class="vis-bar" style="width: 100%; animation-delay: 0s;"></div></div>
                            <span class="vis-val">100%</span>
                        </div>
                        <!-- Layer 2 -->
                        <div class="vis-row">
                            <span class="vis-label">Deep Texture</span>
                            <div class="vis-bar-track"><div class="vis-bar" style="width: 85%; animation-delay: 0.2s;"></div></div>
                            <span class="vis-val">85%</span>
                        </div>
                        <!-- Layer 3 -->
                        <div class="vis-row">
                            <span class="vis-label">Face Mesh</span>
                            <div class="vis-bar-track"><div class="vis-bar" style="width: 60%; animation-delay: 0.4s;"></div></div>
                            <span class="vis-val">60%</span>
                        </div>
                        <!-- Layer 4 -->
                        <div class="vis-row error-row">
                            <span class="vis-label">Anomaly</span>
                            <div class="vis-bar-track"><div class="vis-bar error" style="width: 12%; animation-delay: 0.6s;"></div></div>
                            <span class="vis-val error">0.02%</span>
                        </div>
                    </div>
                </div>

                <style>
                    /* Aesthetic Visualization */
                    .aesthetic-vis {
                        background: #0f172a; /* Dark Tech Blue */
                        border-radius: 20px; padding: 24px; text-align: left;
                        border: 1px solid #1e293b; position: relative; overflow: hidden;
                        box-shadow: inset 0 0 30px rgba(0,0,0,0.5);
                    }
                    .aesthetic-vis::after {
                        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 2px;
                        background: linear-gradient(90deg, transparent, #7C3AED, transparent);
                        animation: scanline 3s infinite linear;
                    }
                    @keyframes scanline { 0% { top: 0; opacity: 0; } 50% { opacity: 1; } 100% { top: 100%; opacity: 0; } }

                    .vis-header { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid #334155; padding-bottom: 12px; }
                    .vis-dot { width: 8px; height: 8px; background: #22c55e; border-radius: 50%; display: block; }
                    .vis-dot.pulse { box-shadow: 0 0 10px #22c55e; animation: pulse-green 2s infinite; }
                    .vis-title { color: #94a3b8; font-size: 0.75rem; letter-spacing: 2px; font-weight: 700; }

                    .vis-row { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; font-size: 0.85rem; }
                    .vis-label { color: #cbd5e1; width: 80px; text-align: right; }
                    .vis-val { color: #64748b; font-family: 'Space Grotesk'; width: 40px; }
                    
                    .vis-bar-track { flex: 1; height: 8px; background: #1e293b; border-radius: 4px; overflow: hidden; }
                    .vis-bar { 
                        height: 100%; background: linear-gradient(90deg, #7C3AED, #a78bfa); 
                        border-radius: 4px; position: relative;
                        animation: loadBar 1.5s ease-out forwards; transform-origin: left;
                    }
                    .vis-bar::after {
                        content: ''; position: absolute; top: 0; right: 0; width: 10px; height: 100%;
                        background: white; opacity: 0.5; filter: blur(4px);
                    }

                    /* Error Row Style */
                    .error-row .vis-label { color: #f87171; }
                    .vis-bar.error { background: linear-gradient(90deg, #ef4444, #fca5a5); box-shadow: 0 0 10px rgba(239, 68, 68, 0.4); }
                    .vis-val.error { color: #f87171; font-weight: bold; }

                    @keyframes loadBar { from { transform: scaleX(0); } to { transform: scaleX(1); } }
                    @keyframes pulse-green { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
                </style>
            </div>

            <!-- Image Content -->
            <div id="modal-content-image" class="modal-body" style="display: none;">
                <div class="modal-header">
                    <div class="modal-icon">
                        <!-- Custom SVG: Image Layers (Matching Main Card) -->
                        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14 9L13.1112 11.6663C12.8795 12.3615 11.8841 12.3828 11.6234 11.698L11 10.0606" stroke="url(#gradImageModal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <rect x="3" y="5" width="18" height="14" rx="3" stroke="url(#gradImageModal)" stroke-width="2"/>
                            <circle cx="8" cy="9" r="1.5" fill="url(#gradImageModal)"/>
                            <path d="M3 16L7 13L10 16L13 13L18 17" stroke="url(#gradImageModal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <defs>
                                <linearGradient id="gradImageModal" x1="0" y1="0" x2="24" y2="24" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#14F195"/>
                                    <stop offset="1" stop-color="#10B981"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div>
                        <h2>Deep Pixel Scan</h2>
                        <p>Generative Fill & Edit Detection</p>
                    </div>
                </div>

                <div class="modal-grid">
                    <div class="modal-stat">
                        <span class="stat-val">120+</span>
                        <span class="stat-label">Format Types</span>
                    </div>
                    <div class="modal-stat">
                        <span class="stat-val">0.5s</span>
                        <span class="stat-label">Analysis Time</span>
                    </div>
                    <div class="modal-stat">
                        <span class="stat-val">100%</span>
                        <span class="stat-label">EXIF Parsing</span>
                    </div>
                </div>

                <div class="modal-section">
                    <h3>How it Works</h3>
                    <p>We analyze the digital fingerprint of images to uncover invisible manipulation traces:</p>
                    <ul class="modal-list">
                        <li><strong>Error Level Analysis (ELA):</strong> Highlights differences in compression rates, revealing where foreign objects (or AI generation) have been inserted.</li>
                        <li><strong>Noise Pattern:</strong> Every camera sensor leaves a unique noise signature. We detect areas where this pattern is broken or synthetic.</li>
                        <li><strong>Metadata Forensics:</strong> Deep scans of EXIF, XMP, and IPTC data to find edit history and software signatures (e.g., Photoshop, Midjourney).</li>
                    </ul>
                </div>

                <div class="modal-chart-placeholder aesthetic-vis">
                    <div class="vis-header">
                        <span class="vis-dot pulse" style="background: #10B981; box-shadow: 0 0 10px #10B981;"></span>
                        <span class="vis-title">PIXEL INTEGRITY SCAN</span>
                    </div>
                    
                    <div class="vis-stack">
                        <!-- Layer 1 -->
                        <div class="vis-row">
                            <span class="vis-label">Metadata</span>
                            <div class="vis-bar-track"><div class="vis-bar" style="width: 100%; background: linear-gradient(90deg, #14F195, #34d399); animation-delay: 0s;"></div></div>
                            <span class="vis-val">OK</span>
                        </div>
                        <!-- Layer 2 -->
                        <div class="vis-row">
                            <span class="vis-label">ELA Map</span>
                            <div class="vis-bar-track"><div class="vis-bar" style="width: 92%; background: linear-gradient(90deg, #14F195, #34d399); animation-delay: 0.2s;"></div></div>
                            <span class="vis-val">92%</span>
                        </div>
                        <!-- Layer 3 -->
                        <div class="vis-row">
                            <span class="vis-label">Noise Map</span>
                            <div class="vis-bar-track"><div class="vis-bar" style="width: 88%; background: linear-gradient(90deg, #14F195, #34d399); animation-delay: 0.4s;"></div></div>
                            <span class="vis-val">88%</span>
                        </div>
                        <!-- Layer 4 -->
                        <div class="vis-row error-row">
                            <span class="vis-label">Gen Fill</span>
                            <div class="vis-bar-track"><div class="vis-bar error" style="width: 45%; animation-delay: 0.6s;"></div></div>
                            <span class="vis-val error">HIGH</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Audio Content -->
            <div id="modal-content-audio" class="modal-body" style="display: none;">
                <div class="modal-header">
                    <div class="modal-icon">
                        <!-- Custom SVG: Audio Wave (Matching Main Card) -->
                        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4V20M16 7V17M8 7V17M4 11V13M20 11V13" stroke="url(#gradAudioModal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <defs>
                                <linearGradient id="gradAudioModal" x1="0" y1="0" x2="24" y2="24" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#00C2FF"/>
                                    <stop offset="1" stop-color="#3B82F6"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div>
                        <h2>Voice Detection</h2>
                        <p>Synthetic Speech & Clone Analysis</p>
                    </div>
                </div>

                <div class="modal-grid">
                    <div class="modal-stat">
                        <span class="stat-val">44.1kHz</span>
                        <span class="stat-label">Sample Analysis</span>
                    </div>
                    <div class="modal-stat">
                        <span class="stat-val">99.5%</span>
                        <span class="stat-label">Voice Match</span>
                    </div>
                    <div class="modal-stat">
                        <span class="stat-val">0-24k</span>
                        <span class="stat-label">Hz Range</span>
                    </div>
                </div>

                <div class="modal-section">
                    <h3>How it Works</h3>
                    <p>We analyze the spectral signature of voice recordings to differentiate human vocal cords from AI generation:</p>
                    <ul class="modal-list">
                        <li><strong>Spectral Analysis:</strong> Visualizes the frequency spectrum to find "perfect" patterns typical of AI, lacking human irregularities.</li>
                        <li><strong>Breath & Pause:</strong> Analyzes natural breathing patterns and micro-pauses that synthetic voices often miss or simulate poorly.</li>
                        <li><strong>Replay Detection:</strong> Identifies if a voice is being played back from a recording device (loudspeaker signature).</li>
                    </ul>
                </div>

                <div class="modal-chart-placeholder aesthetic-vis">
                    <div class="vis-header">
                        <span class="vis-dot pulse" style="background: #3B82F6; box-shadow: 0 0 10px #3B82F6;"></span>
                        <span class="vis-title">AUDIO FREQUENCY SCALAR</span>
                    </div>
                    
                    <div class="vis-stack">
                        <!-- Layer 1 -->
                        <div class="vis-row">
                            <span class="vis-label">Base Tones</span>
                            <div class="vis-bar-track"><div class="vis-bar" style="width: 100%; background: linear-gradient(90deg, #00C2FF, #3B82F6); animation-delay: 0s;"></div></div>
                            <span class="vis-val">OK</span>
                        </div>
                        <!-- Layer 2 -->
                        <div class="vis-row">
                            <span class="vis-label">Micro Tremor</span>
                            <div class="vis-bar-track"><div class="vis-bar" style="width: 95%; background: linear-gradient(90deg, #00C2FF, #3B82F6); animation-delay: 0.2s;"></div></div>
                            <span class="vis-val">95%</span>
                        </div>
                        <!-- Layer 3 -->
                        <div class="vis-row">
                            <span class="vis-label">Ambience</span>
                            <div class="vis-bar-track"><div class="vis-bar" style="width: 80%; background: linear-gradient(90deg, #00C2FF, #3B82F6); animation-delay: 0.4s;"></div></div>
                            <span class="vis-val">80%</span>
                        </div>
                        <!-- Layer 4 -->
                        <div class="vis-row error-row">
                            <span class="vis-label">Synth ID</span>
                            <div class="vis-bar-track"><div class="vis-bar error" style="width: 8%; animation-delay: 0.6s;"></div></div>
                            <span class="vis-val error">LOW</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        /* Modal CSS */
        .modal-backdrop {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000;
            background: rgba(11, 11, 21, 0.8); backdrop-filter: blur(16px);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
        }
        .modal-backdrop.active { opacity: 1; pointer-events: all; }
        
        .modal-glass {
            background: rgba(255, 255, 255, 0.95); width: 90%; max-width: 700px;
            border-radius: 32px; padding: 40px; position: relative;
            box-shadow: 0 50px 100px -20px rgba(0,0,0,0.3);
            transform: scale(0.9) translateY(20px); transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            max-height: 90vh; overflow-y: auto; border: 1px solid rgba(255,255,255,0.5);
            
            /* Custom Scrollbar - Premium Aesthetic */
            scrollbar-width: thin; scrollbar-color: #ddd6fe transparent;
        }
        /* Webkit Scrollbar Design */
        .modal-glass::-webkit-scrollbar { width: 5px; }
        .modal-glass::-webkit-scrollbar-track { background: transparent; margin: 10px 0; }
        
        /* Thumb: Soft Gradient Pill */
        .modal-glass::-webkit-scrollbar-thumb { 
            background: linear-gradient(to bottom, #ddd6fe, #a78bfa); 
            border-radius: 10px; 
            border: none;
        }
        
        /* Hover: Deeper Purple */
        .modal-glass::-webkit-scrollbar-thumb:hover { 
            background: linear-gradient(to bottom, #8b5cf6, #7c3aed); 
            box-shadow: 0 0 10px rgba(124, 58, 237, 0.4);
        }

        .modal-body {
             max-height: 70vh; overflow-y: auto; padding-right: 4px;
             scrollbar-width: thin; scrollbar-color: #ddd6fe transparent;
        }
        .modal-body::-webkit-scrollbar { width: 4px; }
        .modal-body::-webkit-scrollbar-track { background: transparent; }
        .modal-body::-webkit-scrollbar-thumb { 
            background: #e2e8f0; border-radius: 10px; 
        }
        .modal-body::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }


        .modal-header { 
            display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 32px;
        }
        .modal-icon { 
            width: 90px; height: 90px; border-radius: 24px; display: flex; align-items: center; justify-content: center;
            background: #fff; border: 1px solid #f1f5f9;
            box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05);
            margin-bottom: 16px;
        }
        .modal-header h2 { font-family: 'Space Grotesk', sans-serif; font-size: 2.2rem; line-height: 1.1; color: #1e1e2f; margin-bottom: 8px; }
        .modal-header p { color: #64748b; font-size: 1.1rem; margin: 0; }

        .modal-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px; }
        .modal-stat { background: #f8fafc; padding: 16px; border-radius: 16px; text-align: center; border: 1px solid #e2e8f0; }
        .stat-val { display: block; font-family: 'Space Grotesk'; font-size: 1.5rem; font-weight: 700; color: #1e1e2f; }
        .stat-label { font-size: 0.8rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }

        .modal-section { margin-bottom: 32px; }
        .modal-section h3 { font-size: 1.1rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .modal-section h3::before { content: ''; width: 4px; height: 16px; background: #7C3AED; border-radius: 2px; display: inline-block; }
        .modal-section p { line-height: 1.6; color: #475569; margin-bottom: 16px; }
        
        .modal-list li { margin-bottom: 12px; padding-left: 20px; position: relative; color: #475569; line-height: 1.5; }
        .modal-list li::before { content: '•'; position: absolute; left: 0; color: #7C3AED; font-weight: bold; }

        /* Chart styles moved inline for component isolation */
    </style>

    <script>
        // Modal functions are defined below with language listener
    </script>

    <!-- TRANSLATION SCRIPT -->
    <script>
        const translations = {
            en: {
                nav: { dash: "Dashboard", tech: "Technology", dev: "Developers", price: "Pricing", supp: "Support" },
                back: "← Back to Dashboard",
                title: "Advanced Detection Technologies",
                sub: "Our platform uses state-of-the-art multimodal AI models to verify the authenticity of digital content with 99.8% accuracy.",
                s1: { title: "Video Forensics", desc: "Frame-by-frame analysis to detect deepfake inconsistencies and temporal anomalies.", specs: ["Frame Inspection", "Face Tracking", "Light Consistency"] },
                s2: { title: "Deep Pixel Scan", desc: "Detects generative fill, metadata tampering, and noise patterns in images.", specs: ["ELA Analysis", "Noise Pattern", "Metadata Check"] },
                s3: { title: "Voice Detection", desc: "Identifies synthetic voice frequencies and lack of natural micro-tremors.", specs: ["Spectral Analysis", "Breath Pattern", "Replay Detect"] },
                cta: { title: "Ready to Integrate?", desc: "Join developers building the future of content authenticity.", btn: "Get API Access" },
                // Modal Translations
                modal: {
                    video: {
                        title: "Video Forensics Engine",
                        sub: "Deepfake & Manipulation Detection",
                        stats: ["Accuracy Rate", "Processing / Frame", "Inspection Layers"],
                        how: "How it Works",
                        desc: "Our AI decomposes video into individual frames and analyzes them across three dimensions:",
                        points: [
                            "<strong>Temporal Consistency:</strong> Detects unnatural movements or \"jitter\" between frames often found in deepfakes.",
                            "<strong>Biological Signals:</strong> Monitors micro-changes in skin color (photoplethysmography) that indicate a real heartbeat.",
                            "<strong>Lighting Physics:</strong> Verifies that light reflections in eyes and on skin align perfectly with the scene's light sources."
                        ],
                        vis: {
                            title: "AI NEURAL LAYERS",
                            layers: ["Input Frame", "Deep Texture", "Face Mesh", "Anomaly"]
                        }
                    },
                    image: {
                        title: "Deep Pixel Scan",
                        sub: "Generative Fill & Edit Detection",
                        stats: ["Format Types", "Analysis Time", "EXIF Parsing"],
                        how: "How it Works",
                        desc: "We analyze the digital fingerprint of images to uncover invisible manipulation traces:",
                        points: [
                            "<strong>Error Level Analysis (ELA):</strong> Highlights differences in compression rates, revealing where foreign objects (or AI generation) have been inserted.",
                            "<strong>Noise Pattern:</strong> Every camera sensor leaves a unique noise signature. We detect areas where this pattern is broken or synthetic.",
                            "<strong>Metadata Forensics:</strong> Deep scans of EXIF, XMP, and IPTC data to find edit history and software signatures."
                        ],
                        vis: {
                            title: "PIXEL INTEGRITY SCAN",
                            layers: ["Metadata", "ELA Map", "Noise Map", "Gen Fill"]
                        }
                    }
                }
            },
            id: {
                back: "← Kembali ke Dasbor",
                title: "Teknologi Deteksi Canggih",
                sub: "Platform kami menggunakan model AI multimodal mutakhir untuk memverifikasi keaslian konten digital dengan akurasi 99,8%.",
                s1: { title: "Forensik Video", desc: "Analisis frame-by-frame untuk mendeteksi deepfake dan anomali waktu.", specs: ["Inspeksi Frame", "Pelacakan Wajah", "Konsistensi Cahaya"] },
                s2: { title: "Investigasi Piksel", desc: "Mendeteksi generative fill, manipulasi metadata, dan pola noise pada gambar.", specs: ["Analisis ELA", "Pola Noise", "Cek Metadata"] },
                s3: { title: "Deteksi Suara", desc: "Mengidentifikasi frekuensi suara sintetis dan kurangnya getaran mikro alami.", specs: ["Analisis Spektral", "Pola Napas", "Deteksi Replay"] },
                cta: { title: "Siap Integrasi?", desc: "Bergabunglah dengan pengembang yang membangun masa depan autentisitas konten.", btn: "Dapatkan Akses API" },
                modal: {
                    video: {
                        title: "Mesin Forensik Video",
                        sub: "Deteksi Deepfake & Manipulasi",
                        stats: ["Akurasi", "Proses / Frame", "Layer Inspeksi"],
                        how: "Cara Kerja",
                        desc: "AI kami memecah video menjadi frame individu dan menganalisisnya dalam tiga dimensi:",
                        points: [
                            "<strong>Konsistensi Waktu:</strong> Mendeteksi gerakan tidak wajar atau lip-sync jitter antar frame.",
                            "<strong>Sinyal Biologis:</strong> Memantau perubahan mikro warna kulit (detak jantung alami).",
                            "<strong>Fisika Cahaya:</strong> Memverifikasi pantulan cahaya di mata agar sesuai dengan sumber cahaya."
                        ],
                        vis: {
                            title: "LAYER SARAF AI",
                            layers: ["Input Frame", "Tekstur Dalam", "Mesh Wajah", "Anomali"]
                        }
                    },
                    image: {
                        title: "Investigasi Piksel",
                        sub: "Deteksi Generative Fill & Edit",
                        stats: ["Tipe Format", "Waktu Analisis", "Parsing EXIF"],
                        how: "Cara Kerja",
                        desc: "Kami menganalisis sidik jari digital gambar untuk mengungkap jejak manipulasi yang tidak kasat mata:",
                        points: [
                            "<strong>Error Level Analysis (ELA):</strong> Menyoroti perbedaan kompresi untuk menemukan objek asing atau editan AI.",
                            "<strong>Pola Noise:</strong> Sensor kamera meninggalkan jejak noise unik. Kami mendeteksi area di mana pola ini terputus.",
                            "<strong>Forensik Metadata:</strong> Scan mendalam data EXIF & XMP untuk menemukan riwayat edit."
                        ],
                        vis: {
                            title: "SCAN INTEGRITAS PIKSEL",
                            layers: ["Metadata", "Peta ELA", "Peta Noise", "Gen Fill"]
                        }
                    }
                }
            },
            es: {
                back: "← Volver al Tablero",
                title: "Tecnologías de Detección",
                sub: "Nuestra plataforma utiliza modelos de IA para verificar la autenticidad del contenido con 99.8% de precisión.",
                s1: { title: "Forense de Video", desc: "Análisis cuadro por cuadro para detectar deepfakes y anomalías temporales.", specs: ["Inspección de Cuadro", "Rastreo Facial", "Consistencia de Luz"] },
                s2: { title: "Escaneo de Píxeles", desc: "Detecta relleno generativo, manipulación de metadatos y patrones de ruido.", specs: ["Análisis ELA", "Patrón de Ruido", "Metadatos"] },
                s3: { title: "Detección de Voz", desc: "Identifica frecuencias sintéticas y falta de micro-temblores naturales.", specs: ["Análisis Espectral", "Patrón de Respiración", "Detección de Replay"] },
                cta: { title: "¿Listo para Integrar?", desc: "Únase a los desarrolladores que construyen el futuro de la autenticidad.", btn: "Obtener API" },
                modal: {
                    video: {
                        title: "Motor Forense de Video",
                        sub: "Detección de Deepfake y Manipulación",
                        stats: ["Precisión", "Procesamiento / Frame", "Capas de Inspección"],
                        how: "Cómo Funciona",
                        desc: "Nuestra IA descompone el video en cuadros individuales y los analiza en tres dimensiones:",
                        points: [
                            "<strong>Consistencia Temporal:</strong> Detecta movimientos antinaturales entre cuadros.",
                            "<strong>Señales Biológicas:</strong> Monitorea micro-cambios en el color de la piel (latidos reales).",
                            "<strong>Física de la Luz:</strong> Verifica que los reflejos de luz coincidan con la escena."
                        ],
                        vis: {
                            title: "CAPAS NEURONALES IA",
                            layers: ["Input Frame", "Textura Profunda", "Malla Facial", "Anomalía"]
                        }
                    },
                    audio: {
                        title: "Voice Detection",
                        sub: "Synthetic Speech & Clone Analysis",
                        stats: ["Sample Analysis", "Voice Match", "Hz Range"],
                        how: "How it Works",
                        desc: "We analyze the spectral signature of voice recordings to differentiate human vocal cords from AI generation:",
                        points: [
                            "<strong>Spectral Analysis:</strong> Visualizes the frequency spectrum to find \"perfect\" patterns typical of AI.",
                            "<strong>Breath & Pause:</strong> Analyzes natural breathing patterns and micro-pauses that synthetic voices often miss.",
                            "<strong>Replay Detection:</strong> Identifies if a voice is being played back from a recording device."
                        ],
                        vis: {
                            title: "AUDIO FREQUENCY SCALAR",
                            layers: ["Base Tones", "Micro Tremor", "Ambience", "Synth ID"]
                        }
                    }
                }
            },
            id: {
                nav: { dash: "Dasbor", tech: "Teknologi", dev: "Developers", price: "Harga", supp: "Bantuan" },
                back: "← Kembali ke Dasbor",
                title: "Teknologi Deteksi Canggih",
                sub: "Platform kami menggunakan model AI multimodal mutakhir untuk memverifikasi keaslian konten digital dengan akurasi 99,8%.",
                s1: { title: "Forensik Video", desc: "Analisis frame-by-frame untuk mendeteksi deepfake dan anomali waktu.", specs: ["Inspeksi Frame", "Pelacakan Wajah", "Konsistensi Cahaya"] },
                s2: { title: "Investigasi Piksel", desc: "Mendeteksi generative fill, manipulasi metadata, dan pola noise pada gambar.", specs: ["Analisis ELA", "Pola Noise", "Cek Metadata"] },
                s3: { title: "Deteksi Suara", desc: "Mengidentifikasi frekuensi suara sintetis dan kurangnya getaran mikro alami.", specs: ["Analisis Spektral", "Pola Napas", "Deteksi Replay"] },
                cta: { title: "Siap Integrasi?", desc: "Bergabunglah dengan pengembang yang membangun masa depan autentisitas konten.", btn: "Dapatkan Akses API" },
                modal: {
                    video: {
                        title: "Mesin Forensik Video",
                        sub: "Deteksi Deepfake & Manipulasi",
                        stats: ["Akurasi", "Proses / Frame", "Layer Inspeksi"],
                        how: "Cara Kerja",
                        desc: "AI kami memecah video menjadi frame individu dan menganalisisnya dalam tiga dimensi:",
                        points: [
                            "<strong>Konsistensi Waktu:</strong> Mendeteksi gerakan tidak wajar atau lip-sync jitter antar frame.",
                            "<strong>Sinyal Biologis:</strong> Memantau perubahan mikro warna kulit (detak jantung alami).",
                            "<strong>Fisika Cahaya:</strong> Memverifikasi pantulan cahaya di mata agar sesuai dengan sumber cahaya."
                        ],
                        vis: {
                            title: "LAYER SARAF AI",
                            layers: ["Input Frame", "Tekstur Dalam", "Mesh Wajah", "Anomali"]
                        }
                    },
                    image: {
                        title: "Investigasi Piksel",
                        sub: "Deteksi Generative Fill & Edit",
                        stats: ["Tipe Format", "Waktu Analisis", "Parsing EXIF"],
                        how: "Cara Kerja",
                        desc: "Kami menganalisis sidik jari digital gambar untuk mengungkap jejak manipulasi yang tidak kasat mata:",
                        points: [
                            "<strong>Error Level Analysis (ELA):</strong> Menyoroti perbedaan kompresi untuk menemukan objek asing atau editan AI.",
                            "<strong>Pola Noise:</strong> Sensor kamera meninggalkan jejak noise unik. Kami mendeteksi area di mana pola ini terputus.",
                            "<strong>Forensik Metadata:</strong> Scan mendalam data EXIF & XMP untuk menemukan riwayat edit."
                        ],
                        vis: {
                            title: "SCAN INTEGRITAS PIKSEL",
                            layers: ["Metadata", "Peta ELA", "Peta Noise", "Gen Fill"]
                        }
                    },
                    audio: {
                        title: "Deteksi Suara",
                        sub: "Analisis Suara Sintetis & Kloning",
                        stats: ["Sampel Analisis", "Kecocokan Suara", "Rentang Hz"],
                        how: "Cara Kerja",
                        desc: "Kami menganalisis tanda spektral rekaman suara untuk membedakan pita suara manusia dari generasi AI:",
                        points: [
                            "<strong>Analisis Spektral:</strong> Memvisualisasikan spektrum frekuensi untuk menemukan pola \"sempurna\" khas AI.",
                            "<strong>Napas & Jeda:</strong> Menganalisis pola napas alami dan jeda mikro yang sering dilewatkan suara sintetis.",
                            "<strong>Deteksi Replay:</strong> Mengidentifikasi apakah suara diputar ulang dari perangkat perekam (tanda loudspeaker)."
                        ],
                        vis: {
                            title: "SKALAR FREKUENSI AUDIO",
                            layers: ["Nada Dasar", "Getaran Mikro", "Ambiance", "ID Sintetis"]
                        }
                    }
                }
            },
            es: {
                nav: { dash: "Tablero", tech: "Tecnología", dev: "Desarrolladores", price: "Precios", supp: "Ayuda" },
                back: "← Volver al Tablero",
                title: "Tecnologías de Detección",
                sub: "Nuestra plataforma utiliza modelos de IA para verificar la autenticidad del contenido con 99.8% de precisión.",
                s1: { title: "Forense de Video", desc: "Análisis cuadro por cuadro para detectar deepfakes y anomalías temporales.", specs: ["Inspección de Cuadro", "Rastreo Facial", "Consistencia de Luz"] },
                s2: { title: "Escaneo de Píxeles", desc: "Detecta relleno generativo, manipulación de metadatos y patrones de ruido.", specs: ["Análisis ELA", "Patrón de Ruido", "Metadatos"] },
                s3: { title: "Detección de Voz", desc: "Identifica frecuencias sintéticas y falta de micro-temblores naturales.", specs: ["Análisis Espectral", "Patrón de Respiración", "Detección de Replay"] },
                cta: { title: "¿Listo para Integrar?", desc: "Únase a los desarrolladores que construyen el futuro de la autenticidad.", btn: "Obtener API" },
                modal: {
                    video: {
                        title: "Motor Forense de Video",
                        sub: "Detección de Deepfake y Manipulación",
                        stats: ["Precisión", "Procesamiento / Frame", "Capas de Inspección"],
                        how: "Cómo Funciona",
                        desc: "Nuestra IA descompone el video en cuadros individuales y los analiza en tres dimensiones:",
                        points: [
                            "<strong>Consistencia Temporal:</strong> Detecta movimientos antinaturales entre cuadros.",
                            "<strong>Señales Biológicas:</strong> Monitorea micro-cambios en el color de la piel (latidos reales).",
                            "<strong>Física de la Luz:</strong> Verifica que los reflejos de luz coincidan con la escena."
                        ],
                        vis: {
                            title: "CAPAS NEURONALES IA",
                            layers: ["Input Frame", "Textura Profunda", "Malla Facial", "Anomalía"]
                        }
                    },
                    image: {
                        title: "Escaneo de Píxeles",
                        sub: "Detección de Relleno Generativo",
                        stats: ["Tipos de Formato", "Tiempo Análisis", "Análisis EXIF"],
                        how: "Cómo Funciona",
                        desc: "Analizamos la huella digital de las imágenes para descubrir rastros de manipulación:",
                        points: [
                            "<strong>Análisis ELA:</strong> Resalta diferencias de compresión para encontrar objetos insertados.",
                            "<strong>Patrón de Ruido:</strong> Detecta dónde se rompe la firma de ruido única del sensor de la cámara.",
                            "<strong>Forense de Metadatos:</strong> Escaneo profundo de EXIF para encontrar historial de edición."
                        ],
                        vis: {
                            title: "INTEGRIDAD DE PÍXELES",
                            layers: ["Metadatos", "Mapa ELA", "Mapa Ruido", "Gen Fill"]
                        }
                    },
                    audio: {
                        title: "Detección de Voz",
                        sub: "Análisis de Voz Sintética",
                        stats: ["Muestra de Análisis", "Coincidencia Voz", "Rango Hz"],
                        how: "Cómo Funciona",
                        desc: "Analizamos la firma espectral de las grabaciones para diferenciar las cuerdas vocales humanas de la IA:",
                        points: [
                            "<strong>Análisis Espectral:</strong> Visualiza el espectro para encontrar patrones de IA.",
                            "<strong>Respiración y Pausa:</strong> Analiza patrones de respiración natural que las voces sintéticas omiten.",
                            "<strong>Detección de Reproducción:</strong> Identifica si una voz se reproduce desde un dispositivo."
                        ],
                        vis: {
                            title: "ESCALAR DE FRECUENCIA",
                            layers: ["Tonos Base", "Micro Temblor", "Ambiente", "ID Sintético"]
                        }
                    }
                }
            },
            fr: {
                nav: { dash: "Tableau de bord", tech: "Technologies", dev: "Développeurs", price: "Tarifs", supp: "Support" },
                back: "← Retour au tableau de bord",
                title: "Technologies de Détection",
                sub: "Notre plateforme utilise des modèles IA multimodaux de pointe pour vérifier l'authenticité du contenu avec une précision de 99,8%.",
                s1: { title: "Inforensique Vidéo", desc: "Analyse image par image pour détecter les deepfakes et les anomalies temporelles.", specs: ["Inspection d'Image", "Suivi Visage", "Cohérence Lumière"] },
                s2: { title: "Scan de Pixels", desc: "Détecte le remplissage génératif, la manipulation de métadonnées et les motifs de bruit.", specs: ["Analyse ELA", "Motif de Bruit", "Vérif. Métadonnées"] },
                s3: { title: "Détection Vocale", desc: "Identifie les fréquences vocales synthétiques et l'absence de micro-tremblements naturels.", specs: ["Analyse Spectrale", "Motif Respiratoire", "Détection Replay"] },
                cta: { title: "Prêt à Intégrer ?", desc: "Rejoignez les développeurs qui construisent l'avenir de l'authenticité du contenu.", btn: "Obtenir l'Accès API" },
                modal: {
                    video: {
                        title: "Moteur Inforensique Vidéo",
                        sub: "Détection de Deepfake & Manipulation",
                        stats: ["Précision", "Traitement / Image", "Couches d'Inspection"],
                        how: "Comment ça marche",
                        desc: "Notre IA décompose la vidéo en images individuelles et les analyse en trois dimensions :",
                        points: [
                            "<strong>Cohérence Temporelle :</strong> Détecte les mouvements non naturels ou le \"jitter\" entre les images.",
                            "<strong>Signaux Biologiques :</strong> Surveille les micro-changements de couleur de la peau (battements cardiaques).",
                            "<strong>Physique de l'Éclairage :</strong> Vérifie que les reflets de lumière s'alignent parfaitement."
                        ],
                        vis: { title: "COUCHES NEURONALES IA", layers: ["Image Entrée", "Texture Profonde", "Maillage Visage", "Anomalie"] }
                    },
                    image: {
                        title: "Scan de Pixels Profond",
                        sub: "Détection de Remplissage Génératif",
                        stats: ["Types de Format", "Temps d'Analyse", "Analyse EXIF"],
                        how: "Comment ça marche",
                        desc: "Nous analysons l'empreinte numérique des images pour découvrir les traces de manipulation invisibles :",
                        points: [
                            "<strong>Analyse ELA :</strong> Met en évidence les différences de compression pour trouver des objets insérés.",
                            "<strong>Motif de Bruit :</strong> Chaque capteur de caméra laisse une signature de bruit unique. Nous détectons les ruptures.",
                            "<strong>Inforensique Métadonnées :</strong> Analyse approfondie des données EXIF et XMP."
                        ],
                        vis: { title: "SCAN INTÉGRITÉ PIXELS", layers: ["Métadonnées", "Carte ELA", "Carte Bruit", "Remplissage Gen"] }
                    },
                    audio: {
                        title: "Détection Vocale",
                        sub: "Analyse de Parole Synthétique",
                        stats: ["Échantillon", "Corres. Voix", "Plage Hz"],
                        how: "Comment ça marche",
                        desc: "Nous analysons la signature spectrale des enregistrements vocaux pour différencier l'humain de l'IA :",
                        points: [
                            "<strong>Analyse Spectrale :</strong> Visualise le spectre de fréquence pour trouver des motifs \"parfaits\" typiques de l'IA.",
                            "<strong>Respiration & Pause :</strong> Analyse les motifs de respiration naturelle que les voix synthétiques manquent.",
                            "<strong>Détection de Relecture :</strong> Identifie si une voix est rejouée depuis un appareil d'enregistrement."
                        ],
                        vis: { title: "SCALAIRE FRÉQUENCE AUDIO", layers: ["Tons de Base", "Micro Tremblement", "Ambiance", "ID Synthétique"] }
                    }
                }
            },
            de: {
                nav: { dash: "Dashboard", tech: "Technologien", dev: "Entwickler", price: "Preise", supp: "Support" },
                back: "← Zurück zum Dashboard",
                title: "Erkennungstechnologien",
                sub: "Unsere Plattform nutzt modernste multimodale KI-Modelle, um die Authentizität digitaler Inhalte mit 99,8% Genauigkeit zu verifizieren.",
                s1: { title: "Video-Forensik", desc: "Bild-für-Bild-Analyse zur Erkennung von Deepfake-Inkonsistenzen.", specs: ["Bildprüfung", "Gesichtsverfolgung", "Lichtkonsistenz"] },
                s2: { title: "Deep Pixel Scan", desc: "Erkennt generative Füllungen, Metadatenmanipulation und Rauschmuster.", specs: ["ELA-Analyse", "Rauschmuster", "Metadaten-Check"] },
                s3: { title: "Spracherkennung", desc: "Identifiziert synthetische Sprachfrequenzen und fehlende natürliche Mikro-Zittern.", specs: ["Spektralanalyse", "Atemmuster", "Wiedergabeerkennung"] },
                cta: { title: "Bereit zur Integration?", desc: "Schließen Sie sich Entwicklern an, die die Zukunft der Inhaltsauthentizität bauen.", btn: "API-Zugang erhalten" },
                modal: {
                    video: {
                        title: "Video-Forensik-Engine",
                        sub: "Deepfake- & Manipulationserkennung",
                        stats: ["Genauigkeit", "Verarbeitung / Bild", "Prüfschichten"],
                        how: "Wie es funktioniert",
                        desc: "Unsere KI zerlegt Video in Einzelbilder und analysiert sie in drei Dimensionen:",
                        points: [
                            "<strong>Zeitliche Konsistenz:</strong> Erkennt unnatürliche Bewegungen zwischen Bildern.",
                            "<strong>Biologische Signale:</strong> Überwacht Mikroänderungen der Hautfarbe (Herzschlag).",
                            "<strong>Lichtphysik:</strong> Überprüft, ob Lichtreflexionen mit der Szene übereinstimmen."
                        ],
                        vis: { title: "KI-NEURONALE SCHICHTEN", layers: ["Eingangsbild", "Tiefe Textur", "Gesichtsmesh", "Anomalie"] }
                    },
                    image: {
                        title: "Deep Pixel Scan",
                        sub: "Erkennung generativer Füllungen",
                        stats: ["Formattypen", "Analysezeit", "EXIF-Parsing"],
                        how: "Wie es funktioniert",
                        desc: "Wir analysieren den digitalen Fingerabdruck von Bildern auf unsichtbare Spuren:",
                        points: [
                            "<strong>ELA-Analyse:</strong> Hebt Kompressionsunterschiede hervor, um eingefügte Objekte zu finden.",
                            "<strong>Rauschmuster:</strong> Jeder Kamerasensor hinterlässt eine Signatur. Wir finden Brüche.",
                            "<strong>Metadaten-Forensik:</strong> Tiefer Scan von EXIF- und XMP-Daten."
                        ],
                        vis: { title: "PIXEL-INTEGRITÄTSSCAN", layers: ["Metadaten", "ELA-Karte", "Rauschkarte", "Gen-Füllung"] }
                    },
                    audio: {
                        title: "Spracherkennung",
                        sub: "Analyse synthetischer Sprache",
                        stats: ["Probe", "Stimmübereinstimmung", "Hz-Bereich"],
                        how: "Wie es funktioniert",
                        desc: "Wir analysieren die spektrale Signatur, um menschliche Stimmbänder von KI zu unterscheiden:",
                        points: [
                            "<strong>Spektralanalyse:</strong> Visualisiert das Frequenzspektrum auf KI-typische \"perfekte\" Muster.",
                            "<strong>Atmung & Pause:</strong> Analysiert natürliche Atemmuster, die synthetischen Stimmen fehlen.",
                            "<strong>Wiedergabeerkennung:</strong> Identifiziert, ob eine Stimme von einem Gerät abgespielt wird."
                        ],
                        vis: { title: "AUDIO-FREQUENZSKALAR", layers: ["Basistöne", "Mikro-Zittern", "Ambiente", "Synth-ID"] }
                    }
                }
            },
            jp: {
                nav: { dash: "ダッシュボード", tech: "技術", dev: "開発者", price: "価格", supp: "サポート" },
                back: "← ダッシュボードに戻る",
                title: "高度な検出技術",
                sub: "当社のプラットフォームは、最新のマルチモーダルAIモデルを使用して、99.8％の精度でデジタルコンテンツの真正性を検証します。",
                s1: { title: "ビデオフォレンジック", desc: "フレームごとの分析でディープフェイクの不整合や一時的な異常を検出します。", specs: ["フレーム検査", "顔追跡", "光の一貫性"] },
                s2: { title: "ディープピクセルスキャン", desc: "画像の生成的な塗りつぶし、メタデータの改ざん、ノイズパターンを検出します。", specs: ["ELA分析", "ノイズパターン", "メタデータ確認"] },
                s3: { title: "音声検出", desc: "合成音声の周波数や自然な微細な震えの欠如を特定します。", specs: ["スペクトル分析", "呼吸パターン", "再生検出"] },
                cta: { title: "統合する準備はできましたか？", desc: "コンテンツの信頼性の未来を築く開発者に加わりましょう。", btn: "APIアクセスを取得" },
                modal: {
                    video: {
                        title: "ビデオフォレンジックエンジン",
                        sub: "ディープフェイクと操作の検出",
                        stats: ["正確さ", "処理 / フレーム", "検査レイヤー"],
                        how: "仕組み",
                        desc: "当社のAIはビデオを個々のフレームに分解し、3つの次元で分析します：",
                        points: [
                            "<strong>時間的一貫性：</strong> フレーム間の不自然な動きや「ジッター」を検出します。",
                            "<strong>生体信号：</strong> 実際の心拍を示す肌の色の微細な変化を監視します。",
                            "<strong>光の物理学：</strong> 目や肌の光の反射が光源と一致しているか確認します。"
                        ],
                        vis: { title: "AIニューラルレイヤー", layers: ["入力フレーム", "深いテクスチャ", "顔メッシュ", "異常"] }
                    },
                    image: {
                        title: "ディープピクセルスキャン",
                        sub: "生成的塗りつぶしと編集の検出",
                        stats: ["フォーマット", "分析時間", "EXIF解析"],
                        how: "仕組み",
                        desc: "画像のデジタル指紋を分析して、目に見えない操作の痕跡を発見します：",
                        points: [
                            "<strong>エラーレベル分析 (ELA)：</strong> 圧縮率の違いを強調し、挿入されたオブジェクトを見つけます。",
                            "<strong>ノイズパターン：</strong> すべてのカメラセンサーは独自のノイズ署名を残します。このパターンが壊れている場所を検出します。",
                            "<strong>メタデータフォレンジック：</strong> EXIFおよびXMPデータの詳細なスキャン。"
                        ],
                        vis: { title: "ピクセル整合性スキャン", layers: ["メタデータ", "ELAマップ", "ノイズマップ", "Gen塗りつぶし"] }
                    },
                    audio: {
                        title: "音声検出",
                        sub: "合成音声とクローンの分析",
                        stats: ["サンプル", "声の一致", "Hz範囲"],
                        how: "仕組み",
                        desc: "音声録音のスペクトル署名を分析して、人間の声帯とAIを区別します：",
                        points: [
                            "<strong>スペクトル分析：</strong> 周波数スペクトルを可視化し、AI特有の「完璧な」パターンを見つけます。",
                            "<strong>呼吸と一時停止：</strong> 合成音声が見逃しがちな自然な呼吸パターンを分析します。",
                            "<strong>再生検出：</strong> 音声が録音デバイスから再生されているかどうかを識別します。"
                        ],
                        vis: { title: "音声周波数スカラー", layers: ["基本トーン", "微細な震え", "雰囲気", "Synth ID"] }
                    }
                }
            },
            cn: {
                nav: { dash: "仪表板", tech: "技术", dev: "开发者", price: "价格", supp: "支持" },
                back: "← 返回仪表板",
                title: "先进检测技术",
                sub: "我们的平台使用最先进的多模态AI模型，以99.8%的准确率验证数字内容的真实性。",
                s1: { title: "视频取证", desc: "逐帧分析以检测Deepfake的不一致性和时间异常。", specs: ["帧检查", "面部追踪", "光照一致性"] },
                s2: { title: "深度像素扫描", desc: "检测图像中的生成填充、元数据篡改和噪声模式。", specs: ["ELA分析", "噪声模式", "元数据检查"] },
                s3: { title: "语音检测", desc: "识别合成语音频率和缺乏自然微颤的情况。", specs: ["频谱分析", "呼吸模式", "重放检测"] },
                cta: { title: "准备集成了吗？", desc: "加入构建内容真实性未来的开发者行列。", btn: "获取API访问权限" },
                modal: {
                    video: {
                        title: "视频取证引擎",
                        sub: "Deepfake与篡改检测",
                        stats: ["准确率", "处理 / 帧", "检查层"],
                        how: "工作原理",
                        desc: "我们的AI将视频分解为单独的帧，并从三个维度进行分析：",
                        points: [
                            "<strong>时间一致性：</strong> 检测帧之间常见于Deepfake的不自然运动或“抖动”。",
                            "<strong>生物信号：</strong> 监测显示真实心跳的肤色微小变化（光电容积描记术）。",
                            "<strong>光照物理学：</strong> 验证眼睛和皮肤上的光反射是否与场景光源完美对齐。"
                        ],
                        vis: { title: "AI神经网络层", layers: ["输入帧", "深度纹理", "面部网格", "异常"] }
                    },
                    image: {
                        title: "深度像素扫描",
                        sub: "生成填充与编辑检测",
                        stats: ["格式类型", "分析时间", "EXIF解析"],
                        how: "工作原理",
                        desc: "我们分析图像的数字指纹以发现不可见的篡改痕迹：",
                        points: [
                            "<strong>错误级别分析 (ELA)：</strong> 突出显示压缩率差异，揭示插入的外来对象（或AI生成）。",
                            "<strong>噪声模式：</strong> 每个相机传感器都会留下独特的噪声签名。我们检测该模式被破坏的区域。",
                            "<strong>元数据取证：</strong> 深度扫描EXIF和XMP数据以查找编辑历史。"
                        ],
                        vis: { title: "像素完整性扫描", layers: ["元数据", "ELA图", "噪声图", "生成填充"] }
                    },
                    audio: {
                        title: "语音检测",
                        sub: "合成语音与克隆分析",
                        stats: ["样本", "声音匹配", "Hz范围"],
                        how: "工作原理",
                        desc: "我们分析录音的频谱签名，以区分人类声带与AI生成：",
                        points: [
                            "<strong>频谱分析：</strong> 可视化频率频谱以找到AI典型的“完美”模式。",
                            "<strong>呼吸与停顿：</strong> 分析合成语音经常遗漏的自然呼吸模式和微停顿。",
                            "<strong>重放检测：</strong> 识别声音是否从录音设备重放（扬声器签名）。"
                        ],
                        vis: { title: "音频频率标量", layers: ["基调", "微颤", "环境音", "Synth ID"] }
                    }
                }
            },
            ru: {
                nav: { dash: "Дашборд", tech: "Технологии", dev: "Разработчики", price: "Цены", supp: "Поддержка" },
                back: "← Вернуться в Дашборд",
                title: "Технологии Обнаружения",
                sub: "Наша платформа использует передовые мультимодальные модели ИИ для проверки подлинности контента с точностью 99.8%.",
                s1: { title: "Видеокриминалистика", desc: "Покадровый анализ для выявления дипфейков и временных аномалий.", specs: ["Проверка кадров", "Трекинг лица", "Свет"] },
                s2: { title: "Сканирование Пикселей", desc: "Обнаруживает генеративное заполнение, подмену метаданных и шумы.", specs: ["Анализ ELA", "Шумовой паттерн", "Метаданные"] },
                s3: { title: "Детекция Голоса", desc: "Определяет синтетические частоты и отсутствие естественного микротремора.", specs: ["Спектральный анализ", "Паттерн дыхания", "Детекция повтора"] },
                cta: { title: "Готовы к интеграции?", desc: "Присоединяйтесь к разработчикам, создающим будущее аутентичности контента.", btn: "Получить API" },
                modal: {
                    video: {
                        title: "Движок Видеокриминалистики",
                        sub: "Обнаружение Дипфейков и Манипуляций",
                        stats: ["Точность", "Обработка / Кадр", "Слои Проверки"],
                        how: "Как это работает",
                        desc: "Наш ИИ раскладывает видео на отдельные кадры и анализирует их в трех измерениях:",
                        points: [
                            "<strong>Временная согласованность:</strong> Обнаруживает неестественные движения между кадрами.",
                            "<strong>Биологические сигналы:</strong> Отслеживает микроизменения цвета кожи (сердцебиение).",
                            "<strong>Физика света:</strong> Проверяет, совпадают ли отражения света в глазах и на коже с источниками."
                        ],
                        vis: { title: "НЕЙРОННЫЕ СЛОИ ИИ", layers: ["Входной кадр", "Текстура", "Сетка лица", "Аномалия"] }
                    },
                    image: {
                        title: "Глубокий Скан Пикселей",
                        sub: "Обнаружение Генеративного Заполнения",
                        stats: ["Типы Форматов", "Время Анализа", "Парсинг EXIF"],
                        how: "Как это работает",
                        desc: "Мы анализируем цифровой отпечаток изображений, чтобы обнаружить невидимые следы:",
                        points: [
                            "<strong>Анализ ELA:</strong> Выделяет различия в сжатии для поиска вставленных объектов.",
                            "<strong>Шумовой паттерн:</strong> У каждой камеры свой уникальный шум. Мы ищем нарушения этого паттерна.",
                            "<strong>Криминалистика метаданных:</strong> Глубокое сканирование данных EXIF и XMP."
                        ],
                        vis: { title: "СКАН ЦЕЛОСТНОСТИ", layers: ["Метаданные", "Карта ELA", "Карта Шума", "Gen Fill"] }
                    },
                    audio: {
                        title: "Детекция Голоса",
                        sub: "Анализ Синтетической Речи",
                        stats: ["Сэмпл", "Совпадение", "Диапазон Hz"],
                        how: "Как это работает",
                        desc: "Мы анализируем спектральную подпись записей, чтобы отличить человека от ИИ:",
                        points: [
                            "<strong>Спектральный анализ:</strong> Визуализирует спектр для поиска «идеальных» паттернов ИИ.",
                            "<strong>Дыхание и Паузы:</strong> Анализирует естественные паузы, которые часто пропускают синтезаторы.",
                            "<strong>Детекция Повтора:</strong> Определяет, воспроизводится ли голос с записывающего устройства."
                        ],
                        vis: { title: "АУДИО ЧАСТОТЫ", layers: ["Основной тон", "Микротремор", "Амбиенс", "Synth ID"] }
                    }
                }
            }
        };

        // Initialize Language from LocalStorage
        document.addEventListener('DOMContentLoaded', () => {
            const savedLang = localStorage.getItem('privasi_lang') || 'en';
            applyLang(savedLang);
        });

        // Listen for custom event 'languageChanged' from dashboard
        window.addEventListener('languageChanged', (e) => {
            applyLang(e.detail.lang);
        });

        // Modal Logic with Layout Shift Fix
        function openModal(type) {
            const modal = document.getElementById('techModal');
            const contents = document.querySelectorAll('.modal-body');
            contents.forEach(el => el.style.display = 'none');
            
            const target = document.getElementById('modal-content-' + type);
            if(target) target.style.display = 'block';

            modal.classList.add('active');
            // Prevent background scroll & layout shift
            const scrollY = document.documentElement.style.getPropertyValue('--scroll-y');
            const body = document.body;
            body.style.position = 'fixed';
            body.style.top = `-${scrollY || window.scrollY + 'px'}`;
            body.style.width = '100%';
            body.style.overflowY = 'scroll'; // Keep scrollbar gutter to avoid jump
        }

        window.addEventListener('scroll', () => {
             document.documentElement.style.setProperty('--scroll-y', `${window.scrollY}px`);
        });

        function closeModal(e) {
            if (e.target === document.getElementById('techModal') || e.target.classList.contains('close-modal') || e.target.closest('.close-modal')) {
                const modal = document.getElementById('techModal');
                modal.classList.remove('active');
                
                // Restore background scroll
                const body = document.body;
                const scrollY = body.style.top;
                body.style.position = '';
                body.style.top = '';
                body.style.width = '';
                body.style.overflowY = '';
                window.scrollTo(0, parseInt(scrollY || '0') * -1);
            }
        }

        function applyLang(lang) {
            let t = translations[lang];
            if(!t) t = translations['en']; // Fallback

            // ... (Existing mappings for dashboard/Services 1-3) ...
            // Navbar (Component)
            if(t.nav) {
                if(document.getElementById('nav-dash')) document.getElementById('nav-dash').innerText = t.nav.dash;
                if(document.getElementById('nav-tech')) document.getElementById('nav-tech').innerText = t.nav.tech;
                if(document.getElementById('nav-dev')) document.getElementById('nav-dev').innerText = t.nav.dev;
                if(document.getElementById('nav-price')) document.getElementById('nav-price').innerText = t.nav.price;
                if(document.getElementById('nav-support')) document.getElementById('nav-support').innerText = t.nav.supp;
            }
            
            if(document.getElementById('page-title')) document.getElementById('page-title').innerText = t.title;
            if(document.getElementById('page-desc')) document.getElementById('page-desc').innerText = t.sub;

            // Service 1
            if(document.getElementById('s1-title')) document.getElementById('s1-title').innerText = t.s1.title || 'Video';
            if(document.getElementById('s1-desc')) document.getElementById('s1-desc').innerText = t.s1.desc || '';
            if(t.s1.specs) {
                if(document.getElementById('s1-t1')) document.getElementById('s1-t1').innerText = t.s1.specs[0];
                if(document.getElementById('s1-t2')) document.getElementById('s1-t2').innerText = t.s1.specs[1];
                if(document.getElementById('s1-t3')) document.getElementById('s1-t3').innerText = t.s1.specs[2];
            }

            // Service 2
            if(document.getElementById('s2-title')) document.getElementById('s2-title').innerText = t.s2.title || 'Image';
            if(document.getElementById('s2-desc')) document.getElementById('s2-desc').innerText = t.s2.desc || '';
            if(t.s2.specs) {
                if(document.getElementById('s2-t1')) document.getElementById('s2-t1').innerText = t.s2.specs[0];
                if(document.getElementById('s2-t2')) document.getElementById('s2-t2').innerText = t.s2.specs[1];
                if(document.getElementById('s2-t3')) document.getElementById('s2-t3').innerText = t.s2.specs[2];
            }

            // Service 3
            if(document.getElementById('s3-title')) document.getElementById('s3-title').innerText = t.s3.title || 'Audio';
            if(document.getElementById('s3-desc')) document.getElementById('s3-desc').innerText = t.s3.desc || '';
            if(t.s3.specs) {
                if(document.getElementById('s3-t1')) document.getElementById('s3-t1').innerText = t.s3.specs[0];
                if(document.getElementById('s3-t2')) document.getElementById('s3-t2').innerText = t.s3.specs[1];
                if(document.getElementById('s3-t3')) document.getElementById('s3-t3').innerText = t.s3.specs[2];
            }

            // CTA
            if(document.getElementById('cta-title')) document.getElementById('cta-title').innerText = t.cta.title || 'API';
            if(document.getElementById('cta-desc')) document.getElementById('cta-desc').innerText = t.cta.desc || 'Join us';
            if(document.getElementById('cta-btn')) document.getElementById('cta-btn').innerText = t.cta.btn || 'Get Access';

            // Modal Translations (Video)
            if(t.modal && t.modal.video) {
                const tm = t.modal.video;
                if(document.querySelector('#modal-content-video h2')) document.querySelector('#modal-content-video h2').innerText = tm.title;
                if(document.querySelector('#modal-content-video p')) document.querySelector('#modal-content-video p').innerText = tm.sub;
                
                // Stats Labels
                const statLabels = document.querySelectorAll('#modal-content-video .stat-label');
                if(statLabels.length >= 3) {
                    statLabels[0].innerText = tm.stats[0];
                    statLabels[1].innerText = tm.stats[1];
                    statLabels[2].innerText = tm.stats[2];
                }

                if(document.querySelector('#modal-content-video .modal-section h3')) document.querySelector('#modal-content-video .modal-section h3').innerHTML = tm.how;
                if(document.querySelector('#modal-content-video .modal-section p')) document.querySelector('#modal-content-video .modal-section p').innerText = tm.desc;

                // Points - Using innerHTML to keep strong tags
                const listItems = document.querySelectorAll('#modal-content-video .modal-list li');
                if(listItems.length >= 3 && tm.points) {
                    listItems[0].innerHTML = tm.points[0];
                    listItems[1].innerHTML = tm.points[1];
                    listItems[2].innerHTML = tm.points[2];
                }

                // Chart Vis Labels
                if(document.querySelector('#modal-content-video .vis-title')) document.querySelector('#modal-content-video .vis-title').innerText = tm.vis.title;
                const visLabels = document.querySelectorAll('#modal-content-video .vis-label');
                if(visLabels.length >= 4 && tm.vis.layers) {
                    visLabels[0].innerText = tm.vis.layers[0];
                    visLabels[1].innerText = tm.vis.layers[1];
                    visLabels[2].innerText = tm.vis.layers[2];
                    visLabels[3].innerText = tm.vis.layers[3];
                }
            }
            
            // Modal Translations (Image)
            if(t.modal && t.modal.image) {
                const ti = t.modal.image;
                if(document.querySelector('#modal-content-image h2')) document.querySelector('#modal-content-image h2').innerText = ti.title;
                if(document.querySelector('#modal-content-image p')) document.querySelector('#modal-content-image p').innerText = ti.sub;
                
                const statLabels = document.querySelectorAll('#modal-content-image .stat-label');
                if(statLabels.length >= 3) {
                    statLabels[0].innerText = ti.stats[0];
                    statLabels[1].innerText = ti.stats[1];
                    statLabels[2].innerText = ti.stats[2];
                }

                if(document.querySelector('#modal-content-image .modal-section h3')) document.querySelector('#modal-content-image .modal-section h3').innerHTML = ti.how;
                if(document.querySelector('#modal-content-image .modal-section p')) document.querySelector('#modal-content-image .modal-section p').innerText = ti.desc;

                const listItems = document.querySelectorAll('#modal-content-image .modal-list li');
                if(listItems.length >= 3 && ti.points) {
                    listItems[0].innerHTML = ti.points[0];
                    listItems[1].innerHTML = ti.points[1];
                    listItems[2].innerHTML = ti.points[2];
                }

                if(document.querySelector('#modal-content-image .vis-title')) document.querySelector('#modal-content-image .vis-title').innerText = ti.vis.title;
                const visLabels = document.querySelectorAll('#modal-content-image .vis-label');
                if(visLabels.length >= 4 && ti.vis.layers) {
                    visLabels[0].innerText = ti.vis.layers[0];
                    visLabels[1].innerText = ti.vis.layers[1];
                    visLabels[2].innerText = ti.vis.layers[2];
                    visLabels[3].innerText = ti.vis.layers[3];
                }
            }

            // Modal Translations (Audio)
            if(t.modal && t.modal.audio) {
                const ta = t.modal.audio;
                if(document.querySelector('#modal-content-audio h2')) document.querySelector('#modal-content-audio h2').innerText = ta.title;
                if(document.querySelector('#modal-content-audio p')) document.querySelector('#modal-content-audio p').innerText = ta.sub;
                
                const statLabels = document.querySelectorAll('#modal-content-audio .stat-label');
                if(statLabels.length >= 3) {
                    statLabels[0].innerText = ta.stats[0];
                    statLabels[1].innerText = ta.stats[1];
                    statLabels[2].innerText = ta.stats[2];
                }

                if(document.querySelector('#modal-content-audio .modal-section h3')) document.querySelector('#modal-content-audio .modal-section h3').innerHTML = ta.how;
                if(document.querySelector('#modal-content-audio .modal-section p')) document.querySelector('#modal-content-audio .modal-section p').innerText = ta.desc;

                const listItems = document.querySelectorAll('#modal-content-audio .modal-list li');
                if(listItems.length >= 3 && ta.points) {
                    listItems[0].innerHTML = ta.points[0];
                    listItems[1].innerHTML = ta.points[1];
                    listItems[2].innerHTML = ta.points[2];
                }

                if(document.querySelector('#modal-content-audio .vis-title')) document.querySelector('#modal-content-audio .vis-title').innerText = ta.vis.title;
                const visLabels = document.querySelectorAll('#modal-content-audio .vis-label');
                if(visLabels.length >= 4 && ta.vis.layers) {
                    visLabels[0].innerText = ta.vis.layers[0];
                    visLabels[1].innerText = ta.vis.layers[1];
                    visLabels[2].innerText = ta.vis.layers[2];
                    visLabels[3].innerText = ta.vis.layers[3];
                }
            }
        }
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\privasi-app\resources\views/pages/services.blade.php ENDPATH**/ ?>