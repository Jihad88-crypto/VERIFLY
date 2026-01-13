<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="brand">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="url(#paint0_linear)" stroke-width="2"/>
            <path d="M8 12L11 15L16 9" stroke="url(#paint0_linear)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <defs>
                <linearGradient id="paint0_linear" x1="2" y1="2" x2="22" y2="22" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#9945FF"/>
                    <stop offset="1" stop-color="#14F195"/>
                </linearGradient>
            </defs>
        </svg>
        Privasi.ai
    </a>

    <div class="nav-center">
        <!-- Dashboard Link (Hidden on Dashboard, visible elsewhere) -->
        <a href="{{ route('dashboard') }}" class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}" id="nav-dash">Dashboard</a>
        
        <a href="{{ route('services') }}" class="nav-link {{ Route::currentRouteName() == 'services' ? 'active' : '' }}" id="nav-tech">Technology</a>
        <a href="{{ route('developers') }}" class="nav-link {{ Route::currentRouteName() == 'developers' ? 'active' : '' }}" id="nav-dev">Developers</a>
        <a href="{{ route('pricing') }}" class="nav-link {{ Route::currentRouteName() == 'pricing' ? 'active' : '' }}" id="nav-price">Pricing</a>
        <a href="{{ route('support') }}" class="nav-link {{ Route::currentRouteName() == 'support' ? 'active' : '' }}" id="nav-support">Support</a>
    </div>

    <div class="nav-right">
        <!-- Language Switcher (Only on Dashboard) -->
        @if(request()->routeIs('dashboard'))
            <a href="#" class="nav-link" onclick="toggleLang()" id="nav-lang" style="font-size: 1.2rem; margin-right: 10px;">🌐</a>
        @else
            <!-- Optional: Simple switcher for other pages if needed, or hidden -->
        @endif

        @auth
            <div class="user-pill">
                <div class="avatar-circle">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <span class="user-name">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="nav-link">Login</a>
            <a href="{{ route('register') }}" class="cta-btn-small">Get Started</a>
        @endauth
    </div>
</nav>

<style>
    .navbar {
        display: flex; justify-content: space-between; align-items: center; padding: 20px 48px;
        background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--glass-border); position: sticky; top: 0; z-index: 100;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
    }
    .brand {
        display: flex; align-items: center; gap: 10px; font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.5rem; letter-spacing: -0.5px;
        text-decoration: none;
        background: linear-gradient(135deg, var(--primary), #4F46E5); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .nav-center { display: flex; gap: 40px; align-items: center; }
    .nav-link {
        text-decoration: none; color: var(--text-main); font-weight: 600; font-size: 0.95rem; position: relative; padding: 8px 0; cursor: pointer;
        transition: color 0.2s;
    }
    .nav-link:hover { color: var(--primary); }
    .nav-link::after {
        content: ''; position: absolute; bottom: 0; left: 0; width: 0%; height: 2px; background: var(--primary); transition: width 0.3s ease;
    }
    .nav-link:hover::after { width: 100%; }
    .nav-link.active::after { width: 100%; }

    .nav-right { display: flex; align-items: center; gap: 24px; }

    /* Lang Switcher */
    .lang-switch { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); }
    .lang-btn { background: none; border: none; cursor: pointer; color: var(--text-muted); font-weight: 600; transition: color 0.2s; }
    .lang-btn.active { color: var(--primary); }
    .lang-sep { opacity: 0.3; }

    /* User Pill */
    .user-pill {
        display: flex; align-items: center; gap: 12px; 
        background: rgba(255, 255, 255, 0.5); padding: 6px 6px 6px 16px; 
        border-radius: 50px; border: 1px solid rgba(255,255,255,0.8);
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .avatar-circle {
        width: 36px; height: 36px; 
        background: linear-gradient(135deg, #8b5cf6, #6366f1) !important; /* Fixed Premium Purple */
        color: white !important; border-radius: 50%; display: flex !important; align-items: center !important; justify-content: center !important;
        font-weight: 700; font-size: 1rem; text-transform: uppercase;
        line-height: normal !important; padding-bottom: 2px; /* Optical adjustment */
    }
    .user-name { font-weight: 600; font-size: 0.9rem; color: var(--text-main); padding-right: 8px; }
    .logout-btn {
        width: 32px; height: 32px; border-radius: 50%; border: none; background: #fee2e2; color: #ef4444;
        display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;
    }
    .logout-btn:hover { background: #ef4444; color: white; transform: rotate(90deg); }

    /* CTA Button for Guests */
    .cta-btn-small {
        background: var(--primary); color: white; padding: 8px 20px; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: transform 0.2s;
    }
    .cta-btn-small:hover { transform: scale(1.05); }

    @media (max-width: 768px) {
        .nav-center { display: none; }
        .navbar { padding: 15px 24px; }
    }

    /* Language Dropdown */
    .lang-dropdown {
        position: absolute;
        top: 60px;
        right: 48px;
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        display: none;
        z-index: 1000;
        min-width: 200px;
    }
    .lang-dropdown.active {
        display: block;
    }
    .lang-dropdown-title {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--text-main);
        margin-bottom: 12px;
    }
    .lang-dropdown-item {
        padding: 10px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
    }
    .lang-dropdown-item:hover {
        background: #f1f5f9;
    }
    .lang-dropdown-item.active {
        background: var(--primary);
        color: white;
    }
</style>

<!-- Language Dropdown -->
<div class="lang-dropdown" id="langDropdown">
    <div class="lang-dropdown-title">Select Language</div>
    <div class="lang-dropdown-item" onclick="changeLangGlobal('en')">🇬🇧 English</div>
    <div class="lang-dropdown-item" onclick="changeLangGlobal('id')">🇮🇩 Indonesia</div>
    <div class="lang-dropdown-item" onclick="changeLangGlobal('es')">🇪🇸 Español</div>
    <div class="lang-dropdown-item" onclick="changeLangGlobal('fr')">🇫🇷 Français</div>
    <div class="lang-dropdown-item" onclick="changeLangGlobal('de')">🇩🇪 Deutsch</div>
    <div class="lang-dropdown-item" onclick="changeLangGlobal('jp')">🇯🇵 日本語</div>
    <div class="lang-dropdown-item" onclick="changeLangGlobal('cn')">🇨🇳 中文</div>
    <div class="lang-dropdown-item" onclick="changeLangGlobal('ru')">🇷🇺 Русский</div>
</div>

<script>
    // Global Language Toggle
    function toggleLang() {
        const dropdown = document.getElementById('langDropdown');
        dropdown.classList.toggle('active');
    }

    function changeLangGlobal(lang) {
        localStorage.setItem('privasi_lang', lang);
        
        // Dispatch event for pages that listen
        window.dispatchEvent(new CustomEvent('languageChanged', { detail: { lang } }));
        
        // Close dropdown
        document.getElementById('langDropdown').classList.remove('active');
        
        // Reload page to apply language
        location.reload();
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('langDropdown');
        const langBtn = document.getElementById('nav-lang');
        
        if (dropdown && langBtn && !dropdown.contains(e.target) && e.target !== langBtn) {
            dropdown.classList.remove('active');
        }
    });
</script>
