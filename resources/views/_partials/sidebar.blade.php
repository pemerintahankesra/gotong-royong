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
          <a class="nav-link collapsed" href="{{route('bantuan.index')}}">
            <i class="ri-hand-heart-line"></i>
            <span>Input Bantuan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#penarikan-nav" data-bs-toggle="collapse" href="#">
            <i class="bx bx-money"></i><span>Pengajuan Penarikan Uang ke BSP</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="penarikan-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{route('penarikan.index')}}">
                <i class="bi bi-circle"></i><span>Pengajuan Penarikan</span>
              </a>
            </li>
            <li>
              <a href="{{route('penarikan.pelaporan')}}">
                <i class="bi bi-circle"></i><span>Pelaporan Distribusi Bantuan Uang</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{route('distribusi.index')}}">
            <i class="ri-luggage-cart-line"></i>
            <span>Distribusi Bantuan (Barang)</span>
          </a>
        </li>
      </ul>
    </aside>