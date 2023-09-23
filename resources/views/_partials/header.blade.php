    <header id="header" class="header fixed-top d-flex align-items-center">
      <div class="d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
          <span class="d-none d-lg-block">Gotong Royong</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
      </div>
      {{-- <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
          <input type="text" name="query" placeholder="Search" title="Enter search keyword">
          <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
      </div> --}}
      <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
          <li class="nav-item d-block d-lg-none">
            <a class="nav-link nav-icon search-bar-toggle " href="#">
              <i class="bi bi-search"></i>
            </a>
          </li>
          <li class="nav-item dropdown pe-3">
            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
              <span class="d-none d-md-block dropdown-toggle ps-2">{{Auth::user()->name}}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
              <li class="dropdown-header">
                <h6>{{Auth::user()->name}}</h6>
                <span>{{Auth::user()->role}}</span>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                  <i class="bx bx-lock-open"></i>
                  <span>Ubah Password</span>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <form action="{{route('auth.logout_action')}}" method="post">
                  @csrf
                  <button class="dropdown-item d-flex align-items-center">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Sign Out</span>
                  </button>
                </form>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </header>