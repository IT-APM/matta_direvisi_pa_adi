<!DOCTYPE html>
<html>
<style>
.center {
  line-height: 55px;
  text-align: center;
}
</style>
<head>
    <title>Slip Gaji</title>
</head>
@php
$karyawan = json_decode($payroll->nik_property, true)
@endphp
<body>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
                        <td width="25%">
                            <div class="left" style="font-size: 9px"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                                    <tr>
                                        <td colspan="2">SLIP GAJI PT. ALITA PRAYA MITRA</td>
                                    </tr>
                                    <tr>
                                        <td width="40%">BULAN-TAHUN</td>
                                        <td width="60%">: {{ str_pad($payroll->bulan, 2, '0', STR_PAD_LEFT) }}-{{ $payroll->tahun }}</td>
                                    </tr>
                                    <tr>
                                        <td>LEVEL</td>
                                        <td>: {{ $karyawan['level'] }}</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td width="25%">
                            <div class="left" style="font-size: 9px"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="30%">NAMA</td>
                                        <td width="70%">: {{ $karyawan['nama'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>JABATAN</td>
                                        <td>: {{ $karyawan['jabatan'] }}</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td width="25%">
                            <div class="left" style="font-size: 9px"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">NIP</td>
                                        <td width="80%">: {{ $karyawan['nik'] }}</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
						<td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
					</tr>
				</table>
			</td>
		</tr>
        <tr>
			<td>
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
                        <td width="15%">
                            <div class="left" style="font-size: 9px">A. GAJI / SALARY</div>
                        </td>
                        <td width="15%">
                            <div class="left" style="font-size: 9px">test</div>
                        </td>
                        <td width="20%">
                            <div class="left" style="font-size: 9px">test</div>
                        </td>
                        <td width="15%">
                            <div class="left" style="font-size: 9px">test</div>
                        </td>
                        <td width="15%">
                            <div class="left" style="font-size: 9px">test</div>
                        </td>
                        <td width="20%">
                            <div class="left" style="font-size: 9px">test</div>
                        </td>
					</tr>
					<tr>
                        <td width="15%">
                            <div class="left" style="font-size: 9px">A. GAJI / SALARY</div>
                        </td>
                        <td width="15%">
                            <div class="left" style="font-size: 9px">test</div>
                        </td>
                        <td width="20%">
                            <div class="left" style="font-size: 9px">test</div>
                        </td>
                        <td width="15%">
                            <div class="left" style="font-size: 9px">test</div>
                        </td>
                        <td width="15%">
                            <div class="left" style="font-size: 9px">test</div>
                        </td>
                        <td width="20%">
                            <div class="left" style="font-size: 9px">test</div>
                        </td>
					</tr>
				</table>
                <hr/>
			</td>
		</tr>
	</table>
</body>
</html>
