<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 11pt;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            border: 2px solid #1e40af;
        }
        th {
            background: linear-gradient(to bottom, #3b82f6, #2563eb);
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 12px 8px;
            border: 1px solid #1e3a8a;
            font-size: 10pt;
            vertical-align: middle;
        }
        td {
            padding: 8px;
            border: 1px solid #d1d5db;
            text-align: center;
            vertical-align: middle;
        }
        .header-row {
            background-color: #3b82f6;
            color: white;
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .bg-blue {
            background-color: #dbeafe;
            font-weight: 500;
        }
        .bg-green {
            background-color: #dcfce7;
            font-weight: 500;
        }
        .bg-purple {
            background-color: #f3e8ff;
            font-weight: 500;
        }
        .bg-indigo {
            background-color: #e0e7ff;
            font-weight: 500;
        }
        .bg-orange {
            background-color: #fed7aa;
            font-weight: 500;
        }
        .bg-red {
            background-color: #fee2e2;
            font-weight: 500;
        }
        .bg-yellow {
            background-color: #fef9c3;
            font-weight: bold;
        }
        .grade-A {
            background-color: #86efac !important;
            color: #166534 !important;
            font-weight: bold;
        }
        .grade-B {
            background-color: #93c5fd !important;
            color: #1e40af !important;
            font-weight: bold;
        }
        .grade-C {
            background-color: #fde047 !important;
            color: #854d0e !important;
            font-weight: bold;
        }
        .grade-D {
            background-color: #fdba74 !important;
            color: #9a3412 !important;
            font-weight: bold;
        }
        .grade-E {
            background-color: #fca5a5 !important;
            color: #991b1b !important;
            font-weight: bold;
        }
        .number {
            mso-number-format: "0\.00";
            text-align: center;
        }
        .row-number {
            text-align: center;
            font-weight: 500;
        }
        tbody tr:hover {
            background-color: #f9fafb;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
    </style>
</head>
<body>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background-color: #3b82f6; color: white; font-weight: bold;">
                <th style="width: 40px; text-align: center;">No</th>
                <th style="width: 80px; text-align: left;">Kode MK</th>
                <th style="width: 180px; text-align: left;">Mata Kuliah</th>
                <th style="width: 60px; text-align: center;">Semester</th>
                <th style="width: 90px; text-align: left;">NIM</th>
                <th style="width: 150px; text-align: left;">Nama Mahasiswa</th>
                <th style="width: 70px; background-color: #dbeafe; text-align: center;">Hadir</th>
                <th style="width: 70px; background-color: #dcfce7; text-align: center;">Tugas</th>
                <th style="width: 70px; background-color: #f3e8ff; text-align: center;">Quiz</th>
                <th style="width: 70px; background-color: #e0e7ff; text-align: center;">Project</th>
                <th style="width: 70px; background-color: #fed7aa; text-align: center;">UTS</th>
                <th style="width: 70px; background-color: #fee2e2; text-align: center;">UAS</th>
                <th style="width: 80px; background-color: #fef9c3; text-align: center; font-weight: bold;">Nilai Akhir</th>
                <th style="width: 80px; background-color: #fef9c3; text-align: center; font-weight: bold;">Huruf Mutu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nilai as $index => $item)
                <tr>
                    <td class="row-number">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $item->matakuliah->kode_mk ?? '-' }}</td>
                    <td class="text-left">{{ $item->matakuliah->nama_mk ?? '-' }}</td>
                    <td>{{ $item->matakuliah->semester ?? '-' }}</td>
                    <td class="text-left">{{ $item->mahasiswa->nim ?? '-' }}</td>
                    <td class="text-left">{{ $item->mahasiswa->nama ?? '-' }}</td>
                    <td class="bg-blue number">{{ number_format($item->kehadiran, 2) }}</td>
                    <td class="bg-green number">{{ number_format($item->tugas, 2) }}</td>
                    <td class="bg-purple number">{{ number_format($item->kuis, 2) }}</td>
                    <td class="bg-indigo number">{{ number_format($item->project, 2) }}</td>
                    <td class="bg-orange number">{{ number_format($item->uts, 2) }}</td>
                    <td class="bg-red number">{{ number_format($item->uas, 2) }}</td>
                    <td class="bg-yellow number"><strong>{{ number_format($item->nilai_akhir, 2) }}</strong></td>
                    <td class="bg-yellow">
                        @php
                            $hurufMutu = $item->huruf_mutu ?? '-';
                            $gradeStyle = '';
                            if ($hurufMutu != '-') {
                                switch($hurufMutu) {
                                    case 'A': $gradeStyle = 'background-color: #86efac; color: #166534; font-weight: bold;'; break;
                                    case 'B': $gradeStyle = 'background-color: #93c5fd; color: #1e40af; font-weight: bold;'; break;
                                    case 'C': $gradeStyle = 'background-color: #fde047; color: #854d0e; font-weight: bold;'; break;
                                    case 'D': $gradeStyle = 'background-color: #fdba74; color: #9a3412; font-weight: bold;'; break;
                                    case 'E': $gradeStyle = 'background-color: #fca5a5; color: #991b1b; font-weight: bold;'; break;
                                }
                            }
                        @endphp
                        <span style="{{ $gradeStyle }}">{{ $hurufMutu }}</span>
                    </td>
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
</body>
</html>

