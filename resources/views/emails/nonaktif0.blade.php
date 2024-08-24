<p>Semangat Pagi,</p>
<p><strong>{{ $data['pesan'] }}</strong></p>
<p></p>
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
</table>
</p>
<p>Klik link berikut untuk melakukannya:</p>
<h1><strong>Klik <a href="{{ $data['link'] }}" target="_blank">Formulir Karyawan Non Aktif</a></strong></h1>
<p>Terima kasih,</p>
<p><strong>HC</strong></p>