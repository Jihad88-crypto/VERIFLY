<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Image Analysis | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <!-- EXIF.js Library for Metadata Extraction -->
    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
    <style>
        :root {
            --primary: #14F195; /* Green for Image */
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
        .orb-1 { width: 900px; height: 900px; background: #10b981; top: -300px; right: -200px; opacity: 0.4; }
        .orb-2 { width: 700px; height: 700px; background: #14F195; bottom: -200px; left: -200px; animation-duration: 35s; opacity: 0.4; }
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
            transform: translateY(-8px); box-shadow: 0 25px 50px -12px rgba(20, 241, 149, 0.15); 
        }
        .upload-zone::before {
            content: ''; position: absolute; inset: 0; 
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 24px 24px; opacity: 0.3; pointer-events: none;
        }
        
        .upload-icon {
            width: 80px; height: 80px; background: #d1fae5; color: var(--primary);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin: 0 auto 20px;
        }
        .upload-title { font-weight: 700; font-size: 1.2rem; margin-bottom: 8px; }
        .upload-desc { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 24px; }
        .file-types { display: inline-block; padding: 6px 16px; background: #f1f5f9; border-radius: 99px; font-size: 0.8rem; font-weight: 600; color: #64748b; }

        input[type="file"] { display: none; }
        
        /* GLASSMORPHISM SCANNING STATE */
        .scanning-state {
            display: none; 
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 32px;
            padding: 60px 40px;
            box-shadow: 
                0 8px 32px 0 rgba(31, 38, 135, 0.15),
                inset 0 1px 0 0 rgba(255, 255, 255, 0.5);
            position: relative;
            overflow: hidden;
        }
        
        .scanning-state::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(20, 241, 149, 0.1), transparent);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .stage-indicator {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 40px;
        }
        
        .stage-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .stage-dot.active {
            background: var(--primary);
            box-shadow: 0 0 20px rgba(20, 241, 149, 0.6);
            transform: scale(1.3);
        }
        
        .pixel-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 8px;
            max-width: 400px;
            margin: 0 auto 40px;
        }
        
        .pixel {
            aspect-ratio: 1;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            animation: pixelPulse 2s ease-in-out infinite;
        }
        
        .pixel.active {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            box-shadow: 0 0 10px rgba(20, 241, 149, 0.5);
        }
        
        @keyframes pixelPulse {
            0%, 100% { opacity: 0.3; transform: scale(0.9); }
            50% { opacity: 1; transform: scale(1); }
        }
        
        .scan-status {
            color: #1e1e2f;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 8px;
            text-align: center;
        }
        
        .scan-detail {
            color: var(--text-muted);
            font-size: 0.95rem;
            text-align: center;
        }
        
        /* GLASSMORPHISM RESULT CARD */
        .result-card {
            display: none;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 
                0 8px 32px 0 rgba(31, 38, 135, 0.2),
                inset 0 1px 0 0 rgba(255, 255, 255, 0.6);
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        @keyframes slideUp {
            from { transform: translateY(40px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .verdict-header {
            padding: 50px;
            text-align: center;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            position: relative;
            overflow: hidden;
        }
        
        .verdict-header.safe {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .verdict-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .verdict-icon {
            font-size: 3rem;
            margin-bottom: 16px;
            display: block;
        }
        
        .verdict-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 12px;
            text-shadow: 0 2px 20px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
        }
        
        .verdict-score {
            display: inline-block;
            padding: 8px 20px;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 99px;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }
        
        .result-body {
            padding: 40px;
        }
        
        .accordion-item {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            margin-bottom: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .accordion-item:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .accordion-header {
            padding: 20px 24px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            user-select: none;
        }
        
        .accordion-title {
            font-weight: 700;
            font-size: 1.05rem;
            color: #1e1e2f;
        }
        
        .accordion-icon {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--text-muted);
            flex-shrink: 0;
        }
        
        .accordion-item.active .accordion-icon {
            transform: rotate(-180deg);
            color: var(--primary);
        }
        
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .accordion-item.active .accordion-content {
            max-height: 500px;
        }
        
        .accordion-body {
            padding: 0 24px 24px;
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        .score-bar {
            height: 8px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 99px;
            overflow: hidden;
            margin: 12px 0;
        }
        
        .score-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            border-radius: 99px;
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0;
        }
        
        .btn-retry {
            display: block;
            width: 100%;
            padding: 20px;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 0 0 32px 32px;
            color: #1e1e2f;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-retry:hover {
            background: rgba(255, 255, 255, 0.5);
            color: var(--primary);
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
            <a href="{{ route('dashboard') }}" class="btn-back">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back</span>
            </a>
            <h1>Image Analysis <span style="font-size: 0.5em; background: var(--primary); color: #064e3b; padding: 4px 12px; border-radius: 99px; vertical-align: middle;">PRO v2.0</span></h1>
            <p>Scan photos for pixel editing, generative AI fill, and EXIF manipulation.</p>
        </div>

        <!-- Upload Zone -->
        <div class="upload-zone" id="uploadZone" onclick="triggerUpload()">
            <div class="upload-icon">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div class="upload-title">Click to Upload or Drag Image</div>
            <div class="upload-desc">Maximum file size: 20MB</div>
            <div class="file-types">JPG, PNG, WEBP</div>
            <input type="file" id="fileInput" accept="image/*" onchange="handleFile(this.files)">
        </div>

        <!-- Scanning State -->
        <div class="scanning-state" id="scanState">
            <div class="stage-indicator">
                <div class="stage-dot" id="stage1"></div>
                <div class="stage-dot" id="stage2"></div>
                <div class="stage-dot" id="stage3"></div>
            </div>
            
            <div class="pixel-grid" id="pixelGrid"></div>
            
            <div class="scan-status" id="scanStatus">Initializing...</div>
            <div class="scan-detail" id="scanDetail">Preparing image analysis...</div>
        </div>

        <!-- Result Card -->
        <div class="result-card" id="resultCard">
            <div class="verdict-header" id="verdictHeader">
                <span class="verdict-icon" id="verdictIcon">🤖</span>
                <div class="verdict-title" id="verdictTitle">AI GENERATED</div>
                <div class="verdict-score" id="verdictScore">Confidence: 95%</div>
            </div>

            <div class="result-body">
                <!-- Metadata Accordion -->
                <div class="accordion-item" onclick="toggleAccordion(this)">
                    <div class="accordion-header">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--primary);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="accordion-title">Metadata Analysis</span>
                        </div>
                        <svg class="accordion-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="accordion-content">
                        <div class="accordion-body" id="metadataContent">
                            <p>Analyzing EXIF data...</p>
                        </div>
                    </div>
                </div>

                <!-- Pixel Accordion -->
                <div class="accordion-item" onclick="toggleAccordion(this)">
                    <div class="accordion-header">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--primary);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="accordion-title">Pixel Analysis</span>
                        </div>
                        <svg class="accordion-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="accordion-content">
                        <div class="accordion-body" id="pixelContent">
                            <p>Analyzing pixel patterns...</p>
                        </div>
                    </div>
                </div>

                <!-- AI Detection Accordion -->
                <div class="accordion-item" onclick="toggleAccordion(this)">
                    <div class="accordion-header">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--primary);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            <span class="accordion-title">AI Detection</span>
                        </div>
                        <svg class="accordion-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="accordion-content">
                        <div class="accordion-body" id="aiContent">
                            <p>Running AI detection...</p>
                        </div>
                    </div>
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

        // Initialize pixel grid
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
                const file = files[0];
                
                // Hide upload, show scanning
                uploadZone.style.display = 'none';
                scanState.style.display = 'block';
                
                // Start verification process
                startVerification(file);
            }
        }

        async function startVerification(file) {
            // Single backend API call for ALL analysis
            document.getElementById('scanStatus').textContent = 'Analyzing Image...';
            document.getElementById('scanDetail').textContent = 'Running comprehensive AI detection...';
            
            // Activate all stages for visual effect
            document.getElementById('stage1').classList.add('active');
            await new Promise(resolve => setTimeout(resolve, 500));
            document.getElementById('stage2').classList.add('active');
            await new Promise(resolve => setTimeout(resolve, 500));
            document.getElementById('stage3').classList.add('active');
            
            // Animate pixels
            const pixels = pixelGrid.querySelectorAll('.pixel');
            for(let i = 0; i < pixels.length; i++) {
                setTimeout(() => {
                    pixels[i].classList.add('active');
                }, i * 20);
            }
            
            // Call backend API for FULL analysis
            const result = await analyzeWithBackend(file);
            
            // Deactivate pixels
            pixels.forEach(p => p.classList.remove('active'));
            
            // Show results
            showResults(result);
        }

        async function analyzeWithBackend(file) {
            const maxRetries = 3;
            let lastError = null;
            
            for (let attempt = 1; attempt <= maxRetries; attempt++) {
                try {
                    console.log(`Backend Analysis attempt ${attempt}/${maxRetries}`);
                    
                    // Create FormData to send file to Laravel
                    const formData = new FormData();
                    formData.append('image', file);
                    
                    console.log('File details:', {
                        name: file.name,
                        size: file.size,
                        type: file.type
                    });
                    
                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    console.log('CSRF Token:', csrfToken ? 'Present' : 'Missing');
                    
                    // Call Laravel API endpoint
                    const controller = new AbortController();
                    const timeout = setTimeout(() => controller.abort(), 60000); // 60 second timeout

                    console.log('Sending request to /api/detect-ai...');
                    
                    const response = await fetch('/api/detect-ai', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData,
                        signal: controller.signal
                    });

                    clearTimeout(timeout);
                    
                    console.log('Response received:', {
                        status: response.status,
                        statusText: response.statusText,
                        ok: response.ok
                    });

                    const result = await response.json();
                    console.log('Backend API Response:', result);
                    
                    if (result.success) {
                        // Success response from backend - use confidence directly
                        return {
                            confidence: result.confidence,
                            isAI: result.isAI,
                            generator: result.generator,
                            method: result.method,
                            usedGoogleVision: result.usedGoogleVision,
                            scores: result.scores || {},
                            metadata: result.metadata || {}  // ✅ ADD METADATA!
                        };
                    } else {
                        // Error response from backend
                        console.error('Backend API Error Response:', result);
                        
                        if (result.loading && attempt < maxRetries) {
                            // Model is loading, wait and retry
                            console.warn(`Model loading, waiting ${attempt * 3} seconds...`);
                            await new Promise(resolve => setTimeout(resolve, attempt * 3000));
                            continue;
                        }
                        
                        throw new Error(result.error || result.message || 'API request failed');
                    }
                    
                } catch (error) {
                    lastError = error;
                    console.error(`Backend Analysis attempt ${attempt} failed:`, error);
                    
                    // Don't retry on abort
                    if (error.name === 'AbortError') {
                        break;
                    }
                    
                    // Wait before retry (except on last attempt)
                    if (attempt < maxRetries) {
                        await new Promise(resolve => setTimeout(resolve, 2000));
                    }
                }
            }
            
            // All retries failed - use fallback
            console.error('All Backend Analysis attempts failed:', lastError);
            return {
                confidence: 50,
                isAI: false,
                error: `API failed after ${maxRetries} attempts: ${lastError?.message || 'Unknown error'}`
            };
        }

        function showResults(result) {
            // Hide scanning, show results
            scanState.style.display = 'none';
            resultCard.style.display = 'block';
            
            // Use backend confidence directly (includes all 4 solutions!)
            const confidence = result.confidence || 50;
            
            // Update verdict header with proper thresholds
            const verdictHeader = document.getElementById('verdictHeader');
            const verdictIcon = document.getElementById('verdictIcon');
            const verdictTitle = document.getElementById('verdictTitle');
            const verdictScore = document.getElementById('verdictScore');
            
            // Clear previous classes AND inline styles
            verdictHeader.classList.remove('safe');
            verdictHeader.style.background = ''; // Clear inline background
            
            if(confidence >= 70) {
                // 70-100%: AUTHENTIC (Green) - LOWERED from 80%
                verdictHeader.classList.add('safe');
                verdictIcon.textContent = '✅';
                verdictTitle.textContent = 'AUTHENTIC IMAGE';
            } else if(confidence >= 55) {
                // 55-69%: LIKELY REAL (Light Orange)
                verdictHeader.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
                verdictIcon.textContent = '⚠️';
                verdictTitle.textContent = 'LIKELY REAL';
            } else if(confidence >= 45) {
                // 45-64%: SUSPICIOUS (Red/Orange) - Reduced from 40-64%
                verdictHeader.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                verdictIcon.textContent = '⚠️';
                verdictTitle.textContent = 'SUSPICIOUS';
            } else {
                // 0-44%: AI GENERATED (Dark Red)
                verdictHeader.style.background = 'linear-gradient(135deg, #dc2626 0%, #991b1b 100%)';
                verdictIcon.textContent = '❌';
                verdictTitle.textContent = 'AI GENERATED';
            }
            verdictScore.textContent = `Confidence: ${Math.round(confidence)}%`;
            
            // Helper function to format datetime
            const formatDateTime = (datetime) => {
                if (!datetime) return 'Not Available';
                try {
                    const date = new Date(datetime.replace(/:/g, '-').replace(' ', 'T'));
                    return date.toLocaleString('en-US', { 
                        year: 'numeric', month: 'long', day: 'numeric',
                        hour: '2-digit', minute: '2-digit'
                    });
                } catch (e) {
                    return datetime;
                }
            };
            
            // DEBUG: Log what we received
            console.log('🔍 FULL RESULT OBJECT:', result);
            console.log('📦 METADATA FIELD:', result.metadata);
            console.log('🏷️ Make:', result.metadata?.make);
            console.log('🏷️ Model:', result.metadata?.model);
            
            // Calculate individual scores - EXTRACT FROM BACKEND RESPONSE
            const metadataScore = result.scores?.metadata || 50;  // Metadata-only score
            const pixelScore = result.scores?.pixel || 50;        // Pixel-only score
            const finalScore = result.confidence;                 // Final combined score
            
            // DEBUG: Log scores to verify accuracy
            console.log('📊 SCORES FROM BACKEND:', {
                metadata: metadataScore,
                pixel: pixelScore,
                final: finalScore,
                raw_scores: result.scores
            });
            
            // Update accordion content with backend analysis details
            document.getElementById('metadataContent').innerHTML = `
                <p style="font-weight: 600; color: #1f2937; margin-bottom: 10px;">📸 Camera Information</p>
                <p><strong>Camera Brand:</strong> ${result.metadata?.make || 'Not Available'}</p>
                <p><strong>Camera Model:</strong> ${result.metadata?.model || 'Not Available'}</p>
                <p><strong>Photo Taken:</strong> ${formatDateTime(result.metadata?.datetime)}</p>
                <p><strong>Resolution:</strong> ${result.metadata?.width && result.metadata?.height ? `${result.metadata.width} x ${result.metadata.height} pixels` : 'Not Available'}</p>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #e5e7eb;">
                <div style="margin-top: 15px;">
                    <p style="margin-bottom: 8px;"><strong>Metadata Score:</strong></p>
                    <div style="background: #e5e7eb; border-radius: 8px; height: 24px; overflow: hidden; margin-bottom: 5px;">
                        <div style="background: linear-gradient(90deg, #10b981, #14f195); height: 100%; width: ${metadataScore}%; transition: width 0.5s ease; display: flex; align-items: center; justify-content: flex-end; padding-right: 8px; color: white; font-weight: 600; font-size: 12px;">${Math.round(metadataScore)}%</div>
                    </div>
                    <p style="font-size: 12px; color: #6b7280; margin-top: 4px;">Based on EXIF data and camera detection</p>
                </div>
            `;
            
            document.getElementById('pixelContent').innerHTML = `
                <p><strong>Analysis:</strong> Comprehensive pixel-level detection</p>
                <p><strong>Checks Performed:</strong></p>
                <ul style="margin-left: 20px; line-height: 1.8; font-size: 14px;">
                    <li>Edge sharpness analysis</li>
                    <li>Color uniqueness detection</li>
                    <li>Texture variance analysis</li>
                    <li>"Too perfect" pattern detection</li>
                    <li>Scene perfection analysis</li>
                    <li>Skin texture smoothness (for portraits)</li>
                </ul>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #e5e7eb;">
                <div style="margin-top: 15px;">
                    <p style="margin-bottom: 8px;"><strong>Pixel Analysis Score:</strong></p>
                    <div style="background: #e5e7eb; border-radius: 8px; height: 24px; overflow: hidden; margin-bottom: 5px;">
                        <div style="background: linear-gradient(90deg, #3b82f6, #60a5fa); height: 100%; width: ${pixelScore}%; transition: width 0.5s ease; display: flex; align-items: center; justify-content: flex-end; padding-right: 8px; color: white; font-weight: 600; font-size: 12px;">${Math.round(pixelScore)}%</div>
                    </div>
                    <p style="font-size: 12px; color: #6b7280; margin-top: 4px;">Based on visual pattern analysis</p>
                </div>
            `;
            
            
            document.getElementById('aiContent').innerHTML = `
                <p><strong>Detection:</strong> ${result.isAI ? 'AI-generated signatures found' : 'No strong AI signatures detected'}</p>
                <p><strong>Status:</strong> ${
                    finalScore >= 80 ? 'Authentic - Very likely real' :
                    finalScore >= 65 ? 'Likely Real - Probably authentic' :
                    finalScore >= 55 ? 'Likely Real - Probably authentic' :
                    finalScore >= 45 ? 'Suspicious - Possible AI generation' :
                    'AI Generated - High probability'
                }</p>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #e5e7eb;">
                <div style="margin-top: 15px;">
                    <p style="margin-bottom: 8px;"><strong>Final Combined Score:</strong></p>
                    <div style="background: #e5e7eb; border-radius: 8px; height: 28px; overflow: hidden; margin-bottom: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <div style="background: ${
                            finalScore >= 65 ? 'linear-gradient(90deg, #10b981, #14f195)' :
                            finalScore >= 45 ? 'linear-gradient(90deg, #f59e0b, #fbbf24)' :
                            'linear-gradient(90deg, #ef4444, #f87171)'
                        }; height: 100%; width: ${finalScore}%; transition: width 0.5s ease; display: flex; align-items: center; justify-content: flex-end; padding-right: 10px; color: white; font-weight: 700; font-size: 14px;">${Math.round(finalScore)}%</div>
                    </div>
                    <p style="font-size: 12px; color: #6b7280; margin-top: 4px;">Weighted combination: Metadata (70%) + Pixel (30%)</p>
                </div>
            `;
            
            // Animate score bars
            setTimeout(() => {
                document.querySelectorAll('.score-fill').forEach(fill => {
                    fill.style.width = fill.style.width;
                });
            }, 100);
        }

        function toggleAccordion(element) {
            element.classList.toggle('active');
        }

        function resetPage() {
            resultCard.style.display = 'none';
            uploadZone.style.display = 'block';
            fileInput.value = '';
            
            // Reset stage indicators
            document.querySelectorAll('.stage-dot').forEach(dot => {
                dot.classList.remove('active');
            });
        }
    </script>
</body>
</html>
