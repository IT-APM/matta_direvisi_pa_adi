<!DOCTYPE html>
<html lang="en">

<head>
    <title>Surat Teguran</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
@php $nik_property = json_decode($data['trx']->nik_property, true); @endphp

<body>
    <div style="text-align:center;">
        <img src="http://matta-reborn.alita.id/matta/assets/images/matta/sudah_absen_hari_ini.png" alt="Sudahkah anda absen hari ini" width="720" height="479">
    </div>
    <div style="text-align:center;background-color:red;color:white;">
        <p style="padding:5px;">Surat Teguran {{ $data['teguran_ke'] }}</p>
    </div>
    <div>
        <div>
            <div>
                <p>Dear Bapak/Ibu/Sdr/i. {{ $nik_property['nama'] }}</p>
                <p>Terimakasih telah menggunakan Aplikasi Mobile / Web MATTA untuk kegiatan Absensi Kehadiran. Kami informasikan bahwa Absensi Kehadiran wajib dilakukan sekurang-kurangnya 80%.</p>
                <p>Bersama ini Kami sampaikan bahwa absensi kehadiran Bapak/Ibu/Sdr/i. {{ $nik_property['nama'] }} pada tanggal {{ \Carbon\Carbon::parse($data['trx']->date_from)->format('d-m-Y') }} sd {{ \Carbon\Carbon::parse($data['trx']->date_to)->format('d-m-Y') }} kurang dari 80% dari yang seharusnya terpenuhi (hadir = {{ $data['trx']->arr_clock }} dari jumlah hari kerja = {{ $data['trx']->arr_base }}).</p>
                <p>Persentase kehadiran anda adalah {{ round($data['trx']->persentase) }} %.</p>
                <div style="text-align:center;background-color:red;color:white;">
                    @if($data['trx']->arr_clock == 0)
                    <p style="padding:5px;">Bapak/Ibu/Sdr/i. {{ $nik_property['nama'] }} tidak melakukan Absensi Kehadiran sama sekali.</p>
                    @else
                    <p style="padding:5px;">Mohon untuk dapat lebih ditingkatkan lagi Absensi Kehadirannya.</p>
                    @endif
                </div>
                @if($data['teguran_ke'] == 'Kelima')
                <p>Informasi ini juga sebagai Surat Teguran {{ $data['teguran_ke'] }} dan menjadi Surat Teguran terakhir untuk Bapak/Ibu/Sdr/i. {{ $nik_property['nama'] }}. Jika bulan selanjutnya belum menunjukan peningkatan absensi kehadiran, maka akan kami terbitkan Surat Peringatan Pertama. Kami harap kedisplinan absensi kehadiran dapat menunjukan peningkatan.</p>
                @else
                <p>Informasi ini juga sebagai Surat Teguran {{ $data['teguran_ke'] }} untuk Bapak/Ibu/Sdr/i. {{ $nik_property['nama'] }}. Kami harap dapat melakukan Absensi Kehadiran setiap hari ke depannya.</p>
                @endif
                <p>Terimakasih atas perhatiannya.</p>
                <p>Best Regards,<br />Human Capital & Organization<br />www.alita.id</p>
                <p>{{ $data['datetime'] }}</p>
            </div>
        </div>
    </div>
</body>

</html>

{!! $data['history_email'] !!}