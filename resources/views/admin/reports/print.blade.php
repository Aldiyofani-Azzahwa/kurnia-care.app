<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Kurnia Care</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 24px;
            color: #111827;
            font-family: Arial, sans-serif;
            font-size: 12px;
            background: #f3f4f6;
        }

        .sheet {
            max-width: 1120px;
            margin: 0 auto;
            padding: 28px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.08);
        }

        .print-button {
            margin: 0 0 20px;
            padding: 10px 16px;
            background: #047857;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
        }

        .header {
            display: table;
            width: 100%;
            padding-bottom: 18px;
            margin-bottom: 18px;
            border-bottom: 3px solid #047857;
        }

        .header-left,
        .header-right {
            display: table-cell;
            vertical-align: middle;
        }

        .header-left {
            width: 70%;
        }

        .header-right {
            width: 30%;
            text-align: right;
        }

        .brand {
            display: table;
            width: 100%;
        }

        .brand-logo,
        .brand-text {
            display: table-cell;
            vertical-align: middle;
        }

        .brand-logo {
            width: 74px;
        }

        .brand-logo img {
            width: 62px;
            height: 62px;
            object-fit: contain;
        }

        h1 {
            margin: 0;
            color: #047857;
            font-size: 26px;
            line-height: 1.2;
        }

        .subtitle {
            margin: 4px 0 0;
            color: #4b5563;
            font-size: 13px;
        }

        .period-box {
            display: inline-block;
            padding: 10px 12px;
            text-align: left;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 12px;
        }

        .period-box span {
            display: block;
            color: #6b7280;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .period-box strong {
            display: block;
            margin-top: 3px;
            color: #065f46;
            font-size: 12px;
        }

        .section-title {
            margin: 18px 0 10px;
            color: #047857;
            font-size: 15px;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }

        .card {
            min-height: 78px;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-left: 4px solid #047857;
            border-radius: 12px;
            background: #ffffff;
        }

        .card.warning {
            border-left-color: #f59e0b;
        }

        .card.info {
            border-left-color: #2563eb;
        }

        .card.danger {
            border-left-color: #ef4444;
        }

        .card span {
            color: #6b7280;
            font-size: 11px;
        }

        .card strong {
            display: block;
            margin-top: 6px;
            color: #047857;
            font-size: 16px;
        }

        .card.warning strong {
            color: #d97706;
        }

        .card.info strong {
            color: #2563eb;
        }

        .card.danger strong {
            color: #dc2626;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #ffffff;
        }

        th {
            padding: 8px;
            border: 1px solid #047857;
            background: #047857;
            color: white;
            text-align: left;
            font-size: 11px;
        }

        td {
            padding: 8px;
            border: 1px solid #d1d5db;
            vertical-align: top;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .muted {
            color: #6b7280;
            font-size: 10px;
        }

        .money-main {
            color: #047857;
            font-weight: 700;
        }

        .footer {
            display: table;
            width: 100%;
            margin-top: 34px;
            color: #4b5563;
        }

        .footer-left,
        .footer-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .footer-right {
            text-align: center;
        }

        .signature-space {
            height: 54px;
        }

        .signature-line {
            display: inline-block;
            min-width: 170px;
            border-top: 1px solid #111827;
            padding-top: 6px;
            color: #111827;
            font-weight: 700;
        }

        @media print {
            body {
                margin: 0;
                background: #ffffff;
            }

            .sheet {
                max-width: none;
                padding: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .print-button {
                display: none;
            }
        }
    </style>
</head>

<body>

    @php
        $formatRupiah = fn($value) => 'Rp' . number_format((float) $value, 0, ',', '.');
        $periodText = \Carbon\Carbon::parse($startDate)->locale('id')->isoFormat('D MMMM Y') . ' - ' . \Carbon\Carbon::parse($endDate)->locale('id')->isoFormat('D MMMM Y');
    @endphp

    <div class="sheet">
        <button onclick="window.print()" class="print-button">
            Cetak / Simpan PDF
        </button>

        <div class="header">
            <div class="header-left">
                <div class="brand">
                    <div class="brand-logo">
                        <img src="{{ asset('images/logo-kurnia-care.png') }}" alt="Logo Kurnia Care">
                    </div>

                    <div class="brand-text">
                        <h1>Kurnia Care</h1>
                        <p class="subtitle">Laporan Klinik Khitan Modern</p>
                        <p class="subtitle">Appointment, status tindakan, pembayaran DP, dan pendapatan layanan</p>
                    </div>
                </div>
            </div>

            <div class="header-right">
                <div class="period-box">
                    <span>Periode Laporan</span>
                    <strong>{{ $periodText }}</strong>
                </div>
            </div>
        </div>

        <h2 class="section-title">Ringkasan Keuangan</h2>
        <div class="summary">
            <div class="card">
                <span>Pendapatan Lunas</span>
                <strong>{{ $formatRupiah($totalRevenue) }}</strong>
            </div>

            <div class="card warning">
                <span>DP Masuk</span>
                <strong>{{ $formatRupiah($totalDpRevenue) }}</strong>
            </div>

            <div class="card info">
                <span>Sisa Pelunasan</span>
                <strong>{{ $formatRupiah($totalRemainingRevenue) }}</strong>
            </div>

            <div class="card">
                <span>Pembayaran Diterima</span>
                <strong>{{ $verifiedPaymentCount }}</strong>
            </div>
        </div>

        <h2 class="section-title">Ringkasan Appointment</h2>
        <div class="summary">
            <div class="card">
                <span>Total Appointment</span>
                <strong>{{ $totalAppointments }}</strong>
            </div>

            <div class="card warning">
                <span>Menunggu</span>
                <strong>{{ $waitingCount }}</strong>
            </div>

            <div class="card info">
                <span>Dikonfirmasi</span>
                <strong>{{ $processCount }}</strong>
            </div>

            <div class="card">
                <span>Selesai</span>
                <strong>{{ $doneCount }}</strong>
            </div>

            <div class="card danger">
                <span>Dibatalkan</span>
                <strong>{{ $cancelCount }}</strong>
            </div>

            <div class="card warning">
                <span>Pembayaran Pending</span>
                <strong>{{ $pendingPaymentCount }}</strong>
            </div>

            <div class="card danger">
                <span>Pembayaran Ditolak</span>
                <strong>{{ $rejectedPaymentCount }}</strong>
            </div>
        </div>

        <h2 class="section-title">Rekap Layanan</h2>
        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th class="text-center">Total Appointment</th>
                    <th class="text-center">Pembayaran Diterima</th>
                    <th class="text-right">DP Masuk</th>
                    <th class="text-right">Pendapatan Lunas</th>
                    <th class="text-right">Sisa</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($serviceRecaps as $service)
                    <tr>
                        <td>{{ $service['service_name'] }}</td>
                        <td class="text-center">{{ $service['total'] }}</td>
                        <td class="text-center">{{ $service['paid_count'] }}</td>
                        <td class="text-right">{{ $formatRupiah($service['dp_revenue']) }}</td>
                        <td class="text-right money-main">{{ $formatRupiah($service['revenue']) }}</td>
                        <td class="text-right">{{ $formatRupiah($service['remaining_revenue']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada rekap layanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h2 class="section-title">Detail Appointment</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Pasien</th>
                    <th>Layanan</th>
                    <th>Dokter</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th class="text-right">DP</th>
                    <th class="text-right">Pendapatan Lunas</th>
                    <th class="text-right">Sisa</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($appointments as $appointment)
                    @php
                        $dpAmount = (float) (optional($appointment->payment)->amount ?? 0);
                        $servicePrice = (float) (optional($appointment->service)->price ?? 0);
                        $fullAmount = optional($appointment->payment)->status === 'diterima' ? ($servicePrice > 0 ? $servicePrice : $dpAmount) : 0;
                        $remainingAmount = max($fullAmount - $dpAmount, 0);
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{ $appointment->appointment_date?->format('d/m/Y') }}<br>
                            <span class="muted">{{ substr($appointment->appointment_time, 0, 5) }}</span>
                        </td>
                        <td>
                            {{ $appointment->patient->child_name ?? '-' }}<br>
                            <span class="muted">{{ $appointment->patient->phone ?? '-' }}</span>
                        </td>
                        <td>{{ $appointment->service->name ?? '-' }}</td>
                        <td>{{ $appointment->doctor->name ?? '-' }}</td>
                        <td>{{ ucfirst($appointment->status) }}</td>
                        <td>{{ ucfirst($appointment->payment->status ?? '-') }}</td>
                        <td class="text-right">{{ $formatRupiah($dpAmount) }}</td>
                        <td class="text-right money-main">{{ $formatRupiah($fullAmount) }}</td>
                        <td class="text-right">{{ $formatRupiah($remainingAmount) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">
                            Tidak ada data laporan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <div class="footer-left">
                <p>Dicetak pada {{ now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }}</p>
                <p class="muted">
                    Catatan: Pendapatan lunas dihitung dari harga layanan pada appointment dengan pembayaran diterima.
                </p>
            </div>

            <div class="footer-right">
                <p>Admin Kurnia Care</p>
                <div class="signature-space"></div>
                <span class="signature-line">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
        </div>
    </div>

</body>

</html>