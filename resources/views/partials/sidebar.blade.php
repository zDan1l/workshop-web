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
          <li class="nav-item">
            <a class="nav-link" href="{{ route('barang.index') }}">Barang</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('barang-scanner') ? 'active' : '' }}" href="{{ route('barang.scanner') }}">
              <i class="mdi mdi-qrcode-scan"></i> Barcode Scanner
            </a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#studi-kasus" aria-expanded="false" aria-controls="studi-kasus">
        <span class="menu-title">Studi Kasus</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-flask menu-icon"></i>
      </a>
      <div class="collapse" id="studi-kasus">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link {{ request()->is('studi-kasus-html-table') ? 'active' : '' }}" href="{{ route('studi-kasus.table') }}">HTML Table</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('studi-kasus-datatables') ? 'active' : '' }}" href="{{ route('studi-kasus.datatables') }}">DataTables</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('studi-kasus-select-kota') ? 'active' : '' }}" href="{{ route('studi-kasus.select') }}">Select Kota</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('studi-kasus-wilayah-ajax') ? 'active' : '' }}" href="{{ route('studi-kasus.wilayah-ajax') }}">Wilayah Administrasi (Ajax)</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('studi-kasus-wilayah-axios') ? 'active' : '' }}" href="{{ route('studi-kasus.wilayah-axios') }}">Wilayah Administrasi (Axios)</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('studi-kasus-pos-ajax') ? 'active' : '' }}" href="{{ route('studi-kasus.pos-ajax') }}">Point of Sales (Ajax)</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('studi-kasus-pos-axios') ? 'active' : '' }}" href="{{ route('studi-kasus.pos-axios') }}">Point of Sales (Axios)</a>
          </li>
        </ul>
      </div>
    </li>
    @if(session('user.role') === 'admin')
    <li class="nav-item">
      <a class="nav-link {{ request()->is('admin-antrian*') ? 'active' : '' }}" href="{{ route('antrian.admin.index') }}">
        <span class="menu-title">Manajemen Antrian</span>
        <i class="mdi mdi-bell-ring menu-icon"></i>
      </a>
    </li>
    @endif
    @if(session('user.role') === 'admin')
    <li class="nav-item">
      <a class="nav-link {{ request()->is('nfc-admin*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#menu-nfc" aria-expanded="false" aria-controls="menu-nfc">
        <span class="menu-title">Absensi NFC</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-nfc menu-icon"></i>
      </a>
      <div class="collapse {{ request()->is('nfc-admin*') ? 'show' : '' }}" id="menu-nfc">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link {{ request()->is('nfc-admin') ? 'active' : '' }}" href="{{ route('nfc.dashboard') }}">
              <i class="mdi mdi-view-dashboard me-1"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('nfc-admin/mahasiswa*') ? 'active' : '' }}" href="{{ route('nfc.mahasiswa.index') }}">
              <i class="mdi mdi-account me-1"></i> Mahasiswa
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('nfc-admin/dosen*') ? 'active' : '' }}" href="{{ route('nfc.dosen.index') }}">
              <i class="mdi mdi-account-tie me-1"></i> Dosen
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('nfc-admin/kelas*') ? 'active' : '' }}" href="{{ route('nfc.kelas.index') }}">
              <i class="mdi mdi-school me-1"></i> Kelas
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('nfc-admin/sesi*') ? 'active' : '' }}" href="{{ route('nfc.sesi.index') }}">
              <i class="mdi mdi-calendar-clock me-1"></i> Sesi Kuliah
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('nfc-admin/absensi*') ? 'active' : '' }}" href="{{ route('nfc.absensi.index') }}">
              <i class="mdi mdi-clipboard-check me-1"></i> Laporan Absensi
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('nfc-admin/scanner') ? 'active' : '' }}" href="{{ route('nfc.scanner') }}" target="_blank">
              <i class="mdi mdi-cellphone-android me-1"></i> NFC Scanner
            </a>
          </li>
        </ul>
      </div>
    </li>
    @endif
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#menu-pdf" aria-expanded="false" aria-controls="menu-pdf">
        <span class="menu-title">Generator PDF</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-file-pdf menu-icon"></i>
      </a>
      <div class="collapse" id="menu-pdf">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('sertifikat.form') }}">
              <i class="mdi mdi-certificate me-1"></i> Sertifikat
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('pdf.undangan') }}">
              <i class="mdi mdi-email-outline me-1"></i> Undangan
            </a>
          </li>
        </ul>
      </div>
    </li>
  </ul>
</nav>