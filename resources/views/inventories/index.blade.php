@extends('layouts.app')

@section('title', 'Inventaris Barang - Calathea Coffee')

@section('content')

<!-- Header & Form Input Inventaris -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-boxes-stacked me-2 text-primary"></i>Tambah Inventaris Barang Cafe
                </h5>
                <div class="d-flex gap-2">
                    <!-- Tombol Tambah Kategori Baru -->
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-medium" data-bs-toggle="modal" data-bs-target="#addInventoryCategoryModal">
                        <i class="fa-solid fa-folder-plus me-1"></i> + Tambah Kategori Baru
                    </button>
                    <span class="badge bg-primary-subtle text-primary fw-medium px-3 py-2">Form Input Barang</span>
                </div>
            </div>
            <div class="card-body pt-1">
                <form action="{{ route('inventories.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <!-- Kode Barang (Opsional, Auto Generated) -->
                        <div class="col-md-2">
                            <label for="item_code" class="form-label fw-medium small">Kode Barang</label>
                            <input type="text" name="item_code" id="item_code" class="form-control @error('item_code') is-invalid @enderror" placeholder="Auto (INV-0001)" value="{{ old('item_code') }}">
                            @error('item_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Barang -->
                        <div class="col-md-4">
                            <label for="item_name" class="form-label fw-medium small">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="item_name" id="item_name" class="form-control @error('item_name') is-invalid @enderror" placeholder="Contoh: Mesin Espresso / Grinder / Cup Sealer" value="{{ old('item_name') }}" required>
                            @error('item_name')
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

                        <!-- Jumlah / Stok -->
                        <div class="col-md-1">
                            <label for="quantity" class="form-label fw-medium small">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" min="0" value="{{ old('quantity', 1) }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Satuan -->
                        <div class="col-md-2">
                            <label for="unit" class="form-label fw-medium small">Satuan <span class="text-danger">*</span></label>
                            <select name="unit" id="unit" class="form-select @error('unit') is-invalid @enderror" required>
                                @foreach($units as $u)
                                    <option value="{{ $u }}" {{ old('unit', 'Unit') == $u ? 'selected' : '' }}>{{ $u }}</option>
                                @endforeach
                            </select>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kondisi Barang -->
                        <div class="col-md-2">
                            <label for="condition" class="form-label fw-medium small">Kondisi <span class="text-danger">*</span></label>
                            <select name="condition" id="condition" class="form-select @error('condition') is-invalid @enderror" required>
                                @foreach($conditions as $cond)
                                    <option value="{{ $cond }}" {{ old('condition', 'Baik') == $cond ? 'selected' : '' }}>{{ $cond }}</option>
                                @endforeach
                            </select>
                            @error('condition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Harga Perolehan -->
                        <div class="col-md-3">
                            <label for="price" class="form-label fw-medium small">Harga Perolehan/Unit (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" placeholder="0" min="0" step="any" value="{{ old('price', 0) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Pembelian -->
                        <div class="col-md-3">
                            <label for="purchase_date" class="form-label fw-medium small">Tanggal Beli <span class="text-danger">*</span></label>
                            <input type="date" name="purchase_date" id="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                            @error('purchase_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Catatan / Keterangan -->
                        <div class="col-md-4">
                            <label for="notes" class="form-label fw-medium small">Catatan / Lokasi Bar</label>
                            <input type="text" name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="Contoh: Bar Depan / Meja Kasir" value="{{ old('notes') }}">
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-navy px-4 fw-medium">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Inventaris
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori Inventaris Baru -->
<div class="modal fade" id="addInventoryCategoryModal" tabindex="-1" aria-labelledby="addInventoryCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fs-6 fw-bold" id="addInventoryCategoryModalLabel">
                    <i class="fa-solid fa-folder-plus me-2"></i>Tambah Kategori Inventaris Barang Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inventories.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inventory_category_name" class="form-label fw-medium small">Nama Kategori Baru <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="inventory_category_name" class="form-control" placeholder="Contoh: Sound System / Lighting / Seragam Barista" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Kategori baru ini akan langsung muncul pada daftar pilihan kategori inventaris.</small>
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

<!-- KPI Summary Cards Inventaris (4 Grid) -->
<div class="row g-3 mb-4">
    <!-- Total Jenis Barang -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3">
                    <i class="fa-solid fa-cubes fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Total Jenis Barang</span>
                    <h4 class="fw-bold mb-0 text-navy">{{ number_format($totalItems) }} <span class="fs-6 text-muted fw-normal">Item</span></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Stok Unit -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card kpi-green p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-success-subtle text-success p-3 me-3">
                    <i class="fa-solid fa-layer-group fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Total Stok Unit</span>
                    <h4 class="fw-bold mb-0 text-navy">{{ number_format($totalQuantity) }} <span class="fs-6 text-muted fw-normal">Unit</span></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Nilai Aset Inventaris -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card kpi-purple p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background-color: #f3e8ff; color: #8b5cf6;">
                    <i class="fa-solid fa-vault fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Nilai Aset Inventaris</span>
                    <h4 class="fw-bold mb-0 text-navy">Rp {{ number_format($totalValue, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Kondisi Barang (Baik vs Rusak) -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-custom kpi-card p-3 h-100" style="border-left-color: #f59e0b;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-warning-subtle text-warning p-3 me-3">
                    <i class="fa-solid fa-heart-pulse fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium">Status Kondisi</span>
                    <h4 class="fw-bold mb-0 text-navy">
                        <span class="text-success fs-5 fw-bold">{{ $goodConditionCount }} Baik</span> /
                        <span class="text-danger fs-6">{{ $damagedCount }} Rusak</span>
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar & PDF Export Button Inventaris -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-body py-3">
                <form action="{{ route('inventories.index') }}" method="GET" class="row g-2 align-items-center">
                    <!-- Cari Kata Kunci -->
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass me-1"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari nama barang / kode / catatan..." value="{{ $search }}">
                        </div>
                    </div>

                    <!-- Filter Kategori -->
                    <div class="col-md-3">
                        <select name="category" class="form-select form-select-sm searchable-select">
                            <option value="">🔍 -- Semua Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Kondisi -->
                    <div class="col-md-2">
                        <select name="condition" class="form-select form-select-sm">
                            <option value="">-- Semua Kondisi --</option>
                            @foreach($conditions as $cond)
                                <option value="{{ $cond }}" {{ $condition == $cond ? 'selected' : '' }}>{{ $cond }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fa-solid fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('inventories.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                        <a href="{{ route('inventories.export-pdf', ['search' => $search, 'category' => $category, 'condition' => $condition]) }}" class="btn btn-danger btn-sm flex-fill" target="_blank">
                            <i class="fa-solid fa-file-pdf me-1"></i> Cetak PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Data Inventaris Barang -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-navy">
                    <i class="fa-solid fa-list-check me-2 text-primary"></i>Daftar Inventaris Barang Calathea Coffee
                </h6>
                <span class="badge bg-secondary-subtle text-secondary small">Total {{ count($inventories) }} Barang</span>
            </div>
            <div class="card-body p-0">
                @if(count($inventories) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-custom mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th class="text-center">Jumlah / Satuan</th>
                                <th class="text-center">Kondisi</th>
                                <th class="text-end">Harga / Unit</th>
                                <th class="text-end">Total Nilai</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventories as $index => $item)
                            <tr>
                                <td class="text-center fw-medium text-muted">{{ $index + 1 }}</td>
                                <td class="fw-bold font-monospace text-primary" style="white-space: nowrap;">{{ $item->item_code }}</td>
                                <td>
                                    <span class="fw-semibold text-dark d-block">{{ $item->item_name }}</span>
                                    @if($item->notes)
                                        <small class="text-muted" style="font-size: 0.75rem;"><i class="fa-solid fa-circle-info me-1"></i>{{ $item->notes }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td class="text-center fw-bold text-dark">
                                    {{ $item->quantity }} <span class="fw-normal text-muted small">{{ $item->unit }}</span>
                                </td>
                                <td class="text-center">
                                    @if($item->condition == 'Baik')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="fa-solid fa-check me-1"></i>Baik</span>
                                    @elseif($item->condition == 'Rusak Ringan')
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>Rusak Ringan</span>
                                    @elseif($item->condition == 'Rusak Berat')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><i class="fa-solid fa-circle-xmark me-1"></i>Rusak Berat</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1">Habis</span>
                                    @endif
                                </td>
                                <td class="text-end fw-medium text-secondary" style="white-space: nowrap;">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="text-end fw-bold text-navy" style="white-space: nowrap;">
                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editInventoryModal{{ $item->id }}" title="Edit Barang">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <!-- Delete Button -->
                                        <form action="{{ route('inventories.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-delete" title="Hapus Barang">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal Inventaris -->
                            <div class="modal fade" id="editInventoryModal{{ $item->id }}" tabindex="-1" aria-labelledby="editInventoryModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header modal-header-custom">
                                            <h5 class="modal-title fs-6 fw-bold" id="editInventoryModalLabel{{ $item->id }}">
                                                <i class="fa-solid fa-pen-to-square me-2"></i>Edit Inventaris Barang #{{ $item->item_code }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('inventories.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-medium small">Kode Barang <span class="text-danger">*</span></label>
                                                        <input type="text" name="item_code" class="form-control" value="{{ $item->item_code }}" required>
                                                    </div>

                                                    <div class="col-md-8">
                                                        <label class="form-label fw-medium small">Nama Barang <span class="text-danger">*</span></label>
                                                        <input type="text" name="item_name" class="form-control" value="{{ $item->item_name }}" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label fw-medium small">Kategori <span class="text-danger">*</span></label>
                                                        <select name="category" class="form-select" required>
                                                            @foreach($categories as $cat)
                                                                <option value="{{ $cat }}" {{ $item->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label fw-medium small">Jumlah <span class="text-danger">*</span></label>
                                                        <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" min="0" required>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label fw-medium small">Satuan <span class="text-danger">*</span></label>
                                                        <select name="unit" class="form-select" required>
                                                            @foreach($units as $u)
                                                                <option value="{{ $u }}" {{ $item->unit == $u ? 'selected' : '' }}>{{ $u }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label fw-medium small">Kondisi <span class="text-danger">*</span></label>
                                                        <select name="condition" class="form-select" required>
                                                            @foreach($conditions as $cond)
                                                                <option value="{{ $cond }}" {{ $item->condition == $cond ? 'selected' : '' }}>{{ $cond }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label fw-medium small">Harga / Unit (Rp) <span class="text-danger">*</span></label>
                                                        <input type="number" name="price" class="form-control" value="{{ (int)$item->price }}" min="0" step="any" required>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label fw-medium small">Tanggal Beli <span class="text-danger">*</span></label>
                                                        <input type="date" name="purchase_date" class="form-control" value="{{ \Carbon\Carbon::parse($item->purchase_date)->format('Y-m-d') }}" required>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label fw-medium small">Catatan / Lokasi Bar</label>
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
                                <td colspan="4" class="text-end text-uppercase">TOTAL SELURUH BARANG & ASET :</td>
                                <td class="text-center text-primary fs-6">{{ number_format($totalQuantity) }} Unit</td>
                                <td colspan="2"></td>
                                <td class="text-end text-danger fs-6">Rp {{ number_format($totalValue, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="p-5 text-center text-muted">
                    <i class="fa-solid fa-boxes-stacked fa-3x mb-3 text-secondary opacity-50"></i>
                    <h6 class="fw-bold">Belum ada data barang inventaris</h6>
                    <p class="small mb-0">Silakan tambahkan data inventaris peralatan/stok cafe baru pada form di atas.</p>
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
                    title: 'Hapus Barang Inventaris?',
                    text: "Data barang inventaris ini akan dihapus permanen!",
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
