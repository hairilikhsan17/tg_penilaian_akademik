<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KHS - {{ $mahasiswa->nama }} - {{ date('d F Y') }}</title>
    <style>
        @media print {
            @page {
                margin: 1.5cm;
                size: A4 portrait;
            }
            body {
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0;
            font-size: 11px;
        }
        .info-section {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #000;
        }
        .info-section table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-section td {
            padding: 5px 10px;
            border: none;
        }
        .info-section td.label {
            width: 150px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-size: 10px;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .footer-left {
            text-align: left;
        }
        .footer-right {
            text-align: right;
        }
        .signature {
            margin-top: 40px;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            border: none;
            padding: 5px;
            text-align: center;
        }
        .ipk-box {
            display: inline-block;
            padding: 8px 15px;
            border: 2px solid #000;
            font-weight: bold;
            font-size: 14px;
            margin-left: 10px;
        }
        .no-print {
            margin-bottom: 20px;
            text-align: center;
        }
        .no-print button {
            padding: 10px 20px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }
        .no-print button:hover {
            background-color: #2563eb;
        }
        .bg-gray {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">
            <i class="fas fa-print"></i> Cetak PDF
        </button>
        <button onclick="window.close()" style="background-color: #6b7280;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h1>UNIVERSITAS [NAMA UNIVERSITAS]</h1>
        <h2>KARTU HASIL STUDI (KHS)</h2>
        <p>Semester Akademik {{ date('Y') }}/{{ date('Y') + 1 }}</p>
        <p>Tanggal Cetak: {{ date('d F Y, H:i:s') }}</p>
    </div>

    <!-- Informasi Mahasiswa -->
    <div class="info-section">
        <table>
            <tr>
                <td class="label">Nama</td>
                <td>: {{ $mahasiswa->nama }}</td>
                <td class="label" style="width: 150px;">IPK</td>
                <td>: <span class="ipk-box">{{ number_format($ipk, 2) }}</span></td>
            </tr>
            <tr>
                <td class="label">NIM</td>
                <td>: {{ $mahasiswa->nim }}</td>
                <td class="label">Total SKS</td>
                <td>: {{ $totalSKS }} SKS</td>
            </tr>
            <tr>
                <td class="label">Program Studi</td>
                <td>: [Program Studi]</td>
                <td class="label">Total Mata Kuliah</td>
                <td>: {{ $nilai->count() }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Nilai -->
    @if($nilai->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;" class="text-left">Kode MK</th>
                <th class="text-left">Mata Kuliah</th>
                <th style="width: 60px;">Semester</th>
                <th style="width: 40px;">SKS</th>
                <th style="width: 70px;">Nilai Akhir</th>
                <th style="width: 70px;">Huruf Mutu</th>
                <th style="width: 70px;">Bobot</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilai as $index => $item)
                @php
                    $hurufMutu = $item->huruf_mutu ?? '-';
                    $nilaiAkhir = $item->nilai_akhir ?? 0;
                    $sks = $item->matakuliah->sks ?? 0;
                    
                    // Konversi huruf mutu ke bobot
                    $bobot = 0;
                    if ($hurufMutu == 'A') $bobot = 4;
                    elseif ($hurufMutu == 'B') $bobot = 3;
                    elseif ($hurufMutu == 'C') $bobot = 2;
                    elseif ($hurufMutu == 'D') $bobot = 1;
                    elseif ($hurufMutu == 'E') $bobot = 0;
                    else {
                        if ($nilaiAkhir >= 85) $bobot = 4;
                        elseif ($nilaiAkhir >= 75) $bobot = 3;
                        elseif ($nilaiAkhir >= 65) $bobot = 2;
                        elseif ($nilaiAkhir >= 55) $bobot = 1;
                        else $bobot = 0;
                    }
                    
                    $nilaiKualitas = $sks * $bobot;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $item->matakuliah->kode_mk ?? '-' }}</td>
                    <td class="text-left">{{ $item->matakuliah->nama_mk ?? '-' }}</td>
                    <td>{{ $item->matakuliah->semester ?? '-' }}</td>
                    <td>{{ $sks }}</td>
                    <td><strong>{{ $nilaiAkhir > 0 ? number_format($nilaiAkhir, 2) : '-' }}</strong></td>
                    <td><strong>{{ $hurufMutu != '-' ? $hurufMutu : '-' }}</strong></td>
                    <td>{{ $nilaiKualitas > 0 ? number_format($nilaiKualitas, 2) : '-' }}</td>
                </tr>
            @endforeach
            <!-- Total Row -->
            <tr class="bg-gray" style="font-weight: bold;">
                <td colspan="4" class="text-left" style="text-align: right; padding-right: 10px;">TOTAL</td>
                <td>{{ $totalSKS }}</td>
                <td>-</td>
                <td>-</td>
                <td>
                    {{ number_format($nilai->sum(function($item) {
                        $sks = $item->matakuliah->sks ?? 0;
                        $hurufMutu = $item->huruf_mutu ?? '-';
                        $nilaiAkhir = $item->nilai_akhir ?? 0;
                        
                        $bobot = 0;
                        if ($hurufMutu == 'A') $bobot = 4;
                        elseif ($hurufMutu == 'B') $bobot = 3;
                        elseif ($hurufMutu == 'C') $bobot = 2;
                        elseif ($hurufMutu == 'D') $bobot = 1;
                        elseif ($hurufMutu == 'E') $bobot = 0;
                        else {
                            if ($nilaiAkhir >= 85) $bobot = 4;
                            elseif ($nilaiAkhir >= 75) $bobot = 3;
                            elseif ($nilaiAkhir >= 65) $bobot = 2;
                            elseif ($nilaiAkhir >= 55) $bobot = 1;
                            else $bobot = 0;
                        }
                        
                        return $sks * $bobot;
                    }), 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Keterangan -->
    <div style="margin-top: 20px; font-size: 10px;">
        <p><strong>Keterangan:</strong></p>
        <p>Nilai Mutu: A = 4.00 (≥85), B = 3.00 (75-84), C = 2.00 (65-74), D = 1.00 (55-64), E = 0.00 (<55)</p>
        <p>IPK (Indeks Prestasi Kumulatif) = Total (SKS × Nilai Mutu) / Total SKS</p>
    </div>
    @else
    <div style="text-align: center; padding: 40px; border: 1px solid #000;">
        <p style="font-size: 12px;">Belum ada data KHS</p>
    </div>
    @endif

    <!-- Tanda Tangan -->
    <div class="signature">
        <table class="signature-table">
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%;">
                    <p>Mengetahui,</p>
                    <p style="margin-top: 50px;">_______________________</p>
                    <p style="margin-top: 5px;"><strong>Ketua Program Studi</strong></p>
                    <p style="margin-top: 5px;">NIP. ...................</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div class="footer-left">
            <p style="font-size: 9px;">Catatan: Dokumen ini dicetak secara elektronik dan berlaku tanpa tanda tangan basah</p>
        </div>
        <div class="footer-right">
            <p style="font-size: 9px;">Dicetak pada: {{ date('d F Y, H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Optional: Auto print when page loads
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>



