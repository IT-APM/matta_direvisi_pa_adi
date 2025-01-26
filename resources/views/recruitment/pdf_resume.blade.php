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

<body>
	<!-- start -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<td width="25%">&nbsp;<img src="{{ public_path('assets/images/matta/alita_new.png') }}" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">RESUME HASIL TES</div></td>
                        <td width="25%"><div class="center">No: {{ $trx->id }}</div></td>
					</tr>
				</table>
				<br/><br/>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td width="25%">Tanggal Psikotest<br/>Tanggal Interview<br/>Perusahaan</td>
						<td width="75%">: {{ Carbon\Carbon::parse($trx->check_in)->format('d-m-Y') }}<br/>
							@foreach($interview as $data)
							: {{ Carbon\Carbon::parse($data->check_in)->format('d-m-Y') }} Oleh @if($data->jenis == 'hco') HCO @elseif($data->jenis == 'user') User @elseif($data->jenis == 'user_hco') User dan HCO  @endif<br/>
							@endforeach
							: PT. ALITA PRAYA MITRA</td>
					</tr>
				</table>
				<br/><br/>
				{{-- Rekomendasi --}}
				@php $x=0 @endphp
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center">
						<th width="5%"><b>NO</b></th>
						<th width="35%"><b>NAMA</b></th>
						<th width="60%"><b>REKOMENDASI</b></th>
					</tr>
					<tr align="center">
						<td>1</td>
						<td>{{ $trx->nama }}</td>
						<td>@if($resume[$x]->jawaban == 1)DISARANKAN @else TIDAK DISARANKAN @endif</td>
					</tr>
				</table>
				<br/><br/>
				<table width="100%" border="1" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><strong>URAIAN PERILAKU</strong></td>
					</tr>
				</table>
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr align="center">
						<th width="50%">Strong Point</th>
						<th width="50%">To Be Develop</th>
					</tr>
					<tr>
						@php $x=1 @endphp
						{{-- Strong Point --}}
						<td>{!! nl2br(e($resume[$x]->jawaban)) !!}</td>
						@php $x=2 @endphp
						{{-- To Be Develop --}}
						<td>{!! nl2br(e($resume[$x]->jawaban)) !!}</td>
					</tr>
				</table>
				{{-- Kesimpulan --}}
				@php $x=3 @endphp
				<table width="100%" border="1" cellspacing="0" cellpadding="2">
					<tr>
						<td align="justify">Kesimpulan :<br/>{!! nl2br(e($resume[$x]->jawaban)) !!}</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<!-- finish -->
</body>

</html>