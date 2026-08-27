<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Kasir - Calathea Coffee (Fullscreen Tablet)</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-calathea.png') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Inconsolata:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <style>
        :root {
            --majoo-teal: #009688;
            --majoo-teal-dark: #00796b;
            --majoo-teal-light: #e0f2f1;
            --majoo-dark-bar: #263238;
            --majoo-grey-bg: #eceff1;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            height: 100dvh;
            width: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            background-color: #cfd8dc;
        }

        /* Dedicated Fullscreen POS Layout */
        .pos-fullscreen-wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
            height: 100dvh;
            width: 100vw;
            background: #ffffff;
            overflow: hidden;
        }

        /* Top Teal Navigation Bar */
        .majoo-header {
            background: linear-gradient(135deg, #00897b 0%, #009688 100%);
            color: #ffffff;
            padding: 6px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 50px;
            flex-shrink: 0;
            z-index: 10;
        }

        .majoo-brand {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .majoo-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #ffb74d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #333;
            border: 2px solid #fff;
            font-size: 0.8rem;
        }

        .majoo-logo-title {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff;
        }

        /* Secondary Filter & Search Bar */
        .majoo-subhead {
            background-color: #00796b;
            padding: 4px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            height: 44px;
            flex-shrink: 0;
            overflow-x: auto;
            z-index: 9;
        }

        .cat-tab {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 600;
            font-size: 0.75rem;
            padding: 5px 12px;
            border-radius: 4px;
            white-space: nowrap;
            transition: all 0.2s ease;
            text-transform: uppercase;
        }

        .cat-tab.active, .cat-tab:hover {
            background-color: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        .search-input-group {
            position: relative;
            width: 180px;
        }

        .search-input-group input {
            background-color: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            border-radius: 6px;
            padding-left: 28px;
            font-size: 0.75rem;
            height: 30px;
        }

        .search-input-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-input-group i {
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
        }

        /* Main View Split */
        .pos-main-body {
            display: flex;
            flex: 1;
            height: calc(100dvh - 94px);
            max-height: calc(100dvh - 94px);
            overflow: hidden;
        }

        /* Left Side: Product Grid Section */
        .product-section {
            flex: 1;
            padding: 10px;
            background-color: #eceff1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            min-width: 0;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(115px, 1fr));
            gap: 8px;
            flex: 1;
            overflow-y: auto;
            padding-right: 4px;
        }

        .product-card {
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            user-select: none;
            height: 135px;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.12);
        }

        .product-card:active {
            transform: scale(0.96);
        }

        .product-img-wrapper {
            position: relative;
            height: 85px;
            width: 100%;
            background-color: #cfd8dc;
        }

        .product-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .price-tag {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.72rem;
            text-align: center;
            padding: 2px 0;
            backdrop-filter: blur(2px);
        }

        .badge-tag {
            position: absolute;
            top: 4px;
            left: 4px;
            background-color: rgba(255, 255, 255, 0.92);
            color: #37474f;
            font-size: 0.62rem;
            font-weight: bold;
            padding: 1px 4px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .product-title {
            font-size: 0.72rem;
            font-weight: 600;
            color: #1e293b;
            padding: 4px;
            text-align: center;
            line-height: 1.15;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Bottom Drawer Quick Bar */
        .bottom-quick-bar {
            background-color: var(--majoo-dark-bar);
            margin-top: 8px;
            border-radius: 8px;
            padding: 6px 8px;
            flex-shrink: 0;
        }

        .quick-title {
            color: #90a4ae;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }

        .quick-card {
            background: #37474f;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .quick-card:hover {
            background: #455a64;
        }

        .quick-card img {
            height: 42px;
            width: 100%;
            object-fit: cover;
        }

        .quick-card-info {
            padding: 2px 4px;
            text-align: center;
        }

        .quick-card-name {
            color: #00e676;
            font-size: 0.62rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .quick-card-price {
            color: #ffffff;
            font-size: 0.6rem;
            font-weight: bold;
        }

        /* Right Side: Cart Section (Always Solid with Fixed Height & Scrollable Items) */
        .cart-section {
            width: 340px;
            min-width: 300px;
            max-width: 380px;
            background-color: #ffffff;
            border-left: 1px solid #cfd8dc;
            display: flex;
            flex-direction: column;
            height: 100%;
            max-height: 100%;
            flex-shrink: 0;
            overflow: hidden;
            box-shadow: -2px 0 8px rgba(0,0,0,0.03);
            position: relative;
        }

        .cart-header {
            padding: 8px 12px;
            border-bottom: 1px solid #eceff1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fafafa;
            height: 44px;
            flex-shrink: 0;
        }

        .order-id-badge {
            font-weight: 800;
            color: #00796b;
            font-size: 0.9rem;
        }

        .customer-input {
            border: none;
            border-bottom: 1px dashed #b0bec5;
            font-weight: 600;
            font-size: 0.8rem;
            width: 110px;
            text-align: right;
            background: transparent;
        }
        
        .customer-input:focus {
            outline: none;
            border-bottom-color: #009688;
        }

        /* Cart Items List - Fully Scrollable without pushing the Pay button */
        .cart-items-container {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 8px 12px;
            -webkit-overflow-scrolling: touch;
        }

        .cart-item {
            display: flex;
            align-items: flex-start;
            padding: 6px 0;
            border-bottom: 1px solid #f0f4f8;
        }

        .cart-item-qty {
            font-weight: 700;
            color: #37474f;
            font-size: 0.82rem;
            min-width: 24px;
        }

        .cart-item-details {
            flex: 1;
            min-width: 0;
        }

        .cart-item-title {
            font-weight: 600;
            font-size: 0.78rem;
            color: #263238;
            word-break: break-word;
        }

        .cart-item-notes {
            font-size: 0.68rem;
            color: #78909c;
            font-style: italic;
        }

        .cart-item-price {
            text-align: right;
            font-weight: 700;
            font-size: 0.78rem;
            color: #1e293b;
            flex-shrink: 0;
            margin-left: 6px;
        }

        .cart-item-discount {
            font-size: 0.68rem;
            color: #e53935;
        }

        /* Cart Footer Controls - Always Anchored at Bottom */
        .cart-footer {
            padding: 10px 12px;
            border-top: 1px solid #e0e0e0;
            background-color: #fafafa;
            flex-shrink: 0;
            width: 100%;
            box-shadow: 0 -3px 10px rgba(0,0,0,0.04);
            z-index: 5;
        }

        .cart-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            margin-bottom: 6px;
        }

        .btn-cart-action {
            border: 1px solid #cfd8dc;
            background: #ffffff;
            color: #546e7a;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 5px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-cart-action:hover {
            background-color: #eceff1;
            color: #263238;
        }

        /* Main Pay Button - Always Visible & Prominent */
        .btn-pay-main {
            width: 100%;
            background: linear-gradient(135deg, #00897b 0%, #009688 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.95rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0, 150, 136, 0.3);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-pay-main:hover:not(:disabled) {
            background: linear-gradient(135deg, #00796b 0%, #00897b 100%);
            box-shadow: 0 6px 16px rgba(0, 150, 136, 0.4);
            transform: translateY(-1px);
        }

        .btn-pay-main:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background: #90a4ae;
            box-shadow: none;
        }

        .badge-cart-count {
            background-color: #ffb74d;
            color: #333333;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
        }

        /* Responsive Tablet & Screen Rules (768px - 1024px) */
        @media (max-width: 1024px) {
            .cart-section {
                width: 300px;
                min-width: 280px;
            }
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(105px, 1fr));
                gap: 6px;
            }
            .product-card {
                height: 125px;
            }
            .product-img-wrapper {
                height: 75px;
            }
        }

        @media (max-width: 768px) {
            .pos-main-body {
                flex-direction: column;
                height: calc(100dvh - 94px);
                max-height: calc(100dvh - 94px);
            }
            .product-section {
                flex: 1;
                height: 60%;
            }
            .cart-section {
                width: 100%;
                max-width: 100%;
                height: 40%;
                border-left: none;
                border-top: 2px solid #cfd8dc;
            }
        }

        /* ----------------------------------------------------
           THERMAL RECEIPT PRINTER STYLING (58mm / 80mm)
        ---------------------------------------------------- */
        .receipt-container {
            font-family: 'Inconsolata', monospace;
            width: 280px;
            margin: 0 auto;
            padding: 12px;
            background: #ffffff;
            color: #000000;
            font-size: 12px;
            line-height: 1.3;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .receipt-header h5 {
            font-size: 15px;
            font-weight: bold;
            margin: 0 0 2px 0;
            text-transform: uppercase;
        }

        .receipt-divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .receipt-item-title {
            font-weight: bold;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 10px;
            font-size: 11px;
        }

        /* Printable Mode rule */
        @media print {
            body * {
                visibility: hidden;
            }
            #printableReceiptArea, #printableReceiptArea * {
                visibility: visible;
            }
            #printableReceiptArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="pos-fullscreen-wrapper">
    
    <!-- 1. Top Majoo Header Bar -->
    <div class="majoo-header">
        <div class="majoo-brand">
            <div class="majoo-avatar">4+</div>
            <div>
                <div class="fw-bold text-white fs-6 lh-1">Calathea Coffee & Resto</div>
                <div class="small opacity-75" style="font-size: 0.72rem;">
                    <i class="fa-solid fa-user-tie me-1"></i> Kasir: <strong>{{ Auth::user()->name }}</strong>
                </div>
            </div>
        </div>

        <div class="majoo-logo-title d-none d-md-block">
            Calathea <span class="fs-6 fw-normal opacity-75">POS</span>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark px-2 py-1 fw-medium d-none d-sm-inline" style="font-size: 0.72rem;">
                SERVED BY 4+ <i class="fa-solid fa-chevron-down ms-1"></i>
            </span>
            <a href="{{ route('sales.index') }}" class="btn btn-outline-light btn-sm fw-semibold py-1 px-2" style="font-size: 0.75rem;">
                <i class="fa-solid fa-chart-line me-1"></i> Penjualan Harian
            </a>
            <a href="{{ route('sales.index') }}" class="btn btn-danger btn-sm py-1 px-2 fw-semibold" style="font-size: 0.75rem;" title="Keluar POS">
                <i class="fa-solid fa-power-off"></i>
            </a>
        </div>
    </div>

    <!-- 2. Sub Navigation Filter & Search Bar -->
    <div class="majoo-subhead">
        <div class="d-flex align-items-center gap-1 overflow-auto me-auto">
            <button class="btn text-white me-2 p-0"><i class="fa-solid fa-bars fs-5"></i></button>
            <button class="cat-tab active" data-category="ALL">SEMUA</button>
            @foreach($categories as $cat)
                <button class="cat-tab" data-category="{{ $cat }}">{{ strtoupper($cat) }}</button>
            @endforeach
        </div>

        <div class="d-flex align-items-center gap-2">
            <div class="search-input-group">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="posSearchInput" class="form-control" placeholder="CARI PRODUK">
            </div>
            <button class="btn btn-sm text-white border border-light border-opacity-25 rounded px-2" title="Scan Barcode">
                <i class="fa-solid fa-qrcode"></i>
            </button>
        </div>
    </div>

    <!-- 3. Main Fullscreen Split Container -->
    <div class="pos-main-body">
        
        <!-- Left Side: Product Grid Area -->
        <div class="product-section">
            
            <!-- Product Items Cards Grid -->
            <div class="product-grid" id="productGrid">
                @forelse($products as $p)
                <div class="product-card" 
                     data-id="{{ $p['id'] }}" 
                     data-name="{{ $p['name'] }}" 
                     data-category="{{ $p['category'] }}" 
                     data-price="{{ $p['price'] }}"
                     data-discount="{{ $p['discount_amount'] }}"
                     data-badge="{{ $p['badge'] }}">
                    <div class="product-img-wrapper">
                        <span class="badge-tag">{{ $p['badge'] }}</span>
                        <img src="{{ $p['image'] }}" alt="{{ $p['name'] }}">
                        <div class="price-tag">Rp {{ number_format($p['price'], 0, ',', '.') }}</div>
                    </div>
                    <div class="product-title">{{ $p['name'] }}</div>
                </div>
                @empty
                <div class="col-12 text-center py-5 w-100" style="grid-column: 1 / -1;">
                    <i class="fa-solid fa-boxes-stacked fs-1 text-muted opacity-50 mb-3"></i>
                    <h6 class="fw-bold text-secondary">Belum Ada Data Produk / Stok Bahan</h6>
                    <p class="small text-muted mb-3">Data menu POS diambil langsung dari tabel Stok Bahan Baku.</p>
                    <a href="{{ route('stocks.index') }}" class="btn btn-primary btn-sm px-3 rounded-pill">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Data di Stok Bahan Baku
                    </a>
                </div>
                @endforelse
            </div>

            @if(count($quickItems) > 0)
            <!-- Bottom Quick Drawer Bar -->
            <div class="bottom-quick-bar">
                <div class="quick-title">
                    <span><i class="fa-solid fa-bolt text-warning me-1"></i> MENU CEPAT</span>
                    <span class="text-white-50" style="font-size: 0.65rem;">KLIK UNTUK ORDER CEPAT <i class="fa-solid fa-arrow-right ms-1"></i></span>
                </div>
                <div class="quick-grid">
                    @foreach($quickItems as $q)
                    <div class="quick-card"
                         data-id="{{ $q['id'] }}"
                         data-name="{{ $q['name'] }}"
                         data-category="{{ $q['category'] }}"
                         data-price="{{ $q['price'] }}"
                         data-discount="0"
                         data-badge="{{ $q['badge'] }}">
                        <img src="{{ $q['image'] }}" alt="{{ $q['name'] }}">
                        <div class="quick-card-info">
                            <div class="quick-card-name">{{ $q['name'] }}</div>
                            <div class="quick-card-price">Rp {{ number_format($q['price'], 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Right Side: Order Cart Panel -->
        <div class="cart-section">
            
            <!-- Cart Header -->
            <div class="cart-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="order-id-badge">Order #{{ $nextOrderNum }}</span>
                    <i class="fa-solid fa-user text-secondary fs-6"></i>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="small text-muted">Pelanggan:</span>
                    <input type="text" id="customerNameInput" class="customer-input" value="John Bonham" placeholder="Nama..." autocomplete="off">
                </div>
            </div>

            <!-- Cart Items List Container -->
            <div class="cart-items-container" id="cartItemsContainer">
                <!-- Initial Cart Sample Items inserted via JS -->
            </div>

            <!-- Cart Footer Controls -->
            <div class="cart-footer">
                <div class="cart-actions">
                    <button class="btn btn-cart-action" id="btnClearCart">
                        <i class="fa-solid fa-xmark me-1 text-danger"></i> HAPUS
                    </button>
                    <button class="btn btn-cart-action" id="btnHoldOrder">
                        <i class="fa-solid fa-bookmark me-1 text-info"></i> SIMPAN
                    </button>
                </div>

                <!-- Main Pay Button -->
                <button class="btn-pay-main" id="btnPayModal" disabled>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-cart-count" id="cartBadgeQty">0</span>
                        <span>Bayar</span>
                    </div>
                    <div id="cartGrandTotal">Rp 0</div>
                    <i class="fa-solid fa-chevron-right fs-6"></i>
                </button>
            </div>

        </div>

    </div>

</div>

<!-- Modal Checkout Pembayaran POS -->
<div class="modal fade" id="posPayModal" tabindex="-1" aria-labelledby="posPayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #00897b 0%, #009688 100%);">
                <h5 class="modal-header-title mb-0 fw-bold" id="posPayModalLabel">
                    <i class="fa-solid fa-cash-register me-2"></i> Pembayaran Transaksi POS (Order #{{ $nextOrderNum }})
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <div class="row g-4">
                    <!-- Left Order Summary -->
                    <div class="col-md-6 border-end">
                        <h6 class="fw-bold text-uppercase text-secondary mb-3" style="font-size: 0.8rem;">
                            <i class="fa-solid fa-list-check me-1"></i> Ringkasan Order
                        </h6>
                        <div class="bg-light p-3 rounded mb-3" style="max-height: 220px; overflow-y: auto;" id="modalOrderSummary">
                            <!-- Items inserted dynamically -->
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <span class="fw-bold text-dark fs-5">TOTAL BAYAR:</span>
                            <span class="fw-bold text-success fs-4" id="modalGrandTotalDisplay">Rp 0</span>
                        </div>
                    </div>

                    <!-- Right Payment Selection & Cash Input -->
                    <div class="col-md-6">
                        <form id="posCheckoutForm">
                            @csrf
                            <h6 class="fw-bold text-uppercase text-secondary mb-3" style="font-size: 0.8rem;">
                                <i class="fa-solid fa-credit-card me-1"></i> Metode Pembayaran
                            </h6>

                            <div class="row g-2 mb-3">
                                @foreach($paymentMethods as $index => $pm)
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="payment_method" id="pm_{{ $index }}" value="{{ $pm }}" {{ $index === 0 ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-success w-100 py-2 fw-semibold text-start" style="font-size: 0.85rem;" for="pm_{{ $index }}">
                                        @if($pm == 'QRIS')
                                            <i class="fa-solid fa-qrcode me-1"></i> QRIS
                                        @elseif($pm == 'Cash')
                                            <i class="fa-solid fa-money-bill-wave me-1"></i> Cash / Tunai
                                        @elseif($pm == 'Transfer')
                                            <i class="fa-solid fa-building-columns me-1"></i> Transfer
                                        @else
                                            <i class="fa-solid fa-credit-card me-1"></i> Debit / Kredit
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <!-- Cash Payment Options -->
                            <div id="cashInputSection">
                                <label class="form-label small fw-bold text-secondary">Uang Diterima (Rp):</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" id="cashPaidInput" class="form-control fw-bold fs-5" placeholder="0" min="0">
                                </div>

                                <div class="d-flex gap-1 mb-3">
                                    <button type="button" class="btn btn-sm btn-outline-secondary flex-fill quick-cash" data-amount="pas">Uang Pas</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary flex-fill quick-cash" data-amount="50000">50rb</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary flex-fill quick-cash" data-amount="100000">100rb</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary flex-fill quick-cash" data-amount="200000">200rb</button>
                                </div>

                                <div class="bg-light p-2 rounded d-flex justify-content-between align-items-center">
                                    <span class="small fw-bold">Kembalian:</span>
                                    <span class="fw-bold text-primary fs-5" id="cashChangeDisplay">Rp 0</span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success w-100 py-3 fw-bold fs-5 shadow" id="btnSubmitCheckout">
                                    <i class="fa-solid fa-check-circle me-2"></i> SELESAIKAN & CETAK STRUK
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Struk Thermal Printer (Cetak Struk) -->
<div class="modal fade" id="receiptPrintModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white py-2">
                <h6 class="modal-title mb-0 fw-bold"><i class="fa-solid fa-print me-1"></i> Struk Transaksi POS</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2" id="printableReceiptArea">
                <!-- Receipt thermal template injected via JS -->
            </div>
            <div class="modal-footer p-2 bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm fw-bold" id="btnTriggerPrint">
                    <i class="fa-solid fa-print me-1"></i> Cetak Struk Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    
    // POS Cart State initialized empty
    let cart = [];

    let lastCompletedTransaction = null;

    renderCart();

    // 1. Add Product to Cart
    $(document).on('click', '.product-card, .quick-card', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const category = $(this).data('category');
        const price = parseFloat($(this).data('price'));
        const discount = parseFloat($(this).data('discount')) || 0;
        let notes = discount > 0 ? `Diskon Rp ${discount.toLocaleString('id-ID')}` : '';

        const existingItemIndex = cart.findIndex(item => item.id === id);
        if (existingItemIndex > -1) {
            cart[existingItemIndex].qty += 1;
        } else {
            cart.push({
                id: id,
                name: name,
                category: category,
                price: price,
                discount: discount,
                qty: 1,
                notes: notes
            });
        }

        renderCart();
    });

    // 2. Category Tab Filtering
    $('.cat-tab').on('click', function() {
        $('.cat-tab').removeClass('active');
        $(this).addClass('active');

        const cat = $(this).data('category');
        if (cat === 'ALL') {
            $('.product-card').show();
        } else {
            $('.product-card').each(function() {
                const pCat = $(this).data('category');
                if (pCat === cat) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });

    // 3. Search Bar Filter
    $('#posSearchInput').on('keyup', function() {
        const query = $(this).val().toLowerCase();
        $('.product-card').each(function() {
            const name = $(this).data('name').toLowerCase();
            const cat = $(this).data('category').toLowerCase();
            if (name.includes(query) || cat.includes(query)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // 4. Cart Qty Controls
    $(document).on('click', '.cart-qty-plus', function() {
        const idx = $(this).data('index');
        cart[idx].qty += 1;
        renderCart();
    });

    $(document).on('click', '.cart-qty-minus', function() {
        const idx = $(this).data('index');
        if (cart[idx].qty > 1) {
            cart[idx].qty -= 1;
        } else {
            cart.splice(idx, 1);
        }
        renderCart();
    });

    $(document).on('click', '.cart-item-remove', function() {
        const idx = $(this).data('index');
        cart.splice(idx, 1);
        renderCart();
    });

    // Clear Cart
    $('#btnClearCart').on('click', function() {
        if (cart.length === 0) return;
        Swal.fire({
            title: 'Hapus Keranjang?',
            text: 'Semua item dalam pesanan akan dihapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                renderCart();
            }
        });
    });

    // Hold Order
    $('#btnHoldOrder').on('click', function() {
        if (cart.length === 0) return;
        Swal.fire({
            icon: 'info',
            title: 'Order Disimpan',
            text: 'Pesanan telah disimpan sebagai Draft Order #{{ $nextOrderNum }}',
            timer: 2000,
            showConfirmButton: false
        });
    });

    // 5. Render Cart Function
    function renderCart() {
        const container = $('#cartItemsContainer');
        container.empty();

        if (cart.length === 0) {
            container.append(`
                <div class="text-center text-muted py-5">
                    <i class="fa-solid fa-basket-shopping fs-1 mb-2 text-light opacity-50"></i>
                    <p class="mb-0">Keranjang masih kosong</p>
                    <small class="text-secondary">Klik produk di sebelah kiri untuk menambah order</small>
                </div>
            `);
            $('#cartBadgeQty').text('0');
            $('#cartGrandTotal').text('Rp 0');
            $('#btnPayModal').prop('disabled', true);
            return;
        }

        let totalQty = 0;
        let grandTotal = 0;

        cart.forEach((item, index) => {
            const itemTotal = (item.price * item.qty) - (item.discount * item.qty);
            totalQty += item.qty;
            grandTotal += itemTotal;

            const discHtml = item.discount > 0 
                ? `<div class="cart-item-discount">Diskon (${(item.discount * item.qty).toLocaleString('id-ID')})</div>` 
                : '';

            const notesHtml = item.notes ? `<div class="cart-item-notes">${item.notes}</div>` : '';

            container.append(`
                <div class="cart-item">
                    <div class="cart-item-qty">${item.qty}</div>
                    <div class="cart-item-details">
                        <div class="cart-item-title">${item.name}</div>
                        ${discHtml}
                        ${notesHtml}
                    </div>
                    <div class="cart-item-price">
                        ${itemTotal.toLocaleString('id-ID')}
                        <div class="d-flex align-items-center justify-content-end gap-1 mt-1">
                            <button class="btn btn-outline-secondary btn-sm py-0 px-1 cart-qty-minus" data-index="${index}" style="font-size: 0.65rem;">-</button>
                            <button class="btn btn-outline-secondary btn-sm py-0 px-1 cart-qty-plus" data-index="${index}" style="font-size: 0.65rem;">+</button>
                            <button class="btn btn-outline-danger btn-sm py-0 px-1 cart-item-remove" data-index="${index}" style="font-size: 0.65rem;"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            `);
        });

        $('#cartBadgeQty').text(totalQty);
        $('#cartGrandTotal').text('Rp ' + grandTotal.toLocaleString('id-ID'));
        $('#btnPayModal').prop('disabled', false);
    }

    // 6. Open Pay Modal
    $('#btnPayModal').on('click', function() {
        if (cart.length === 0) return;

        let grandTotal = 0;
        let summaryHtml = '<ul class="list-group list-group-flush">';

        cart.forEach(item => {
            const itemTotal = (item.price * item.qty) - (item.discount * item.qty);
            grandTotal += itemTotal;

            summaryHtml += `
                <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-2 px-0">
                    <div>
                        <span class="fw-bold">${item.qty}x</span> ${item.name}
                        ${item.notes ? `<br><small class="text-muted fs-7">${item.notes}</small>` : ''}
                    </div>
                    <span class="fw-semibold">Rp ${itemTotal.toLocaleString('id-ID')}</span>
                </li>
            `;
        });

        summaryHtml += '</ul>';

        $('#modalOrderSummary').html(summaryHtml);
        $('#modalGrandTotalDisplay').text('Rp ' + grandTotal.toLocaleString('id-ID'));
        $('#cashPaidInput').val(grandTotal);
        calculateCashChange(grandTotal);

        $('#posPayModal').modal('show');
    });

    // 7. Calculate Cash Change
    function calculateCashChange(grandTotal) {
        const cashPaid = parseFloat($('#cashPaidInput').val()) || 0;
        const change = cashPaid - grandTotal;
        if (change >= 0) {
            $('#cashChangeDisplay').text('Rp ' + change.toLocaleString('id-ID')).removeClass('text-danger').addClass('text-primary');
        } else {
            $('#cashChangeDisplay').text('Kurang Rp ' + Math.abs(change).toLocaleString('id-ID')).removeClass('text-primary').addClass('text-danger');
        }
    }

    $('#cashPaidInput').on('input', function() {
        let grandTotal = 0;
        cart.forEach(i => grandTotal += ((i.price * i.qty) - (i.discount * i.qty)));
        calculateCashChange(grandTotal);
    });

    $('.quick-cash').on('click', function() {
        let grandTotal = 0;
        cart.forEach(i => grandTotal += ((i.price * i.qty) - (i.discount * i.qty)));

        const amt = $(this).data('amount');
        if (amt === 'pas') {
            $('#cashPaidInput').val(grandTotal);
        } else {
            $('#cashPaidInput').val(parseFloat(amt));
        }
        calculateCashChange(grandTotal);
    });

    // Toggle Cash Input
    $('input[name="payment_method"]').on('change', function() {
        if ($(this).val() === 'Cash') {
            $('#cashInputSection').slideDown();
        } else {
            $('#cashInputSection').slideUp();
        }
    });

    // 8. Submit Checkout & Generate Struk
    $('#posCheckoutForm').on('submit', function(e) {
        e.preventDefault();

        const customerName = $('#customerNameInput').val() || 'John Bonham';
        const paymentMethod = $('input[name="payment_method"]:checked').val();
        const cashPaid = parseFloat($('#cashPaidInput').val()) || 0;

        const payload = {
            _token: '{{ csrf_token() }}',
            customer_name: customerName,
            payment_method: paymentMethod,
            items: cart.map(i => ({
                product_name: i.name,
                category: i.category,
                quantity_sold: i.qty,
                price_per_unit: i.price,
                discount_amount: i.discount * i.qty,
                notes: i.notes
            }))
        };

        $('#btnSubmitCheckout').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Memproses...');

        $.ajax({
            url: "{{ route('pos.checkout') }}",
            type: "POST",
            data: JSON.stringify(payload),
            contentType: "application/json",
            dataType: "json",
            success: function(res) {
                $('#btnSubmitCheckout').prop('disabled', false).html('<i class="fa-solid fa-check-circle me-2"></i> SELESAIKAN & CETAK STRUK');
                $('#posPayModal').modal('hide');

                if (res.success) {
                    // Store transaction details for receipt printing
                    lastCompletedTransaction = {
                        orderNum: '{{ $nextOrderNum }}',
                        customerName: customerName,
                        paymentMethod: paymentMethod,
                        cashPaid: cashPaid,
                        items: JSON.parse(JSON.stringify(cart)),
                        grandTotal: res.grand_total,
                        dateStr: res.order_date
                    };

                    // Generate thermal receipt template
                    generateThermalReceipt(lastCompletedTransaction);

                    // Open Receipt Print Modal
                    $('#receiptPrintModal').modal('show');

                    // Clear cart
                    cart = [];
                    renderCart();
                }
            },
            error: function(xhr) {
                $('#btnSubmitCheckout').prop('disabled', false).html('<i class="fa-solid fa-check-circle me-2"></i> SELESAIKAN & CETAK STRUK');
                const err = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem';
                Swal.fire('Gagal!', err, 'error');
            }
        });
    });

    // 9. Generate Thermal Receipt Function (Struk Cetak 58mm/80mm)
    function generateThermalReceipt(tx) {
        let itemsHtml = '';
        tx.items.forEach(i => {
            const itemTotal = (i.price * i.qty) - (i.discount * i.qty);
            itemsHtml += `
                <div class="receipt-row receipt-item-title">
                    <span>${i.name}</span>
                </div>
                <div class="receipt-row">
                    <span>${i.qty} x ${i.price.toLocaleString('id-ID')}</span>
                    <span>${itemTotal.toLocaleString('id-ID')}</span>
                </div>
            `;
            if (i.discount > 0) {
                itemsHtml += `
                    <div class="receipt-row" style="font-size: 10px; color: #555;">
                        <span>* Diskon</span>
                        <span>-${(i.discount * i.qty).toLocaleString('id-ID')}</span>
                    </div>
                `;
            }
            if (i.notes) {
                itemsHtml += `
                    <div class="receipt-row" style="font-size: 10px; color: #555;">
                        <span>(${i.notes})</span>
                    </div>
                `;
            }
        });

        const change = tx.paymentMethod === 'Cash' ? (tx.cashPaid - tx.grandTotal) : 0;

        const receiptHtml = `
            <div class="receipt-container">
                <div class="receipt-header">
                    <h5>CALATHEA COFFEE</h5>
                    <div>Jl. Calathea Raya No. 88, Malang</div>
                    <div>Telp: (0341) 555-8899</div>
                </div>

                <div class="receipt-divider"></div>

                <div class="receipt-row">
                    <span>No. Order:</span>
                    <span>#${tx.orderNum}</span>
                </div>
                <div class="receipt-row">
                    <span>Tgl:</span>
                    <span>${tx.dateStr}</span>
                </div>
                <div class="receipt-row">
                    <span>Kasir:</span>
                    <span>{{ Auth::user()->name }}</span>
                </div>
                <div class="receipt-row">
                    <span>Pelanggan:</span>
                    <span>${tx.customerName}</span>
                </div>

                <div class="receipt-divider"></div>

                ${itemsHtml}

                <div class="receipt-divider"></div>

                <div class="receipt-row" style="font-weight: bold; font-size: 13px;">
                    <span>TOTAL:</span>
                    <span>Rp ${tx.grandTotal.toLocaleString('id-ID')}</span>
                </div>
                <div class="receipt-row">
                    <span>Metode Bayar:</span>
                    <span>${tx.paymentMethod}</span>
                </div>
                ${tx.paymentMethod === 'Cash' ? `
                    <div class="receipt-row">
                        <span>Tunai Diterima:</span>
                        <span>Rp ${tx.cashPaid.toLocaleString('id-ID')}</span>
                    </div>
                    <div class="receipt-row">
                        <span>Kembalian:</span>
                        <span>Rp ${(change > 0 ? change : 0).toLocaleString('id-ID')}</span>
                    </div>
                ` : ''}

                <div class="receipt-divider"></div>

                <div class="receipt-footer">
                    <div>TERIMA KASIH ATAS KUNJUNGAN ANDA!</div>
                    <div>Simpan struk ini sebagai bukti pembayaran.</div>
                </div>
            </div>
        `;

        $('#printableReceiptArea').html(receiptHtml);
    }

    // 10. Trigger Print Event
    $('#btnTriggerPrint').on('click', function() {
        window.print();
    });

});
</script>

</body>
</html>
