<!DOCTYPE html>
<html>

<style>
.center {
  line-height: 55px;
  text-align: center;
}
</style>
<head>
    <title>Matta Reborn</title>
</head>

<!-- start -->

<body>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
            <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center"><strong>FORM KARYAWAN NON AKTIF</strong></div></td>
                        <td width="25%"><div class="center">No: {{ $trxM->id }}</div></td>
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>Karyawan</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen<br />Atasan Langsung<br />Tanggal Bergabung<br />Tanggal Berakhir Kontrak<br />Tanggal Non-Aktif<br />Alasan Non-Aktif<br />Keterangan</td>
                        <td width="70%">: {{ $karyawan['nama'] }}<br />: {{ $karyawan['nik'] }}<br />: {{ $karyawan['jabatan'] }}<br />: {{ $karyawan['divisi'] }} / {{ $karyawan['dep'] }} / {{ $karyawan['sub_dep'] }}<br />: {{ $atasan['nama'] }}<br />: @if(!empty($karyawan_properties->tanggal_bergabung)) {{ \Carbon\Carbon::parse($karyawan_properties->tanggal_bergabung)->format('d-m-Y') }} @endif<br />: @if(!empty($karyawan_properties->tanggal_berakhir)) {{ \Carbon\Carbon::parse($karyawan_properties->tanggal_berakhir)->format('d-m-Y') }} @endif<br />: @if(!empty($trxM->tanggal)) {{ \Carbon\Carbon::parse($trxM->tanggal)->format('d-m-Y') }} @endif<br />: {{ $trxM->_alasan->alasan }}<br />: {!! $trxM->keterangan !!}</td>
                    </tr>
                </table>
                <hr />
                <!-- di -->
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>Direktorat / Divisi / Departemen</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2"><strong>Kepada</strong></td>
                    </tr>
                    @foreach ($trxD_di as $value)
                    @php
                    $kepada = json_decode($value->kepada_property, true)
                    @endphp
                    <tr>
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen<br />Obyek Pengembalian<br />Keterangan</td>
                        <td width="70%">: {{ $kepada['nama'] }}<br />
                        : {{ $kepada['nik'] }}<br />
                        : {{ $kepada['jabatan'] }}<br />
                        : {{ $kepada['divisi'] }} / {{ $kepada['dep'] }} / {{ $kepada['sub_dep'] }}<br />
                        : {{ $value->_obyek->obyek }}<br />
                        : {!! $value->keterangan !!}
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr />
                <!-- ga -->
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>General Affairs</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2"><strong>Kepada</strong></td>
                    </tr>
                    @foreach ($trxD_ga as $value)
                    @php
                    $kepada = json_decode($value->kepada_property, true)
                    @endphp
                    <tr>
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen<br />Obyek Pengembalian<br />Keterangan</td>
                        <td width="70%">: {{ $kepada['nama'] }}<br />
                        : {{ $kepada['nik'] }}<br />
                        : {{ $kepada['jabatan'] }}<br />
                        : {{ $kepada['divisi'] }} / {{ $kepada['dep'] }} / {{ $kepada['sub_dep'] }}<br />
                        : {{ $value->_obyek->obyek }}<br />
                        : {!! $value->keterangan !!}
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr />
                <!-- it -->
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>IT</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2"><strong>Kepada</strong></td>
                    </tr>
                    @foreach ($trxD_it as $value)
                    @php
                    $kepada = json_decode($value->kepada_property, true)
                    @endphp
                    <tr>
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen<br />Obyek Pengembalian<br />Keterangan</td>
                        <td width="70%">: {{ $kepada['nama'] }}<br />
                        : {{ $kepada['nik'] }}<br />
                        : {{ $kepada['jabatan'] }}<br />
                        : {{ $kepada['divisi'] }} / {{ $kepada['dep'] }} / {{ $kepada['sub_dep'] }}<br />
                        : {{ $value->_obyek->obyek }}<br />
                        : {!! $value->keterangan !!}
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr />
                <!-- hc -->
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>Human Capital</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2"><strong>Kepada</strong></td>
                    </tr>
                    @foreach ($trxD_hc as $value)
                    @php
                    $kepada = json_decode($value->kepada_property, true)
                    @endphp
                    <tr>
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen<br />Obyek Pengembalian<br />Keterangan</td>
                        <td width="70%">: {{ $kepada['nama'] }}<br />
                        : {{ $kepada['nik'] }}<br />
                        : {{ $kepada['jabatan'] }}<br />
                        : {{ $kepada['divisi'] }} / {{ $kepada['dep'] }} / {{ $kepada['sub_dep'] }}<br />
                        : {{ $value->_obyek->obyek }}<br />
                        : {!! $value->keterangan !!}
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr />
                <!-- ko -->
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong>Koperasi Karyawan</strong></td>
                    </tr>
                </table>
                <hr />
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="2"><strong>Kepada</strong></td>
                    </tr>
                    @foreach ($trxD_ko as $value)
                    @php
                    $kepada = json_decode($value->kepada_property, true)
                    @endphp
                    <tr>
                        <td width="30%">Nama<br />NIP<br />Jabatan<br />Direktorat / Divisi / Departemen<br />Obyek Pengembalian<br />Keterangan</td>
                        <td width="70%">: {{ $kepada['nama'] }}<br />
                        : {{ $kepada['nik'] }}<br />
                        : {{ $kepada['jabatan'] }}<br />
                        : {{ $kepada['divisi'] }} / {{ $kepada['dep'] }} / {{ $kepada['sub_dep'] }}<br />
                        : {{ $value->_obyek->obyek }}<br />
                        : {!! $value->keterangan !!}
                        </td>
                    </tr>
                    @endforeach
                </table>
                <hr />
                <!--  -->
                {!! $htmlLog !!}
                <!--  -->
            </td>
        </tr>
    </table>
</body>
<!-- finish -->

</html>