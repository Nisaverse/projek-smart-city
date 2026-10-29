<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Mingguan Environment IoT</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #0284c7; }
        .info { margin-bottom: 15px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px 10px; text-align: center; font-size: 12px; }
        th { background-color: #f1f5f9; color: #0f172a; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REKAP LAPORAN MINGGUAN SMART CITY</h2>
        <p>Modul Lingkungan & Sensor Kebencanaan (AetherSense)</p>
    </div>

    <div class="info">
        <p><strong>Tanggal Cetak:</strong> {{ date('d-m-Y H:i:s') }}</p>
        <p><strong>Total Data Terekam:</strong> {{ count($logs) }} Log</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu (Created At)</th>
                <th>Suhu (°C)</th>
                <th>Kelembapan (%)</th>
                <th>Kualitas Udara (MQ135)</th>
                <th>Status Hujan</th>
                <th>Level Air (cm)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $index => $log)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $log->created_at }}</td>
                <td>{{ $log->temperature }}</td>
                <td>{{ $log->humidity }}</td>
                <td>{{ $log->air_quality }}</td>
                <td>{{ $log->rain_status }}</td>
                <td>{{ $log->water_level }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>