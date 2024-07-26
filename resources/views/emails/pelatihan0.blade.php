<p>Semangat Pagi,</p>
<p><strong>Terdapat Formulir Pengajuan Pelatihan yang harus anda review</strong></p>
<p></p>
<p>Berikut datanya:</p>
<strong>Pengaju:</strong><br/>
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
<strong>Pelatihan:</strong><br/>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="20%">Judul</td>
    <td width="80%">: {{ $data['trx']->judul }}</td>
  </tr>
  <tr>
    <td>Penyelenggara</td>
    <td>: {{ $data['trx']->penyelenggara }}</td>
  </tr>
  <tr>
    <td>Tanggal Pelaksanaan</td>
    <td>: {{ Carbon\Carbon::createFromFormat('Y-m-d', $data['trx']->tanggal_mulai)->format('d-m-Y') }} sampai {{ Carbon\Carbon::createFromFormat('Y-m-d', $data['trx']->tanggal_akhir)->format('d-m-Y') }}</td>
  </tr>
  <tr>
    <td>Tempat</td>
    <td>: {{ $data['trx']->tempat }}</td>
  </tr>
  <tr>
    <td>Peserta</td>
    <td>: 
    @php $arrPeserta = explode(';', $data['trx']->peserta_property) @endphp
      @foreach ($arrPeserta as $value)
        @php $_value = json_decode($value, true) @endphp
        {{ $_value['nama'] }} /  {{ $_value['nik'] }}<br/>
      @endforeach
    </td>
  </tr>
</table>
<p>Klik link berikut untuk melakukan review tersebut:</p>
<h1><strong>Klik <a href="{{ $data['link'] }}" target="_blank">Formulir Pengajuan Pelatihan</a></strong></h1>
<p>Terima kasih,</p>
<p><strong>HC</strong></p>