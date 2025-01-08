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
$karyawan = json_decode($payroll->nik_property, true);
@endphp
<body>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
                        <td colspan="2">SLIP GAJI PT. ALITA PRAYA MITRA</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
						<td rowspan="3"><img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
					</tr>
					<tr>
                        <td width="14%">BULAN-TAHUN</td>
                        <td width="19%">: {{ str_pad($payroll->bulan, 2, '0', STR_PAD_LEFT) }}-{{ $payroll->tahun }}</td>
                        <td width="9%">NAMA</td>
                        <td width="25%">: {{ $karyawan['nama'] }}</td>
                        <td width="5%">NIP</td>
                        <td width="12%">: {{ $karyawan['nik'] }}</td>
						<td width="16%">&nbsp;</td>
					</tr>
					<tr>
                        <td>LEVEL</td>
                        <td>: {{ $karyawan['level'] }}</td>
                        <td>JABATAN</td>
                        <td>: {{ $karyawan['jabatan'] }}</td>
                        <td></td>
                        <td></td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr><tr>
			<td>
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
                        <td width="27%">A. GAJI / SALARY</td>
                        <td width="9%">&nbsp;</td>
                        <td width="14%">&nbsp;</td>
                        <td width="5%">&nbsp;</td>
                        <td width="22%">&nbsp;</td>
                        <td width="8%">&nbsp;</td>
                        <td width="15%">&nbsp;</td>
					</tr>
					<tr>
                        <td>A.1. GAJI POKOK</td>
                        <td></td>
                        <td align="right">{{ number_format($payroll->gapok, 0) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
					</tr>
					<tr>
                        @php
                        $arr_abjad = ['a','b','c','d','e'];
                        $where = ['tipe'=>'add'];
                        $arr_id = [1,2,3,4,5];
                        $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                        @endphp
                        <td>A.2. TUNJANGAN TETAP<br/>
                            @foreach ($add as $key => $value)
                                {{ $arr_abjad[$key] }}. {{ $value->nama }}@if($key != count($arr_abjad)-1)<br/>@endif
                            @endforeach
                        </td>
                        @php
                        $where = ['nik'=>$payroll->nik, 'tipe'=>'add', 'bulan'=>$payroll->bulan, 'tahun'=>$payroll->tahun];
                        $add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                        $total_tunjangan = $add->sum('angka');
                        @endphp
                        <td align="right">&nbsp;<br/>
                            @foreach ($add as $key => $value)
                                {{ number_format($value->angka, 0) }}@if($key != count($add)-1)<br/>@endif
                            @endforeach
                        </td>
                        <td></td>
                        <td></td>
                        @php
                        $arr_abjad = ['a','b','c','d','e','f','g','h'];
                        $where = ['tipe'=>'sub'];
                        $arr_id = [13,14,15,16,17,18,19,20];
                        $sub = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                        @endphp
                        <td>B. POTONGAN&nbsp;<br/>
                            @foreach ($sub as $key => $value){{ $arr_abjad[$key] }}. {{ $value->nama }}@if($key != count($arr_abjad)-1)<br/>@endif
                            @endforeach
                        </td>
                        @php
                        $where = ['nik'=>$payroll->nik, 'tipe'=>'sub', 'bulan'=>$payroll->bulan, 'tahun'=>$payroll->tahun];
                        $sub = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                        $total_potongan = $sub->sum('angka');
                        @endphp
                        <td align="right">&nbsp;<br/>
                            @foreach ($sub as $key => $value)
                                {{ number_format($value->angka, 0) }}@if($key != count($sub)-1)<br/>@endif
                            @endforeach
                        </td>
                        <td></td>
					</tr>
					<tr>
                        <td>Total Tunjangan Tetap (A.2)</td>
                        <td></td>
                        <td align="right">{{ number_format($total_tunjangan, 0) }}</td>
                        <td></td>
                        <td>Total Potongan (B)</td>
                        <td></td>
                        <td align="right">{{ number_format($total_potongan, 0) }}</td>
					</tr>
					<tr>
                        <td>Gaji / Salary Yang Diterima Per Bulan</td>
                        <td></td>
                        <td align="right">{{ number_format($payroll->gapok + $total_tunjangan, 0) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
					</tr>
					<tr>
                        @php
                        $arr_abjad = ['a','b','c','d','e','f'];
                        $where = ['tipe'=>'add'];
                        $arr_id = [6,7,8,9,10,11];
                        $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                        @endphp
                        <td>A.3. TUNJANGAN TIDAK TETAP<br/>
                            @foreach ($add as $key => $value)
                                {{ $arr_abjad[$key] }}. {{ $value->nama }}@if($key != count($arr_abjad)-1)<br/>@endif
                            @endforeach
                        </td>
                        @php
                        $where = ['nik'=>$payroll->nik, 'tipe'=>'add', 'bulan'=>$payroll->bulan, 'tahun'=>$payroll->tahun];
                        $_add = App\Models\TrxPayrollAddsubLog::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                        $_total_tunjangan = $_add->sum('angka');
                        @endphp
                        <td align="right">&nbsp;<br/>
                            @foreach ($_add as $key => $value)
                                {{ number_format($value->angka, 0) }}@if($key != count($_add)-1)<br/>@endif
                            @endforeach
                        </td>
                        <td></td>
                        <td></td>
                        <td>TOTAL DITERIMA = (A-B)</td>
                        <td></td>
                        <td align="right">{{ number_format($payroll->gapok + $total_tunjangan + $_total_tunjangan - $total_potongan, 0) }}</td>
					</tr>
					<tr>
                        <td>Total Tunjangan Tidak Tetap (A.3)</td>
                        <td></td>
                        <td align="right">{{ number_format($_total_tunjangan, 0) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
					</tr>
					<tr>
                        <td>TAKE HOME PAY (A)</td>
                        <td></td>
                        <td align="right">{{ number_format($payroll->gapok + $total_tunjangan + $_total_tunjangan, 0) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
