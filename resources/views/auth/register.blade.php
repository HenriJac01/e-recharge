<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription - DGI Madagascar</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  </head>
  <body>
    <div class="auth-container">
      <div class="auth-card">
        <div class="auth-header">
          <h2><i class="fas fa-user-plus"></i> Inscription</h2>
          <p>Créez votre compte de service</p>
        </div>        
        <form action="{{ route('register.save') }}" method="POST"  class="auth-form" id="registerForm" enctype="multipart/form-data">
        @csrf   
          <div class="form-group">
            <label for="name">Nom complet</label>
            <div class="input-icon-wrapper">
              <i class="fas fa-user input-icon"></i>
              <input
                type="text"
                id="name"
                name="name"
                placeholder="Votre nom complet"
                required
              />
            </div>
          </div>
          <div class="form-group">
            <label for="email">Email professionnel</label>
            <div class="input-icon-wrapper">
              <i class="fas fa-envelope input-icon"></i>
              <input
                type="email"
                id="email"
                name="email"
                placeholder="Votre email professionnel"
                required
              />
            </div>
          </div>
          <div class="form-group">
            <label for="phone_number">Téléphone</label>
            <div class="input-icon-wrapper">
              <i class="fas fa-phone input-icon"></i>
              <input
                type="tel"
                id="phone_number"
                name="phone_number"
                placeholder="0XX XX XXX XX"
                required
                pattern="^0\d{2} \d{2} \d{3} \d{2}$"
                title="Numéro de téléphone valide: 0XX XX XXX XX"
              />
            </div>
          </div>
          <div class="form-group">
            <label for="image">Image</label>
            <div class="input-icon-wrapper">
              <i class="fas fa-image input-icon"></i>
              <input type="file" id="image" name="image" class="file-input" accept="image/*">
              <label for="image" class="file-label">Choisir un fichier</label>
            </div>
          </div>
          <div class="form-group">
            <label for="password">Mot de passe</label>
            <div class="input-icon-wrapper">
              <i class="fas fa-lock input-icon"></i>
              <input
                type="password"
                id="password"
                name="password"
                placeholder="Créez un mot de passe fort"
                required
              />
              <i class="fas fa-eye toggle-password"></i>
            </div>
            <div class="password-strength" id="passwordStrength">
              <div class="strength-bar">
                <div class="strength-progress"></div>
              </div>
              <div class="strength-label">Force du mot de passe</div>
            </div>
          </div>
          <div class="form-group">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <div class="input-icon-wrapper">
              <i class="fas fa-lock input-icon"></i>
                <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Confirmez votre mot de passe"
                required
              />
              <i class="fas fa-eye toggle-password"></i>
            </div>
          </div>
          <div class="form-requirements">
            <h4>Le mot de passe doit contenir :</h4>
            <ul>
              <li id="length">
                <i class="fas fa-circle"></i> Au moins 8 caractères
              </li>
              <li id="uppercase">
                <i class="fas fa-circle"></i> Une lettre majuscule
              </li>
              <li id="lowercase">
                <i class="fas fa-circle"></i> Une lettre minuscule
              </li>
              <li id="number"><i class="fas fa-circle"></i> Un chiffre</li>
              <li id="special">
                <i class="fas fa-circle"></i> Un caractère spécial
              </li>
            </ul>
          </div>
          <div class="form-options">
            <label class="checkbox-container">
              <input type="checkbox" id="terms" required />
              <span class="checkmark"></span>
              J'accepte les
              <a href="#" class="terms-link">conditions d'utilisation</a>
            </label>
          </div>
          <button
            type="submit"
            class="auth-button"
            id="registerButton"
            disabled
          >
            <i class="fas fa-user-plus"></i>
            Créer mon compte
          </button>
        </form>
        <div class="auth-footer">
          <p>Déjà inscrit? <a href="{{ route('login') }}">Se connecter</a></p>
        </div>
      </div>
    </div>
     </body>
  <script src="{{ asset('js/auth.js') }}"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</html>
