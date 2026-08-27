@extends('layouts.app')

@section('title', 'Dashboard - Daily Expense Tracker')

@section('content')

<!-- Header & Quick Input Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-square-plus me-2 text-primary"></i>Tambah Pengeluaran Harian
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#expenseAnalysisModal">
                        <i class="fa-solid fa-chart-pie me-1 text-primary"></i> Form & Simulator Analisa Pengeluaran
                    </button>
                    <span class="badge bg-primary-subtle text-primary fw-medium px-3 py-2 d-none d-md-inline-block">Form Input Cepat</span>
                </div>
            </div>
            <div class="card-body pt-1">
                <form action="{{ route('expenses.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <!-- Tanggal -->
                        <div class="col-md-2">
                            <label for="date" class="form-label fw-medium small">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori (Searchable Select2) -->
                        <div class="col-md-3">
                            <label for="category" class="form-label fw-medium small">Kategori <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-select searchable-select @error('category') is-invalid @enderror" required>
                                <option value="" disabled selected>🔍 Cari / Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi Pengeluaran -->
                        <div class="col-md-3">
                            <label for="description" class="form-label fw-medium small">Nama / Deskripsi <span class="text-danger">*</span></label>
                            <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Contoh: Makan Siang / Listrik" value="{{ old('description') }}" required>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="col-md-2">
                            <label for="payment_method" class="form-label fw-medium small">Metode Bayar <span class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih --</option>
                                @foreach($paymentMethods as $pm)
                                    <option value="{{ $pm }}" {{ old('payment_method') == $pm ? 'selected' : '' }}>{{ $pm }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nominal Amount -->
                        <div class="col-md-2">
                            <label for="amount" class="form-label fw-medium small">Nominal (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="0" min="1" step="any" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-navy px-4 fw-medium">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Pengeluaran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- KPI / Summary Cards (4 Cards Grid) -->
<div class="row g-3 mb-4">
    <!-- Total Pengeluaran -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3">
                    <i class="fa-solid fa-wallet fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Total Pengeluaran</span>
                    <h4 class="fw-bold mb-0 text-navy">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Transaksi -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card kpi-green p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-success-subtle text-success p-3 me-3">
                    <i class="fa-solid fa-receipt fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Total Transaksi</span>
                    <h4 class="fw-bold mb-0 text-navy">{{ number_format($totalTransactions) }} <span class="fs-6 text-muted fw-normal">Item</span></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Rata-rata per Transaksi -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card kpi-purple p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background-color: #f3e8ff; color: #8b5cf6;">
                    <i class="fa-solid fa-chart-line fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Rata-rata / Transaksi</span>
                    <h4 class="fw-bold mb-0 text-navy">Rp {{ number_format($averagePerTransaction, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengeluaran Terbesar (Highest Expense) -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card p-3 h-100" style="border-left-color: #ef4444;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-danger-subtle text-danger p-3 me-3">
                    <i class="fa-solid fa-fire fa-2x"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="text-muted small fw-medium">Pengeluaran Terbesar</span>
                    <h4 class="fw-bold mb-0 text-danger">Rp {{ number_format($highestExpense, 0, ',', '.') }}</h4>
                    <small class="text-muted text-truncate d-block" style="font-size: 0.72rem;" title="{{ $highestExpenseDesc }}">
                        {{ $highestExpenseDesc }}
                    </small>
                </div>
            </div>
        </div>
</div>

<!-- SECTION QUICK ANALISA & EVALUASI ANGGARAN -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom border-start border-4 border-info style-banner" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);">
            <div class="card-body py-3">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info text-white p-3 me-3 shadow-sm">
                            <i class="fa-solid fa-chart-line fa-xl"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-navy">
                                Quick Analysis & Proyeksi Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                            </h6>
                            <p class="mb-0 text-secondary small">
                                Pengeluaran MTD: <strong>Rp {{ number_format($currentMonthExpenses, 0, ',', '.') }}</strong> 
                                &bull; Rata-rata: <strong>Rp {{ number_format($dailyAverageThisMonth, 0, ',', '.') }}/hari</strong> 
                                &bull; Proyeksi Akhir Bulan: <strong class="text-primary">Rp {{ number_format($projectedMonthEndExpenses, 0, ',', '.') }}</strong>
                            </p>
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-navy btn-sm px-3 shadow-sm fw-medium" data-bs-toggle="modal" data-bs-target="#expenseAnalysisModal">
                            <i class="fa-solid fa-sliders me-1"></i> Input Target & Simulasi Analisa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar & PDF Export Button -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-body py-3">
                <form action="{{ route('expenses.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="fa-regular fa-calendar me-1"></i> Dari</span>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="fa-regular fa-calendar me-1"></i> Sampai</span>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                    </div>

                    <!-- Filter Kategori (Searchable Select2) -->
                    <div class="col-md-3">
                        <select name="category" class="form-select form-select-sm searchable-select">
                            <option value="">🔍 -- Semua Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fa-solid fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                        <a href="{{ route('expenses.export-pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'category' => $category]) }}" class="btn btn-danger btn-sm flex-fill" target="_blank">
                            <i class="fa-solid fa-file-pdf me-1"></i> Cetak PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SECTION GRAFIK PENGELUARAN HARIAN, BULANAN, & TAHUNAN -->
<div class="row g-4 mb-4">
    <!-- Grafik Pengeluaran Harian -->
    <div class="col-lg-4">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-chart-area me-2 text-primary"></i>Grafik Harian
                </h6>
                <span class="badge bg-primary-subtle text-primary small">Trend Harian</span>
            </div>
            <div class="card-body">
                @if(count($dailyChartData) > 0)
                    <div class="chart-container">
                        <canvas id="dailyExpenseChart"></canvas>
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="fa-solid fa-chart-line fa-2x mb-2 opacity-50"></i>
                        <p class="mb-0 small">Belum ada data harian.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Grafik Pengeluaran Bulanan -->
    <div class="col-lg-4">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-chart-column me-2 text-success"></i>Grafik Bulanan
                </h6>
                <span class="badge bg-success-subtle text-success small">Per Bulan</span>
            </div>
            <div class="card-body">
                @if(count($monthlyChartData) > 0)
                    <div class="chart-container">
                        <canvas id="monthlyExpenseChart"></canvas>
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="fa-solid fa-chart-column fa-2x mb-2 opacity-50"></i>
                        <p class="mb-0 small">Belum ada data bulanan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Grafik Pengeluaran Tahunan -->
    <div class="col-lg-4">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-chart-bar me-2 text-purple" style="color: #8b5cf6;"></i>Grafik Tahunan
                </h6>
                <span class="badge bg-purple-subtle text-purple small" style="background-color: #f3e8ff; color: #8b5cf6;">Per Tahun</span>
            </div>
            <div class="card-body">
                @if(count($yearlyChartData) > 0)
                    <div class="chart-container">
                        <canvas id="yearlyExpenseChart"></canvas>
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="fa-solid fa-chart-pie fa-2x mb-2 opacity-50"></i>
                        <p class="mb-0 small">Belum ada data tahunan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Tabel Ringkasan Kategori dengan Live Search Box -->
    <div class="col-lg-4">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0 text-navy">
                        <i class="fa-solid fa-chart-pie me-2 text-warning"></i>Ringkasan per Kategori
                    </h6>
                </div>
                <!-- Live Search Box Kategori -->
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" id="categorySearchInput" class="form-control border-start-0" placeholder="Ketik untuk mencari kategori...">
                </div>
            </div>
            <div class="card-body p-0">
                @if($categoryBreakdown->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="categoryBreakdownTable" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Kategori</th>
                                <th class="text-end">Jumlah</th>
                                <th class="text-end">Total & Porsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categoryBreakdown as $item)
                            <tr class="category-row" data-name="{{ strtolower($item['name']) }}">
                                <td>
                                    <span class="fw-medium text-dark d-block category-title">{{ $item['name'] }}</span>
                                </td>
                                <td class="text-end fw-semibold">{{ $item['count'] }} x</td>
                                <td class="text-end">
                                    <span class="fw-bold d-block">Rp {{ number_format($item['total'], 0, ',', '.') }}</span>
                                    <div class="progress mt-1" style="height: 6px;" title="{{ $item['percentage'] }}%">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $item['percentage'] }}%" aria-valuenow="{{ $item['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $item['percentage'] }}%</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4 text-center text-muted">
                    <i class="fa-solid fa-inbox fa-2x mb-2 opacity-50"></i>
                    <p class="mb-0 small">Belum ada data pengeluaran.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Pengeluaran Utama -->
    <div class="col-lg-8">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-list-check me-2 text-primary"></i>Rincian Data Pengeluaran
                </h6>
                <span class="badge bg-secondary-subtle text-secondary small">Total {{ count($expenses) }} Transaksi</span>
            </div>
            <div class="card-body p-0">
                @if(count($expenses) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-custom mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>Kategori</th>
                                <th>Metode</th>
                                <th class="text-end">Nominal</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $index => $expense)
                            <tr>
                                <td class="text-center fw-medium text-muted">{{ $index + 1 }}</td>
                                <td style="white-space: nowrap;">
                                    <i class="fa-regular fa-calendar me-1 text-muted"></i>
                                    {{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $expense->description }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                                        {{ $expense->category }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis px-2 py-1">
                                        {{ $expense->payment_method }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-navy" style="white-space: nowrap;">
                                    Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- Edit Modal Trigger Button -->
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $expense->id }}" title="Edit Data">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <!-- Delete Form Button -->
                                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-delete" title="Hapus Data">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal for Row -->
                            <div class="modal fade" id="editModal{{ $expense->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $expense->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header modal-header-custom">
                                            <h5 class="modal-title fs-6 fw-bold" id="editModalLabel{{ $expense->id }}">
                                                <i class="fa-solid fa-pen-to-square me-2"></i>Edit Pengeluaran #{{ $expense->id }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Tanggal <span class="text-danger">*</span></label>
                                                    <input type="date" name="date" class="form-control" value="{{ \Carbon\Carbon::parse($expense->date)->format('Y-m-d') }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Kategori <span class="text-danger">*</span></label>
                                                    <select name="category" class="form-select modal-searchable-select" required>
                                                        @foreach($categories as $cat)
                                                            <option value="{{ $cat }}" {{ $expense->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Nama / Deskripsi <span class="text-danger">*</span></label>
                                                    <input type="text" name="description" class="form-control" value="{{ $expense->description }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Metode Pembayaran <span class="text-danger">*</span></label>
                                                    <select name="payment_method" class="form-select" required>
                                                        @foreach($paymentMethods as $pm)
                                                            <option value="{{ $pm }}" {{ $expense->payment_method == $pm ? 'selected' : '' }}>{{ $pm }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Nominal (Rp) <span class="text-danger">*</span></label>
                                                    <input type="number" name="amount" class="form-control" value="{{ (int)$expense->amount }}" min="1" step="any" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light py-2">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                        <!-- TOTAL ROW AT BOTTOM -->
                        <tfoot class="table-group-divider bg-light fw-bold">
                            <tr class="table-secondary">
                                <td colspan="5" class="text-end text-uppercase">TOTAL PENGELUARAN :</td>
                                <td class="text-end text-danger fs-6">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="p-5 text-center text-muted">
                    <i class="fa-solid fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>
                    <h6 class="fw-bold">Tidak ada data pengeluaran</h6>
                    <p class="small mb-0">Silakan tambahkan transaksi pengeluaran baru atau atur ulang filter pencarian Anda.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- MODAL FORM ANALISA PENGELUARAN & SIMULASI ANGGARAN -->
<div class="modal fade" id="expenseAnalysisModal" tabindex="-1" aria-labelledby="expenseAnalysisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header modal-header-custom py-3">
                <h5 class="modal-title fs-6 fw-bold" id="expenseAnalysisModalLabel">
                    <i class="fa-solid fa-chart-pie me-2 text-warning"></i>Form Analisa Pengeluaran & Evaluasi Anggaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                
                <!-- SECTION FORM INPUT PARAMETER ANALISA -->
                <div class="card card-custom mb-4 border-top border-4 border-primary">
                    <div class="card-header bg-white py-2">
                        <h6 class="fw-bold mb-0 text-navy small">
                            <i class="fa-solid fa-sliders me-2 text-primary"></i>1. Parameter Target & Ambang Anggaran (Budget Input Form)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Target Anggaran (Rp) -->
                            <div class="col-md-5">
                                <label for="targetBudgetInput" class="form-label fw-medium small">Target Anggaran Bulanan (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text fw-bold bg-white">Rp</span>
                                    <input type="number" id="targetBudgetInput" class="form-control fw-bold text-navy" value="10000000" min="100000" step="100000">
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Ketik nominal batas maksimal anggaran usaha bulan ini.</small>
                            </div>

                            <!-- Batas Warning (%) -->
                            <div class="col-md-3">
                                <label for="warningThresholdInput" class="form-label fw-medium small">Batas Waspada (%)</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" id="warningThresholdInput" class="form-control text-center" value="85" min="50" max="100">
                                    <span class="input-group-text bg-white">%</span>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Alert jika melebihi % ini.</small>
                            </div>

                            <!-- Opsi Periode Analisa -->
                            <div class="col-md-4">
                                <label for="analysisScopeSelect" class="form-label fw-medium small">Cakupan Data Analisa</label>
                                <select id="analysisScopeSelect" class="form-select form-select-sm">
                                    <option value="mtd" selected>Bulan Berjalan (MTD)</option>
                                    <option value="filtered">Data Terfilter Saat Ini</option>
                                </select>
                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Pilih scope data yang dihitung.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION HASIL KALKULASI & INDIKATOR EFISIENSI -->
                <div class="card card-custom mb-4">
                    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-navy small">
                            <i class="fa-solid fa-gauge-high me-2 text-success"></i>2. Hasil Analisa Realisasi & Status Efisiensi
                        </h6>
                        <span id="analysisEfficiencyBadge" class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fw-bold fs-6">
                            EFISIEN / HEMAT
                        </span>
                    </div>
                    <div class="card-body">
                        <!-- Progress Gauge Bar -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-semibold text-muted">Penggunaan Anggaran: <span id="realizationPercentageText" class="text-navy fw-bold">0%</span></span>
                                <span class="small text-muted" id="realizationVsTargetText">Rp 0 / Rp 10.000.000</span>
                            </div>
                            <div class="progress" style="height: 14px; border-radius: 8px;">
                                <div id="analysisProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- 4 Stat Cards in Modal -->
                        <div class="row g-2 text-center">
                            <div class="col-md-3 col-6">
                                <div class="p-2 border rounded bg-white">
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">Total Realisasi</small>
                                    <strong class="text-navy d-block" id="modalTotalRealizationText">Rp 0</strong>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-2 border rounded bg-white">
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">Sisa / Selisih Anggaran</small>
                                    <strong class="text-success d-block" id="modalVarianceText">Rp 0</strong>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-2 border rounded bg-white">
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">Rata-rata / Hari</small>
                                    <strong class="text-info d-block" id="modalDailyAverageText">Rp 0</strong>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-2 border rounded bg-white">
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">Proyeksi Akhir Bulan</small>
                                    <strong class="text-primary d-block" id="modalProjectedText">Rp 0</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION INSIGHT FINANSIAL & REKOMENDASI -->
                <div class="card card-custom">
                    <div class="card-header bg-white py-2">
                        <h6 class="fw-bold mb-0 text-navy small">
                            <i class="fa-solid fa-lightbulb me-2 text-warning"></i>3. Insight Finansial & Rekomendasi Penghematan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white h-100">
                                    <h6 class="fw-bold text-navy small mb-2"><i class="fa-solid fa-trophy me-1 text-danger"></i> Top Kategori Pengeluaran Dominan</h6>
                                    @if(count($categoryBreakdown) > 0)
                                        @php $topCat = $categoryBreakdown->first(); @endphp
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fw-semibold text-dark">{{ $topCat['name'] }}</span>
                                            <span class="badge bg-danger-subtle text-danger fw-bold">{{ $topCat['percentage'] }}% dari total</span>
                                        </div>
                                        <div class="small text-muted">
                                            Total nominal: <strong>Rp {{ number_format($topCat['total'], 0, ',', '.') }}</strong> ({{ $topCat['count'] }} transaksi)
                                        </div>
                                    @else
                                        <span class="small text-muted">Belum ada data transaksi.</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white h-100">
                                    <h6 class="fw-bold text-navy small mb-2"><i class="fa-solid fa-comment-dollar me-1 text-primary"></i> Advice & Rekomendasi Sistem</h6>
                                    <p id="modalSmartAdviceText" class="small mb-0 text-secondary">
                                        Memuat rekomendasi...
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-white py-2 justify-content-between">
                <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>Dihitung secara real-time</small>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-navy btn-sm" onclick="window.print()">
                        <i class="fa-solid fa-print me-1"></i> Cetak Ringkasan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Select2 for Searchable Category Dropdowns
        if (typeof $.fn.select2 !== 'undefined') {
            $('.searchable-select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Ketik untuk mencari kategori...'
            });
        }

        // Live Search Input for Category Breakdown Table
        const categorySearchInput = document.getElementById('categorySearchInput');
        if (categorySearchInput) {
            categorySearchInput.addEventListener('keyup', function () {
                const query = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('#categoryBreakdownTable .category-row');

                rows.forEach(row => {
                    const catName = row.getAttribute('data-name') || '';
                    if (catName.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Confirmation before delete using SweetAlert2
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Hapus Data Pengeluaran?',
                    text: "Data yang telah dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Helper Format Currency
        const formatRupiahTick = function(value) {
            if (value >= 1000000000) return 'Rp ' + (value/1000000000).toFixed(1) + ' M';
            if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + ' Jt';
            if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + ' Rb';
            return 'Rp ' + value;
        };

        // 1. Grafik Harian (Line Chart)
        @if(count($dailyChartData) > 0)
        const dailyCtx = document.getElementById('dailyExpenseChart').getContext('2d');
        const dailyGradient = dailyCtx.createLinearGradient(0, 0, 0, 300);
        dailyGradient.addColorStop(0, 'rgba(37, 99, 235, 0.35)');
        dailyGradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyChartLabels) !!},
                datasets: [{
                    label: 'Pengeluaran (Rp)',
                    data: {!! json_encode($dailyChartData) !!},
                    borderColor: '#2563eb',
                    borderWidth: 3,
                    backgroundColor: dailyGradient,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' Pengeluaran: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw || 0);
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { callback: formatRupiahTick } }
                }
            }
        });
        @endif

        // 2. Grafik Bulanan (Bar Chart)
        @if(count($monthlyChartData) > 0)
        const monthlyCtx = document.getElementById('monthlyExpenseChart').getContext('2d');
        
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyChartLabels) !!},
                datasets: [{
                    label: 'Total Pengeluaran (Rp)',
                    data: {!! json_encode($monthlyChartData) !!},
                    backgroundColor: '#10b981',
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' Total: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw || 0);
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { callback: formatRupiahTick } }
                }
            }
        });
        @endif

        // 3. Grafik Tahunan (Bar Chart)
        @if(count($yearlyChartData) > 0)
        const yearlyCtx = document.getElementById('yearlyExpenseChart').getContext('2d');
        
        new Chart(yearlyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($yearlyChartLabels) !!},
                datasets: [{
                    label: 'Total Tahunan (Rp)',
                    data: {!! json_encode($yearlyChartData) !!},
                    backgroundColor: '#8b5cf6',
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' Total Tahun: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw || 0);
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { callback: formatRupiahTick } }
                }
            }
        });
        @endif

        // 4. Interactive Live Expense Analysis Simulator
        const targetBudgetInput = document.getElementById('targetBudgetInput');
        const warningThresholdInput = document.getElementById('warningThresholdInput');
        const analysisScopeSelect = document.getElementById('analysisScopeSelect');

        const mtdTotal = {{ (float) $currentMonthExpenses }};
        const filteredTotal = {{ (float) $totalExpenses }};
        const daysPassed = {{ (int) $daysPassedInMonth }};
        const totalDays = {{ (int) $totalDaysInMonth }};

        const updateAnalysisMetrics = function() {
            if (!targetBudgetInput) return;

            const targetBudget = parseFloat(targetBudgetInput.value) || 1;
            const warningThreshold = parseFloat(warningThresholdInput.value) || 85;
            const scope = analysisScopeSelect ? analysisScopeSelect.value : 'mtd';

            const activeTotal = (scope === 'filtered') ? filteredTotal : mtdTotal;
            const dailyAvg = activeTotal > 0 ? (activeTotal / daysPassed) : 0;
            const projectedMonthEnd = dailyAvg * totalDays;

            const percentage = (activeTotal / targetBudget) * 100;
            const variance = targetBudget - activeTotal;

            const formatRp = (num) => 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(num));

            const realizationPercentageText = document.getElementById('realizationPercentageText');
            if (realizationPercentageText) realizationPercentageText.textContent = percentage.toFixed(1) + '%';

            const realizationVsTargetText = document.getElementById('realizationVsTargetText');
            if (realizationVsTargetText) realizationVsTargetText.textContent = formatRp(activeTotal) + ' / ' + formatRp(targetBudget);

            const modalTotalRealizationText = document.getElementById('modalTotalRealizationText');
            if (modalTotalRealizationText) modalTotalRealizationText.textContent = formatRp(activeTotal);

            const modalDailyAverageText = document.getElementById('modalDailyAverageText');
            if (modalDailyAverageText) modalDailyAverageText.textContent = formatRp(dailyAvg);

            const modalProjectedText = document.getElementById('modalProjectedText');
            if (modalProjectedText) modalProjectedText.textContent = formatRp(projectedMonthEnd);

            const varianceEl = document.getElementById('modalVarianceText');
            if (varianceEl) {
                if (variance >= 0) {
                    varianceEl.textContent = formatRp(variance) + ' (Hemat)';
                    varianceEl.className = 'text-success d-block fw-bold';
                } else {
                    varianceEl.textContent = formatRp(Math.abs(variance)) + ' (Over)';
                    varianceEl.className = 'text-danger d-block fw-bold';
                }
            }

            const progressBar = document.getElementById('analysisProgressBar');
            const efficiencyBadge = document.getElementById('analysisEfficiencyBadge');

            if (progressBar && efficiencyBadge) {
                let boundedPercent = Math.min(100, Math.max(0, percentage));
                progressBar.style.width = boundedPercent + '%';

                let advice = '';

                if (percentage > 100) {
                    progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated bg-danger';
                    efficiencyBadge.className = 'badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 fw-bold fs-6';
                    efficiencyBadge.textContent = '🚨 OVER BUDGET';
                    advice = 'Pengeluaran Anda telah melebihi target anggaran yang ditetapkan sebesar ' + formatRp(Math.abs(variance)) + '! Disarankan melakukan evaluasi ketat pada transaksi tidak mendesak.';
                } else if (percentage >= warningThreshold) {
                    progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated bg-warning text-dark';
                    efficiencyBadge.className = 'badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1 fw-bold fs-6';
                    efficiencyBadge.textContent = '⚠️ MENDEKATI LIMIT';
                    advice = 'Pengeluaran sudah mencapai ' + percentage.toFixed(1) + '% dari batas anggaran. Harap perhatikan sisa pengeluaran harian hingga akhir bulan.';
                } else if (percentage >= 50) {
                    progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated bg-info';
                    efficiencyBadge.className = 'badge bg-info-subtle text-info-emphasis border border-info-subtle px-3 py-1 fw-bold fs-6';
                    efficiencyBadge.textContent = 'MODERAT / NORMAL';
                    advice = 'Laju pengeluaran terkendali dengan baik. Sisa anggaran Anda sebesar ' + formatRp(variance) + '. Penuhi kebutuhan prioritas terlebih dahulu.';
                } else {
                    progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated bg-success';
                    efficiencyBadge.className = 'badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fw-bold fs-6';
                    efficiencyBadge.textContent = 'EFISIEN / SANGAT HEMAT';
                    advice = 'Pengeluaran sangat hemat dan jauh di bawah anggaran. Pertahankan efisiensi operasional usaha Anda!';
                }

                const modalSmartAdviceText = document.getElementById('modalSmartAdviceText');
                if (modalSmartAdviceText) modalSmartAdviceText.textContent = advice;
            }
        };

        if (targetBudgetInput) targetBudgetInput.addEventListener('input', updateAnalysisMetrics);
        if (warningThresholdInput) warningThresholdInput.addEventListener('input', updateAnalysisMetrics);
        if (analysisScopeSelect) analysisScopeSelect.addEventListener('change', updateAnalysisMetrics);

        // Calculate initially
        updateAnalysisMetrics();
    });
</script>
@endpush
