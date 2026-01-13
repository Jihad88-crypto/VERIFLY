<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #64748b; /* Slate to match Activity Theme */
            --secondary: #14F195; 
            --accent: #00C2FF; 
            --success: #10b981;
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

        /* DYNAMIC AURORA BACKGROUND (Matching Dashboard) */
        .ambient-bg {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -3;
            background: #ffffff; overflow: hidden;
        }
        
        .orb {
            position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.8;
            animation: floatOrb 20s infinite ease-in-out alternate;
        }
        
        /* Slate/Cool Gray Theme */
        .orb-1 { width: 900px; height: 900px; background: #64748b; /* Slate */ top: -300px; right: -200px; opacity: 0.5; }
        .orb-2 { width: 700px; height: 700px; background: #71717a; /* Zinc */ bottom: -200px; left: -200px; animation-duration: 35s; opacity: 0.4; }
        .orb-3 { 
            width: 500px; height: 500px; background: #94a3b8; /* Cool Gray */ 
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
        @keyframes pulseOrb {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 0.4; }
            50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.6; }
            100% { transform: translate(-50%, -50%) scale(1); opacity: 0.4; }
        }

        .container { max-width: 1280px; margin: 0 auto; padding: 40px 20px 80px; }

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

        /* TABLE CARD */
        .table-card {
            background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px);
            border: 1px solid #e2e8f0; border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
        }
        
        /* FILTERS */
        .filter-bar {
            padding: 20px; border-bottom: 1px solid #e2e8f0;
            display: flex; justify-content: space-between; align-items: center;
            gap: 16px; flex-wrap: wrap;
        }
        .search-wrapper { position: relative; flex: 1; min-width: 250px; }
        .search-input {
            width: 100%; padding: 10px 16px 10px 40px; border-radius: 99px; border: 1px solid #cbd5e1;
            font-family: inherit; font-size: 0.95rem; outline: none; transition: border 0.2s;
        }
        .search-input:focus { border-color: var(--primary); }
        .search-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8;
        }
        
        .filter-group { display: flex; gap: 10px; }
        .filter-select {
            padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1;
            font-family: inherit; font-size: 0.9rem; color: #475569; cursor: pointer;
        }

        /* TABLE STYLE */
        .log-table { width: 100%; border-collapse: collapse; }
        .log-table th {
            text-align: left; padding: 16px 24px; background: #f8fafc; color: #64748b;
            font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .log-table td {
            padding: 16px 24px; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 0.95rem;
        }
        .log-table tr:hover { background: #fdfdfd; }
        .log-table tr:last-child td { border-bottom: none; }

        /* STATUS BADGES */
        .status-badge {
            display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 99px;
            font-weight: 600; font-size: 0.85rem;
        }
        .status-safe { background: #dcfce7; color: #15803d; }
        .status-fake { background: #fee2e2; color: #b91c1c; }
        .status-risk { background: #ffedd5; color: #c2410c; }

        .type-icon { font-size: 1.1rem; vertical-align: middle; margin-right: 6px; }

        .btn-action {
            padding: 6px 12px; border-radius: 6px; border: 1px solid #e2e8f0; background: #fff;
            color: #64748b; cursor: pointer; font-size: 0.85rem; font-weight: 600; transition: all 0.2s;
        }
        .btn-action:hover { border-color: var(--primary); color: var(--primary); }

        .empty-state {
            padding: 60px; text-align: center; color: #94a3b8;
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
        
        <div class="page-header">
            <a href="{{ route('dashboard') }}" class="btn-back" id="btn-back">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span id="btn-back-text">Back</span>
            </a>
            <h1><span id="page-title-text">Activity Logs</span> <span style="font-size: 0.5em; background: var(--primary); color: white; padding: 4px 12px; border-radius: 99px; vertical-align: middle;">PRO v2.0</span></h1>
            <p id="page-subtitle">Track your verification history, audit trails, and forensic reports.</p>
        </div>

        <div class="table-card">
            
            <div class="filter-bar">
                <div class="search-wrapper">
                    <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" class="search-input" id="search-input" placeholder="Search by file name or ID...">
                </div>
                <div class="filter-group">
                    <select class="filter-select" id="filter-type">
                        <option>All Types</option>
                        <option>Video</option>
                        <option>Image</option>
                        <option>Audio</option>
                    </select>
                    <select class="filter-select" id="filter-status">
                        <option>All Status</option>
                        <option>Safe</option>
                        <option>Fake</option>
                    </select>
                    <button class="btn-action" style="background: #1e293b; color: #fff; border: none;" id="btn-export">Export CSV</button>
                </div>
            </div>

            <table class="log-table">
                <thead>
                    <tr>
                        <th width="15%" id="th-date">Date</th>
                        <th width="30%" id="th-name">File Name</th>
                        <th width="15%" id="th-type">Type</th>
                        <th width="15%" id="th-result">Result</th>
                        <th width="10%" id="th-conf">Confidence</th>
                        <th width="15%" style="text-align: right;" id="th-acts">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Mock Data 1 -->
                    <tr>
                        <td>Dec 27, 10:42 AM</td>
                        <td>interview_clip_v2.mp4</td>
                        <td><span class="type-icon">🎥</span> Video</td>
                        <td><span class="status-badge status-fake">⚠️ Deepfake</span></td>
                        <td>99.8%</td>
                        <td style="text-align: right;">
                            <button class="btn-action report-btn">Report</button>
                        </td>
                    </tr>
                    <!-- Mock Data 2 -->
                    <tr>
                        <td>Dec 27, 09:15 AM</td>
                        <td>profile_photo_hq.jpg</td>
                        <td><span class="type-icon">🖼️</span> Image</td>
                        <td><span class="status-badge status-safe">✅ Authentic</span></td>
                        <td>98.5%</td>
                        <td style="text-align: right;">
                            <button class="btn-action report-btn">Report</button>
                        </td>
                    </tr>
                     <!-- Mock Data 3 -->
                     <tr>
                        <td>Dec 26, 14:20 PM</td>
                        <td>voice_message_001.mp3</td>
                        <td><span class="type-icon">🎙️</span> Audio</td>
                        <td><span class="status-badge status-fake">🤖 Synthetic</span></td>
                        <td>94.2%</td>
                        <td style="text-align: right;">
                            <button class="btn-action report-btn">Report</button>
                        </td>
                    </tr>
                     <!-- Mock Data 4 -->
                     <tr>
                        <td>Dec 26, 11:05 AM</td>
                        <td>cctv_footage_raw.avi</td>
                        <td><span class="type-icon">🎥</span> Video</td>
                        <td><span class="status-badge status-safe">✅ Authentic</span></td>
                        <td>99.1%</td>
                        <td style="text-align: right;">
                            <button class="btn-action report-btn">Report</button>
                        </td>
                    </tr>
                    <!-- Mock Data 5 -->
                    <tr>
                        <td>Dec 25, 16:45 PM</td>
                        <td>document_scan_04.png</td>
                        <td><span class="type-icon">🖼️</span> Image</td>
                        <td><span class="status-badge status-risk">⚡ Edited</span></td>
                        <td>76.4%</td>
                        <td style="text-align: right;">
                            <button class="btn-action report-btn">Report</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Pagination Mock -->
            <div style="padding: 20px; border-top: 1px solid #f1f5f9; text-align: center; color: #94a3b8; font-size: 0.9rem;" id="pagination-text">
                Showing 5 of 24 results
            </div>

        </div>

    </div>

    <script>
        const translations = {
            en: {
                nav: { dash: "Dashboard", tech: "Technology", dev: "Developers", price: "Pricing", supp: "Support" },
                page: { title: "Activity Logs", sub: "Track your verification history, audit trails, and forensic reports." },
                back: "Back",
                filter: { search: "Search by file name or ID...", type: "All Types", status: "All Status", export: "Export CSV" },
                table: { date: "Date", name: "File Name", type: "Type", res: "Result", conf: "Confidence", act: "Actions", rep: "Report" },
                pg: "Showing 5 of 24 results"
            },
            id: {
                nav: { dash: "Dasbor", tech: "Teknologi", dev: "Developers", price: "Harga", supp: "Bantuan" },
                page: { title: "Riwayat Aktivitas", sub: "Lacak riwayat verifikasi, jejak audit, dan laporan forensik Anda." },
                back: "Kembali",
                filter: { search: "Cari nama file atau ID...", type: "Semua Tipe", status: "Semua Status", export: "Ekspor CSV" },
                table: { date: "Tanggal", name: "Nama File", type: "Tipe", res: "Hasil", conf: "Keyakinan", act: "Aksi", rep: "Laporan" },
                pg: "Menampilkan 5 dari 24 hasil"
            },
            es: {
                nav: { dash: "Tablero", tech: "Tecnología", dev: "Desarrolladores", price: "Precios", supp: "Ayuda" },
                page: { title: "Registros de Actividad", sub: "Rastree su historial de verificación y reportes forenses." },
                back: "Volver",
                filter: { search: "Buscar por nombre...", type: "Todos los Tipos", status: "Todos los Estados", export: "Exportar CSV" },
                table: { date: "Fecha", name: "Nombre Archivo", type: "Tipo", res: "Resultado", conf: "Confianza", act: "Acciones", rep: "Reporte" },
                pg: "Mostrando 5 de 24 resultados"
            },
            fr: {
                nav: { dash: "Tableau", tech: "Technologie", dev: "Développeurs", price: "Tarifs", supp: "Support" },
                page: { title: "Journaux d'activité", sub: "Suivez votre historique de vérification et rapports." },
                back: "Retour",
                filter: { search: "Rechercher un fichier...", type: "Tous les types", status: "Tous les statuts", export: "Exporter CSV" },
                table: { date: "Date", name: "Nom du fichier", type: "Type", res: "Résultat", conf: "Confiance", act: "Actions", rep: "Rapport" },
                pg: "Affichage de 5 sur 24 résultats"
            },
            de: {
                nav: { dash: "Dashboard", tech: "Technologie", dev: "Entwickler", price: "Preise", supp: "Support" },
                page: { title: "Aktivitätsprotokolle", sub: "Verfolgen Sie Ihren Verifizierungsverlauf." },
                back: "Zurück",
                filter: { search: "Suche nach Dateiname...", type: "Alle Typen", status: "Alle Status", export: "CSV Exportieren" },
                table: { date: "Datum", name: "Dateiname", type: "Typ", res: "Ergebnis", conf: "Vertrauen", act: "Aktionen", rep: "Bericht" },
                pg: "Zeige 5 von 24 Ergebnissen"
            },
            jp: {
                nav: { dash: "ダッシュボード", tech: "技術", dev: "開発者", price: "価格", supp: "サポート" },
                page: { title: "活動ログ", sub: "検証履歴、監査証跡、フォレンジックレポートを追跡します。" },
                back: "戻る",
                filter: { search: "ファイル名で検索...", type: "全タイプ", status: "全ステータス", export: "CSV出力" },
                table: { date: "日付", name: "ファイル名", type: "タイプ", res: "結果", conf: "信頼度", act: "アクション", rep: "レポート" },
                pg: "24件中5件を表示"
            },
            cn: {
                nav: { dash: "仪表板", tech: "技术", dev: "开发者", price: "价格", supp: "支持" },
                page: { title: "活动日志", sub: "跟踪您的验证历史记录、审计跟踪和取证报告。" },
                back: "返回",
                filter: { search: "按文件名搜索...", type: "所有类型", status: "所有状态", export: "导出CSV" },
                table: { date: "日期", name: "文件名", type: "类型", res: "结果", conf: "置信度", act: "操作", rep: "报告" },
                pg: "显示 24 个结果中的 5 个"
            },
            ru: {
                nav: { dash: "Дашборд", tech: "Технологии", dev: "Разработчики", price: "Цены", supp: "Поддержка" },
                page: { title: "Журналы активности", sub: "Отслеживайте историю проверок и отчеты." },
                back: "Назад",
                filter: { search: "Поиск по имени...", type: "Все типы", status: "Все статусы", export: "Экспорт CSV" },
                table: { date: "Дата", name: "Имя файла", type: "Тип", res: "Результат", conf: "Доверие", act: "Действия", rep: "Отчет" },
                pg: "Показано 5 из 24 результатов"
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

            // Page Header
            const titleText = document.getElementById('page-title-text');
            if(titleText) titleText.innerText = t.page.title;
            document.getElementById('page-subtitle').innerText = t.page.sub;
            
            // Fixed Back Button Translation
            const backText = document.getElementById('btn-back-text');
            if(backText) backText.innerText = t.back;

            // Filters
            document.getElementById('search-input').placeholder = t.filter.search;
            document.getElementById('filter-type').options[0].text = t.filter.type;
            document.getElementById('filter-status').options[0].text = t.filter.status;
            document.getElementById('btn-export').innerText = t.filter.export;

            // Table Header
            document.getElementById('th-date').innerText = t.table.date;
            document.getElementById('th-name').innerText = t.table.name;
            document.getElementById('th-type').innerText = t.table.type;
            document.getElementById('th-result').innerText = t.table.res;
            document.getElementById('th-conf').innerText = t.table.conf;
            document.getElementById('th-acts').innerText = t.table.act;

            // Table Content (Buttons)
            document.querySelectorAll('.report-btn').forEach(btn => btn.innerText = t.table.rep);

            // Pagination
            document.getElementById('pagination-text').innerText = t.pg;
        }

        window.addEventListener('languageChanged', (e) => applyLang(e.detail.lang));
        document.addEventListener('DOMContentLoaded', () => {
             const savedLang = localStorage.getItem('privasi_lang') || 'en';
             applyLang(savedLang);
        });
    </script>
</body>
</html>
