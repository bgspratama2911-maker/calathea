<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Bahan Baku - Calathea Coffee</title>
    <style>
        @page {
            margin: 25px 30px 40px 30px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.4;
        }

        /* Header Style */
        .header-bg {
            background-color: #0f172a;
            color: #ffffff;
            padding: 16px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .header-subtitle {
            font-size: 9px;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* Meta Filter Summary */
        .meta-info {
            width: 100%;
            margin-bottom: 15px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            border-radius: 4px;
        }

        .meta-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-info td {
            font-size: 9px;
            color: #475569;
        }

        .meta-info td strong {
            color: #0f172a;
        }

        /* KPI Cards Grid */
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 20px;
            margin-left: -8px;
            margin-right: -8px;
        }

        .kpi-box {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #2563eb;
            border-radius: 4px;
            padding: 8px;
        }

        .kpi-box.green {
            border-left-color: #10b981;
        }

        .kpi-box.purple {
            border-left-color: #8b5cf6;
        }

        .kpi-label {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }

        .kpi-value {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 3px;
        }

        /* Table Styling */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 8px;
            text-transform: uppercase;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 4px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #0f172a;
        }

        table.data-table td {
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
            font-size: 9px;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        table.data-table tfoot td {
            background-color: #e2e8f0;
            font-weight: bold;
            font-size: 9px;
            border-top: 2px solid #0f172a;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge-tag {
            background-color: #e0f2fe;
            color: #0369a1;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        /* Footer Page Number */
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 20px;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('images/logo-calathea.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
    @endphp

    <!-- Footer Page Number -->
    <div class="footer">
        <table width="100%">
            <tr>
                <td>Laporan Monitoring Stok Bahan Baku Calathea Coffee System - Dokumen Resmi</td>
                <td class="text-end">Halaman <span class="page-number"></span></td>
            </tr>
        </table>
    </div>

    <!-- Header Dokumen -->
    <div class="header-bg">
        <table width="100%">
            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            @if($logoBase64)
                            <td style="padding-right: 10px; vertical-align: middle;">
                                <img src="{{ $logoBase64 }}" style="width: 38px; height: 38px; border-radius: 50%; border: 1px solid #22c55e;">
                            </td>
                            @endif
                            <td style="vertical-align: middle;">
                                <div class="header-title">Laporan Stok Bahan Baku</div>
                                <div class="header-subtitle">Dicetak otomatis pada: {{ $generatedAt }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="text-end" style="vertical-align: middle;">
                    <div style="font-size: 12px; font-weight: bold; color: #38bdf8;">CALATHEA COFFEE</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Meta Information -->
    <div class="meta-info">
        <table>
            <tr>
                <td width="25%">
                    <strong>Kata Kunci:</strong> {{ $search ? $search : 'Semua' }}
                </td>
                <td width="25%">
                    <strong>Tipe Stok:</strong> {{ $type == 'pos_menu' ? 'Menu POS' : ($type == 'raw_material' ? 'Bahan Baku' : 'Semua Tipe') }}
                </td>
                <td width="25%">
                    <strong>Kategori:</strong> {{ $category ? $category : 'Semua Kategori' }}
                </td>
                <td width="25%" class="text-end">
                    <strong>Status Filter:</strong> {{ $status ? $status : 'Semua Status' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- KPI Summary Grid -->
    <table class="kpi-table">
        <tr>
            <td width="33%">
                <div class="kpi-box">
                    <div class="kpi-label">Total Jenis Produk</div>
                    <div class="kpi-value">{{ number_format($totalProducts) }} Item</div>
                </div>
            </td>
            <td width="33%">
                <div class="kpi-box green">
                    <div class="kpi-label">Total Stok Tersedia</div>
                    <div class="kpi-value">{{ number_format($totalStockUnits) }} Unit</div>
                </div>
            </td>
            <td width="34%">
                <div class="kpi-box purple">
                    <div class="kpi-label">Total Nilai Stok</div>
                    <div class="kpi-value">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabel Data Stok -->
    <div class="section-title">Daftar Stok Bahan Baku & Menu POS Calathea</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Tipe Item</th>
                <th width="25%">Nama Produk / Item</th>
                <th width="15%">Kategori</th>
                <th width="12%" class="text-center">Stok Saat Ini</th>
                <th width="8%" class="text-center">Min.</th>
                <th width="10%" class="text-center">Status</th>
                <th width="10%" class="text-end">Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stocks as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    @if($item->type === 'pos_menu' || $item->is_pos_item)
                        <span style="color: #15803d; font-weight: bold;">Menu POS</span>
                    @else
                        <span style="color: #64748b;">Bahan Baku</span>
                    @endif
                </td>
                <td>
                    <strong>{{ $item->product_name }}</strong>
                </td>
                <td><span class="badge-tag">{{ $item->category }}</span></td>
                <td class="text-center"><strong>{{ $item->current_stock }}</strong> {{ $item->unit }}</td>
                <td class="text-center">{{ $item->minimum_stock }} {{ $item->unit }}</td>
                <td class="text-center">
                    @if($item->status == 'Aman')
                        <span style="color: #10b981; font-weight: bold;">Aman</span>
                    @elseif($item->status == 'Menipis')
                        <span style="color: #f59e0b; font-weight: bold;">Menipis</span>
                    @else
                        <span style="color: #ef4444; font-weight: bold;">Habis</span>
                    @endif
                </td>
                <td class="text-end">Rp {{ number_format($item->current_stock * $item->unit_price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 15px; color: #64748b;">
                    Tidak ada produk stok bahan baku ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end" style="font-weight: bold;">TOTAL SELURUH STOK & ASET :</td>
                <td class="text-center" style="font-weight: bold;">{{ number_format($totalStockUnits) }} Unit</td>
                <td colspan="2"></td>
                <td class="text-end" style="font-weight: bold; color: #b91c1c;">
                    Rp {{ number_format($totalStockValue, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
