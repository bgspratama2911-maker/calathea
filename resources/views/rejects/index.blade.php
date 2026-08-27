@extends('layouts.app')

@section('title', 'Bahan Reject - Calathea Coffee')

@section('content')

<!-- Header & Form Input Bahan Reject -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>Pencatatan Bahan Reject / Kerusakan
                </h5>
                <div class="d-flex gap-2">
                    <!-- Tombol Tambah Barista Baru -->
                    <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-medium" data-bs-toggle="modal" data-bs-target="#addBaristaModal">
                        <i class="fa-solid fa-user-plus me-1 text-primary"></i> + Tambah Barista Baru
                    </button>
                    <!-- Tombol Tambah Kategori Baru -->
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-medium" data-bs-toggle="modal" data-bs-target="#addRejectCategoryModal">
                        <i class="fa-solid fa-folder-plus me-1"></i> + Tambah Kategori Baru
                    </button>
                    <span class="badge bg-danger-subtle text-danger fw-medium px-3 py-2">Form Input Reject</span>
                </div>
            </div>
            <div class="card-body pt-1">
                <form action="{{ route('rejects.store') }}" method="POST">
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

                        <!-- Nama Produk (Free Text Input) -->
                        <div class="col-md-4">
                            <label for="product_name" class="form-label fw-medium small">Nama Produk / Bahan Reject <span class="text-danger">*</span></label>
                            <input type="text" name="product_name" id="product_name" class="form-control @error('product_name') is-invalid @enderror" placeholder="Contoh: Susu UHT / Biji Kopi / Cup 16oz" value="{{ old('product_name') }}" required>
                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori Reject -->
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

                        <!-- Nama Barista -->
                        <div class="col-md-3">
                            <label for="barista_name" class="form-label fw-medium small">Nama Barista / Staff</label>
                            <select name="barista_name" id="barista_name" class="form-select searchable-select @error('barista_name') is-invalid @enderror">
                                <option value="">-- Pilih Barista --</option>
                                @foreach($baristas as $bName)
                                    <option value="{{ $bName }}" {{ old('barista_name') == $bName ? 'selected' : '' }}>{{ $bName }}</option>
                                @endforeach
                            </select>
                            @error('barista_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jumlah Qty -->
                        <div class="col-md-1">
                            <label for="quantity" class="form-label fw-medium small">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" min="1" value="{{ old('quantity', 1) }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Satuan -->
                        <div class="col-md-2">
                            <label for="unit" class="form-label fw-medium small">Satuan <span class="text-danger">*</span></label>
                            <select name="unit" id="unit" class="form-select @error('unit') is-invalid @enderror" required>
                                @foreach($units as $u)
                                    <option value="{{ $u }}" {{ old('unit', 'Pcs') == $u ? 'selected' : '' }}>{{ $u }}</option>
                                @endforeach
                            </select>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Estimasi Kerugian (Rp) -->
                        <div class="col-md-3">
                            <label for="estimated_loss" class="form-label fw-medium small">Estimasi Kerugian (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="estimated_loss" id="estimated_loss" class="form-control @error('estimated_loss') is-invalid @enderror" placeholder="0" min="0" step="any" value="{{ old('estimated_loss', 0) }}" required>
                            @error('estimated_loss')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alasan Reject -->
                        <div class="col-md-3">
                            <label for="reason" class="form-label fw-medium small">Alasan Reject / Penyebab</label>
                            <input type="text" name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" placeholder="Contoh: Kadaluarsa / Salah Racik" value="{{ old('reason') }}">
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="col-md-3">
                            <label for="notes" class="form-label fw-medium small">Catatan Tambahan</label>
                            <input type="text" name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="Keterangan tambahan" value="{{ old('notes') }}">
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-danger px-4 fw-medium">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Bahan Reject
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Barista Baru -->
<div class="modal fade" id="addBaristaModal" tabindex="-1" aria-labelledby="addBaristaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fs-6 fw-bold" id="addBaristaModalLabel">
                    <i class="fa-solid fa-user-plus me-2"></i>Tambah Nama Barista / Staff Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('rejects.baristas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="barista_name_input" class="form-label fw-medium small">Nama Barista <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="barista_name_input" class="form-control" placeholder="Contoh: Andi / Budi / Rian" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Nama barista ini akan tersimpan dan dapat dipilih pada form reject.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-medium">Simpan Barista Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori Reject Baru -->
<div class="modal fade" id="addRejectCategoryModal" tabindex="-1" aria-labelledby="addRejectCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fs-6 fw-bold" id="addRejectCategoryModalLabel">
                    <i class="fa-solid fa-folder-plus me-2"></i>Tambah Kategori Bahan Reject Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('rejects.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reject_category_name" class="form-label fw-medium small">Nama Kategori Baru <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="reject_category_name" class="form-control" placeholder="Contoh: Kemasan Penyok / Sirup Jamuran" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Kategori baru ini akan langsung muncul pada daftar pilihan kategori reject.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm fw-medium">Simpan Kategori Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- KPI Summary Cards (Reject HARIAN, MINGGUAN, BULANAN) -->
<div class="row g-3 mb-4">
    <!-- Reject HARIAN -->
    <div class="col-lg-4 col-md-6">
        <div class="card card-custom kpi-card p-3 h-100" style="border-left-color: #ef4444;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-danger-subtle text-danger p-3 me-3">
                    <i class="fa-solid fa-calendar-day fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Reject HARIAN (Hari Ini)</span>
                    <h4 class="fw-bold mb-0 text-danger">Rp {{ number_format($dailyLoss, 0, ',', '.') }}</h4>
                    <small class="text-muted" style="font-size: 0.75rem;">Total <strong>{{ number_format($dailyQty) }}</strong> Item Reject</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject MINGGUAN -->
    <div class="col-lg-4 col-md-6">
        <div class="card card-custom kpi-card p-3 h-100" style="border-left-color: #f59e0b;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-warning-subtle text-warning p-3 me-3">
                    <i class="fa-solid fa-calendar-week fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Reject MINGGUAN (Minggu Ini)</span>
                    <h4 class="fw-bold mb-0 text-warning-emphasis">Rp {{ number_format($weeklyLoss, 0, ',', '.') }}</h4>
                    <small class="text-muted" style="font-size: 0.75rem;">Total <strong>{{ number_format($weeklyQty) }}</strong> Item Reject</small>
                </div>
            </div>
        </div>
    </div>

    <!-- TOTAL BULANAN Reject -->
    <div class="col-lg-4 col-md-6">
        <div class="card card-custom kpi-card kpi-purple p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background-color: #f3e8ff; color: #8b5cf6;">
                    <i class="fa-solid fa-calendar-days fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">TOTAL BULANAN (Bulan Ini)</span>
                    <h4 class="fw-bold mb-0 text-navy">Rp {{ number_format($monthlyLoss, 0, ',', '.') }}</h4>
                    <small class="text-purple fw-semibold" style="color: #8b5cf6; font-size: 0.75rem;">⚠️ Total <strong>{{ number_format($monthlyQty) }}</strong> Item Reject</small>
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
                <form action="{{ route('rejects.index') }}" method="GET" class="row g-2 align-items-center">
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
                        <select name="category" class="form-select form-select-sm searchable-select">
                            <option value="">🔍 -- Semua Kategori Reject --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fa-solid fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('rejects.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                        <a href="{{ route('rejects.export-pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'category' => $category]) }}" class="btn btn-danger btn-sm flex-fill" target="_blank">
                            <i class="fa-solid fa-file-pdf me-1"></i> Cetak PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Tabel Ringkasan Kategori Kerugian -->
    <div class="col-lg-5">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-chart-pie me-2 text-danger"></i>Ringkasan Kerugian per Kategori
                </h6>
            </div>
            <div class="card-body p-0">
                @if($categoryBreakdown->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Kategori Reject</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Total Kerugian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categoryBreakdown as $item)
                            <tr>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $item['name'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1">{{ $item['total_qty'] }} Item</span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold text-danger d-block">Rp {{ number_format($item['total_loss'], 0, ',', '.') }}</span>
                                    <div class="progress mt-1" style="height: 6px;" title="{{ $item['percentage'] }}%">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $item['percentage'] }}%" aria-valuenow="{{ $item['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
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
                    <p class="mb-0 small">Belum ada data reject.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Data Bahan Reject -->
    <div class="col-lg-7">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-list-check me-2 text-danger"></i>Rincian Data Bahan Reject / Waste
                </h6>
                <span class="badge bg-secondary-subtle text-secondary small">Total {{ count($rejects) }} Item</span>
            </div>
            <div class="card-body p-0">
                @if(count($rejects) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-custom mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 40px;">No</th>
                                <th>Tanggal</th>
                                <th>Nama Produk / Bahan</th>
                                <th>Kategori</th>
                                <th>Barista</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Est. Kerugian</th>
                                <th class="text-center" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rejects as $index => $item)
                            <tr>
                                <td class="text-center fw-medium text-muted">{{ $index + 1 }}</td>
                                <td style="white-space: nowrap;">
                                    <i class="fa-regular fa-calendar me-1 text-muted"></i>
                                    {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $item->product_name }}</span>
                                    @if($item->reason)
                                        <small class="text-danger" style="font-size: 0.72rem;"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $item->reason }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->barista_name)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                            <i class="fa-solid fa-user me-1"></i>{{ $item->barista_name }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold text-dark">
                                    {{ $item->quantity }} <span class="small fw-normal text-muted">{{ $item->unit }}</span>
                                </td>
                                <td class="text-end fw-bold text-danger" style="white-space: nowrap;">
                                    Rp {{ number_format($item->estimated_loss, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- Edit Modal Trigger -->
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editRejectModal{{ $item->id }}" title="Edit Data">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <!-- Delete Form -->
                                        <form action="{{ route('rejects.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-delete" title="Hapus Data">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Reject Modal -->
                            <div class="modal fade" id="editRejectModal{{ $item->id }}" tabindex="-1" aria-labelledby="editRejectModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header modal-header-custom">
                                            <h5 class="modal-title fs-6 fw-bold" id="editRejectModalLabel{{ $item->id }}">
                                                <i class="fa-solid fa-pen-to-square me-2"></i>Edit Reject #{{ $item->id }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('rejects.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Tanggal <span class="text-danger">*</span></label>
                                                    <input type="date" name="date" class="form-control" value="{{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Nama Produk / Bahan Reject <span class="text-danger">*</span></label>
                                                    <input type="text" name="product_name" class="form-control" value="{{ $item->product_name }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Kategori Reject <span class="text-danger">*</span></label>
                                                    <select name="category" class="form-select" required>
                                                        @foreach($categories as $cat)
                                                            <option value="{{ $cat }}" {{ $item->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Nama Barista</label>
                                                    <select name="barista_name" class="form-select">
                                                        <option value="">-- Pilih Barista --</option>
                                                        @foreach($baristas as $bName)
                                                            <option value="{{ $bName }}" {{ $item->barista_name == $bName ? 'selected' : '' }}>{{ $bName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-medium small">Jumlah <span class="text-danger">*</span></label>
                                                        <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" min="1" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-medium small">Satuan <span class="text-danger">*</span></label>
                                                        <select name="unit" class="form-select" required>
                                                            @foreach($units as $u)
                                                                <option value="{{ $u }}" {{ $item->unit == $u ? 'selected' : '' }}>{{ $u }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Estimasi Kerugian (Rp) <span class="text-danger">*</span></label>
                                                    <input type="number" name="estimated_loss" class="form-control" value="{{ (int)$item->estimated_loss }}" min="0" step="any" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Alasan Reject</label>
                                                    <input type="text" name="reason" class="form-control" value="{{ $item->reason }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-medium small">Catatan Tambahan</label>
                                                    <input type="text" name="notes" class="form-control" value="{{ $item->notes }}">
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
                                <td colspan="5" class="text-end text-uppercase">TOTAL ESTIMASI KERUGIAN :</td>
                                <td class="text-center text-primary fs-6">{{ number_format($filteredQty) }} Item</td>
                                <td class="text-end text-danger fs-6">Rp {{ number_format($filteredLoss, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="p-5 text-center text-muted">
                    <i class="fa-solid fa-triangle-exclamation fa-3x mb-3 text-secondary opacity-50"></i>
                    <h6 class="fw-bold">Belum ada data bahan reject</h6>
                    <p class="small mb-0">Silakan catat bahan yang rusak/kadaluarsa pada form di atas.</p>
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
                width: '100%'
            });
        }

        // Confirmation before delete using SweetAlert2
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Hapus Data Reject?',
                    text: "Data reject ini akan dihapus dari laporan!",
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
