<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rekap Keuangan & Reject Calathea')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logocala.jpeg') }}?v={{ time() }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- jQuery & Select2 (Searchable Dropdown) CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        :root {
            --primary-navy: #0f172a;
            --accent-blue: #2563eb;
            --accent-cyan: #06b6d4;
            --bg-body: #f8fafc;
            --card-border: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: #334155;
            min-height: 100vh;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .nav-link-custom {
            color: rgba(255,255,255,0.85);
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            color: #ffffff;
            background-color: rgba(255,255,255,0.18);
        }

        .card-custom {
            border: 1px solid var(--card-border);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            background: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-custom:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        }

        .kpi-card {
            border: none;
            border-left: 4px solid var(--accent-blue);
            background: #ffffff;
            border-radius: 12px;
        }
        
        .kpi-card.kpi-green {
            border-left-color: #10b981;
        }
        
        .kpi-card.kpi-purple {
            border-left-color: #8b5cf6;
        }

        .badge-category {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .table-custom th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }

        .btn-navy {
            background-color: var(--primary-navy);
            color: #ffffff;
        }
        .btn-navy:hover {
            background-color: #1e293b;
            color: #ffffff;
        }

        .modal-header-custom {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
        }

        .chart-container {
            position: relative;
            height: 320px;
            width: 100%;
        }

        .select2-container--bootstrap-5 .select2-selection {
            font-size: 0.875rem;
            min-height: 38px;
            border-color: #dee2e6;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Navbar Header -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3 shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold text-white fs-4 me-3" href="{{ route('sales.index') }}">
                <img src="{{ asset('images/logocala.jpeg') }}?v={{ time() }}" alt="Logo Calathea Coffee" class="me-2 rounded-circle border border-success" style="height: 40px; width: 40px; object-fit: cover;">
                <span>Calathea Coffee</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Navigation Links for Modules -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('pos.*') ? 'active' : '' }} bg-success bg-opacity-25 text-white fw-bold" href="{{ route('pos.index') }}">
                            <i class="fa-solid fa-cash-register me-1 text-info"></i> POS Kasir
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                            <i class="fa-solid fa-chart-line me-1 text-warning"></i> Penjualan Harian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                            <i class="fa-solid fa-wallet me-1"></i> Pengeluaran Harian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('stocks.*') ? 'active' : '' }}" href="{{ route('stocks.index') }}">
                            <i class="fa-solid fa-cubes me-1"></i> Stok Bahan Baku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('rejects.*') ? 'active' : '' }}" href="{{ route('rejects.index') }}">
                            <i class="fa-solid fa-triangle-exclamation me-1 text-danger"></i> Bahan Reject
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('inventories.*') ? 'active' : '' }}" href="{{ route('inventories.index') }}">
                            <i class="fa-solid fa-boxes-stacked me-1"></i> Inventaris Barang
                        </a>
                    </li>
                </ul>

                <ul class="navbar-menu navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item text-light opacity-75 d-none d-md-inline fs-6">
                        <i class="fa-regular fa-calendar-days me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </li>
                    @auth
                    <li class="nav-item d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle rounded-pill px-3 py-1 fw-medium" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user-circle me-1 text-info"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <span class="dropdown-item-text small text-muted">Signed in as<br><strong>{{ Auth::user()->email }}</strong></span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger fw-semibold">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i>Keluar / Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="container mb-5">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center py-4 text-muted border-top mt-auto bg-white">
        <div class="container">
            <small>&copy; {{ date('Y') }} <strong>Calathea Coffee System</strong> - Penjualan, Pengeluaran, Stok, Bahan Reject & Inventaris Cafe.</small>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

</body>
</html>
