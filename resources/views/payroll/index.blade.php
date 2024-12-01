@extends('layouts.simple.master')

@section('title', 'Payroll')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
@endsection

@section('style')
<style>
    button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Payroll Transaction</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Payroll</li>
<li class="breadcrumb-item active">Payroll Transaction</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (count($errors) > 0)
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <h6>Upload File is ERROR</h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form action="{{ route('payroll.upload_payroll') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to upload this data?');">
                    @csrf
                    <input type="hidden" id="jenis" name="jenis" value="trx">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <input name="template" id="template" class="form-control" type="file" required="">
                            <label class="form-label">Maksimal 2 MB dengan format xls/xlsx</label>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button>
                        </div>
                    </div>
                    </form>
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="pills-semua-tab" data-bs-toggle="pill" href="#pills-semua" role="tab" aria-controls="pills-semua" aria-selected="true">Semua</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-potongan-tab" data-bs-toggle="pill" href="#pills-potongan" role="tab" aria-controls="pills-potongan" aria-selected="true">Potongan</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-bpjstk-tab" data-bs-toggle="pill" href="#pills-bpjstk" role="tab" aria-controls="pills-bpjstk" aria-selected="true">BPJS TK</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-bpjskes-tab" data-bs-toggle="pill" href="#pills-bpjskes" role="tab" aria-controls="pills-bpjskes" aria-selected="true">BPJS Kesehatan</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-kerja-tab" data-bs-toggle="pill" href="#pills-kerja" role="tab" aria-controls="pills-kerja" aria-selected="true">Kertas Kerja</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        {{-- Semua --}}
                        <div class="tab-pane fade show active" id="pills-semua" role="tabpanel" aria-labelledby="pills-semua-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>Gaji Pokok</th>
                                                @php
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [1,2,3,4,5];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($add as $data)
                                                <th>{{ $data->nama }}</th>
                                                @endforeach
                                                <th>Total Tunjangan</th>
                                                <th>Upah</th>
                                                @php
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [6,7,8,9];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($add as $data)
                                                <th>{{ $data->nama }}</th>
                                                @endforeach
                                                <th>Total Upah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($payroll as $data)
                                            @php
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [1,2,3,4,5];
                                                $add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_tunjangan = $add->sum('angka');
                                                $arr_id = [6,7,8,9];
                                                $_add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $_total_tunjangan = $_add->sum('angka');
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->_nik->nama }}</td>
                                                <td>{{ $data->nik }}</td>
                                                <td>{{ $data->bulan }}</td>
                                                <td>{{ $data->tahun }}</td>
                                                <td class="text-end">{{ number_format($data->gapok, 0) }}</td>
                                                {{--  --}}
                                                @foreach ($add as $_data)
                                                <td class="text-end">{{ number_format($_data->angka, 0) }}</td>
                                                @endforeach
                                                {{-- total_tunjangan --}}
                                                <td class="text-end">{{ number_format($total_tunjangan, 0) }}</td>
                                                {{-- upah --}}
                                                <td class="text-end">{{ number_format($data->gapok + $total_tunjangan, 0) }}</td>
                                                {{--  --}}
                                                @foreach ($_add as $_data)
                                                <td class="text-end">{{ number_format($_data->angka, 0) }}</td>
                                                @endforeach
                                                {{-- total upah --}}
                                                <td class="text-end">{{ number_format($data->gapok + $total_tunjangan + $_total_tunjangan, 0) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- potongan --}}
                        <div class="tab-pane fade" id="pills-potongan" role="tabpanel" aria-labelledby="pills-potongan-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable2">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>Total Upah</th>
                                                @php
                                                    $where = ['tipe'=>'sub'];
                                                    $arr_id = [13,14,15,16,17,18,19,20];
                                                    $sub = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [10,11];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($sub as $data)
                                                <th>{{ $data->nama }}</th>
                                                @endforeach
                                                <th>Jumlah Potongan</th>
                                                @foreach ($add as $data)
                                                <th>{{ $data->nama }}</th>
                                                @endforeach
                                                <th>THP Ditransfer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($payroll as $data)
                                            @php
                                                // 
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [1,2,3,4,5];
                                                $add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_tunjangan = $add->sum('angka');
                                                $arr_id = [6,7,8,9];
                                                $_add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $_total_tunjangan = $_add->sum('angka');
                                                // 
                                                $where = ['nik'=>$data->nik, 'tipe'=>'sub', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [13,14,15,16,17,18,19,20];
                                                $sub = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_potongan = $sub->sum('angka');
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [10,11];
                                                $add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_add = $add->sum('angka');
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->_nik->nama }}</td>
                                                <td>{{ $data->nik }}</td>
                                                <td>{{ $data->bulan }}</td>
                                                <td>{{ $data->tahun }}</td>
                                                {{-- total upah --}}
                                                <td class="text-end">{{ number_format($data->gapok + $total_tunjangan + $_total_tunjangan, 0) }}</td>
                                                @foreach ($sub as $_data)
                                                <td class="text-end">{{ number_format($_data->angka, 0) }}</td>
                                                @endforeach
                                                {{-- total_potongan --}}
                                                <td class="text-end">{{ number_format($total_potongan, 0) }}</td>
                                                @foreach ($add as $_data)
                                                <td class="text-end">{{ number_format($_data->angka, 0) }}</td>
                                                @endforeach
                                                {{-- thp ditransfer --}}
                                                <td class="text-end">{{ number_format($data->gapok + $total_tunjangan + $_total_tunjangan - $total_potongan + $total_add, 0) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- bpjstk --}}
                        <div class="tab-pane fade" id="pills-bpjstk" role="tabpanel" aria-labelledby="pills-bpjstk-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable3">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>Upah Sekarang</th>
                                                <th>Upah Maksimum</th>
                                                <th>Upah Perhitungan</th>
                                                @php
                                                    $arr = App\Models\MtrPayrollBpjstk::orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($arr as $_arr)
                                                <th>{{ $_arr->nama }}<br/>{{ $_arr->persen }}%</th>
                                                @endforeach
                                                <th>Total Iuran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($payroll as $data)
                                            @php
                                                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $bpjstk = App\Models\TrxPayrollBpjstk::where($where)->orderBy('id_bpjstk', 'asc')->get();
                                                $potongan = 0;
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->_nik->nama }}</td>
                                                <td>{{ $data->nik }}</td>
                                                <td>{{ $data->bulan }}</td>
                                                <td>{{ $data->tahun }}</td>
                                                <td class="text-end">{{ number_format($bpjstk[0]->upah, 0) }}</td>
                                                <td class="text-end">{{ number_format($bpjstk[0]->maksimal, 0) }}</td>
                                                <td class="text-end">{{ number_format($bpjstk[0]->angka, 0) }}</td>
                                                @foreach ($bpjstk as $_data)
                                                @php
                                                    $angka = round($_data->angka*$_data->persen/100, 0);
                                                    $potongan += $angka;
                                                @endphp
                                                <td class="text-end">{{ number_format($angka, 0) }}</td>
                                                @endforeach
                                                <td class="text-end">{{ number_format($potongan, 0) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- bpjskes --}}
                        <div class="tab-pane fade" id="pills-bpjskes" role="tabpanel" aria-labelledby="pills-bpjskes-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable4">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>Upah Sekarang</th>
                                                <th>Upah Maksimum</th>
                                                <th>Upah Perhitungan</th>
                                                @php
                                                    $arr = App\Models\MtrPayrollBpjskes::orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($arr as $_arr)
                                                <th>{{ $_arr->nama }}<br/>{{ $_arr->persen }}%</th>
                                                @endforeach
                                                <th>Total Iuran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($payroll as $data)
                                            @php
                                                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $bpjskes = App\Models\TrxPayrollBpjskes::where($where)->orderBy('id_bpjskes', 'asc')->get();
                                                $potongan = 0;
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->_nik->nama }}</td>
                                                <td>{{ $data->nik }}</td>
                                                <td>{{ $data->bulan }}</td>
                                                <td>{{ $data->tahun }}</td>
                                                <td class="text-end">{{ number_format($bpjskes[0]->upah, 0) }}</td>
                                                <td class="text-end">{{ number_format($bpjskes[0]->maksimal, 0) }}</td>
                                                <td class="text-end">{{ number_format($bpjskes[0]->angka, 0) }}</td>
                                                @foreach ($bpjskes as $_data)
                                                @php
                                                    $angka = round($_data->angka*$_data->persen/100, 0);
                                                    $potongan += $angka;
                                                @endphp
                                                <td class="text-end">{{ number_format($angka, 0) }}</td>
                                                @endforeach
                                                <td class="text-end">{{ number_format($potongan, 0) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- kertas kerja --}}
                        <div class="tab-pane fade" id="pills-kerja" role="tabpanel" aria-labelledby="pills-kerja-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable5">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Direktorat</th>
                                                <th>Divisi</th>
                                                <th>Departemen</th>
                                                <th>Status Karyawan</th>
                                                <th>Jabatan</th>
                                                <th>Level</th>
                                                <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>Gaji Pokok</th>
                                                @php
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [1,2,3,4,5];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($add as $data)
                                                <th>{{ $data->nama }}</th>
                                                @endforeach
                                                <th>Total Tunjangan</th>
                                                <th>Upah</th>
                                                @php
                                                    $where = ['tipe'=>'add'];
                                                    $arr_id = [6,7,8,9];
                                                    $add = App\Models\MtrPayrollAddsub::where($where)->whereIn('id', $arr_id)->orderBy('id', 'asc')->get();
                                                @endphp
                                                @foreach ($add as $data)
                                                <th>{{ $data->nama }}</th>
                                                @endforeach
                                                <th>Total Upah</th>
                                                <th>No KPJ (BPJSTK)</th>
                                                <th>No JKN (BPJSKS)</th>
                                                <th>THP Dilaporkan BPJSTK</th>
                                                <th>THP Dilaporkan BPJSKS</th>
                                                <th>Iuran BPJSTK</th>
                                                <th>Iuran BPJSKS</th>
                                                <th>Total Iuran BPJS/ASTEK</th>
                                                <th>Kehamilan</th>
                                                <th>Persalinan</th>
                                                <th>Kacamata</th>
                                                <th>Medical Reimburse</th>
                                                <th>Total Medical Cost</th>
                                                <th>Overtime</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($payroll as $data)
                                            @php
                                                $nik_property = json_decode($data->nik_property, true);
                                                $where = ['nik'=>$data->nik, 'tipe'=>'add', 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $arr_id = [1,2,3,4,5];
                                                $add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $total_tunjangan = $add->sum('angka');
                                                $arr_id = [6,7,8,9];
                                                $_add = App\Models\TrxPayrollAddsub::where($where)->whereIn('id_addsub', $arr_id)->orderBy('id_addsub', 'asc')->get();
                                                $_total_tunjangan = $_add->sum('angka');
                                                $where = ['nik'=>$data->nik];
                                                $mtr = App\Models\MtrPayroll::where($where)->first();
                                                $where = ['nik'=>$data->nik, 'bulan'=>$data->bulan, 'tahun'=>$data->tahun];
                                                $bpjstk = App\Models\TrxPayrollBpjstk::where($where)->orderBy('id', 'asc')->get();
                                                $bpjsks = App\Models\TrxPayrollBpjskes::where($where)->orderBy('id', 'asc')->get();
                                                $pot_bpjstk = 0;
                                                $pot_bpjsks = 0;
                                                // 
                                                // $date_from = '2024-10-01';
                                                // $date_to = '2024-10-31';
                                                // kesehatan data bulan berjalan
                                                $date_from = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->startOfMonth()->format('Y-m-d');
                                                $date_to = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->endOfMonth()->format('Y-m-d');
                                                $total_kesehatan = 0;
                                                // 4 = Kehamilan
                                                $kehamilan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.created_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (4)"))[0]->jumlah_klaim;
                                                $total_kesehatan += $kehamilan;
                                                // 5,6 = Persalinan-Normal dan Persalinan-Caesar
                                                $persalinan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.created_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (5,6)"))[0]->jumlah_klaim;
                                                $total_kesehatan += $persalinan;
                                                // 2,3 = Kacamata-Frame dan Kacamata-Lensa
                                                $kacamata = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.created_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (2,3)"))[0]->jumlah_klaim;
                                                $total_kesehatan += $kacamata;
                                                // 1 = Kesehatan
                                                $kesehatan = DB::select(DB::raw("SELECT CASE WHEN SUM(a.jumlah_klaim) IS NULL THEN 0 ELSE SUM(a.jumlah_klaim) END AS jumlah_klaim FROM trx_tbl_reimburse_detail AS a LEFT JOIN trx_tbl_reimburse_master AS b ON a.id_reimburse=b.id WHERE b.nik_karyawan='".$data->nik."' AND DATE(b.created_at) BETWEEN '".$date_from."' AND '".$date_to."' AND b.status_pengajuan=2 AND a.id_layanan IN (1)"))[0]->jumlah_klaim;
                                                $total_kesehatan += $kesehatan;
                                                // overtime data bulan lalu
                                                $date_from = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->subMonth()->startOfMonth()->format('Y-m-d');
                                                $date_to = Carbon\Carbon::createFromFormat('Y-m-d', "{$data->tahun}-{$data->bulan}-01")->subMonth()->endOfMonth()->format('Y-m-d');
                                                $where = ['ot_maker_by'=>$data->nik, 'ot_approver_status'=>'Approve'];
                                                // $overtime = App\Models\Overtime::where($where)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy('id', 'asc')->get();
                                                $overtime = App\Models\Overtime::select(DB::raw('SUM(ROUND(ot_length_rp_adj,-3)) as ot_length_rp_adj'))->where($where)->whereBetween(DB::raw('DATE(ot_start_at)'), [$date_from, $date_to])->orderBy( 'ot_start_at', 'desc' )->groupBy('ot_maker_by')->get();
                                                $total_overtime = $overtime->sum('ot_length_rp_adj');
                                                // dd($total_overtime);
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $data->_nik->nama }}</td>
                                                <td>{{ $data->nik }}</td>
                                                <td>{{ $nik_property['divisi'] }}</td>
                                                <td>{{ $nik_property['dep'] }}</td>
                                                <td>{{ $nik_property['sub_dep'] }}</td>
                                                <td>{{ $nik_property['status_karyawan'] }}</td>
                                                <td>{{ $nik_property['jabatan'] }}</td>
                                                <td>{{ $nik_property['level'] }}</td>
                                                <td>{{ $data->bulan }}</td>
                                                <td>{{ $data->tahun }}</td>
                                                <td class="text-end">{{ number_format($data->gapok, 0) }}</td>
                                                {{--  --}}
                                                @foreach ($add as $_data)
                                                <td class="text-end">{{ number_format($_data->angka, 0) }}</td>
                                                @endforeach
                                                {{-- total_tunjangan --}}
                                                <td class="text-end">{{ number_format($total_tunjangan, 0) }}</td>
                                                {{-- upah --}}
                                                <td class="text-end">{{ number_format($data->gapok + $total_tunjangan, 0) }}</td>
                                                {{--  --}}
                                                @foreach ($_add as $_data)
                                                <td class="text-end">{{ number_format($_data->angka, 0) }}</td>
                                                @endforeach
                                                {{-- total upah --}}
                                                <td class="text-end">{{ number_format($data->gapok + $total_tunjangan + $_total_tunjangan, 0) }}</td>
                                                <td>{{ $mtr->no_kpj }}</td>
                                                <td>{{ $mtr->no_jkn }}</td>
                                                <td class="text-end">{{ number_format($bpjstk[0]->angka, 0) }}</td>
                                                <td class="text-end">{{ number_format($bpjsks[0]->angka, 0) }}</td>
                                                {{--  --}}
                                                @foreach ($bpjstk as $_data)
                                                @php
                                                    $angka = round($_data->angka*$_data->persen/100, 0);
                                                    $pot_bpjstk += $angka;
                                                @endphp
                                                @endforeach
                                                <td class="text-end">{{ number_format($pot_bpjstk, 0) }}</td>
                                                {{--  --}}
                                                @foreach ($bpjsks as $_data)
                                                @php
                                                    $angka = round($_data->angka*$_data->persen/100, 0);
                                                    $pot_bpjsks += $angka;
                                                @endphp
                                                @endforeach
                                                <td class="text-end">{{ number_format($pot_bpjsks, 0) }}</td>
                                                {{--  --}}
                                                <td class="text-end">{{ number_format($pot_bpjstk + $pot_bpjsks, 0) }}</td>
                                                <td class="text-end">{{ number_format($kehamilan, 0) }}</td>
                                                <td class="text-end">{{ number_format($persalinan, 0) }}</td>
                                                <td class="text-end">{{ number_format($kacamata, 0) }}</td>
                                                <td class="text-end">{{ number_format($kesehatan, 0) }}</td>
                                                <td class="text-end">{{ number_format($total_kesehatan, 0) }}</td>
                                                <td class="text-end">{{ number_format($total_overtime, 0) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{--  --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
<script type="text/javascript">
// semua
$('#_datatable1').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
// potongan
$('#_datatable2').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
// bpjstk
$('#_datatable3').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
// bpjskes
$('#_datatable4').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
// kertas kerja
$('#_datatable5').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
</script>
@endsection