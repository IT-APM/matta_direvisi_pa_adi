@extends('layouts.simple.master')
@section('title', 'Basic DataTables')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
@endsection

@section('style')
<style>
    button.dt-button {
        float: none;
        text-align: center;
        color: black;
        background: white;
    }
/* css untuk table izin */
    .tbl-laporan {
        border-collapse: collapse;
        margin: 5px;
        width: 100%;
    }
    .tbl-laporan th,
    .tbl-laporan td {
        border: 1px solid #dee2e6;
        padding: 6px 10px;
        text-align: center;
        vertical-align: middle;
    }
    .tbl-laporan thead th {
        background-color: #f0f7f3;
        color: #0B3D2E;
        font-weight: 600;
    }
    .tbl-laporan tbody tr:hover {
        background-color: #f0faf4;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Pengingat Absensi & Surat Peringatan</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data</li>
<li class="breadcrumb-item active">Pengingat Absensi & Surat Peringatan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if (Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form action="{{ route('reminder_sp.store') }}" method="post" enctype="multipart/form-data"
                        onsubmit="return confirm('Are you sure want to do this?');">
                        @csrf
                        @if (count($trx) > 0)
                        <button class="btn btn-success" type="submit" name="submit" id="submit" value="execute"><i class="fa fa-plus-circle" aria-hidden="true"></i> Execute</button>
                        | <button class="btn btn-danger" type="submit" name="submit" id="submit" value="delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button> |
                        @endif
                        <a href="{{ route('reminder_sp.show', 0) }}" class="btn btn-primary"><i class="fa fa-history"></i> History</a>
                        <div class="mt-2 table-responsive">
                            <table class="display nowrap" id="_datatable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" onClick="toggle_status_check(this)" /></th>
                                        <th>No</th>
                                        <th>Ke</th>
                                        <th>Tanggal Awal</th>
                                        <th>Tanggal Akhir</th>
                                        <th>Working Group</th>
                                        <th>NIP</th>
                                        <th>Nama Karyawan</th>
                                        <th>Level</th>
                                        <th>Perusahaan</th>
                                        <th>Direktorat</th>
                                        <th>Divisi</th>
                                        <th>Departemen</th>
                                        <th>Email Karyawan</th>
                                        <th>Nama Layer 1</th>
                                        <th>Email Layer 1</th>
                                        <th>Jumlah Hari Kerja</th>
                                        <th>Jumlah Kehadiran</th>
                                        <th>Persentase Kehadiran</th>
                                        <th>Keterlambatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 0; @endphp
                                    @foreach ($trx as $data)
                                    @php
                                    $nik_property = json_decode($data->nik_property, true);
                                    $where = ['nik' => $nik_property['layer1']];
                                    $layer1 = App\Models\Karyawan::where($where)->first();
                                    // check history
                                    $teguran_ke = 'Pertama';
                                    $type = 'Pengingat Absensi';
                                    // check nik yang sama pada 1 bulan sebelumnya
                                    $where = ['nik' => $data->nik, 'date_from' => \Carbon\Carbon::parse($data->date_from)->modify('-1 month')->format('Y-m-d')];
                                    $trxSend = App\Models\TrxReminderSpSend::where($where)->whereNull('terlambat')->orderBy( 'date_from', 'desc' )->first();
                                    if(!empty($trxSend)){
                                        if($trxSend->type == 'reminder'){
                                            if($trxSend->number == 1){
                                                $teguran_ke = 'Kedua';
                                            }elseif($trxSend->number == 2){
                                                $teguran_ke = 'Ketiga';
                                            }elseif($trxSend->number == 3){
                                                $teguran_ke = 'Keempat';
                                            }elseif($trxSend->number == 4){
                                                $teguran_ke = 'Kelima';
                                            }elseif($trxSend->number == 5){
                                                $teguran_ke = 'Pertama';
                                                $type = 'Surat Peringatan';
                                            }
                                        }elseif($trxSend->type == 'warning'){
                                            $type = 'Surat Peringatan';
                                            if($trxSend->number == 1){
                                                $teguran_ke = 'Kedua';
                                            }elseif($trxSend->number == 2){
                                                $teguran_ke = 'Ketiga';
                                            }
                                        }
                                    }
                                    // terlambat
                                    if(!empty($data->terlambat)){
                                        $type = 'Pengingat Keterlambatan';
                                        $teguran_ke = 'Pertama';
                                        // check history
                                        // check nik yang sama pada 1 bulan sebelumnya
                                        $where = ['nik' => $data->nik, 'date_from' => \Carbon\Carbon::parse($data->date_from)->modify('-1 month')->format('Y-m-d')];
                                        $trxSend = App\Models\TrxReminderSpSend::where($where)->whereNotNull('terlambat')->orderBy( 'date_from', 'desc' )->first();
                                        if(!empty($trxSend)){
                                            if($trxSend->type == 'late'){
                                                $type = 'Pengingat Keterlambatan';
                                                $arr = ['Pertama', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam', 'Ketujuh', 'Kedelapan', 'Kesembilan', 'Kesepuluh', 'Kesebelas', 'Keduabelas'];
                                                $teguran_ke = $arr[$trxSend->number];
                                            }
                                        }
                                    }
                                    @endphp
                                    <tr @if(App\Models\Karyawan::where('nik', $data->nik)->where('is_aktif', false)->exists()) class="bg-danger" @endif>
                                        <td><input id="reminder_sp[]" name="reminder_sp[]" type="checkbox" value="{{ $data->id }}"></td>
                                        <td>{{ ++$no }}</td>
                                        <td>{{ $type }} {{ $teguran_ke }}</td>
                                        <td>{{ \Carbon\Carbon::parse($data->date_from) ->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($data->date_to) ->format('d-m-Y') }}</td>
                                        <td>{{ $nik_property['wg_name'] }}</td>
                                        <td>{{ $data->nik }}</td>
                                        <td>{{ $nik_property['nama'] }}</td>
                                        <td>{{ $nik_property['level'] }}</td>
                                        <td>{{ $nik_property['perusahaan'] }}</td>
                                        <td>{{ $nik_property['divisi'] }}</td>
                                        <td>{{ $nik_property['departemen'] }}</td>
                                        <td>{{ $nik_property['sub_departemen'] }}</td>
                                        <td>{{ $nik_property['email'] }}</td>
                                        <td>{{ $layer1->nama }}</td>
                                        <td>{{ $layer1->email }}</td>
                                        <td>{{ $data->arr_base }}</td>
                                        <td>{{ $data->arr_clock }}</td>
                                        <td>{{ round($data->persentase) }} %</td>
                                        <td>
                                            @php
                                            $str_jam_kerja = 'Toleransi Jam kerja';
                                            if($nik_property['wg_id'] > 1){
                                                $str_jam_kerja = 'Jam kerja';
                                            }
                                            if(!empty($data->terlambat)){
                                                $html = '<table class="tbl-laporan text-dark">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Hari</th>
                                                        <th>Tanggal</th>
                                                        <th>'.$str_jam_kerja.'</th>
                                                        <th>Clock-in</th>
                                                        <th>Keterlambatan</th>
                                                        <th>Izin</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                                $terlambat = json_decode($data->terlambat, true);
                                                $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                                $_no = 0;
                                                foreach ($terlambat as $_terlambat){
                                                    $str_izin = '';
                                                    if(!empty($_terlambat['izin'])){
                                                        $izin = $_terlambat['izin'][0];
                                                        $str_izin .= App\Models\IjinTipe::find($izin['tipe'])->tipe;
                                                        if($izin['tipe'] == 2){
                                                            $str_izin .= ' Jam '.$izin['jam_awal'].' s/d '.$izin['jam_akhir'];
                                                        }
                                                    }
                                                    $clockin = $_terlambat['clockin'];
                                                    if($nik_property['wg_id'] > 1){
                                                        $clockin = \Carbon\Carbon::parse($_terlambat['clockin']) ->format('H:i:s');
                                                    }
                                                    $html .= '<tr>
                                                        <th>'.++$_no.'</th>
                                                        <td>'.$hari[\Carbon\Carbon::parse($_terlambat['tanggal'])->dayOfWeek].'</td>
                                                        <td>'.\Carbon\Carbon::parse($_terlambat['tanggal']) ->format('d-m-Y').'</td>
                                                        <td>'.$clockin.'</td>
                                                        <td>'.\Carbon\Carbon::parse($_terlambat['att_timestamp']) ->format('H:i:s').'</td>
                                                        <td>'.$_terlambat['durasi'].'</td>
                                                        <td>'.$str_izin.'</td>
                                                    </tr>';
                                                }
                                                $html .= '</tbody></table>';
                                                echo $html;
                                            }
                                            @endphp
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
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
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script type="text/javascript">
    $('#_datatable').DataTable({
        // dom: 'lBfrtip',
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
            "<'row'<'col-sm-12'rt>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "lengthMenu": [
            [50, 100, 200, 500, -1],
            [50, 100, 200, 500, "All"]
        ]
    });
    // 
    function toggle_status_check(source) {
        checkboxes = document.getElementsByName('reminder_sp[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

</script>
@endsection
