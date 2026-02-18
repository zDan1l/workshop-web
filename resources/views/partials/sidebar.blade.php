<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile" />
          <span class="login-status online"></span>
          <!--change to offline or busy as needed-->
        </div>
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2">{{ session('user.name', 'Guest') }}</span>
          <span class="text-secondary text-small">{{ ucfirst(session('user.role', 'User')) }}</span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('dashboard') }}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#master-data" aria-expanded="false" aria-controls="master-data">
        <span class="menu-title">Master Data</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-database menu-icon"></i>
      </a>
      <div class="collapse" id="master-data">
        <ul class="nav flex-column sub-menu">
          @if(session('user.role') === 'admin')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('kategori.index') }}">Kategori</a>
          </li>
          @endif
          <li class="nav-item">
            <a class="nav-link" href="{{ route('buku.index') }}">Buku</a>
          </li>
        </ul>
      </div>
    </li>
  </ul>
</nav>