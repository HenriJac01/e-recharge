<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mot de passe oublié - DGI Madagascar</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
  </head>
  <body>
    <div class="auth-container">
      <div class="auth-card">
        <div class="auth-header">
          <h2><i class="fas fa-key"></i> Mot de passe oublié</h2>
          <p>Entrez votre email pour réinitialiser votre mot de passe</p>
        </div>
        <form class="auth-form" id="forgotPasswordForm">
          <div class="form-group">
            <div class="input-icon-wrapper">
              <i class="fas fa-envelope input-icon"></i>
              <input
                type="email"
                id="email"
                name="email"
                placeholder="Votre adresse email"
                required
              />
            </div>
          </div>
          <button type="submit" class="auth-button">
            <i class="fas fa-paper-plane"></i>
            Envoyer le lien de réinitialisation
          </button>
        </form>
        <div class="auth-footer">
          <p>
            Vous vous souvenez de votre mot de passe?
            <a href="{{ route('login') }}">Se connecter</a>
          </p>
        </div>
      </div>
    </div>
    <script src="{{ asset('js/auth.js') }}"></script>
  </body>
</html>
