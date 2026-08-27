<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris Barang - Calathea Coffee</title>
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
                <td>Laporan Inventaris Barang Calathea Coffee System - Dokumen Resmi</td>
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
                                <div class="header-title">Laporan Inventaris Barang</div>
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
                    <strong>Filter Pencarian:</strong> {{ $search ? $search : 'Semua Barang' }}
                </td>
                <td width="33%">
                    <strong>Kategori:</strong> {{ $category ? $category : 'Semua Kategori' }}
                </td>
                <td width="34%" class="text-end">
                    <strong>Kondisi:</strong> {{ $condition ? $condition : 'Semua Kondisi' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- KPI Summary Grid -->
    <table class="kpi-table">
        <tr>
            <td width="33%">
                <div class="kpi-box">
                    <div class="kpi-label">Total Jenis Barang</div>
                    <div class="kpi-value">{{ number_format($totalItems) }} Item</div>
                </div>
            </td>
            <td width="33%">
                <div class="kpi-box green">
                    <div class="kpi-label">Total Stok Unit</div>
                    <div class="kpi-value">{{ number_format($totalQuantity) }} Unit</div>
                </div>
            </td>
            <td width="34%">
                <div class="kpi-box purple">
                    <div class="kpi-label">Total Nilai Aset</div>
                    <div class="kpi-value">Rp {{ number_format($totalValue, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabel Data Inventaris -->
    <div class="section-title">Daftar Inventaris Peralatan & Stok Barang</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%" class="text-center">No</th>
                <th width="10%">Kode</th>
                <th width="28%">Nama Barang</th>
                <th width="18%">Kategori</th>
                <th width="8%" class="text-center">Jumlah</th>
                <th width="10%" class="text-center">Kondisi</th>
                <th width="11%" class="text-end">Harga/Unit</th>
                <th width="11%" class="text-end">Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventories as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td style="font-weight: bold; font-family: monospace;">{{ $item->item_code }}</td>
                <td>
                    <strong>{{ $item->item_name }}</strong>
                    @if($item->notes)
                        <br><span style="color: #64748b; font-size: 8px;">Loc: {{ $item->notes }}</span>
                    @endif
                </td>
                <td><span class="badge-tag">{{ $item->category }}</span></td>
                <td class="text-center"><strong>{{ $item->quantity }}</strong> {{ $item->unit }}</td>
                <td class="text-center">{{ $item->condition }}</td>
                <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 15px; color: #64748b;">
                    Tidak ada data barang inventaris ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end" style="font-weight: bold;">TOTAL SELURUH BARANG & ASET :</td>
                <td class="text-center" style="font-weight: bold;">{{ number_format($totalQuantity) }} Unit</td>
                <td colspan="2"></td>
                <td class="text-end" style="font-weight: bold; color: #b91c1c;">
                    Rp {{ number_format($totalValue, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
