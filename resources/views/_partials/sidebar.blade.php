    <aside id="sidebar" class="sidebar">
      <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{route('dashboards.index')}}">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>Master</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="master-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="#">
                <i class="bi bi-circle"></i><span>Barang</span>
              </a>
            </li>
            <li>
              <a href="{{route('donatur.index')}}">
                <i class="bi bi-circle"></i><span>Donatur</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.html">
            <i class="ri-hand-heart-line"></i>
            <span>Input Bantuan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.html">
            <i class="ri-luggage-cart-line"></i>
            <span>Distribusi Bantuan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.html">
            <i class="bi bi-journal-text"></i>
            <span>Laporan Distribusi Bantuan</span>
          </a>
        </li>
      </ul>
    </aside>