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
            max-width: 100%;
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
            white-space: nowrap;
            overflow: hidden;
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
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <table border="1" cellpadding="8" cellspacing="0" style="table-layout: fixed; width: 100%;">
        <colgroup>
            <col style="width: 50px;">
            <col style="width: 100px;">
            <col style="width: 250px;">
            <col style="width: 80px;">
            <col style="width: 120px;">
            <col style="width: 200px;">
            <col style="width: 80px;">
            <col style="width: 80px;">
            <col style="width: 80px;">
            <col style="width: 80px;">
            <col style="width: 80px;">
            <col style="width: 80px;">
            <col style="width: 100px;">
            <col style="width: 100px;">
        </colgroup>
        <thead>
            <tr style="height: 35px;">
                <th style="background-color: #3b82f6; color: white; font-weight: bold; text-align: center; white-space: nowrap; padding: 10px 8px;">No</th>
                <th style="background-color: #3b82f6; color: white; font-weight: bold; text-align: left; white-space: nowrap; padding: 10px 8px;">Kode MK</th>
                <th style="background-color: #3b82f6; color: white; font-weight: bold; text-align: left; white-space: nowrap; padding: 10px 8px;">Mata Kuliah</th>
                <th style="background-color: #3b82f6; color: white; font-weight: bold; text-align: center; white-space: nowrap; padding: 10px 8px;">Semester</th>
                <th style="background-color: #3b82f6; color: white; font-weight: bold; text-align: left; white-space: nowrap; padding: 10px 8px;">NIM</th>
                <th style="background-color: #3b82f6; color: white; font-weight: bold; text-align: left; white-space: nowrap; padding: 10px 8px;">Nama Mahasiswa</th>
                <th style="background-color: #dbeafe; color: #000000; font-weight: bold; text-align: center; white-space: nowrap; padding: 10px 8px;">Hadir</th>
                <th style="background-color: #dcfce7; color: #000000; font-weight: bold; text-align: center; white-space: nowrap; padding: 10px 8px;">Tugas</th>
                <th style="background-color: #f3e8ff; color: #000000; font-weight: bold; text-align: center; white-space: nowrap; padding: 10px 8px;">Quiz</th>
                <th style="background-color: #e0e7ff; color: #000000; font-weight: bold; text-align: center; white-space: nowrap; padding: 10px 8px;">Project</th>
                <th style="background-color: #fed7aa; color: #000000; font-weight: bold; text-align: center; white-space: nowrap; padding: 10px 8px;">UTS</th>
                <th style="background-color: #fee2e2; color: #000000; font-weight: bold; text-align: center; white-space: nowrap; padding: 10px 8px;">UAS</th>
                <th style="background-color: #fef9c3; color: #000000; font-weight: bold; text-align: center; white-space: nowrap; padding: 10px 8px;">Nilai Akhir</th>
                <th style="background-color: #fef9c3; color: #000000; font-weight: bold; text-align: center; white-space: nowrap; padding: 10px 8px;">Huruf Mutu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nilai as $index => $item)
                @php
                    $hurufMutu = $item->huruf_mutu ?? '-';
                    $gradeClass = '';
                    if ($hurufMutu != '-') {
                        switch($hurufMutu) {
                            case 'A': $gradeClass = 'grade-A'; break;
                            case 'B': $gradeClass = 'grade-B'; break;
                            case 'C': $gradeClass = 'grade-C'; break;
                            case 'D': $gradeClass = 'grade-D'; break;
                            case 'E': $gradeClass = 'grade-E'; break;
                        }
                    }
                @endphp
                <tr>
                    <td style="text-align: center; padding: 8px; white-space: nowrap;">{{ $index + 1 }}</td>
                    <td style="text-align: left; padding: 8px; white-space: nowrap;">{{ $item->matakuliah->kode_mk ?? '-' }}</td>
                    <td style="text-align: left; padding: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $item->matakuliah->nama_mk ?? '-' }}">{{ $item->matakuliah->nama_mk ?? '-' }}</td>
                    <td style="text-align: center; padding: 8px; white-space: nowrap;">{{ $item->matakuliah->semester ?? '-' }}</td>
                    <td style="text-align: left; padding: 8px; white-space: nowrap;">{{ $item->mahasiswa->nim ?? '-' }}</td>
                    <td style="text-align: left; padding: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $item->mahasiswa->nama ?? '-' }}">{{ $item->mahasiswa->nama ?? '-' }}</td>
                    <td class="bg-blue number" style="background-color: #dbeafe; padding: 8px; white-space: nowrap; text-align: center;">{{ number_format($item->kehadiran, 2) }}</td>
                    <td class="bg-green number" style="background-color: #dcfce7; padding: 8px; white-space: nowrap; text-align: center;">{{ number_format($item->tugas, 2) }}</td>
                    <td class="bg-purple number" style="background-color: #f3e8ff; padding: 8px; white-space: nowrap; text-align: center;">{{ number_format($item->kuis, 2) }}</td>
                    <td class="bg-indigo number" style="background-color: #e0e7ff; padding: 8px; white-space: nowrap; text-align: center;">{{ number_format($item->project, 2) }}</td>
                    <td class="bg-orange number" style="background-color: #fed7aa; padding: 8px; white-space: nowrap; text-align: center;">{{ number_format($item->uts, 2) }}</td>
                    <td class="bg-red number" style="background-color: #fee2e2; padding: 8px; white-space: nowrap; text-align: center;">{{ number_format($item->uas, 2) }}</td>
                    <td class="bg-yellow number" style="background-color: #fef9c3; font-weight: bold; padding: 8px; white-space: nowrap; text-align: center;">{{ number_format($item->nilai_akhir, 2) }}</td>
                    <td class="bg-yellow {{ $gradeClass }}" style="text-align: center; font-weight: bold; padding: 8px; white-space: nowrap;">
                        @if($hurufMutu != '-')
                            @php
                                $gradeBgColor = '';
                                $gradeTextColor = '';
                                switch($hurufMutu) {
                                    case 'A': $gradeBgColor = '#86efac'; $gradeTextColor = '#166534'; break;
                                    case 'B': $gradeBgColor = '#93c5fd'; $gradeTextColor = '#1e40af'; break;
                                    case 'C': $gradeBgColor = '#fde047'; $gradeTextColor = '#854d0e'; break;
                                    case 'D': $gradeBgColor = '#fdba74'; $gradeTextColor = '#9a3412'; break;
                                    case 'E': $gradeBgColor = '#fca5a5'; $gradeTextColor = '#991b1b'; break;
                                    default: $gradeBgColor = '#fef9c3'; $gradeTextColor = '#000000'; break;
                                }
                            @endphp
                            <span style="background-color: {{ $gradeBgColor }}; color: {{ $gradeTextColor }}; padding: 4px 8px; border-radius: 4px; display: inline-block; font-weight: bold;">{{ $hurufMutu }}</span>
                        @else
                            {{ $hurufMutu }}
                        @endif
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

