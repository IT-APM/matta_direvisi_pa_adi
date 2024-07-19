@extends('layouts.simple.master')
@section('title', 'Formulir Penilaian Karyawan')

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
    </style>
@endsection

@section('breadcrumb-title')
    <h3>Formulir Penilaian Karyawan</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Formulir</li>
    <li class="breadcrumb-item active">Penilaian Karyawan</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        @if (Session::has('msg'))
                            <!-- <div class="alert alert-success">{{ Session::get('msg') }}</div> -->
                            <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                                <h6>{{ session('msg') }}</h6>
                                <button class="btn-close" type="button" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @can('hc')
                            <a href="{{ route('penilaian.create') }}" class="btn btn-primary" type="button"><i
                                    class="fa fa-plus" aria-hidden="true"></i> Add new</a> |
                            <a href="{{ route('penilaian_auto.index') }}" class="btn btn-success" type="button"><i
                                    class="fa fa-plus-circle" aria-hidden="true"></i> Auto</a> |
                            <a href="{{ route('penilaian_reminder.index') }}" class="btn btn-info" type="button"><i
                                    class="fa fa-repeat" aria-hidden="true"></i> Reminder</a> |
                        @endcan
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse_filter" aria-expanded="false" aria-controls="collapse_filter"><i
                                class="fa fa-search" aria-hidden="true"></i> Filter</button>
                        <div class="collapse" id="collapse_filter">
                            <form class="mt-2" action="{{ route('penilaian.index') }}" method="get"
                                enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="h6">Status Pengajuan</label>&nbsp;<input type="checkbox"
                                            onClick="toggle_id_status(this)" /> Check All
                                        <label class="d-block" for="id_status">
                                            @foreach ($id_status as $data)
                                                <input class="checkbox_animated" id="id_status[]" name="id_status[]"
                                                    type="checkbox" value="{{ $data->id }}"
                                                    @if (in_array($data->id, $arr_id_status)) checked @endif>
                                                {{ $data->status_penilaian }}<br />
                                            @endforeach
                                        </label>
                                        @can('hc')
                                            <label class="h6">Status Pengajuan Lengkap</label>
                                            <label class="d-block" for="id_status_lengkap">
                                                @foreach ($id_status_lengkap as $data)
                                                    <input class="checkbox_animated" id="id_status_lengkap[]"
                                                        name="id_status_lengkap[]" type="checkbox" value="{{ $data }}"
                                                        @if (in_array($data, $arr_id_status_lengkap)) checked @endif> Done By Reviewer 2 &
                                                    3<br />
                                                @endforeach
                                            </label>
                                        @endcan
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="h6">Status Perpanjangan</label>&nbsp;<input type="checkbox"
                                            onClick="toggle_is_perpanjang(this)" /> Check All
                                        <label class="d-block" for="is_perpanjang">
                                            @foreach ($is_perpanjang as $data)
                                                <input class="checkbox_animated" id="is_perpanjang[]" name="is_perpanjang[]"
                                                    type="checkbox" value="{{ $data->id }}"
                                                    @if (in_array($data->id, $arr_is_perpanjang)) checked @endif>
                                                {{ $data->informasi }}<br />
                                            @endforeach
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="h6">Perusahaan</label>&nbsp;<input type="checkbox"
                                            onClick="toggle_perusahaan(this)" /> Check All
                                        <label class="d-block" for="perusahaan">
                                            @foreach ($perusahaan as $data)
                                                <input class="checkbox_animated" id="perusahaan[]" name="perusahaan[]"
                                                    type="checkbox" value="{{ $data->id }}"
                                                    @if (in_array($data->id, $arr_perusahaan)) checked @endif>
                                                {{ $data->nama }}<br />
                                            @endforeach
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button class="btn btn-warning" id="filter" name="filter" value="true"
                                            type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filter</button> |
                                        <button class="btn btn-info" id="reset" name="reset" value="true"
                                            type="submit"><i class="fa fa-refresh" aria-hidden="true"></i>
                                            Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br /><br />
                        <div class="table-responsive">
                            <table class="display" id="_datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>#ID</th>
                                        <th>Action</th>
                                        <th>Karyawan</th>
                                        <th>NIP</th>
                                        <th>Perusahaan</th>
                                        <th>Tipe</th>
                                        <th>Tanggal Selesai PKWT</th>
                                        <th>Penilai 1</th>
                                        <th>Penilai 2</th>
                                        <th>Penilai 3</th>
                                        <th>Status Perpanjangan Penilai 1</th>
                                        <th>Status Perpanjangan Penilai 2</th>
                                        <th>Status Perpanjangan Penilai 3</th>
                                        <th>Created_at</th>
                                        <th>Timestamp Penilai 1</th>
                                        <th>Timestamp Penilai 2</th>
                                        <th>Timestamp Penilai 3</th>
                                        <th>Timestamp HCO</th>
                                        <th>Status</th>
                                        <th>Perpanjang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trx as $data)
                                        <tr>
                                            @php
                                                $_karyawan_property = json_decode($data->karyawan_property, true);
                                            @endphp
                                            <td>{{ ++$no }}</td>
                                            <td>{{ $data->id }}</td>
                                            <td>
                                                <a href="{{ route('penilaian.show', [$data->id, 'view' => 'detail']) }}"
                                                    data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                                &nbsp;<a
                                                    href="{{ route('penilaian.show', [$data->id, 'view' => 'pdf']) }}"
                                                    data-toggle="tooltip" title="PDF" target="_blank"><i
                                                        class="fa fa-file-pdf-o"></i></a>
                                                <br /><a
                                                    href="{{ route('penilaian.show', [$data->id, 'view' => 'result']) }}"
                                                    data-toggle="tooltip" title="Result" target="_blank"><i
                                                        class="fa fa-file-text-o"></i></a>
                                                @if($data->nik_penilai_1 == auth()->user()->nik || $data->nik_penilai_2 == auth()->user()->nik || $data->nik_penilai_3 == auth()->user()->nik)
                                                &nbsp;<a href="{{ route('revisi_penilaian', [$data->id]) }}"
                                                    data-toggle="tooltip" title='Revision'><i class="icon-pencil-alt"
                                                        style="color:red"></i></a>
                                                @endif
                                                @can('hc')
                                                    &nbsp;<a href="{{ route('penilaian.edit', [$data->id]) }}"
                                                        data-toggle="tooltip" title='Update'><i
                                                            class="icon-pencil-alt"></i></a>
                                                    {{-- @if ($data->id_status == 4 || $data->id_status == 8) --}}
                                                    <br /><a href="{{ route('revisi_penilaian', [$data->id]) }}"
                                                        data-toggle="tooltip" title='Revision'><i class="icon-pencil-alt"
                                                            style="color:red"></i></a>
                                                    {{-- @endif --}}
                                                @endcan
                                            </td>
                                            <td>{{ $_karyawan_property['nama'] }}</td>
                                            <td>{{ $_karyawan_property['nik'] }}</td>
                                            <td>{{ $_karyawan_property['perusahaan'] }}</td>
                                            <td>{{ $data->_tipe->tipe_penilaian }}</td>
                                            <td>
                                                @if (!empty($_karyawan_property['tanggal_berakhir']))
                                                    {{ Carbon\Carbon::parse($_karyawan_property['tanggal_berakhir'])->format('d-m-Y') }}
                                                @endif
                                            </td>
                                            <td>{{ $data->_penilai1->nama }}</td>
                                            <td>
                                                @if (!empty($data->nik_penilai_2))
                                                    {{ $data->_penilai2->nama }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($data->nik_penilai_3))
                                                    {{ $data->_penilai3->nama }}
                                                @endif
                                            </td>
                                            <!-- nik_penilai_1 -->
                                            @php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_1];
                                                $trx = App\Models\TrxPenilaianLog::where($_where)
                                                    ->whereNotNull('is_perpanjang')
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            @endphp
                                            <td>
                                                @if (!empty($trx))
                                                    @if ($trx->is_perpanjang == 1)
                                                        Diperpanjang {{ $trx->bulan }} bulan
                                                    @else
                                                        Tidak Diperpanjang
                                                    @endif
                                                @endif
                                            </td>
                                            <!-- nik_penilai_2 -->
                                            @php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_2];
                                                $trx = App\Models\TrxPenilaianLog::where($_where)
                                                    ->whereNotNull('is_perpanjang')
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            @endphp
                                            <td>
                                                @if (!empty($trx))
                                                    @if ($trx->is_perpanjang == 1)
                                                        Diperpanjang {{ $trx->bulan }} bulan
                                                    @else
                                                        Tidak Diperpanjang
                                                    @endif
                                                @endif
                                            </td>
                                            <!-- nik_penilai_3 -->
                                            @php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_3];
                                                $trx = App\Models\TrxPenilaianLog::where($_where)
                                                    ->whereNotNull('is_perpanjang')
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            @endphp
                                            <td>
                                                @if (!empty($trx))
                                                    @if ($trx->is_perpanjang == 1)
                                                        Diperpanjang {{ $trx->bulan }} bulan
                                                    @else
                                                        Tidak Diperpanjang
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ $data->created_at }}</td>
                                            <!-- timestamp nik_penilai_1 -->
                                            @php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_1];
                                                $_created_at = App\Models\TrxPenilaianLog::where($_where)
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            @endphp
                                            <td>
                                                @if (!empty($_created_at))
                                                    {{ $_created_at->created_at }}
                                                @endif
                                            </td>
                                            <!-- timestamp nik_penilai_2 -->
                                            @php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_2];
                                                $_created_at = App\Models\TrxPenilaianLog::where($_where)
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            @endphp
                                            <td>
                                                @if (!empty($_created_at))
                                                    {{ $_created_at->created_at }}
                                                @endif
                                            </td>
                                            <!-- timestamp nik_penilai_3 -->
                                            @php
                                                $_where = ['id_trx' => $data->id, 'nik_pic' => $data->nik_penilai_3];
                                                $_created_at = App\Models\TrxPenilaianLog::where($_where)
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            @endphp
                                            <td>
                                                @if (!empty($_created_at))
                                                    {{ $_created_at->created_at }}
                                                @endif
                                            </td>
                                            <!-- timestamp hco -->
                                            @php
                                                $_where = ['id_trx' => $data->id];
                                                $_created_at = App\Models\TrxPenilaianLog::where($_where)
                                                    ->whereIn('id_status', [4, 8])
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                            @endphp
                                            <td>
                                                @if (!empty($_created_at))
                                                    {{ $_created_at->created_at }}
                                                @endif
                                            </td>
                                            <td>{{ $data->_status->status_penilaian }}</td>
                                            <td>{{ $data->_perpanjang->informasi }}
                                                @if ($data->is_perpanjang == 1)
                                                    {{ $data->bulan }} Bulan
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        function toggle_id_status(source) {
            checkboxes = document.getElementsByName('id_status[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        function toggle_is_perpanjang(source) {
            checkboxes = document.getElementsByName('is_perpanjang[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        function toggle_perusahaan(source) {
            checkboxes = document.getElementsByName('perusahaan[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
        // 
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
    </script>
@endsection
