<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexion - DGI Madagascar</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-header">
        <h2><i class="fas fa-user-circle"></i> Connexion</h2>
        <p>Bienvenue sur la page de connexion</p>
      </div>
      <form class="auth-form" id="loginForm" method="POST" action="{{ route('login.action') }}">
        @csrf
        <div class="form-group">
          <label for="email">Email</label>
          <div class="input-icon-wrapper">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" id="email" name="email" placeholder="Votre adresse email" required />
          </div>
        </div>
        <div class="form-group">
          <label for="password">Mot de passe</label>
          <div class="input-icon-wrapper">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" id="password" name="password" placeholder="Votre mot de passe" required />
            <i class="fas fa-eye toggle-password"></i>
          </div>
        </div>
        <div class="form-options">
          <label class="checkbox-container">
            <input name="remember" id="remember" aria-describedby="remember" type="checkbox" required="">
            <span class="checkmark"></span>
            Se souvenir de moi
          </label>
          <a href="{{ route('forgotPassword') }}" class="forgot-password">Mot de passe oublié?</a>
        </div>
        <button type="submit" class="auth-button">
          <i class="fas fa-sign-in-alt"></i>
          Se connecter
        </button>
      </form>
      <div class="auth-footer">
          <p>Pas encore de compte? <a href="{{ route('register') }}">S'inscrire</a></p>
        </div>
    </div>
  </div>
  <!-- Loading Overlay 
  <div id="loadingOverlay" class="loading-overlay">
    <div class="loading-spinner"></div>
  </div>-->
  <script src="{{ asset('js/auth.js') }}"></script>
  <script src="{{ asset('js/loading.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  </script>
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js"></script>
</body>
</html>
