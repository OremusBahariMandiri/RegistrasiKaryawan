<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'aa') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/layout.css'])


</head>

<body>
    <div id="app">
        <!-- Navbar -->
        <nav class="modern-navbar">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="sidebar-toggle" id="sidebarToggleBtn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-cube"></i>
                    Registrasi Karyawan
                </a>
            </div>

            <div class="navbar-right">
                @guest
                    <div class="auth-links">
                        @if (Route::has('login'))
                            <a class="auth-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i>
                                {{ __('Login') }}
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a class="auth-link register" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i>
                                {{ __('Register') }}
                            </a>
                        @endif
                    </div>
                @else
                    <div class="user-dropdown">
                        <button class="user-menu-btn" onclick="toggleUserMenu()">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
                        </button>

                        <div class="dropdown-menu" id="userDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt" style="margin-right: 0.5rem;"></i>
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </nav>

        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-content">
                <ul class="sidebar-menu">
                    <li class="sidebar-menu-item" data-tooltip="Dashboard">
                        <a href="{{ url('/') }}"
                            class="sidebar-menu-link {{ request()->is('/') || request()->is('home') || request()->is('dashboard') || request()->is('index.php') || request()->fullUrlIs(url('/')) ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span class="sidebar-menu-text">Dashboard</span>
                        </a>
                    </li>
                    @auth
                        @if (Auth::user()->is_admin == 1)
                            <li class="sidebar-menu-item" data-tooltip="Data Pengguna">
                                <a href="{{ route('users.index') }}"
                                    class="sidebar-menu-link {{ request()->is('users*') ? 'active' : '' }}">
                                    <i class="fas fa-users"></i>
                                    <span class="sidebar-menu-text">Pengguna</span>
                                </a>
                            </li>
                        @endif
                    @endauth
                    <li class="sidebar-menu-item" data-tooltip="Data Pribadi">
                        <a href="{{ route('data-pribadi.index') }}"
                            class="sidebar-menu-link {{ request()->is('data-pribadi*') ? 'active' : '' }}">
                            <i class="fas fa-book"></i>
                            <span class="sidebar-menu-text">Data Pribadi</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item" data-tooltip="Data Keluarga Kandung">
                        <a href="{{ route('keluarga-kandung.index') }}"
                            class="sidebar-menu-link {{ request()->is('keluarga-kandung*') ? 'active' : '' }}">
                            <i class="fas fa-user-friends"></i>
                            <span class="sidebar-menu-text">Keluarga Kandung</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item" data-tooltip="Data Pribadi">
                        <a href="{{ route('keluarga-inti.index') }}"
                            class="sidebar-menu-link {{ request()->is('keluarga-inti*') ? 'active' : '' }}">
                            <i class="fas fa-user-friends"></i>
                            <span class="sidebar-menu-text">Keluarga Inti</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item" data-tooltip="Data Pribadi">
                        <a href="{{ route('pendidikan-formal.index') }}"
                            class="sidebar-menu-link {{ request()->is('pendidikan-formal*') ? 'active' : '' }}">
                            <i class="fas fa-graduation-cap"></i>
                            <span class="sidebar-menu-text">Penndidikan Formal</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item" data-tooltip="Data Pribadi">
                        <a href="{{ route('pendidikan-non-formal.index') }}"
                            class="sidebar-menu-link {{ request()->is('pendidikan-non-formal*') ? 'active' : '' }}">
                            <i class="fas fa-certificate"></i>
                            <span class="sidebar-menu-text">Penndidikan Non Formal</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item" data-tooltip="Data Pribadi">
                        <a href="{{ route('riwayat-organisasi.index') }}"
                            class="sidebar-menu-link {{ request()->is('riwayat-organisasi*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span class="sidebar-menu-text">Riwayat Organisasi</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item" data-tooltip="Data Pribadi">
                        <a href="{{ route('riwayat-kerja.index') }}"
                            class="sidebar-menu-link {{ request()->is('riwayat-kerja*') ? 'active' : '' }}">
                            <i class="fas fa-briefcase"></i>
                            <span class="sidebar-menu-text">Riwayat Kerja</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item" data-tooltip="Profil">
                        <a href="{{route ('settings.index')}}" class="sidebar-menu-link {{ request()->is('profil*') ? 'active' : '' }}">
                            <i class="fas fa-cogs"></i>
                            <span class="sidebar-menu-text">Pengaturan</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            @yield('content')
        </main>
    </div>

    <script>
        class SidebarManager {
            constructor() {
                this.sidebar = document.getElementById('sidebar');
                this.mainContent = document.getElementById('mainContent');
                this.overlay = document.getElementById('sidebarOverlay');
                this.toggleBtn = document.getElementById('sidebarToggleBtn');
                this.isCollapsed = false;
                this.isMobile = window.innerWidth <= 768;

                this.init();
            }

            init() {
                // Load saved state
                this.loadState();

                // Setup event listeners
                this.setupEventListeners();

                // Initial state check
                this.checkScreenSize();

                // Update toggle button appearance
                this.updateToggleButton();
            }

            setupEventListeners() {
                window.addEventListener('resize', () => {
                    this.checkScreenSize();
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (event) => {
                    const userDropdown = document.getElementById('userDropdown');
                    const userMenuBtn = document.querySelector('.user-menu-btn');

                    if (userDropdown &&
                        !userMenuBtn?.contains(event.target) &&
                        !userDropdown.contains(event.target)) {
                        userDropdown.classList.remove('show');
                    }
                });
            }

            checkScreenSize() {
                const wasMobile = this.isMobile;
                this.isMobile = window.innerWidth <= 768;

                if (wasMobile !== this.isMobile) {
                    if (this.isMobile) {
                        // Switched to mobile
                        this.sidebar.classList.remove('collapsed');
                        this.mainContent.classList.remove('sidebar-collapsed');
                        this.closeMobileSidebar();
                        this.updateToggleButton();
                    } else {
                        // Switched to desktop
                        this.sidebar.classList.remove('mobile-open');
                        this.overlay.classList.remove('show');
                        this.loadState(); // Restore desktop state
                        this.updateToggleButton();
                    }
                }
            }

            toggle() {
                if (this.isMobile) {
                    this.toggleMobile();
                } else {
                    this.toggleDesktop();
                }
                this.updateToggleButton();
            }

            toggleMobile() {
                const isOpen = this.sidebar.classList.contains('mobile-open');

                if (isOpen) {
                    this.closeMobileSidebar();
                } else {
                    this.openMobileSidebar();
                }
            }

            openMobileSidebar() {
                this.sidebar.classList.add('mobile-open');
                this.overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            closeMobileSidebar() {
                this.sidebar.classList.remove('mobile-open');
                this.overlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            toggleDesktop() {
                this.isCollapsed = !this.isCollapsed;

                if (this.isCollapsed) {
                    this.sidebar.classList.add('collapsed');
                    this.mainContent.classList.add('sidebar-collapsed');
                } else {
                    this.sidebar.classList.remove('collapsed');
                    this.mainContent.classList.remove('sidebar-collapsed');
                }

                this.saveState();
            }

            updateToggleButton() {
                if (this.toggleBtn) {
                    if (!this.isMobile && this.isCollapsed) {
                        this.toggleBtn.classList.add('collapsed');
                    } else {
                        this.toggleBtn.classList.remove('collapsed');
                    }
                }
            }

            saveState() {
                if (!this.isMobile) {
                    localStorage.setItem('sidebarCollapsed', this.isCollapsed);
                }
            }

            loadState() {
                if (!this.isMobile) {
                    const savedState = localStorage.getItem('sidebarCollapsed');
                    if (savedState !== null) {
                        this.isCollapsed = savedState === 'true';

                        if (this.isCollapsed) {
                            this.sidebar.classList.add('collapsed');
                            this.mainContent.classList.add('sidebar-collapsed');
                        } else {
                            this.sidebar.classList.remove('collapsed');
                            this.mainContent.classList.remove('sidebar-collapsed');
                        }
                    }
                }
            }
        }

        // Initialize sidebar manager
        let sidebarManager;

        document.addEventListener('DOMContentLoaded', () => {
            sidebarManager = new SidebarManager();
        });

        // Global functions for onclick handlers
        function toggleSidebar() {
            sidebarManager?.toggle();
        }

        function closeMobileSidebar() {
            sidebarManager?.closeMobileSidebar();
        }

        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown?.classList.toggle('show');
        }
    </script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    @stack('scripts')
</body>

</html>
