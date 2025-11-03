<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Nilai - {{ date('d F Y') }}</title>
    <style>
        @media print {
            @page {
                margin: 1cm;
                size: A4 landscape;
            }
            body {
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9px;
        }
        td {
            font-size: 9px;
        }
        .text-left {
            text-align: left;
        }
        .bg-blue { background-color: #dbeafe; }
        .bg-green { background-color: #dcfce7; }
        .bg-purple { background-color: #f3e8ff; }
        .bg-indigo { background-color: #e0e7ff; }
        .bg-orange { background-color: #fed7aa; }
        .bg-red { background-color: #fee2e2; }
        .bg-yellow { background-color: #fef9c3; }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
            <i class="fas fa-print"></i> Cetak PDF
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #6b7280; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-left: 10px;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h1>LAPORAN NILAI MAHASISWA</h1>
        <p>Sistem Penilaian Akademik</p>
        <p>Tanggal Cetak: {{ date('d F Y, H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;" class="text-left">Kode MK</th>
                <th style="width: 150px;" class="text-left">Mata Kuliah</th>
                <th style="width: 50px;">Semester</th>
                <th style="width: 80px;" class="text-left">NIM</th>
                <th style="width: 120px;" class="text-left">Nama Mahasiswa</th>
                <th style="width: 50px;" class="bg-blue">Hadir</th>
                <th style="width: 50px;" class="bg-green">Tugas</th>
                <th style="width: 50px;" class="bg-purple">Quiz</th>
                <th style="width: 50px;" class="bg-indigo">Project</th>
                <th style="width: 50px;" class="bg-orange">UTS</th>
                <th style="width: 50px;" class="bg-red">UAS</th>
                <th style="width: 60px;" class="bg-yellow">Nilai Akhir</th>
                <th style="width: 60px;" class="bg-yellow">Huruf Mutu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nilai as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $item->matakuliah->kode_mk ?? '-' }}</td>
                    <td class="text-left">{{ $item->matakuliah->nama_mk ?? '-' }}</td>
                    <td>{{ $item->matakuliah->semester ?? '-' }}</td>
                    <td class="text-left">{{ $item->mahasiswa->nim ?? '-' }}</td>
                    <td class="text-left">{{ $item->mahasiswa->nama ?? '-' }}</td>
                    <td class="bg-blue">{{ number_format($item->kehadiran, 2) }}</td>
                    <td class="bg-green">{{ number_format($item->tugas, 2) }}</td>
                    <td class="bg-purple">{{ number_format($item->kuis, 2) }}</td>
                    <td class="bg-indigo">{{ number_format($item->project, 2) }}</td>
                    <td class="bg-orange">{{ number_format($item->uts, 2) }}</td>
                    <td class="bg-red">{{ number_format($item->uas, 2) }}</td>
                    <td class="bg-yellow"><strong>{{ number_format($item->nilai_akhir, 2) }}</strong></td>
                    <td class="bg-yellow"><strong>{{ $item->huruf_mutu ?? '-' }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" style="text-align: center; padding: 20px;">
                        Tidak ada data nilai
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i:s') }}</p>
        <p>Total Data: {{ $nilai->count() }} nilai</p>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>


