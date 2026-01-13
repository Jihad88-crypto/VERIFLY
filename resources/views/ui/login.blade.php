<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Privacy Platform</title>

  {{-- CSS dari folder public/Privacy --}}
  <link rel="stylesheet" href="{{ asset('Privacy/style.css') }}">
</head>

<body>
   <div class="app-container">
    <!-- Left Section: Content -->
    <section class="login-section">
      <header class="header">
        <div class="brand">
          <div class="brand-dot"></div>
          <span>VeriFly</span>
        </div>
        <nav class="nav-links">
          <a href="#">Help</a>
          <a href="#">Docs</a>
        </nav>
      </header>

      <div class="content-wrapper">
        <div class="hero-text">
          <h1><span id="login-title">Access the</span> <br><span class="gradient-text" id="login-title-sub">Future of Privacy</span></h1>
          <p id="login-desc">Securely manage your digital assets and identity with AI-driven protection.</p>
        </div>

        <form class="login-form" method="POST" action="{{ route('login') }}">
          @csrf
          <div class="form-group">
            <div class="input-wrapper">
              <input type="email" id="input-email" name="email" class="form-input @error('email') is-invalid @enderror" placeholder="Email address" value="{{ old('email') }}" required autofocus>
            </div>
            @error('email')
                <span class="text-danger" style="color: red; font-size: 0.875rem; margin-top: 5px;">{!! $message !!}</span>
            @enderror
          </div>

          <div class="form-group">
            <div class="input-wrapper">
              <input type="password" id="input-pass" name="password" class="form-input" placeholder="Password" required>
            </div>
          </div>

          <button type="submit" class="btn-primary" id="btn-submit">
            Sign In
          </button>

          <div class="form-footer">
            <span id="login-footer">Don't have an account?</span> <a href="{{ route('register') }}" class="link-accent" id="link-signup">Sign up</a>
          </div>
        </form>
      </div>

      <!-- Empty div for flex spacing if needed, or footer reserved space -->
      <div></div>
    </section>

    <!-- Right Section: Visuals -->
    <section class="visual-section">
      <div class="glass-visual"></div>
      <div class="gradient-blob blob-1"></div>
      <div class="gradient-blob blob-2"></div>
      <div class="gradient-blob blob-3"></div>

      <!-- AI Floating Element -->
      <div class="ai-fab">
        <div class="ai-icon"></div>
        <span class="ai-text" id="ai-help">Ask AI Help</span>
      </div>
    </section>
  </div>

  <script src="script.js"></script>

  <script>
    const translations = {
        en: {
            t1: "Access the", t2: "Future of Privacy",
            desc: "Securely manage your digital assets and identity with AI-driven protection.",
            phEmail: "Email address", phPass: "Password",
            btn: "Sign In", footer: "Don't have an account?", signup: "Sign up", ai: "Ask AI Help"
        },
        id: {
            t1: "Akses", t2: "Masa Depan Privasi",
            desc: "Kelola aset digital dan identitas Anda secara aman dengan perlindungan berbasis AI.",
            phEmail: "Alamat Email", phPass: "Kata Sandi",
            btn: "Masuk", footer: "Belum punya akun?", signup: "Daftar", ai: "Tanya AI"
        },
        jp: {
            t1: "アクセスする", t2: "プライバシーの未来",
            desc: "AI主導の保護機能により、デジタル資産とIDを安全に管理します。",
            phEmail: "メールアドレス", phPass: "パスワード",
            btn: "ログイン", footer: "アカウントをお持ちでないですか？", signup: "サインアップ", ai: "AIヘルプ"
        },
        es: { t1: "Accede al", t2: "Futuro Privado", desc: "Gestiona seguros tus activos digitales.", phEmail: "Correo", phPass: "Contraseña", btn: "Entrar", footer: "¿Sin cuenta?", signup: "Registrarse", ai: "Ayuda AI" },
        fr: { t1: "Accédez au", t2: "Futur Privé", desc: "Gérez vos actifs numériques en toute sécurité.", phEmail: "Email", phPass: "Mot de passe", btn: "Connexion", footer: "Pas de compte ?", signup: "S'inscrire", ai: "Aide IA" },
        de: { t1: "Zugang zur", t2: "Privatsphäre", desc: "Verwalten Sie Ihre digitalen Assets sicher.", phEmail: "E-Mail", phPass: "Passwort", btn: "Anmelden", footer: "Kein Konto?", signup: "Registrieren", ai: "KI-Hilfe" },
        cn: { t1: "访问", t2: "隐私的未来", desc: "通过AI驱动的保护安全地管理您的数字资产。", phEmail: "电子邮件", phPass: "密码", btn: "登录", footer: "没有帐户？", signup: "注册", ai: "AI帮助" },
        ru: { t1: "Доступ к", t2: "Будущему", desc: "Безопасное управление цифровыми активами.", phEmail: "Email", phPass: "Пароль", btn: "Войти", footer: "Нет аккаунта?", signup: "Регистрация", ai: "Помощь ИИ" }
    };

    document.addEventListener('DOMContentLoaded', () => {
        const savedLang = localStorage.getItem('privasi_lang') || 'en';
        const t = translations[savedLang] || translations['en'];

        if(document.getElementById('login-title')) document.getElementById('login-title').innerText = t.t1;
        if(document.getElementById('login-title-sub')) document.getElementById('login-title-sub').innerText = t.t2;
        if(document.getElementById('login-desc')) document.getElementById('login-desc').innerText = t.desc;
        
        if(document.getElementById('input-email')) document.getElementById('input-email').placeholder = t.phEmail;
        if(document.getElementById('input-pass')) document.getElementById('input-pass').placeholder = t.phPass;
        
        if(document.getElementById('btn-submit')) document.getElementById('btn-submit').innerText = t.btn;
        if(document.getElementById('login-footer')) document.getElementById('login-footer').innerText = t.footer;
        if(document.getElementById('link-signup')) document.getElementById('link-signup').innerText = t.signup;
        if(document.getElementById('ai-help')) document.getElementById('ai-help').innerText = t.ai;
    });
  </script>
</body>
</html>
