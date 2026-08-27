<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Pengeluaran Calathea</title>
    <style>
        @page {
            margin: 25px 30px 40px 30px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
        }

        /* Header Style */
        .header-bg {
            background-color: #0f172a;
            color: #ffffff;
            padding: 18px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .header-subtitle {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* Meta Filter Summary */
        .meta-info {
            width: 100%;
            margin-bottom: 15px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px 12px;
            border-radius: 4px;
        }

        .meta-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-info td {
            font-size: 10px;
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

        .kpi-box.red {
            border-left-color: #ef4444;
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
            font-size: 12px;
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
            font-size: 10px;
            text-transform: uppercase;
            padding: 7px 8px;
            text-align: left;
            border: 1px solid #0f172a;
        }

        table.data-table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        table.data-table tfoot td {
            background-color: #e2e8f0;
            font-weight: bold;
            font-size: 10px;
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
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        /* Footer Page Number */
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 20px;
            font-size: 9px;
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
                <td>Rekap Pengeluaran Calathea System - Laporan Resmi</td>
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
                            <td style="padding-right: 12px; vertical-align: middle;">
                                <img src="{{ $logoBase64 }}" style="height: 38px; width: auto; max-width: 80px; border-radius: 4px; background: #ffffff; padding: 2px;">
                            </td>
                            @endif
                            <td style="vertical-align: middle;">
                                <div class="header-title">Rekap Pengeluaran Calathea</div>
                                <div class="header-subtitle">Dicetak otomatis pada: {{ $generatedAt }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="text-end" style="vertical-align: middle;">
                    <div style="font-size: 13px; font-weight: bold; color: #38bdf8;">CALATHEA COFFEE</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Filter Meta Information -->
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
                    <strong>Kategori:</strong> {{ $category ? $category : 'Semua Kategori' }}
                </td>
                <td width="34%" class="text-end">
                    <strong>Total Item Transaksi:</strong> {{ $totalTransactions }} Data
                </td>
            </tr>
        </table>
    </div>

    <!-- KPI Summary Grid (4 Cards) -->
    <table class="kpi-table">
        <tr>
            <td width="25%">
                <div class="kpi-box">
                    <div class="kpi-label">Total Pengeluaran</div>
                    <div class="kpi-value">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
                </div>
            </td>
            <td width="25%">
                <div class="kpi-box green">
                    <div class="kpi-label">Total Transaksi</div>
                    <div class="kpi-value">{{ number_format($totalTransactions) }} Items</div>
                </div>
            </td>
            <td width="25%">
                <div class="kpi-box purple">
                    <div class="kpi-label">Rata-rata / Tx</div>
                    <div class="kpi-value">Rp {{ number_format($averagePerTransaction, 0, ',', '.') }}</div>
                </div>
            </td>
            <td width="25%">
                <div class="kpi-box red">
                    <div class="kpi-label">Pengeluaran Terbesar</div>
                    <div class="kpi-value" style="color: #b91c1c;">Rp {{ number_format($highestExpense, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Ringkasan per Kategori -->
    @if(count($categoryBreakdown) > 0)
    <div class="section-title">Ringkasan per Kategori</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Nama Kategori</th>
                <th width="15%" class="text-center">Jumlah Transaksi</th>
                <th width="20%" class="text-end">Porsi (%)</th>
                <th width="15%" class="text-end">Total Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php $noCat = 1; @endphp
            @foreach($categoryBreakdown as $item)
            <tr>
                <td class="text-center">{{ $noCat++ }}</td>
                <td><strong>{{ $item['name'] }}</strong></td>
                <td class="text-center">{{ $item['count'] }} x</td>
                <td class="text-end">{{ $item['percentage'] }}%</td>
                <td class="text-end">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Rincian Data Transaksi Pengeluaran -->
    <div class="section-title">Rincian Transaksi Pengeluaran</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="12%">Tanggal</th>
                <th width="35%">Deskripsi / Catatan</th>
                <th width="20%">Kategori</th>
                <th width="13%">Metode</th>
                <th width="15%" class="text-end">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $index => $expense)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                <td>{{ $expense->description }}</td>
                <td><span class="badge-tag">{{ $expense->category }}</span></td>
                <td>{{ $expense->payment_method }}</td>
                <td class="text-end">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 15px; color: #64748b;">
                    Tidak ada data pengeluaran ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-end" style="font-weight: bold;">TOTAL PENGELUARAN :</td>
                <td class="text-end" style="font-weight: bold; color: #b91c1c;">
                    Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
