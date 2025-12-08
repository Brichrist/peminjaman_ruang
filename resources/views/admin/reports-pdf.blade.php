<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Ruangan</title>
    <style>
        @page {
            margin: 25mm 20mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4F46E5;
        }

        .header h1 {
            font-size: 18px;
            color: #4F46E5;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        .date-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .date-header {
            background-color: #4F46E5;
            color: white;
            padding: 8px 10px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .room-section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .room-name {
            background-color: #E0E7FF;
            color: #4338CA;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
            border-left: 4px solid #4F46E5;
        }

        .booking-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .booking-table th {
            background-color: #F3F4F6;
            color: #374151;
            font-size: 10px;
            font-weight: bold;
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #D1D5DB;
        }

        .booking-table td {
            padding: 6px 8px;
            border: 1px solid #E5E7EB;
            font-size: 10px;
        }

        .booking-table tbody tr:nth-child(even) {
            background-color: #F9FAFB;
        }

        .time-badge {
            background-color: #DBEAFE;
            color: #1E40AF;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #9CA3AF;
            font-style: italic;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #9CA3AF;
            padding: 10px 0;
            border-top: 1px solid #E5E7EB;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PEMINJAMAN RUANGAN</h1>
        <p>
            Periode: {{ \Carbon\Carbon::parse($startDate)->locale('id')->isoFormat('D MMMM YYYY') }} -
            {{ \Carbon\Carbon::parse($endDate)->locale('id')->isoFormat('D MMMM YYYY') }}
        </p>
        <p style="margin-top: 3px;">
            Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') }} WIB
        </p>
    </div>

    @if($bookingsByDate->count() > 0)
        @foreach($bookingsByDate as $date => $bookings)
            <div class="date-section">
                <div class="date-header">
                    {{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    ({{ $bookings->count() }} peminjaman)
                </div>

                @php
                    $bookingsByRoom = $bookings->groupBy('room_id');
                @endphp

                @foreach($bookingsByRoom as $roomId => $roomBookings)
                    <div class="room-section">
                        <div class="room-name">
                            {{ $roomBookings->first()->room->name }}
                        </div>

                        <table class="booking-table">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">Waktu</th>
                                    <th style="width: 35%;">Kegiatan</th>
                                    <th style="width: 25%;">Nama Peminjam</th>
                                    <th style="width: 20%;">No. Telepon</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roomBookings as $booking)
                                    <tr>
                                        <td>
                                            <span class="time-badge">
                                                {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}
                                            </span>
                                        </td>
                                        <td>{{ $booking->activity }}</td>
                                        <td>{{ $booking->getContactName() }}</td>
                                        <td>{{ $booking->getContactPhone() ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>

            @if(!$loop->last)
                <div style="margin-bottom: 20px; border-top: 1px dashed #D1D5DB;"></div>
            @endif
        @endforeach
    @else
        <div class="no-data">
            <p>Tidak ada data peminjaman pada periode yang dipilih.</p>
        </div>
    @endif

    <div class="footer">
        Sistem Peminjaman Ruangan NICC - Halaman {{ $loop->iteration ?? 1 }}
    </div>
</body>
</html>
