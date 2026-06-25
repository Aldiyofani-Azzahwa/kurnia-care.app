<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kurnia Care</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111827;
            font-size: 12px;
            margin: 24px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #047857;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #047857;
            font-size: 24px;
        }

        .header p {
            margin: 4px 0 0;
            color: #4b5563;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #d1d5db;
            padding: 10px;
            border-radius: 8px;
        }

        .card span {
            color: #6b7280;
            font-size: 11px;
        }

        .card strong {
            display: block;
            margin-top: 4px;
            font-size: 16px;
            color: #047857;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        th {
            background: #047857;
            color: white;
            padding: 8px;
            border: 1px solid #047857;
            text-align: left;
        }

        td {
            padding: 8px;
            border: 1px solid #d1d5db;
            vertical-align: top;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            color: #4b5563;
        }

        .print-button {
            margin-bottom: 20px;
            padding: 10px 16px;
            background: #047857;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        @media print {
            .print-button {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>

<button onclick="window.print()" class="print-button">
    Cetak / Simpan PDF
</button>

<div class="header">
    <h1>Kurnia Care</h1>
    <p>Laporan Klinik Sunat Modern</p>
    <p>
        Periode:
        {{ \Carbon\Carbon::parse($startDate)->locale('id')->isoFormat('D MMMM Y') }}
        -
        {{ \Carbon\Carbon::parse($endDate)->locale('id')->isoFormat('D MMMM Y') }}
    </p>
</div>

<div class="summary">
    <div class="card">
        <span>Total Appointment</span>
        <strong>{{ $totalAppointments }}</strong>
    </div>

    <div class="card">
        <span>Menunggu</span>
        <strong>{{ $waitingCount }}</strong>
    </div>

    <div class="card">
        <span>dikonfirmasi</span>
        <strong>{{ $processCount }}</strong>
    </div>

    <div class="card">
        <span>Selesai</span>
        <strong>{{ $doneCount }}</strong>
    </div>

    <div class="card">
        <span>Dibatalkan</span>
        <strong>{{ $cancelCount }}</strong>
    </div>

    <div class="card">
        <span>Pembayaran Diterima</span>
        <strong>{{ $verifiedPaymentCount }}</strong>
    </div>

    <div class="card">
        <span>Pembayaran Pending</span>
        <strong>{{ $pendingPaymentCount }}</strong>
    </div>

    <div class="card">
        <span>Pendapatan Terverifikasi</span>
        <strong>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</strong>
    </div>
</div>

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
            <th>Nominal</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($appointments as $appointment)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $appointment->appointment_date?->format('d/m/Y') }}<br>
                    {{ substr($appointment->appointment_time, 0, 5) }}
                </td>
                <td>
                    {{ $appointment->patient->child_name ?? '-' }}<br>
                    {{ $appointment->patient->phone ?? '-' }}
                </td>
                <td>{{ $appointment->service->name ?? '-' }}</td>
                <td>{{ $appointment->doctor->name ?? '-' }}</td>
                <td>{{ ucfirst($appointment->status) }}</td>
                <td>{{ ucfirst($appointment->payment->status ?? '-') }}</td>
                <td>
                    Rp{{ number_format($appointment->payment->amount ?? 0, 0, ',', '.') }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align:center;">
                    Tidak ada data laporan.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    Dicetak pada {{ now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }}
</div>

</body>
</html>