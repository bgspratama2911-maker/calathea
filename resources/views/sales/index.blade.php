@extends('layouts.app')

@section('title', 'Penjualan Kopi - Calathea Coffee')

@section('content')

<!-- Header & Quick Input Form Penjualan Kopi -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-mug-hot me-2 text-warning"></i>Catat Penjualan Kopi Kasir
                </h5>
                <div class="d-flex gap-2">
                    <!-- Tombol Tambah Kategori Baru -->
                    <button type="button" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-medium" data-bs-toggle="modal" data-bs-target="#addSaleCategoryModal">
                        <i class="fa-solid fa-folder-plus me-1"></i> + Tambah Kategori Baru
                    </button>
                    <span class="badge bg-warning-subtle text-warning-emphasis fw-medium px-3 py-2">Form Input Penjualan</span>
                </div>
            </div>
            <div class="card-body pt-1">
                <form action="{{ route('sales.store') }}" method="POST">
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

                        <!-- Nama Produk (Tampil HANYA nama produk tanpa nominal harga langsung) -->
                        <div class="col-md-4">
                            <label for="product_name" class="form-label fw-medium small">Nama Produk Kopi / Minuman <span class="text-danger">*</span></label>
                            <select name="product_name" id="product_name" class="form-select searchable-select @error('product_name') is-invalid @enderror" required>
                                <option value="" disabled selected>🔍 Cari / Pilih Produk Kopi --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p['name'] }}" data-category="{{ $p['category'] }}" data-price="{{ $p['default_price'] }}" {{ old('product_name') == $p['name'] ? 'selected' : '' }}>
                                        {{ $p['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori (Auto Filled / Selectable) -->
                        <div class="col-md-3">
                            <label for="category" class="form-label fw-medium small">Kategori <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jumlah Terjual (Cup / Botol) -->
                        <div class="col-md-1">
                            <label for="quantity_sold" class="form-label fw-medium small">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="quantity_sold" id="quantity_sold" class="form-control @error('quantity_sold') is-invalid @enderror" min="1" value="{{ old('quantity_sold', 1) }}" required>
                            @error('quantity_sold')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Harga per Unit (Rp) -->
                        <div class="col-md-2">
                            <label for="price_per_unit" class="form-label fw-medium small">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price_per_unit" id="price_per_unit" class="form-control @error('price_per_unit') is-invalid @enderror" placeholder="0" min="0" step="any" value="{{ old('price_per_unit') }}" required>
                            @error('price_per_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="col-md-2">
                            <label for="payment_method" class="form-label fw-medium small">Metode Bayar <span class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                @foreach($paymentMethods as $pm)
                                    <option value="{{ $pm }}" {{ old('payment_method', 'QRIS') == $pm ? 'selected' : '' }}>{{ $pm }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Catatan Kasir -->
                        <div class="col-md-10">
                            <label for="notes" class="form-label fw-medium small">Catatan Kasir / Request Pelanggan</label>
                            <input type="text" name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="Contoh: Less sugar / Less ice" value="{{ old('notes') }}">
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-navy px-4 fw-medium">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Transaksi Penjualan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori Penjualan Baru -->
<div class="modal fade" id="addSaleCategoryModal" tabindex="-1" aria-labelledby="addSaleCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fs-6 fw-bold" id="addSaleCategoryModalLabel">
                    <i class="fa-solid fa-folder-plus me-2"></i>Tambah Kategori Penjualan Kopi Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sales.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="sale_category_name" class="form-label fw-medium small">Nama Kategori Baru <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="sale_category_name" class="form-control" placeholder="Contoh: Manual Brew / Mocktail & Soda / Pastry" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Kategori baru ini akan langsung muncul pada daftar pilihan kategori penjualan.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-medium">Simpan Kategori Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- KPI Summary Cards (Penjualan HARIAN, MINGGUAN, BULANAN & BEST SELLER) -->
<div class="row g-3 mb-4">
    <!-- Penjualan HARIAN -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card p-3 h-100" style="border-left-color: #2563eb;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3">
                    <i class="fa-solid fa-sun fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Penjualan HARIAN (Hari Ini)</span>
                    <h4 class="fw-bold mb-0 text-navy">Rp {{ number_format($dailyIncome, 0, ',', '.') }}</h4>
                    <small class="text-muted" style="font-size: 0.75rem;">Total <strong>{{ number_format($dailyCups) }}</strong> Cup/Botol</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Penjualan MINGGUAN -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card kpi-green p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-success-subtle text-success p-3 me-3">
                    <i class="fa-solid fa-calendar-week fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Penjualan MINGGUAN (Minggu Ini)</span>
                    <h4 class="fw-bold mb-0 text-navy">Rp {{ number_format($weeklyIncome, 0, ',', '.') }}</h4>
                    <small class="text-muted" style="font-size: 0.75rem;">Total <strong>{{ number_format($weeklyCups) }}</strong> Cup/Botol</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Total BULANAN Kopi yang Laku -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card kpi-purple p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background-color: #f3e8ff; color: #8b5cf6;">
                    <i class="fa-solid fa-calendar-days fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">TOTAL BULANAN (Bulan Ini)</span>
                    <h4 class="fw-bold mb-0 text-navy">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</h4>
                    <small class="text-purple fw-semibold" style="color: #8b5cf6; font-size: 0.75rem;">🔥 <strong>{{ number_format($monthlyCups) }}</strong> Cup/Botol Laku</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Best Seller Product -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card p-3 h-100" style="border-left-color: #f59e0b;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-warning-subtle text-warning p-3 me-3">
                    <i class="fa-solid fa-trophy fa-2x"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="text-muted small fw-medium">Terlaris Bulan Ini</span>
                    <h4 class="fw-bold mb-0 text-warning-emphasis text-truncate" title="{{ $bestSellerName }}">{{ $bestSellerName }}</h4>
                    <small class="text-muted" style="font-size: 0.75rem;">Terjual <strong>{{ number_format($bestSellerQty) }}</strong> Cup</small>
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
                <form action="{{ route('sales.index') }}" method="GET" class="row g-2 align-items-center">
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

                    <div class="col-md-3">
                        <select name="product_name" class="form-select form-select-sm searchable-select">
                            <option value="">🔍 -- Semua Produk Kopi --</option>
                            @foreach($productNames as $pn)
                                <option value="{{ $pn }}" {{ $productName == $pn ? 'selected' : '' }}>{{ $pn }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fa-solid fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                        <a href="{{ route('sales.export-pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'product_name' => $productName, 'category' => $category]) }}" class="btn btn-danger btn-sm flex-fill" target="_blank">
                            <i class="fa-solid fa-file-pdf me-1"></i> Cetak PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SECTION GRAFIK PENJUALAN HARIAN & PRODUK TERLARIS -->
<div class="row g-4 mb-4">
    <!-- Grafik Trend Penjualan Harian -->
    <div class="col-lg-7">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-chart-line me-2 text-primary"></i>Grafik Trend Omzet Harian
                </h6>
                <span class="badge bg-primary-subtle text-primary small">Harian</span>
            </div>
            <div class="card-body">
                @if(count($dailyChartData) > 0)
                    <div class="chart-container">
                        <canvas id="salesDailyChart"></canvas>
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="fa-solid fa-chart-line fa-2x mb-2 opacity-50"></i>
                        <p class="mb-0 small">Belum ada data penjualan harian.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Grafik Top 5 Produk Terlaris -->
    <div class="col-lg-5">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-chart-column me-2 text-warning"></i>Top 5 Produk Terlaris
                </h6>
                <span class="badge bg-warning-subtle text-warning-emphasis small">Best Seller</span>
            </div>
            <div class="card-body">
                @if(count($top5ChartData) > 0)
                    <div class="chart-container">
                        <canvas id="salesTop5Chart"></canvas>
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="fa-solid fa-trophy fa-2x mb-2 opacity-50"></i>
                        <p class="mb-0 small">Belum ada data best seller.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Tabel Total Kopi yang Laku per Produk (Live Searchable) -->
    <div class="col-lg-5">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0 text-navy">
                        <i class="fa-solid fa-cup-tasting me-2 text-success"></i>Total Kopi yang Laku (Per Produk)
                    </h6>
                </div>
                <!-- Live Search Input -->
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" id="productSearchInput" class="form-control border-start-0" placeholder="Ketik untuk mencari produk kopi...">
                </div>
            </div>
            <div class="card-body p-0">
                @if($productBreakdown->count() > 0)
                <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="productBreakdownTable" style="font-size: 0.85rem;">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Produk Kopi</th>
                                <th class="text-center">Cup Laku</th>
                                <th class="text-end">Total Omzet & Porsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productBreakdown as $item)
                            <tr class="product-row" data-name="{{ strtolower($item['name']) }}">
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $item['name'] }}</span>
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">{{ $item['category'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success fs-6 fw-bold px-2 py-1">{{ $item['total_qty'] }} Cup</span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold d-block">Rp {{ number_format($item['total_rev'], 0, ',', '.') }}</span>
                                    <div class="progress mt-1" style="height: 6px;" title="{{ $item['percentage'] }}%">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $item['percentage'] }}%" aria-valuenow="{{ $item['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $item['percentage'] }}% dari total</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4 text-center text-muted">
                    <i class="fa-solid fa-inbox fa-2x mb-2 opacity-50"></i>
                    <p class="mb-0 small">Belum ada data penjualan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Data Penjualan Kasir -->
    <div class="col-lg-7">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-receipt me-2 text-primary"></i>Rincian Transaksi Penjualan Kopi
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <!-- Bulk Delete Button (Hidden by default until at least 1 checkbox checked) -->
                    <button type="button" class="btn btn-danger btn-sm fw-medium d-none" id="btnBulkDelete">
                        <i class="fa-solid fa-trash-can me-1"></i> Hapus Terpilih (<span id="selectedCount">0</span>)
                    </button>
                    <span class="badge bg-secondary-subtle text-secondary small">Total {{ $sales->total() }} Transaksi</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if($sales->count() > 0)
                <form id="bulkDeleteForm" action="{{ route('sales.bulk-delete') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-custom mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 35px;">
                                        <input type="checkbox" class="form-check-input" id="checkAllSales" title="Pilih Semua Transaksi di Halaman Ini">
                                    </th>
                                    <th class="text-center" style="width: 35px;">No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Total Omzet</th>
                                    <th class="text-center" style="width: 85px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $index => $sale)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="ids[]" value="{{ $sale->id }}" class="form-check-input sale-checkbox">
                                    </td>
                                    <td class="text-center fw-medium text-muted">{{ $sales->firstItem() + $index }}</td>
                                    <td style="white-space: nowrap;">
                                        <i class="fa-regular fa-calendar me-1 text-muted"></i>
                                        {{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark d-block">{{ $sale->product_name }}</span>
                                        <small class="text-muted" style="font-size: 0.72rem;">{{ $sale->payment_method }} @if($sale->notes) • {{ $sale->notes }} @endif</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary fw-bold">{{ $sale->quantity_sold }} Cup</span>
                                    </td>
                                    <td class="text-end text-muted small" style="white-space: nowrap;">
                                        Rp {{ number_format($sale->price_per_unit, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold text-success" style="white-space: nowrap;">
                                        Rp {{ number_format($sale->total_income, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- Edit Modal Trigger -->
                                            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editSaleModal{{ $sale->id }}" title="Edit Transaksi">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <!-- Delete Button -->
                                            <button type="button" class="btn btn-outline-danger btn-delete-single" data-form-id="deleteForm{{ $sale->id }}" title="Hapus Transaksi">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <!-- TOTAL ROW AT BOTTOM -->
                            <tfoot class="table-group-divider bg-light fw-bold">
                                <tr class="table-secondary">
                                    <td colspan="4" class="text-end text-uppercase">TOTAL PENJUALAN TERFILTER :</td>
                                    <td class="text-center text-primary fs-6">{{ number_format($filteredCups) }} Cup</td>
                                    <td></td>
                                    <td class="text-end text-success fs-6">Rp {{ number_format($filteredIncome, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>

                <!-- Hidden Delete Single Forms -->
                @foreach($sales as $sale)
                <form id="deleteForm{{ $sale->id }}" action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>

                <!-- Edit Sale Modal -->
                <div class="modal fade" id="editSaleModal{{ $sale->id }}" tabindex="-1" aria-labelledby="editSaleModalLabel{{ $sale->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header modal-header-custom">
                                <h5 class="modal-title fs-6 fw-bold" id="editSaleModalLabel{{ $sale->id }}">
                                    <i class="fa-solid fa-pen-to-square me-2"></i>Edit Penjualan #{{ $sale->id }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('sales.update', $sale->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium small">Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" name="date" class="form-control" value="{{ \Carbon\Carbon::parse($sale->date)->format('Y-m-d') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-medium small">Nama Produk Kopi <span class="text-danger">*</span></label>
                                        <select name="product_name" class="form-select" required>
                                            @foreach($productNames as $pn)
                                                <option value="{{ $pn }}" {{ $sale->product_name == $pn ? 'selected' : '' }}>{{ $pn }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-medium small">Kategori <span class="text-danger">*</span></label>
                                        <select name="category" class="form-select" required>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat }}" {{ $sale->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label fw-medium small">Jumlah Cup <span class="text-danger">*</span></label>
                                            <input type="number" name="quantity_sold" class="form-control" value="{{ $sale->quantity_sold }}" min="1" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-medium small">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                                            <input type="number" name="price_per_unit" class="form-control" value="{{ (int)$sale->price_per_unit }}" min="0" step="any" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-medium small">Metode Pembayaran <span class="text-danger">*</span></label>
                                        <select name="payment_method" class="form-select" required>
                                            @foreach($paymentMethods as $pm)
                                                <option value="{{ $pm }}" {{ $sale->payment_method == $pm ? 'selected' : '' }}>{{ $pm }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-medium small">Catatan Kasir</label>
                                        <input type="text" name="notes" class="form-control" value="{{ $sale->notes }}">
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

                <!-- Pagination Links -->
                <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="small text-muted">
                        Menampilkan <strong>{{ $sales->firstItem() ?? 0 }}</strong> sampai <strong>{{ $sales->lastItem() ?? 0 }}</strong> dari <strong>{{ $sales->total() }}</strong> transaksi
                    </div>
                    <div>
                        {{ $sales->links() }}
                    </div>
                </div>

                @else
                <div class="p-5 text-center text-muted">
                    <i class="fa-solid fa-mug-hot fa-3x mb-3 text-secondary opacity-50"></i>
                    <h6 class="fw-bold">Belum ada data penjualan kopi</h6>
                    <p class="small mb-0">Silakan tambahkan transaksi penjualan kasir pada form di atas.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Select2 & Auto-fill price & category when product chosen
        if (typeof $.fn.select2 !== 'undefined') {
            $('#product_name').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Ketik nama kopi...'
            }).on('change', function () {
                const selectedOption = $(this).find(':selected');
                const price = selectedOption.data('price');
                const category = selectedOption.data('category');

                if (price) $('#price_per_unit').val(price);
                if (category) $('#category').val(category);
            });

            $('.searchable-select').not('#product_name').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }

        // Live Search Input for Product Breakdown Table
        const productSearchInput = document.getElementById('productSearchInput');
        if (productSearchInput) {
            productSearchInput.addEventListener('keyup', function () {
                const query = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('#productBreakdownTable .product-row');

                rows.forEach(row => {
                    const productName = row.getAttribute('data-name') || '';
                    if (productName.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // ==========================================
        // BULK DELETE & CHECKBOX SELECT ALL LOGIC
        // ==========================================
        const checkAllSales = document.getElementById('checkAllSales');
        const saleCheckboxes = document.querySelectorAll('.sale-checkbox');
        const btnBulkDelete = document.getElementById('btnBulkDelete');
        const selectedCount = document.getElementById('selectedCount');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');

        function updateBulkDeleteButton() {
            const checkedBoxes = document.querySelectorAll('.sale-checkbox:checked');
            const totalChecked = checkedBoxes.length;

            if (totalChecked > 0) {
                btnBulkDelete.classList.remove('d-none');
                selectedCount.textContent = totalChecked;
            } else {
                btnBulkDelete.classList.add('d-none');
                selectedCount.textContent = '0';
            }

            if (checkAllSales) {
                checkAllSales.checked = (saleCheckboxes.length > 0 && totalChecked === saleCheckboxes.length);
                checkAllSales.indeterminate = (totalChecked > 0 && totalChecked < saleCheckboxes.length);
            }
        }

        if (checkAllSales) {
            checkAllSales.addEventListener('change', function () {
                saleCheckboxes.forEach(cb => {
                    cb.checked = checkAllSales.checked;
                });
                updateBulkDeleteButton();
            });
        }

        saleCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkDeleteButton);
        });

        if (btnBulkDelete) {
            btnBulkDelete.addEventListener('click', function () {
                const totalChecked = document.querySelectorAll('.sale-checkbox:checked').length;
                if (totalChecked === 0) return;

                Swal.fire({
                    title: `Hapus ${totalChecked} Transaksi Terpilih?`,
                    text: "Semua data transaksi penjualan yang dipilih akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: `Ya, Hapus (${totalChecked})!`,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkDeleteForm.submit();
                    }
                });
            });
        }

        // Confirmation before delete single item using SweetAlert2
        const deleteSingleButtons = document.querySelectorAll('.btn-delete-single');
        deleteSingleButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const formId = this.getAttribute('data-form-id');
                const form = document.getElementById(formId);
                
                Swal.fire({
                    title: 'Hapus Transaksi Penjualan?',
                    text: "Data transaksi ini akan dihapus dari laporan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed && form) {
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

        // 1. Grafik Penjualan Harian (Line Chart)
        @if(count($dailyChartData) > 0)
        const dailyCtx = document.getElementById('salesDailyChart').getContext('2d');
        const dailyGradient = dailyCtx.createLinearGradient(0, 0, 0, 300);
        dailyGradient.addColorStop(0, 'rgba(37, 99, 235, 0.35)');
        dailyGradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyChartLabels) !!},
                datasets: [{
                    label: 'Omzet (Rp)',
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
                                return ' Omzet Penjualan: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw || 0);
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

        // 2. Grafik Top 5 Best Sellers (Bar Chart)
        @if(count($top5ChartData) > 0)
        const top5Ctx = document.getElementById('salesTop5Chart').getContext('2d');
        
        new Chart(top5Ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($top5ChartLabels) !!},
                datasets: [{
                    label: 'Terjual (Cup)',
                    data: {!! json_encode($top5ChartData) !!},
                    backgroundColor: '#f59e0b',
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
                                return ' Terjual: ' + context.raw + ' Cup/Botol';
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true }
                }
            }
        });
        @endif
    });
</script>
@endpush
