<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #14b8a6; /* Teal */
            --secondary: #2dd4bf;
            --accent: #0d9488; 
            --text-main: #1e1e2f;
            --text-muted: #64748b;
            --glass-border: rgba(255, 255, 255, 0.5);
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

        /* Ambient BG (Dominant Teal Theme) */
        .ambient-bg {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -3;
            background: #ffffff; overflow: hidden;
        }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.6;
            animation: floatOrb 25s infinite ease-in-out alternate;
        }
        .orb-1 { width: 900px; height: 900px; background: #14b8a6; top: -300px; right: -200px; opacity: 0.4; }
        .orb-2 { width: 700px; height: 700px; background: #5eead4; bottom: -200px; left: -200px; animation-duration: 35s; opacity: 0.5; }
        .orb-3 { width: 400px; height: 400px; background: #0d9488; top: 40%; left: 10%; opacity: 0.3; animation-duration: 20s; }
        
        .noise-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -2;
            opacity: 0.03; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }
        @keyframes floatOrb {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(40px, 40px) rotate(5deg); }
        }

        .container { max-width: 1000px; margin: 0 auto; padding: 0 40px; }
        
        /* HERO */
        .support-hero { text-align: center; padding: 80px 0 60px; }
        .support-hero h1 {
            font-family: 'Space Grotesk', sans-serif; font-size: 3.5rem; font-weight: 800; color: #1a1b2e; margin-bottom: 24px;
        }
        .support-hero p { font-size: 1.2rem; color: var(--text-muted); margin-bottom: 40px; }
        
        .search-box {
            max-width: 500px; margin: 0 auto; position: relative;
        }
        .search-input {
            width: 100%; padding: 16px 24px 16px 50px; border-radius: 100px; border: 1px solid #e2e8f0;
            background: rgba(255,255,255,0.8); backdrop-filter: blur(10px);
            font-size: 1rem; outline: none; transition: all 0.3s;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
        }
        .search-input:focus { border-color: var(--primary); box-shadow: 0 15px 40px -10px rgba(153, 69, 255, 0.15); }
        .search-icon { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #94a3b8; }

        /* CONTACT GRID */
        .contact-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 80px;
        }
        .contact-card {
            background: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.8);
            border-radius: 20px; padding: 32px; text-align: center;
            transition: all 0.3s ease; cursor: pointer;
        }
        .contact-card:hover { transform: translateY(-5px); background: #fff; box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
        .contact-card:active { transform: translateY(-2px) scale(0.98); transition: all 0.1s; } /* Fixed vibration */
        
        .cc-icon {
            width: 60px; height: 60px; margin: 0 auto 20px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; color: var(--primary); background: #f3e8ff;
        }
        .contact-card h3 { font-family: 'Space Grotesk'; font-size: 1.2rem; margin-bottom: 8px; }
        .contact-card p { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 16px; }
        .cc-link { font-weight: 600; color: var(--primary); text-decoration: none; }

        /* FAQ SECTION */
        .faq-section { margin-bottom: 80px; }
        .section-title { text-align: center; font-family: 'Space Grotesk'; font-size: 2rem; margin-bottom: 40px; }
        
        .faq-item {
            background: #fff; border-radius: 16px; margin-bottom: 16px; border: 1px solid #e2e8f0; overflow: hidden;
            transition: all 0.3s;
        }
        .faq-question {
            padding: 20px 24px; cursor: pointer; font-weight: 600; display: flex; justify-content: space-between; align-items: center;
        }
        .faq-question:hover { background: #f8fafc; }
        .faq-answer {
            max-height: 0; overflow: hidden; padding: 0 24px; color: var(--text-muted); line-height: 1.6;
            transition: all 0.3s ease; opacity: 0;
        }
        .faq-item.active .faq-answer { max-height: 200px; padding-bottom: 24px; opacity: 1; }
        .faq-item.active .icon-plus { transform: rotate(45deg); }
        .icon-plus { transition: transform 0.3s; }

        /* CONTACT FORM */
        .form-section { 
            background: white; border-radius: 32px; padding: 48px; border: 1px solid #e2e8f0;
            box-shadow: 0 20px 60px -20px rgba(0,0,0,0.05); max-width: 800px; margin: 0 auto 80px;
        }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
        .input-group { display: flex; flex-direction: column; gap: 8px; }
        .input-group label { font-size: 0.9rem; font-weight: 600; color: #475569; }
        .input-group input, .input-group textarea {
            padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-family: inherit; font-size: 0.95rem; outline: none;
            transition: border 0.2s;
        }
        .input-group input:focus, .input-group textarea:focus { border-color: var(--primary); }
        .btn-submit {
            background: var(--primary); color: white; border: none; padding: 14px 32px; border-radius: 10px;
            font-weight: 600; cursor: pointer; transition: all 0.2s; width: 100%;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(153, 69, 255, 0.25); }
        .btn-submit:active { transform: translateY(-1px); scale: 0.98; }

        @media (max-width: 768px) {
            .contact-grid { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
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
        
        <!-- Hero -->
        <div class="support-hero">
            <h1 id="sup-title">How can we help?</h1>
            <p id="sup-desc">Find answers, read documentation, or contact our team.</p>
            <div class="search-box">
                <svg class="search-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" class="search-input" id="search-input" placeholder="Search for answers...">
            </div>
        </div>

        <!-- Contact Grid -->
        <div class="contact-grid">
            <!-- Email -->
            <div class="contact-card">
                <div class="cc-icon"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
                <h3 id="cc1-title">Email Support</h3>
                <p id="cc1-desc">Get response within 24 hours.</p>
                <a href="mailto:support@privasi.ai" class="cc-link">support@privasi.ai</a>
            </div>
            <!-- Live Chat -->
            <div class="contact-card">
                <div class="cc-icon" style="color: var(--secondary); background: #ecfdf5;"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg></div>
                <h3 id="cc2-title">Live Chat</h3>
                <p id="cc2-desc">Available Mon-Fri, 9am-5pm.</p>
                <span class="cc-link" id="cc2-link">Start Chat</span>
            </div>
            <!-- Docs -->
            <div class="contact-card" onclick="window.location.href='{{ route('developers') }}'">
                <div class="cc-icon" style="color: var(--accent); background: #f0f9ff;"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg></div>
                <h3 id="cc3-title">Documentation</h3>
                <p id="cc3-desc">Guides, API reference, and tutorials.</p>
                <span class="cc-link" id="cc3-link">Read Docs</span>
            </div>
        </div>

        <!-- FAQ -->
        <div class="faq-section">
            <h2 class="section-title" id="faq-title">Frequently Asked Questions</h2>
            
            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-question">
                    <span id="q1">How do I reset my password?</span>
                    <svg class="icon-plus" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div class="faq-answer" id="a1">
                    You can reset your password by going to the Login page and clicking "Forgot Password". A reset link will be sent to your email.
                </div>
            </div>

            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-question">
                    <span id="q2">Can I upgrade my plan later?</span>
                    <svg class="icon-plus" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div class="faq-answer" id="a2">
                    Yes, you can upgrade your subscription at any time from the Billing page in your Dashboard. Prorated charges will apply.
                </div>
            </div>

            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-question">
                    <span id="q3">Where can I find my API Key?</span>
                    <svg class="icon-plus" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div class="faq-answer" id="a3">
                    Your API Key is located in the Developer Settings section of your Dashboard. Do not share this key with anyone.
                </div>
            </div>
        </div>

        <!-- Visual Contact Form -->
        <div class="form-section">
            <h2 class="section-title" id="form-title" style="margin-bottom: 32px;">Send us a message</h2>
            <div class="form-grid">
                <div class="input-group">
                    <label id="lbl-name">Full Name</label>
                    <input type="text" placeholder="John Doe">
                </div>
                <div class="input-group">
                    <label id="lbl-email">Email Address</label>
                    <input type="email" placeholder="john@example.com">
                </div>
            </div>
            <div class="input-group" style="margin-bottom: 24px;">
                <label id="lbl-subject">Subject</label>
                <input type="text" placeholder="Billing Inquiry">
            </div>
            <div class="input-group" style="margin-bottom: 32px;">
                <label id="lbl-message">Message</label>
                <textarea rows="4" placeholder="How can we help you?"></textarea>
            </div>
            <button class="btn-submit" id="btn-send">Send Message</button>
        </div>

    </div>

    <!-- SCRIPTS -->
    <script>
        function toggleFaq(element) {
            // Close others
            document.querySelectorAll('.faq-item').forEach(item => {
                if(item !== element) item.classList.remove('active');
            });
            element.classList.toggle('active');
        }

        const translations = {
            en: {
                // Navbar
                nav: { dash: "Dashboard", tech: "Technology", dev: "Developers", price: "Pricing", supp: "Support" },
                // Hero
                hero: { title: "How can we help?", desc: "Find answers, read documentation, or contact our team.", search: "Search for answers..." },
                // Cards
                cc: { 
                    c1: ["Email Support", "Get response within 24 hours."],
                    c2: ["Live Chat", "Available Mon-Fri, 9am-5pm.", "Start Chat"],
                    c3: ["Documentation", "Guides, API reference, and tutorials.", "Read Docs"]
                },
                // FAQ
                faq: { title: "Frequently Asked Questions" },
                q: { 
                    q1: "How do I reset my password?", a1: "You can reset your password by going to the Login page and clicking 'Forgot Password'. A reset link will be sent to your email.",
                    q2: "Can I upgrade my plan later?", a2: "Yes, you can upgrade your subscription at any time from the Billing page in your Dashboard. Prorated charges will apply.",
                    q3: "Where can I find my API Key?", a3: "Your API Key is located in the Developer Settings section of your Dashboard. Do not share this key with anyone."
                },
                // Form
                form: { title: "Send us a message", name: "Full Name", email: "Email Address", subj: "Subject", msg: "Message", btn: "Send Message" }
            },
            id: {
                nav: { dash: "Dasbor", tech: "Teknologi", dev: "Developers", price: "Harga", supp: "Bantuan" },
                hero: { title: "Ada yang bisa kami bantu?", desc: "Temukan jawaban, baca dokumentasi, atau hubungi tim kami.", search: "Cari jawaban..." },
                cc: { 
                    c1: ["Email Support", "Dapatkan respon dalam 24 jam."],
                    c2: ["Live Chat", "Senin-Jumat, 09.00-17.00.", "Mulai Chat"],
                    c3: ["Dokumentasi", "Panduan, referensi API, dan tutorial.", "Baca Docs"]
                },
                faq: { title: "Pertanyaan Umum" },
                q: { 
                    q1: "Bagaimana cara reset password?", a1: "Anda dapat reset password di halaman Login dengan klik 'Lupa Password'. Link reset akan dikirim ke email Anda.",
                    q2: "Bisakah upgrade paket nanti?", a2: "Ya, Anda bisa upgrade kapan saja melalui halaman Billing di Dashboard. Biaya akan disesuaikan (prorata).",
                    q3: "Dimana saya melihat API Key?", a3: "API Key Anda ada di menu Developer Settings di Dashboard. Jangan bagikan key ini kepada siapapun."
                },
                form: { title: "Kirim pesan kepada kami", name: "Nama Lengkap", email: "Alamat Email", subj: "Subjek", msg: "Pesan", btn: "Kirim Pesan" }
            },
            es: {
                nav: { dash: "Tablero", tech: "Tecnología", dev: "Desarrolladores", price: "Precios", supp: "Ayuda" },
                hero: { title: "¿Cómo podemos ayudar?", desc: "Encuentre respuestas, documentación o contacte al equipo.", search: "Buscar respuestas..." },
                cc: { 
                    c1: ["Soporte por Email", "Respuesta en 24 horas."],
                    c2: ["Chat en Vivo", "Lun-Vie, 9am-5pm.", "Iniciar Chat"],
                    c3: ["Documentación", "Guías, referencias API y tutoriales.", "Leer Docs"]
                },
                faq: { title: "Preguntas Frecuentes" },
                q: { 
                    q1: "¿Cómo restablezco mi contraseña?", a1: "Vaya a Login y haga clic en 'Olvidé mi contraseña'. Se enviará un enlace a su correo.",
                    q2: "¿Puedo mejorar mi plan luego?", a2: "Sí, puede actualizar en cualquier momento desde Facturación. Se aplicarán cargos prorrateados.",
                    q3: "¿Dónde está mi Clave API?", a3: "Su Clave API está en la configuración de desarrollador. No la comparta con nadie."
                },
                form: { title: "Envíenos un mensaje", name: "Nombre Completo", email: "Correo Electrónico", subj: "Asunto", msg: "Mensaje", btn: "Enviar Mensaje" }
            },
            fr: {
                nav: { dash: "Tableau", tech: "Technologie", dev: "Développeurs", price: "Tarifs", supp: "Support" },
                hero: { title: "Comment pouvons-nous aider ?", desc: "Trouvez des réponses, lisez la documentation ou contactez-nous.", search: "Rechercher..." },
                cc: { 
                    c1: ["Support Email", "Réponse sous 24h."],
                    c2: ["Chat en Direct", "Lun-Ven, 9h-17h.", "Lancer le Chat"],
                    c3: ["Documentation", "Guides, référence API et tutoriels.", "Lire la Doc"]
                },
                faq: { title: "Foire Aux Questions" },
                q: { 
                    q1: "Comment réinitialiser le mot de passe ?", a1: "Allez sur Connexion et cliquez sur 'Mot de passe oublié'. Un lien sera envoyé par email.",
                    q2: "Puis-je changer de forfait ?", a2: "Oui, vous pouvez mettre à niveau à tout moment depuis la page Facturation.",
                    q3: "Où est ma clé API ?", a3: "Votre clé API se trouve dans les paramètres développeur. Ne la partagez pas."
                },
                form: { title: "Envoyez-nous un message", name: "Nom Complet", email: "Email", subj: "Sujet", msg: "Message", btn: "Envoyer" }
            },
            de: {
                nav: { dash: "Dashboard", tech: "Technologie", dev: "Entwickler", price: "Preise", supp: "Support" },
                hero: { title: "Wie können wir helfen?", desc: "Finden Sie Antworten, Dokumentation oder kontaktieren Sie uns.", search: "Suchen..." },
                cc: { 
                    c1: ["E-Mail-Support", "Antwort innerhalb 24 Std."],
                    c2: ["Live-Chat", "Mo-Fr, 9-17 Uhr.", "Chat starten"],
                    c3: ["Dokumentation", "Anleitungen, API-Referenz und Tutorials.", "Docs lesen"]
                },
                faq: { title: "Häufig gestellte Fragen" },
                q: { 
                    q1: "Passwort zurücksetzen?", a1: "Klicken Sie beim Login auf 'Passwort vergessen'. Ein Link wird gesendet.",
                    q2: "Plan später upgraden?", a2: "Ja, Sie können jederzeit über die Abrechnungsseite upgraden.",
                    q3: "Wo ist mein API-Schlüssel?", a3: "Ihr API-Schlüssel befindet sich in den Entwicklereinstellungen. Nicht teilen."
                },
                form: { title: "Senden Sie uns eine Nachricht", name: "Vollständiger Name", email: "E-Mail-Adresse", subj: "Betreff", msg: "Nachricht", btn: "Senden" }
            },
            jp: {
                nav: { dash: "ダッシュボード", tech: "技術", dev: "開発者", price: "価格", supp: "サポート" },
                hero: { title: "どのようなご用件ですか？", desc: "回答の検索、ドキュメントの閲覧、チームへの連絡。", search: "回答を検索..." },
                cc: { 
                    c1: ["メールサポート", "24時間以内に返信。"],
                    c2: ["ライブチャット", "月-金 9時-17時。", "チャット開始"],
                    c3: ["ドキュメント", "ガイド、APIリファレンス、チュートリアル。", "ドキュメント"]
                },
                faq: { title: "よくある質問" },
                q: { 
                    q1: "パスワードのリセット方法は？", a1: "ログイン画面で「パスワードを忘れた場合」をクリックしてください。リンクが送信されます。",
                    q2: "プランのアップグレードは？", a2: "はい、ダッシュボードの請求ページからいつでも可能です。",
                    q3: "APIキーはどこですか？", a3: "APIキーは開発者設定にあります。他人と共有しないでください。"
                },
                form: { title: "メッセージを送信", name: "氏名", email: "メールアドレス", subj: "件名", msg: "メッセージ", btn: "送信" }
            },
            cn: {
                nav: { dash: "仪表板", tech: "技术", dev: "开发者", price: "价格", supp: "支持" },
                hero: { title: "我们能为您做什么？", desc: "查找答案、阅读文档或联系我们的团队。", search: "搜索答案..." },
                cc: { 
                    c1: ["电子邮件支持", "24小时内回复。"],
                    c2: ["在线聊天", "周一至周五 9点-17点。", "开始聊天"],
                    c3: ["文档", "指南、API参考和教程。", "阅读文档"]
                },
                faq: { title: "常见问题" },
                q: { 
                    q1: "如何重置密码？", a1: "在登录页面点击“忘记密码”。重置链接将发送到您的邮箱。",
                    q2: "我可以稍后升级计划吗？", a2: "是的，您可以随时在仪表板的账单页面升级。",
                    q3: "我的API密钥在哪里？", a3: "您的API密钥位于开发者设置中。请勿共享。"
                },
                form: { title: "给我们留言", name: "全名", email: "电子邮件", subj: "主题", msg: "消息", btn: "发送消息" }
            },
            ru: {
                nav: { dash: "Дашборд", tech: "Технологии", dev: "Разработчики", price: "Цены", supp: "Поддержка" },
                hero: { title: "Чем мы можем помочь?", desc: "Найдите ответы, документацию или свяжитесь с нами.", search: "Поиск..." },
                cc: { 
                    c1: ["Email поддержка", "Ответ в течение 24 часов."],
                    c2: ["Живой чат", "Пн-Пт, 9:00-17:00.", "Начать чат"],
                    c3: ["Документация", "Руководства и API.", "Читать"]
                },
                faq: { title: "Частые вопросы" },
                q: { 
                    q1: "Как сбросить пароль?", a1: "На странице входа нажмите «Забыли пароль». Ссылка придет на почту.",
                    q2: "Можно ли улучшить план?", a2: "Да, вы можете обновить подписку в любое время в разделе Биллинг.",
                    q3: "Где мой API ключ?", a3: "Ключ находится в настройках разработчика. Не делитесь им."
                },
                form: { title: "Отправить сообщение", name: "Полное имя", email: "Email", subj: "Тема", msg: "Сообщение", btn: "Отправить" }
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
            document.getElementById('sup-title').innerText = t.hero.title;
            document.getElementById('sup-desc').innerText = t.hero.desc;
            document.getElementById('search-input').placeholder = t.hero.search;

            // Cards
            document.getElementById('cc1-title').innerText = t.cc.c1[0]; document.getElementById('cc1-desc').innerText = t.cc.c1[1];
            document.getElementById('cc2-title').innerText = t.cc.c2[0]; document.getElementById('cc2-desc').innerText = t.cc.c2[1]; document.getElementById('cc2-link').innerText = t.cc.c2[2];
            document.getElementById('cc3-title').innerText = t.cc.c3[0]; document.getElementById('cc3-desc').innerText = t.cc.c3[1]; document.getElementById('cc3-link').innerText = t.cc.c3[2];
            
            // FAQ
            document.getElementById('faq-title').innerText = t.faq.title;
            document.getElementById('q1').innerText = t.q.q1; document.getElementById('a1').innerText = t.q.a1;
            document.getElementById('q2').innerText = t.q.q2; document.getElementById('a2').innerText = t.q.a2;
            document.getElementById('q3').innerText = t.q.q3; document.getElementById('a3').innerText = t.q.a3;

            // Form
            document.getElementById('form-title').innerText = t.form.title;
            document.getElementById('lbl-name').innerText = t.form.name;
            document.getElementById('lbl-email').innerText = t.form.email;
            document.getElementById('lbl-subject').innerText = t.form.subj;
            document.getElementById('lbl-message').innerText = t.form.msg;
            document.getElementById('btn-send').innerText = t.form.btn;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedLang = localStorage.getItem('privasi_lang') || 'en';
            applyLang(savedLang);
        });
    </script>
</body>
</html>
