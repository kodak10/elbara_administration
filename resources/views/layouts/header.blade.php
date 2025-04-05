<header class="navbar navbar-expand-md d-print-none" data-bs-theme="dark">
    <div class="container-xl">
      <!-- BEGIN NAVBAR TOGGLER -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- END NAVBAR TOGGLER -->
      <!-- BEGIN NAVBAR LOGO -->
      <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
        <a href="/dashboard">
          ELBARA EXPRESS
        </a>
      </div>
      <!-- END NAVBAR LOGO -->
      <div class="navbar-nav flex-row order-md-last">
        
        <div class="d-none d-md-flex">
         
          <div class="nav-item dropdown d-none d-md-flex me-3">
            <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
              <!-- Download SVG icon from http://tabler.io/icons/icon/bell -->
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path>
                <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
              </svg>
              <span class="badge bg-red"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Last updates</h3>
                </div>
                <div class="list-group list-group-flush list-group-hoverable">
                  <div class="list-group-item">
                    <div class="row align-items-center">
                      <div class="col-auto"><span class="status-dot status-dot-animated bg-red d-block"></span></div>
                      <div class="col text-truncate">
                        <a href="#" class="text-body d-block">Example 1</a>
                        <div class="d-block text-secondary text-truncate mt-n1">Change deprecated html tags to text decoration classes (#29604)</div>
                      </div>
                      <div class="col-auto">
                        <a href="#" class="list-group-item-actions">
                          <!-- Download SVG icon from http://tabler.io/icons/icon/star -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted icon-2">
                            <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path>
                          </svg>
                        </a>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="nav-item dropdown">
          <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Open user menu">
            <!-- Avatar de l'utilisateur -->
            <span class="avatar avatar-sm" style="background-image: url({{ Storage::url(auth()->user()->image ?? 'avatars/default-avatar.png') }})"></span>
            <div class="d-none d-xl-block ps-2">
                <!-- Nom de l'utilisateur -->
                <div>{{ auth()->user()->name }}</div>
                <div class="mt-1 small text-secondary">
                    <!-- Rôle de l'utilisateur -->
                    @if(auth()->user()->roles->isNotEmpty())
                        {{ auth()->user()->roles->first()->name }}
                    @else
                        Aucune rôle attribué
                    @endif
                </div>
            </div>
        </a>
        
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <a href="{{ route('profil.edit') }}" class="dropdown-item">Mon Profil</a>
            <div class="dropdown-divider"></div>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
              @csrf
              <button type="submit" class="dropdown-item">Se Déconnecter</button>
          </form>
                    </div>
        </div>
      </div>
    </div>
  </header>
  <header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
      <div class="navbar">
        <div class="container-xl">
          <div class="row flex-fill align-items-center">
            <div class="col">
              <!-- BEGIN NAVBAR MENU -->
              <ul class="navbar-nav">
                <li class="nav-item active">
                  <a class="nav-link" href="/dashboard">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                      <!-- Download SVG icon from http://tabler.io/icons/icon/home -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                        <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                      </svg>
                    </span>
                    <span class="nav-link-title"> Accueil </span>
                  </a>
                </li>
                
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                      <span class="nav-link-icon d-md-none d-lg-inline-block">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <path d="M12 3l8 4.5v9l-8 4.5l-8-4.5v-9l8-4.5"></path>
                              <path d="M12 12l8-4.5"></path>
                              <path d="M12 12v9"></path>
                              <path d="M12 12l-8-4.5"></path>
                              <path d="M16 5.25l-8 4.5"></path>
                          </svg>
                      </span>
                      <span class="nav-link-title">Gestion des Commandes</span>
                  </a>
                  <div class="dropdown-menu">
                      <div class="dropdown-menu-columns">
                          <div class="dropdown-menu-column">
                              <a class="dropdown-item" href="{{ route('orders.index') }}">Toutes les commandes</a>
                             
                              
                              <a class="dropdown-item" href="{{ route('orders.historique') }}">Historique</a>
                          </div>
                      </div>
                  </div>
              </li>
              

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                      <!-- Download SVG icon from http://tabler.io/icons/icon/package -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"></path>
                        <path d="M12 12l8 -4.5"></path>
                        <path d="M12 12l0 9"></path>
                        <path d="M12 12l-8 -4.5"></path>
                        <path d="M16 5.25l-8 4.5"></path>
                      </svg>
                    </span>
                    <span class="nav-link-title"> Gestion des Livreurs </span>
                  </a>
                  <div class="dropdown-menu">
                    <div class="dropdown-menu-columns">
                      <div class="dropdown-menu-column">
                        <a class="dropdown-item" href="{{ route('livreurs.demandes') }}">
                          Demandes 
                          
                        </a>
                        <a class="dropdown-item" href="{{ route('livreurs.index') }}"> Livreurs </a>
                        
                       
                       
                    </div>
                  </div>
                </li>

                

               

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                      <!-- Download SVG icon from http://tabler.io/icons/icon/package -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"></path>
                        <path d="M12 12l8 -4.5"></path>
                        <path d="M12 12l0 9"></path>
                        <path d="M12 12l-8 -4.5"></path>
                        <path d="M16 5.25l-8 4.5"></path>
                      </svg>
                    </span>
                    <span class="nav-link-title"> Finances </span>
                  </a>
                  <div class="dropdown-menu">
                    <div class="dropdown-menu-columns">
                      <div class="dropdown-menu-column">
                        <a class="dropdown-item" href="{{ route('livreurs.financier') }}">
                           
                          Point Financier des Livreurs 
                        </a>
                        <a class="dropdown-item" href="#"> Point Financier Global  </a>
                        <a class="dropdown-item" href="#"> Transferts d'Argent   </a>

                       
                       
                    </div>
                  </div>
                </li>

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                      <!-- Download SVG icon from http://tabler.io/icons/icon/package -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"></path>
                        <path d="M12 12l8 -4.5"></path>
                        <path d="M12 12l0 9"></path>
                        <path d="M12 12l-8 -4.5"></path>
                        <path d="M16 5.25l-8 4.5"></path>
                      </svg>
                    </span>
                    <span class="nav-link-title"> Marketing & Promotions </span>
                  </a>
                  <div class="dropdown-menu">
                    <div class="dropdown-menu-columns">
                      <div class="dropdown-menu-column">
                        <a class="dropdown-item" href="#">
                           
                          Marketing
                        </a>
                        <a class="dropdown-item" href="#"> Promotions </a>
                        <a class="dropdown-item" href="#">Partenaires </a>

                        <a class="dropdown-item" href="#">Statistiques (Nombre sms, nombre client, nombre livreurs, exportation des données) </a>

                       
                       
                    </div>
                  </div>
                </li>

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                      <!-- Download SVG icon from http://tabler.io/icons/icon/package -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"></path>
                        <path d="M12 12l8 -4.5"></path>
                        <path d="M12 12l0 9"></path>
                        <path d="M12 12l-8 -4.5"></path>
                        <path d="M16 5.25l-8 4.5"></path>
                      </svg>
                    </span>
                    <span class="nav-link-title"> Parametrage </span>
                  </a>
                  <div class="dropdown-menu">
                    <div class="dropdown-menu-columns">
                      <div class="dropdown-menu-column">
                        <a class="dropdown-item" href="{{ route('utilisateurs.index') }}"> Accès Utilisateurs </a>
                        <a class="dropdown-item" href="{{ route('companies.index') }}">Compagnies</a>
                        <a class="dropdown-item" href="{{ route('gares.index') }}"> Gares </a>
                        <a class="dropdown-item" href="/services"> Les Services </a>

                       
                       
                    </div>
                  </div>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="{{ route('activity.index') }}">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                      <!-- Download SVG icon from http://tabler.io/icons/icon/package -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"></path>
                        <path d="M12 12l8 -4.5"></path>
                        <path d="M12 12l0 9"></path>
                        <path d="M12 12l-8 -4.5"></path>
                        <path d="M16 5.25l-8 4.5"></path>
                      </svg>
                    </span>
                    <span class="nav-link-title"> Traçabilité </span>
                  </a>
                </li>
                
              </ul>
              <!-- END NAVBAR MENU -->
            </div>
            <div class="col-2 d-none d-xxl-block">
              <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
                <form action="./" method="get" autocomplete="off" novalidate="">
                  <div class="input-icon">
                    <span class="input-icon-addon">
                      <!-- Download SVG icon from http://tabler.io/icons/icon/search -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                        <path d="M21 21l-6 -6"></path>
                      </svg>
                    </span>
                    <input type="text" value="" class="form-control" placeholder="Search…" aria-label="Search in website">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>