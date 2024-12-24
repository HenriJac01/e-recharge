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
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />
   
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
    <img src="{{ asset('img/yas.jpg') }}" class="logo" id="fixedLogo" alt="Logo" />
</a>
</a>
  <ul class="side_menu">
    <li class="{{ request()->routeIs('yas/dashYas') ? 'active' : '' }}">
      <a href="{{ route('yas/dashYas') }}">
        <i class="bx bxs-dashboard icon"></i> Tableau de bord
      </a>
    </li>
    <li class="divider" data-text="menu">Menu</li>
    <li>
     <a href=""><i class="bx bx-dollar-circle icon"></i> Revenus</a>
   </li>
    <li class="{{ request()->routeIs('yas/transaction') ? 'active' : '' }}">
      <a href="{{ route('yas/transaction') }}">
        <i class="bx bx-transfer icon"></i> Transactions
      </a>
    </li>
    <li>
        <a href=""><i class="bx bx-bar-chart-alt-2 icon"></i> Rapports</a>
    </li>
    <li class="divider" data-text="tables et Formes">Tables et Formes</li>
    
    <!-- Menu avec sous-menu -->
    <li class="has-dropdown {{ request()->is('yas/tables/*') ? 'active' : '' }}">
      <a href="javascript:void(0)" class="menu-toggle">
        <i class="bx bx-table icon"></i> Tables
        <i class="bx bx-chevron-right icon_right"></i>
      </a>
      <ul class="side_dropdown {{ request()->is('yas/tables/*') ? 'show' : '' }}">
      <li><a href="{{ route('yas/tables.client') }}" class="{{ request()->routeIs('yas/tables.client') ? 'active' : '' }}">Client</a></li>
      <li><a href="" class="">Stock</a></li>
      <li><a href="" class="">Transmission</a></li>
      </ul>
    </li>

    <!-- Autre sous-menu -->
    <li class="has-dropdown {{ request()->is('yas/formes/*') ? 'active' : '' }}">
      <a href="javascript:void(0)" class="menu-toggle">
        <i class="bx bx-notepad icon"></i> Formes
        <i class="bx bx-chevron-right icon_right"></i>
      </a>
      <ul class="side_dropdown {{ request()->is('yas/formes/*') ? 'show' : '' }}">
      <li><a href="{{ route('yas/formes.client') }}" class="{{ request()->routeIs('yas/formes.client') ? 'active' : '' }}">Client</a></li>
      <li><a href="" class="">Stock</a></li>
      <li><a href="" class="">Transmission</a></li>
      </ul>
    </li>

    <li class="divider" data-text="outils">Outils</li>
    <li class="has-dropdown">
      <a href="javascript:void(0)" class="menu-toggle">
        <i class="bx bx-cog icon"></i> Paramètres
        <i class="bx bx-chevron-right icon_right"></i>
      </a>
      <ul class="side_dropdown">
        <li><a href="">Notifications</a></li>
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
    <!--NAVBAR-->
    @vite('resources/js/app.js') <!-- Inclut le fichier JavaScript -->
    @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/active.js') }}"></script>
  <!--graphique chart-->
  <script src="{{ asset('js/scrollTop.js') }}"></script>
  <script>
  document.getElementById('fixedLogo').style.pointerEvents = 'none';
  </script>
  </body>
</html>
