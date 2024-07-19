<p>Semangat Pagi,</p>
<p><strong>Formulir Pengajuan Pelatihan anda sudah disetujui para pihak.</strong></p>
<p>Berikut datanya:<br />
<table width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr>
        <td width="20%">Nama Karyawan</td>
        <td width="80%">: {{ $data['nama'] }}</td>
    </tr>
    <tr>
        <td>Jabatan</td>
        <td>: {{ $data['jabatan'] }}</td>
    </tr>
    <tr>
        <td>Direktorat / Divisi / Departemen</td>
        <td>: {{ $data['unit_kerja'] }}</td>
    </tr>
    <tr>
        <td>Judul Pelatihan</td>
        <td>: {{ $data['pelatihan']['judul'] }}</td>
    </tr>
    <tr>
        <td>Penyelenggara</td>
        <td>: {{ $data['pelatihan']['penyelenggara'] }}</td>
    </tr>
    <tr>
        <td>Tempat</td>
        <td>: {{ $data['pelatihan']['tempat'] }}</td>
    </tr>
    <tr>
        <td>Tanggal</td>
        <td>: {{ $data['pelatihan']['tanggal_mulai'] }} sd {{ $data['pelatihan']['tanggal_akhir'] }}</td>
    </tr>
</table>
</p>
</p>
<p>Terima kasih,</p>
<p><strong>HC</strong></p>