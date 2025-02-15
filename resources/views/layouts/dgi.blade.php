<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Administration</title>
    @vite('resources/css/app.css') <!-- Inclut le fichier CSS -->
    @yield('style')
    
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
      <!-- dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboardDgi.css') }}" />
   
    <link rel="stylesheet" href="{{ asset('css/scrollTop.css') }}" />
    
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Styles CSS -->
  <style>
    /* Par défaut, le chevron pointe vers la droite */
    .side_menu .has-dropdown .icon_right {
      transform: rotate(0deg);
      transition: transform 0.3s ease; /* Transition pour la rotation */
    }

    /* Lorsque le sous-menu est actif, faire tourner le chevron */
    .side_menu .has-dropdown.active .icon_right {
      transform: rotate(90deg); /* Rotation du chevron */
    }

    /* Cacher le sous-menu par défaut */
    .side_menu .side_dropdown {
      display: none;
      list-style: none;
      padding: 0;
    }

    .side_menu .side_dropdown li a {
      padding-left: 20px;
    }

    /* Cacher ou afficher le sous-menu lorsqu'il est actif */
    .side_menu .has-dropdown.active .side_dropdown {
      display: block;
    }
  </style>
  </head>
  <body>
    <!--SIDEBAR-->
<section id="sidebar" >
<a href="javascript:void(0)" class="brand">
    <img src="{{ asset('img/dgi.png') }}" class="logo" id="fixedLogo" alt="Logo" />
</a>
</a>
  <ul class="side_menu">
    <li class="{{ request()->routeIs('dgi/dashDgi') ? 'active' : '' }}">
      <a href="{{ route('dgi/dashDgi') }}">
        <i class="bx bxs-dashboard icon"></i> Tableau de bord
      </a>
    </li>
    <li class="divider" data-text="menu">Menu</li>
    <li class="{{ request()->routeIs('dgi/transmission') ? 'active' : '' }}">
      <a href="{{ route('dgi/transmission') }}">
        <i class="bx bx-transfer icon"></i> Transmission
      </a>
    </li>
    <li class="divider" data-text="tables et Formes">Tables et Formes</li>
    
    <!-- Menu avec sous-menu -->
    <li class="has-dropdown {{ request()->is('dgi/tables/*') ? 'active' : '' }}">
      <a href="javascript:void(0)" class="menu-toggle">
        <i class="bx bx-table icon"></i> Tables
        <i class="bx bx-chevron-right icon_right"></i>
      </a>
      <ul class="side_dropdown {{ request()->is('dgi/tables/*') ? 'show' : '' }}">
        <li><a href="{{ route('dgi/tables.dgi') }}" class="{{ request()->routeIs('dgi/tables.dgi') ? 'active' : '' }}">DGI</a></li>
      </ul>
    </li>

    <!-- Autre sous-menu -->
    <li class="has-dropdown {{ request()->is('dgi/formes/*') ? 'active' : '' }}">
      <a href="javascript:void(0)" class="menu-toggle">
        <i class="bx bx-notepad icon"></i> Formes
        <i class="bx bx-chevron-right icon_right"></i>
      </a>
      <ul class="side_dropdown {{ request()->is('dgi/formes/*') ? 'show' : '' }}">
       <li><a href="{{ route('dgi/formes.dgi') }}" class="{{ request()->routeIs('dgi/formes.dgi') ? 'active' : '' }}">DGI</a></li>
      </ul>
    </li>

    <li class="divider" data-text="outils">Outils</li>
    <li class="has-dropdown">
      <a href="javascript:void(0)" class="menu-toggle">
        <i class="bx bx-cog icon"></i> Paramètres
        <i class="bx bx-chevron-right icon_right"></i>
      </a>
      <ul class="side_dropdown">
        <li><a href="">Sauvegarde</a></li>
      </ul>
    </li>
  </ul>
</section>

    <!--SIDEBAR-->
    <!--NAVBAR-->
    <section id="content">
      <nav>
        <i class="bx bx-menu toggle_sidebar"></i>
        <form action="">
          <div class="from-group">
            <input type="text" placeholder="Recherche..." />
            <i class="bx bx-search icon"></i>
          </div>
        </form>
        <a href="" class="nav_link">
          <i class="bx bxs-bell icon"></i>
          <span class="badge">50</span>
        </a>
        <span class="divider"></span>
        <div class="profile">
          <img src="{{ asset('images/' . Auth::user()->image) }}"  alt="" />
          <ul class="profile_link">
            <li>
              <a href=""><i class="bx bxs-user-circle icon"></i> Profile</a>
            </li>
            <li>
              <a href=""><i class="bx bxs-cog icon"></i> Paramètres</a>
            </li>
            <li>
              <a href="{{ url('/logout') }}"
                ><i class="bx bxs-log-out-circle icon"></i> Déconnexion</a
              >
            </li>
        </ul>
    </div>
</nav>
<!--NAVBAR-->
      <!--MAIN-->
      <main>
      @yield('content')
        <!-- Bouton Scroll Top avec Progress -->
        <button
          id="scrollTopBtn"
          class="scroll-top-btn"
          aria-label="Retour en haut"
        >
          <svg class="progress-ring" width="44" height="44">
            <circle
              class="progress-ring-circle"
              stroke="white"
              stroke-width="2"
              fill="transparent"
              r="17"
              cx="22"
              cy="22"
            />
          </svg>
          <i class="bx bx-chevron-up"></i>
        </button>
        <!-- Bouton Scroll Top avec Progress -->
      </main>
      <!--MAIN-->
    </section>
    
</body>
@vite('resources/js/app.js') <!-- Inclut le fichier JavaScript -->
  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/active.js') }}"></script>
  <!--graphique chart-->
  <script src="{{ asset('js/scrollTop.js') }}"></script>
  <script>
  document.getElementById('fixedLogo').style.pointerEvents = 'none';
  </script>
</html>
