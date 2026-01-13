<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing & Plans | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ec4899; /* Pink for Billing */
            --secondary: #8b5cf6; 
            --accent: #f43f5e; 
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

        /* Ambient BG (Dominant Pink Theme) */
        .ambient-bg {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -3;
            background: #ffffff; overflow: hidden;
        }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.6;
            animation: floatOrb 25s infinite ease-in-out alternate;
        }
        /* Pink + Rose + Fuchsia */
        .orb-1 { width: 900px; height: 900px; background: #ec4899; /* Pink */ top: -300px; right: -200px; opacity: 0.5; }
        .orb-2 { width: 700px; height: 700px; background: #f43f5e; /* Rose */ bottom: -200px; left: -200px; animation-duration: 35s; opacity: 0.4; }
        .orb-3 { 
            width: 500px; height: 500px; background: #d946ef; /* Fuchsia */ 
            top: 40%; left: 40%; transform: translate(-50%, -50%);
            animation-name: pulseOrb; animation-duration: 15s; opacity: 0.3;
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
            0% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.1); }
            100% { transform: translate(-50%, -50%) scale(1); }
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

        /* GRID */
        .billing-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
        @media(max-width: 900px) { .billing-grid { grid-template-columns: 1fr; } }

        .card {
            background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px);
            border: 1px solid #e2e8f0; border-radius: 20px;
            padding: 30px; box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
            margin-bottom: 24px;
        }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .card-title { font-weight: 700; font-size: 1.25rem; font-family: 'Space Grotesk'; display: flex; align-items: center; gap: 10px; }

        /* PLAN DETAILS */
        .plan-badge { 
            background: linear-gradient(135deg, #ec4899, #f43f5e); color: white; 
            font-weight: 700; padding: 6px 16px; border-radius: 99px; font-size: 0.9rem;
        }
        .plan-price { font-family: 'Space Grotesk'; font-weight: 800; font-size: 2.5rem; margin-bottom: 8px; color: #1e1e2f; }
        .plan-price span { font-size: 1rem; color: #64748b; font-weight: 600; }

        /* USAGE METER */
        .usage-box { margin-top: 24px; padding: 20px; background: #fff; border-radius: 16px; border: 1px solid #f1f5f9; }
        .progress-bar-bg { width: 100%; height: 10px; background: #e2e8f0; border-radius: 99px; margin: 10px 0; overflow: hidden; }
        .progress-bar-fill { height: 100%; background: var(--primary); width: 65%; border-radius: 99px; transition: width 1s ease; }
        
        /* PAYMENT METHODS */
        .pay-method {
            display: flex; align-items: center; gap: 16px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px;
            margin-bottom: 12px; transition: all 0.2s; background: #fff;
        }
        .pay-method:hover { border-color: var(--primary); box-shadow: 0 4px 12px rgba(236, 72, 153, 0.1); }
        .card-icon { width: 40px; height: 25px; background: #1e1e2f; border-radius: 4px; }

        /* ACTIONS */
        .btn-upgrade {
            width: 100%; padding: 14px; border-radius: 12px; background: var(--primary);
            color: white; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; font-size: 1rem;
        }
        .btn-upgrade:hover { transform: translateY(-2px); box-shadow: 0 8px 20px -4px rgba(236, 72, 153, 0.4); }
        
        .btn-outline {
            padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: transparent;
            color: #475569; font-weight: 600; cursor: pointer; transition: all 0.2s;
        }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); background: #fff; }

        /* HISTORY TABLE */
        .hist-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .hist-table th { text-align: left; padding: 12px; color: #64748b; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
        .hist-table td { padding: 16px 12px; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 0.95rem; }
        .download-link { color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.9rem; }
        .download-link:hover { text-decoration: underline; }

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
            <h1><span id="page-title">Billing & Plans</span></h1>
            <p id="page-sub">Manage your subscription, payment details, and invoices.</p>
        </div>

        <div class="billing-grid">
            
            <!-- LEFT: CURRENT PLAN & USAGE -->
            <div style="display: flex; flex-direction: column;">
                
                <div class="card">
                    <div class="card-header">
                        <div class="card-title" id="t-curr-plan">⚡ Current Plan</div>
                        <span class="plan-badge" id="curr-badge">PRO PLAN</span>
                    </div>
                    
                    <div style="display: flex; align-items: baseline; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                        <div>
                            <div class="plan-price">$49 <span>/ month</span></div>
                            <p style="color: #64748b; font-size: 0.95rem;" id="next-bill">Next billing date: <strong>Jan 27, 2026</strong></p>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn-outline" id="btn-cancel">Cancel</button>
                            <button class="btn-upgrade" style="width: auto; padding: 10px 24px;" id="btn-change">Change Plan</button>
                        </div>
                    </div>

                    <div class="usage-box">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <strong style="color: #334155;" id="t-usage">Monthly Credits</strong>
                            <span style="color: var(--primary); font-weight: 700;">850 / 1000</span>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill"></div>
                        </div>
                        <p style="font-size: 0.85rem; color: #94a3b8;" id="usage-desc">Credits reset on Feb 01, 2026. Need more?</p>
                    </div>
                </div>

                <div class="card">
                     <div class="card-header">
                        <div class="card-title" id="t-pay-methods">💳 Payment Methods</div>
                        <button class="btn-outline" style="font-size: 0.85rem;" id="btn-add-pay">+ Add New</button>
                    </div>
                    
                    <div class="pay-method">
                        <div class="card-icon" style="background: #1e1e2f; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.6rem;">VISA</div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: #334155;">Visa ending in 4242</div>
                            <div style="font-size: 0.85rem; color: #64748b;">Expiry 12/28 • Default</div>
                        </div>
                        <button style="border: none; background: none; color: #94a3b8; cursor: pointer;">✏️</button>
                    </div>

                    <div class="pay-method">
                         <div class="card-icon" style="background: #0070ba; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-family: arial; font-size: 0.6rem;">Pay</div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: #334155;">PayPal</div>
                            <div style="font-size: 0.85rem; color: #64748b;">user@example.com</div>
                        </div>
                         <button style="border: none; background: none; color: #94a3b8; cursor: pointer;">🗑️</button>
                    </div>
                </div>

            </div>

            <!-- RIGHT: HISTORY -->
            <div>
                 <div class="card" style="height: 100%;">
                    <div class="card-header">
                        <div class="card-title" id="t-hist">📄 Invoice History</div>
                    </div>
                    
                    <table class="hist-table">
                        <thead>
                            <tr>
                                <th id="th-date">Date</th>
                                <th id="th-amount">Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Dec 27, 2025</td>
                                <td style="font-weight: 600;">$49.00</td>
                                <td style="text-align: right;"><a href="#" class="download-link">PDF</a></td>
                            </tr>
                            <tr>
                                <td>Nov 27, 2025</td>
                                <td style="font-weight: 600;">$49.00</td>
                                <td style="text-align: right;"><a href="#" class="download-link">PDF</a></td>
                            </tr>
                            <tr>
                                <td>Oct 27, 2025</td>
                                <td style="font-weight: 600;">$49.00</td>
                                <td style="text-align: right;"><a href="#" class="download-link">PDF</a></td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn-outline" style="width: 100%; margin-top: 20px; font-size: 0.9rem;" id="btn-view-all">View All Invoices</button>
                </div>
            </div>

        </div>

    </div>

    <script>
        const translations = {
            en: {
                nav: { dash: "Dashboard", tech: "Technology", dev: "Developers", price: "Pricing", supp: "Support" },
                page: { title: "Billing & Plans", sub: "Manage your subscription, payment details, and invoices." },
                back: "Back",
                sect: { curr: "⚡ Current Plan", pay: "💳 Payment Methods", hist: "📄 Invoice History" },
                plan: { badge: "PRO PLAN", next: "Next billing date:", cancel: "Cancel", change: "Change Plan" },
                usage: { title: "Monthly Credits", desc: "Credits reset on Feb 01. Need more?" },
                pay: { add: "+ Add New" },
                hist: { date: "Date", amount: "Amount", view: "View All Invoices" }
            },
            id: {
                nav: { dash: "Dasbor", tech: "Teknologi", dev: "Developers", price: "Harga", supp: "Bantuan" },
                page: { title: "Tagihan & Paket", sub: "Kelola langganan, detail pembayaran, dan faktur Anda." },
                back: "Kembali",
                sect: { curr: "⚡ Paket Saat Ini", pay: "💳 Metode Pembayaran", hist: "📄 Riwayat Faktur" },
                plan: { badge: "PAKET PRO", next: "Tagihan berikutnya:", cancel: "Batalkan", change: "Ubah Paket" },
                usage: { title: "Kredit Bulanan", desc: "Kredit direset 1 Feb. Butuh lagi?" },
                pay: { add: "+ Tambah Baru" },
                hist: { date: "Tanggal", amount: "Jumlah", view: "Lihat Semua Faktur" }
            },
            es: {
                nav: { dash: "Tablero", tech: "Tecnología", dev: "Desarrolladores", price: "Precios", supp: "Ayuda" },
                page: { title: "Facturación", sub: "Gestione su suscripción y facturas." },
                back: "Volver",
                sect: { curr: "⚡ Plan Actual", pay: "💳 Métodos de Pago", hist: "📄 Historial" },
                plan: { badge: "PLAN PRO", next: "Próxima factura:", cancel: "Cancelar", change: "Cambiar Plan" },
                usage: { title: "Créditos Mensuales", desc: "Reinicio el 1 de Feb. ¿Más?" },
                pay: { add: "+ Agregar" },
                hist: { date: "Fecha", amount: "Monto", view: "Ver Todas" }
            },
            fr: {
                nav: { dash: "Tableau", tech: "Technologie", dev: "Développeurs", price: "Tarifs", supp: "Support" },
                page: { title: "Facturation", sub: "Gérez votre abonnement et vos factures." },
                back: "Retour",
                sect: { curr: "⚡ Plan Actuel", pay: "💳 Moyens de paiement", hist: "📄 Historique" },
                plan: { badge: "PLAN PRO", next: "Prochaine facture:", cancel: "Annuler", change: "Changer" },
                usage: { title: "Crédits Mensuels", desc: "Réinitialisation 1 Fév.", pay: { add: "+ Ajouter" } },
                hist: { date: "Date", amount: "Montant", view: "Voir tout" }
            },
            de: {
                nav: { dash: "Dashboard", tech: "Technologie", dev: "Entwickler", price: "Preise", supp: "Support" },
                page: { title: "Abrechnung", sub: "Verwalten Sie Ihr Abonnement und Rechnungen." },
                back: "Zurück",
                sect: { curr: "⚡ Aktueller Plan", pay: "💳 Zahlungsarten", hist: "📄 Rechnungsverlauf" },
                plan: { badge: "PRO PLAN", next: "Nächste Rechnung:", cancel: "Kündigen", change: "Plan Ändern" },
                usage: { title: "Monatliche Credits", desc: "Reset am 1. Feb. Mehr?" },
                pay: { add: "+ Neu Hinzufügen" },
                hist: { date: "Datum", amount: "Betrag", view: "Alle Ansehen" }
            },
            jp: {
                nav: { dash: "ダッシュボード", tech: "技術", dev: "開発者", price: "価格", supp: "サポート" },
                page: { title: "請求とプラン", sub: "サブスクリプションと請求書を管理します。" },
                back: "戻る",
                sect: { curr: "⚡ 現在のプラン", pay: "💳 支払い方法", hist: "📄 請求履歴" },
                plan: { badge: "プロプラン", next: "次回の請求日:", cancel: "キャンセル", change: "プラン変更" },
                usage: { title: "月間クレジット", desc: "2月1日にリセット。追加？" },
                pay: { add: "+ 新規追加" },
                hist: { date: "日付", amount: "金額", view: "すべて表示" }
            },
            cn: {
                nav: { dash: "仪表板", tech: "技术", dev: "开发者", price: "价格", supp: "支持" },
                page: { title: "账单与计划", sub: "管理您的订阅和发票。" },
                back: "返回",
                sect: { curr: "⚡ 当前计划", pay: "💳 支付方式", hist: "📄 发票历史" },
                plan: { badge: "专业版", next: "下次账单日期:", cancel: "取消", change: "更改计划" },
                usage: { title: "每月积分", desc: "2月1日重置。需要更多？" },
                pay: { add: "+ 添加新方式" },
                hist: { date: "日期", amount: "金额", view: "查看全部" }
            },
            ru: {
                nav: { dash: "Дашборд", tech: "Технологии", dev: "Разработчики", price: "Цены", supp: "Поддержка" },
                page: { title: "Биллинг", sub: "Управление подпиской и счетами." },
                back: "Назад",
                sect: { curr: "⚡ Текущий план", pay: "💳 Способы оплаты", hist: "📄 История счетов" },
                plan: { badge: "PRO ПЛАН", next: "След. списание:", cancel: "Отмена", change: "Сменить план" },
                usage: { title: "Кредиты", desc: "Сброс 1 фев. Нужно больше?" },
                pay: { add: "+ Добавить" },
                hist: { date: "Дата", amount: "Сумма", view: "Показать все" }
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
            document.getElementById('page-title').innerText = t.page.title;
            document.getElementById('page-sub').innerText = t.page.sub;
            document.getElementById('btn-back-text').innerText = t.back;

            // Headers
            document.getElementById('t-curr-plan').innerText = t.sect.curr;
            document.getElementById('t-pay-methods').innerText = t.sect.pay;
            document.getElementById('t-hist').innerText = t.sect.hist;

            // Plan
            document.getElementById('curr-badge').innerText = t.plan.badge;
            document.getElementById('next-bill').innerHTML = t.plan.next + " <strong>Jan 27, 2026</strong>";
            document.getElementById('btn-cancel').innerText = t.plan.cancel;
            document.getElementById('btn-change').innerText = t.plan.change;

            // Usage
            document.getElementById('t-usage').innerText = t.usage.title;
            document.getElementById('usage-desc').innerText = t.usage.desc;

            // Btns
            document.getElementById('btn-add-pay').innerText = t.pay.add;
            document.getElementById('btn-view-all').innerText = t.hist.view;

            // Table
            document.getElementById('th-date').innerText = t.hist.date;
            document.getElementById('th-amount').innerText = t.hist.amount;
        }

        window.addEventListener('languageChanged', (e) => applyLang(e.detail.lang));
        document.addEventListener('DOMContentLoaded', () => {
             const savedLang = localStorage.getItem('privasi_lang') || 'en';
             applyLang(savedLang);
        });
    </script>
</body>
</html>
