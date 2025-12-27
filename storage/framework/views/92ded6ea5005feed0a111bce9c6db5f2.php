<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register | Privacy Platform</title>

  
  <link rel="stylesheet" href="<?php echo e(asset('Privacy/style.css')); ?>">
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

        <form class="login-form" method="POST" action="<?php echo e(route('register.store')); ?>">
          <?php echo csrf_field(); ?>
          
          <div class="form-group">
            <div class="input-wrapper">
              <input type="text" id="input-name" name="name" class="form-input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Full Name" value="<?php echo e(old('name')); ?>" required autofocus>
            </div>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-danger" style="color: red; font-size: 0.875rem; margin-top: 5px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="form-group">
            <div class="input-wrapper">
              <input type="email" id="input-email" name="email" class="form-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Email address" value="<?php echo e(old('email')); ?>" required>
            </div>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-danger" style="color: red; font-size: 0.875rem; margin-top: 5px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="form-group">
            <div class="input-wrapper">
              <input type="password" id="input-pass" name="password" class="form-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Password" required>
            </div>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-danger" style="color: red; font-size: 0.875rem; margin-top: 5px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <button type="submit" class="btn-primary" id="btn-submit">
            Create Account
          </button>

          <div class="form-footer">
            <span id="reg-footer">Already have an account?</span> <a href="<?php echo e(route('login')); ?>" class="link-accent" id="link-login">Sign In</a>
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

  <script src="<?php echo e(asset('Privacy/script.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\privasi-app\resources\views/ui/register.blade.php ENDPATH**/ ?>