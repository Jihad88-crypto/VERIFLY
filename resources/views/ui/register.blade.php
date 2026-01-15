<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register | Privacy Platform</title>

  {{-- CSS dari folder public/Privacy --}}
  <link rel="stylesheet" href="{{ asset('Privacy/style.css') }}">
  <link rel="stylesheet" href="{{ asset('Privacy/rainbow-gradient.css') }}">
  <link rel="stylesheet" href="{{ asset('Privacy/register-fix.css') }}">
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
          <h1><span id="reg-title">Join the</span> <br><span class="gradient-text" id="reg-title-sub">Revolution</span></h1>
          <p id="reg-desc">Create your account to start securing your digital presence.</p>
        </div>

        <form class="login-form" method="POST" action="{{ route('register.store') }}">
          @csrf
          
          <div class="form-group">
            <div class="input-wrapper">
              <input type="text" id="input-name" name="name" class="form-input @error('name') is-invalid @enderror" placeholder="Full Name" value="{{ old('name') }}" required autofocus>
            </div>
            @error('name')
                <span class="text-danger" style="color: red; font-size: 0.875rem; margin-top: 5px;">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <div class="input-wrapper">
              <input type="email" id="input-email" name="email" class="form-input @error('email') is-invalid @enderror" placeholder="Email address" value="{{ old('email') }}" required>
            </div>
            @error('email')
                <span class="text-danger" style="color: red; font-size: 0.875rem; margin-top: 5px;">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <div class="input-wrapper">
              <input type="password" id="input-pass" name="password" class="form-input @error('password') is-invalid @enderror" placeholder="Password" required>
            </div>
            @error('password')
                <span class="text-danger" style="color: red; font-size: 0.875rem; margin-top: 5px;">{{ $message }}</span>
            @enderror
          </div>

          <button type="submit" class="btn-primary" id="btn-submit">
            Create Account
          </button>

          <div class="form-footer">
            <span id="reg-footer">Already have an account?</span> <a href="{{ route('login') }}" class="link-accent" id="link-login">Sign In</a>
          </div>
        </form>
      </div>

      <div></div>
    </section>

    <!-- Right Section: Visuals -->
    <section class="visual-section">
      <div class="glass-visual"></div>
      <div class="gradient-blob blob-1"></div>
      <div class="gradient-blob blob-2"></div>
      <div class="gradient-blob blob-3"></div>

      <div class="ai-fab">
        <div class="ai-icon"></div>
        <span class="ai-text" id="ai-help">Ask AI Help</span>
      </div>
    </section>
  </div>

  <script src="{{ asset('Privacy/script.js') }}"></script>
</body>
</html>
