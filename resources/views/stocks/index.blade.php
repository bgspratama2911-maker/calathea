@extends('layouts.app')

@section('title', 'Stok Bahan Baku - Calathea Coffee')

@section('content')

<!-- Form Tambah Produk Stok Baru -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-square-plus me-2 text-primary"></i>Tambah Stok Bahan Baku Baru
                </h5>
                <div class="d-flex gap-2">
                    <!-- Tombol Tambah Kategori Baru -->
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-medium" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fa-solid fa-folder-plus me-1"></i> + Tambah Kategori Baru
                    </button>
                    <span class="badge bg-primary-subtle text-primary fw-medium px-3 py-2">Form Input Stok</span>
                </div>
            </div>
            <div class="card-body pt-1">
                <form action="{{ route('stocks.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <!-- Tipe Stok (Pemisah Bahan Baku vs Menu POS) -->
                        <div class="col-md-3">
                            <label for="type" class="form-label fw-bold small text-navy">
                                <i class="fa-solid fa-tags me-1 text-primary"></i>Tipe Item Stok <span class="text-danger">*</span>
                            </label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="raw_material" {{ old('type', 'raw_material') == 'raw_material' ? 'selected' : '' }}>
                                    📦 Bahan Baku (Gudang/Dapur)
                                </option>
                                <option value="pos_menu" {{ old('type') == 'pos_menu' ? 'selected' : '' }}>
                                    ☕ Menu Jual POS Kasir (Tampil di Kasir)
                                </option>
                            </select>
                            <small class="text-muted" style="font-size: 0.72rem;">Pilih jika item ini untuk dijual di kasir atau bahan baku ditarik gudang.</small>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Produk -->
                        <div class="col-md-3">
                            <label for="product_name" class="form-label fw-medium small">Nama Produk / Item <span class="text-danger">*</span></label>
                            <input type="text" name="product_name" id="product_name" class="form-control @error('product_name') is-invalid @enderror" placeholder="Contoh: Biji Kopi / Caramel Latte" value="{{ old('product_name') }}" required>
                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-3">
                            <label for="category" class="form-label fw-medium small">Kategori <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-select searchable-select @error('category') is-invalid @enderror" required>
                                <option value="" disabled selected>🔍 Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stok Saat Ini -->
                        <div class="col-md-3">
                            <label for="current_stock" class="form-label fw-medium small">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" name="current_stock" id="current_stock" class="form-control @error('current_stock') is-invalid @enderror" min="0" value="{{ old('current_stock', 10) }}" required>
                            @error('current_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Batas Stok Minimal (Warning) -->
                        <div class="col-md-3">
                            <label for="minimum_stock" class="form-label fw-medium small">Stok Minimal (Warning) <span class="text-danger">*</span></label>
                            <input type="number" name="minimum_stock" id="minimum_stock" class="form-control @error('minimum_stock') is-invalid @enderror" min="0" value="{{ old('minimum_stock', 3) }}" required>
                            @error('minimum_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Satuan Stok -->
                        <div class="col-md-2">
                            <label for="unit" class="form-label fw-medium small">Satuan <span class="text-danger">*</span></label>
                            <select name="unit" id="unit" class="form-select @error('unit') is-invalid @enderror" required>
                                @foreach($units as $u)
                                    <option value="{{ $u }}" {{ old('unit', 'Kg') == $u ? 'selected' : '' }}>{{ $u }}</option>
                                @endforeach
                            </select>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Harga Satuan -->
                        <div class="col-md-3">
                            <label for="unit_price" class="form-label fw-medium small">Harga Jual / Beli per Satuan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="unit_price" id="unit_price" class="form-control @error('unit_price') is-invalid @enderror" placeholder="0" min="0" step="any" value="{{ old('unit_price', 0) }}" required>
                            @error('unit_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Restock -->
                        <div class="col-md-2">
                            <label for="last_restock_date" class="form-label fw-medium small">Tanggal Restock <span class="text-danger">*</span></label>
                            <input type="date" name="last_restock_date" id="last_restock_date" class="form-control @error('last_restock_date') is-invalid @enderror" value="{{ old('last_restock_date', date('Y-m-d')) }}" required>
                            @error('last_restock_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="col-md-2">
                            <label for="notes" class="form-label fw-medium small">Catatan / Supplier</label>
                            <input type="text" name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="Keterangan..." value="{{ old('notes') }}">
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-navy px-4 fw-medium">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Produk Stok
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori Stok Baru -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fs-6 fw-bold" id="addCategoryModalLabel">
                    <i class="fa-solid fa-folder-plus me-2"></i>Tambah Kategori Stok Bahan Baku Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('stocks.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label fw-medium small">Nama Kategori Baru <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="category_name" class="form-control" placeholder="Contoh: Tea & Herbal / Topping & Jelly / Saus" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Kategori baru ini akan langsung muncul pada daftar pilihan kategori stok.</small>
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

<!-- KPI Summary Cards Stok (4 Grid) -->
<div class="row g-3 mb-4">
    <!-- Total Jenis Produk -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3">
                    <i class="fa-solid fa-cubes fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Total Item Stok</span>
                    <h4 class="fw-bold mb-1 text-navy">{{ number_format($totalProducts) }} <span class="fs-6 text-muted fw-normal">Item</span></h4>
                    <div class="small" style="font-size: 0.75rem;">
                        <span class="badge bg-secondary-subtle text-secondary me-1">📦 {{ $rawMaterialCount }} Bahan</span>
                        <span class="badge bg-success-subtle text-success">☕ {{ $posMenuCount }} Menu POS</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Jumlah Stok Unit -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card kpi-green p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-success-subtle text-success p-3 me-3">
                    <i class="fa-solid fa-layer-group fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Total Stok Tersedia</span>
                    <h4 class="fw-bold mb-0 text-navy">{{ number_format($totalStockUnits) }} <span class="fs-6 text-muted fw-normal">Unit/Kg</span></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Nilai Aset Stok -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card kpi-purple p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background-color: #f3e8ff; color: #8b5cf6;">
                    <i class="fa-solid fa-vault fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Total Nilai Stok</span>
                    <h4 class="fw-bold mb-0 text-navy">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Warning Alerts (Stok Menipis & Habis) -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card p-3 h-100" style="border-left-color: #ef4444;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-danger-subtle text-danger p-3 me-3">
                    <i class="fa-solid fa-triangle-exclamation fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Peringatan Stok</span>
                    <h4 class="fw-bold mb-0 text-navy">
                        <span class="text-warning fs-5 fw-bold">{{ $lowStockCount }} Menipis</span> /
                        <span class="text-danger fs-6">{{ $outOfStockCount }} Habis</span>
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar & PDF Export Button Stok -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-body py-3">
                <form action="{{ route('stocks.index') }}" method="GET" class="row g-2 align-items-center">
                    <!-- Cari Kata Kunci -->
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass me-1"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari nama produk / stok..." value="{{ $search }}">
                        </div>
                    </div>

                    <!-- Filter Tipe Stok (Pemisah Bahan Baku vs Menu POS) -->
                    <div class="col-md-3">
                        <select name="type" class="form-select form-select-sm">
                            <option value="">🏷️ -- Semua Tipe Stok --</option>
                            <option value="raw_material" {{ $type == 'raw_material' ? 'selected' : '' }}>📦 Bahan Baku (Gudang)</option>
                            <option value="pos_menu" {{ $type == 'pos_menu' ? 'selected' : '' }}>☕ Menu POS Kasir (Tampil di POS)</option>
                        </select>
                    </div>

                    <!-- Filter Kategori -->
                    <div class="col-md-2">
                        <select name="category" class="form-select form-select-sm searchable-select">
                            <option value="">🔍 -- Semua Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Status -->
                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">-- Semua Status --</option>
                            <option value="Aman" {{ $status == 'Aman' ? 'selected' : '' }}>Stok Aman</option>
                            <option value="Menipis" {{ $status == 'Menipis' ? 'selected' : '' }}>Stok Menipis</option>
                            <option value="Habis" {{ $status == 'Habis' ? 'selected' : '' }}>Stok Habis</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fa-solid fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                        <a href="{{ route('stocks.export-pdf', ['search' => $search, 'category' => $category, 'type' => $type, 'status' => $status]) }}" class="btn btn-danger btn-sm flex-fill" target="_blank">
                            <i class="fa-solid fa-file-pdf me-1"></i> PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Data Stok Bahan Baku & Menu POS -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-list-check me-2 text-primary"></i>Daftar Stok Bahan Baku & Menu POS Kasir
                </h6>
                <span class="badge bg-secondary-subtle text-secondary small">Total {{ count($stocks) }} Item</span>
            </div>
            <div class="card-body p-0">
                @if(count($stocks) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-custom mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 45px;">No</th>
                                <th>Tipe Item</th>
                                <th>Nama Produk / Item</th>
                                <th>Kategori</th>
                                <th class="text-center">Stok Saat Ini</th>
                                <th class="text-center">Batas Minimal</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Total Nilai</th>
                                <th class="text-center" style="width: 140px;">Aksi / Restock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $index => $item)
                            <tr class="{{ $item->status == 'Habis' ? 'table-danger' : ($item->status == 'Menipis' ? 'table-warning' : '') }}">
                                <td class="text-center fw-medium text-muted">{{ $index + 1 }}</td>
                                <td>
                                    @if($item->type === 'pos_menu' || $item->is_pos_item)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.72rem;">
                                            <i class="fa-solid fa-cash-register me-1"></i>Menu POS
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1" style="font-size: 0.72rem;">
                                            <i class="fa-solid fa-box-archive me-1"></i>Bahan Baku
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block fs-6">{{ $item->product_name }}</span>
                                    @if($item->notes)
                                        <small class="text-muted" style="font-size: 0.75rem;"><i class="fa-solid fa-circle-info me-1"></i>{{ $item->notes }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold fs-6 {{ $item->status == 'Habis' ? 'text-danger' : ($item->status == 'Menipis' ? 'text-warning-emphasis' : 'text-success') }}">
                                        {{ $item->current_stock }}
                                    </span>
                                    <span class="fw-normal text-muted small">{{ $item->unit }}</span>
                                </td>
                                <td class="text-center text-muted">
                                    {{ $item->minimum_stock }} <span class="small">{{ $item->unit }}</span>
                                </td>
                                <td class="text-center">
                                    @if($item->status == 'Aman')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="fa-solid fa-check-circle me-1"></i>Stok Aman</span>
                                    @elseif($item->status == 'Menipis')
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>Stok Menipis</span>
                                    @else
                                        <span class="badge bg-danger text-white px-2 py-1"><i class="fa-solid fa-circle-xmark me-1"></i>Stok Habis</span>
                                    @endif
                                </td>
                                <td class="text-end fw-medium text-secondary" style="white-space: nowrap;">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="text-end fw-bold text-navy" style="white-space: nowrap;">
                                    Rp {{ number_format($item->current_stock * $item->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- Quick Adjust Restock Modal Trigger -->
                                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#adjustModal{{ $item->id }}" title="Restock / Ubah Stok Cepat">
                                            <i class="fa-solid fa-arrows-rotate"></i> Restock
                                        </button>
                                        <!-- Edit Modal Trigger -->
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editStockModal{{ $item->id }}" title="Edit Produk">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <!-- Delete Button -->
                                        <form action="{{ route('stocks.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-delete" title="Hapus Produk">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Quick Restock / Adjust Modal -->
                            <div class="modal fade" id="adjustModal{{ $item->id }}" tabindex="-1" aria-labelledby="adjustModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title fs-6 fw-bold" id="adjustModalLabel{{ $item->id }}">
                                                <i class="fa-solid fa-boxes-packing me-2"></i>Penyesuaian Stok: {{ $item->product_name }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('stocks.adjust', $item->id) }}" method="POST">
                                             @csrf
                                            <div class="modal-body">
                                                <div class="alert alert-info py-2 small mb-3">
                                                    Stok saat ini: <strong>{{ $item->current_stock }} {{ $item->unit }}</strong>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Pilih Aksi Transaksi <span class="text-danger">*</span></label>
                                                    <select name="action" class="form-select" required>
                                                        <option value="add">➕ Tambah Stok (Restock / Pembelian Masuk)</option>
                                                        <option value="subtract">➖ Kurangi Stok (Penggunaan Dapur / Rusak)</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Jumlah Unit/Satuan ({{ $item->unit }}) <span class="text-danger">*</span></label>
                                                    <input type="number" name="amount" class="form-control" min="1" value="1" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light py-2">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success btn-sm fw-medium">Proses Penyesuaian</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Stock Modal -->
                            <div class="modal fade" id="editStockModal{{ $item->id }}" tabindex="-1" aria-labelledby="editStockModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header modal-header-custom">
                                            <h5 class="modal-title fs-6 fw-bold" id="editStockModalLabel{{ $item->id }}">
                                                <i class="fa-solid fa-pen-to-square me-2"></i>Edit Produk Stok: {{ $item->product_name }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('stocks.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small text-navy">Tipe Item Stok <span class="text-danger">*</span></label>
                                                        <select name="type" class="form-select" required>
                                                            <option value="raw_material" {{ ($item->type == 'raw_material' && !$item->is_pos_item) ? 'selected' : '' }}>
                                                                📦 Bahan Baku (Gudang/Dapur)
                                                            </option>
                                                            <option value="pos_menu" {{ ($item->type == 'pos_menu' || $item->is_pos_item) ? 'selected' : '' }}>
                                                                ☕ Menu Jual POS Kasir (Tampil di Kasir)
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-medium small">Nama Produk <span class="text-danger">*</span></label>
                                                        <input type="text" name="product_name" class="form-control" value="{{ $item->product_name }}" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-medium small">Kategori <span class="text-danger">*</span></label>
                                                        <select name="category" class="form-select" required>
                                                            @foreach($categories as $cat)
                                                                <option value="{{ $cat }}" {{ $item->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-medium small">Satuan <span class="text-danger">*</span></label>
                                                        <select name="unit" class="form-select" required>
                                                            @foreach($units as $u)
                                                                <option value="{{ $u }}" {{ $item->unit == $u ? 'selected' : '' }}>{{ $u }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label fw-medium small">Stok Saat Ini <span class="text-danger">*</span></label>
                                                        <input type="number" name="current_stock" class="form-control" value="{{ $item->current_stock }}" min="0" required>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label fw-medium small">Batas Minimal <span class="text-danger">*</span></label>
                                                        <input type="number" name="minimum_stock" class="form-control" value="{{ $item->minimum_stock }}" min="0" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-medium small">Harga per Satuan (Rp) <span class="text-danger">*</span></label>
                                                        <input type="number" name="unit_price" class="form-control" value="{{ (int)$item->unit_price }}" min="0" step="any" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-medium small">Tanggal Restock Terakhir <span class="text-danger">*</span></label>
                                                        <input type="date" name="last_restock_date" class="form-control" value="{{ \Carbon\Carbon::parse($item->last_restock_date)->format('Y-m-d') }}" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-medium small">Catatan / Supplier</label>
                                                        <input type="text" name="notes" class="form-control" value="{{ $item->notes }}">
                                                    </div>
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
                                <td colspan="4" class="text-end text-uppercase">TOTAL SELURUH STOK & ASET :</td>
                                <td class="text-center text-primary fs-6">{{ number_format($totalStockUnits) }} Unit</td>
                                <td colspan="3"></td>
                                <td class="text-end text-danger fs-6">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="p-5 text-center text-muted">
                    <i class="fa-solid fa-boxes-packing fa-3x mb-3 text-secondary opacity-50"></i>
                    <h6 class="fw-bold">Belum ada produk stok bahan baku</h6>
                    <p class="small mb-0">Silakan tambahkan data stok produk baru pada form di atas.</p>
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
        // Initialize Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $('.searchable-select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Cari kategori...'
            });
        }

        // Confirmation before delete using SweetAlert2
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Hapus Produk Stok?',
                    text: "Data stok produk ini akan dihapus dari sistem!",
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
    });
</script>
@endpush
