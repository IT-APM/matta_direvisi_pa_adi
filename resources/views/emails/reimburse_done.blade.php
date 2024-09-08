@include('emails.header')
<p>Semangat Pagi,</p>
<p><strong>Formulir Reimbursement anda telah disetujui para pihak</strong></p>
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
<p>Klik link berikut untuk download pdf Formulir Reimbursement:</p>
<h1><strong>Klik <a href="{{ $data['link'] }}" target="_blank">Formulir Reimbursement</a></strong></h1>
@include('emails.footer')