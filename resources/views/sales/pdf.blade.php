<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Kopi - Calathea Coffee</title>
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
            background-color: #fef3c7;
            color: #b45309;
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
                <td>Laporan Penjualan Kopi Calathea Coffee System - Dokumen Resmi Kasir</td>
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
                                <div class="header-title">Laporan Penjualan Kopi</div>
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
                <td width="33%">
                    <strong>Rentang Tanggal:</strong> 
                    @if($startDate || $endDate)
                        {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Awal' }} s/d {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Sekarang' }}
                    @else
                        Semua Waktu
                    @endif
                </td>
                <td width="33%">
                    <strong>Produk:</strong> {{ $productName ? $productName : 'Semua Produk Kopi' }}
                </td>
                <td width="34%" class="text-end">
                    <strong>Total Transaksi:</strong> {{ count($sales) }} Transaksi
                </td>
            </tr>
        </table>
    </div>

    <!-- KPI Summary Grid -->
    <table class="kpi-table">
        <tr>
            <td width="50%">
                <div class="kpi-box green">
                    <div class="kpi-label">Total Cup/Botol Kopi Terjual</div>
                    <div class="kpi-value" style="color: #10b981;">{{ number_format($totalCups) }} Cup / Botol</div>
                </div>
            </td>
            <td width="50%">
                <div class="kpi-box">
                    <div class="kpi-label">Total Omzet Penjualan</div>
                    <div class="kpi-value">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Ringkasan Total Kopi yang Laku (Per Produk) -->
    @if(count($productBreakdown) > 0)
    <div class="section-title">Ringkasan Total Kopi Terjual (Per Produk)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="40%">Nama Produk Kopi</th>
                <th width="20%">Kategori</th>
                <th width="15%" class="text-center">Jumlah Cup Laku</th>
                <th width="20%" class="text-end">Total Omzet</th>
            </tr>
        </thead>
        <tbody>
            @php $noP = 1; @endphp
            @foreach($productBreakdown as $item)
            <tr>
                <td class="text-center">{{ $noP++ }}</td>
                <td><strong>{{ $item['name'] }}</strong></td>
                <td><span class="badge-tag">{{ $item['category'] }}</span></td>
                <td class="text-center"><strong>{{ $item['total_qty'] }}</strong> Cup</td>
                <td class="text-end">Rp {{ number_format($item['total_rev'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Rincian Data Transaksi Kasir -->
    <div class="section-title">Rincian Transaksi Penjualan Kasir</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="12%">Tanggal</th>
                <th width="33%">Nama Produk</th>
                <th width="10%" class="text-center">Jumlah</th>
                <th width="15%" class="text-end">Harga Satuan</th>
                <th width="15%" class="text-end">Total Omzet</th>
                <th width="10%" class="text-center">Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $index => $sale)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</td>
                <td><strong>{{ $sale->product_name }}</strong></td>
                <td class="text-center">{{ $sale->quantity_sold }} Cup</td>
                <td class="text-end">Rp {{ number_format($sale->price_per_unit, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($sale->total_income, 0, ',', '.') }}</td>
                <td class="text-center">{{ $sale->payment_method }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 15px; color: #64748b;">
                    Tidak ada data penjualan kopi ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end" style="font-weight: bold;">TOTAL PENJUALAN KOPI :</td>
                <td class="text-center" style="font-weight: bold;">{{ number_format($totalCups) }} Cup</td>
                <td></td>
                <td class="text-end" style="font-weight: bold; color: #10b981;">
                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
