@extends('layouts.simple.master')

@section('title', 'Recruitment')

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
<h3>List Of Applicant</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">List Of Applicant</li>
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
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="pills-psychotest-tab" data-bs-toggle="pill"
                                href="#pills-psychotest" role="tab" aria-controls="pills-psychotest" aria-selected="false">Psychotest</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-interview-tab"
                                data-bs-toggle="pill" href="#pills-interview" role="tab" aria-controls="pills-interview" aria-selected="true">Interview</a></li>
                    </ul>
                    {{--  --}}
                    <div class="tab-content" id="pills-warningtabContent">
                        <div class="tab-pane fade show active" id="pills-psychotest" role="tabpanel" aria-labelledby="pills-psychotest-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive">
                                    <table class="display" id="_datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>#ID</th>
                                                <th>Action</th>
                                                <th>&nbsp;Hasil&nbsp;</th>
                                                <th>Posisi</th>
                                                <th>Nama</th>
                                                {{-- <th>Email</th> --}}
                                                <th>CV</th>
                                                <th>Check_in</th>
                                                <th>Check_out</th>
                                                <th>File</th>
                                                <th>Status</th>
                                                <th>Created_at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($trx as $data)
                                            @php
                                                $recruitment = App\Models\TrxKebutuhanMaster::find($data->id_kebutuhan);
                                                $nomor = $data->id_kebutuhan.'/'.$data->id;
                                                $where = ['id_identitas' => $data->id, 'jenis' => 4];
                                                $soal = App\Models\TrxRecruitmentPsychotest::where($where)->get();
                                                // cek apakah sudah ada soal psikotes
                                                $check1 = App\Models\TrxRecruitmentPsychotest::where(['id_identitas' => $data->id])->exists();
                                                // cek apakah masih ada yang belum selesai
                                                $check2 = App\Models\TrxRecruitmentPsychotest::where(['id_identitas' => $data->id])->whereNull('check_out')->exists();
                                                // jika ada soal dan sudah selesai maka tampilkan hasilnya
                                                $check = $check1 && !$check2;
                                                $log = App\Models\TrxRecruitmentLog::where(['id_identitas'=>$data->id])->orderBy('id', 'desc')->first();
                                                // dd($log);
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $nomor }}</td>
                                                <td>
                                                    <a href="{{ route('recruitment.applicant_process', [Crypt::encrypt($data->id)]) }}" data-toggle="tooltip" title='Process'><i class="icon-pencil-alt"></i></a>
                                                    <br/><a href="{{ route('recruitment.applicant_item', [Crypt::encrypt($data->id)]) }}" data-toggle="tooltip" title='Form' target="_blank"><i class="fa fa-file-text-o" style="color:green"></i></a>
                                                    &nbsp;<a href="{{ route('recruitment.psychotest', [Crypt::encrypt(array($data->id, 'psychotest'))]) }}" data-toggle="tooltip" title='Psychotest' target="_blank"><i class="fa fa-file-text-o" style="color:green"></i></a>
                                                </td>
                                                <td>
                                                    {{-- jika ada soal dan sudah selesai maka tampilkan hasilnya --}}
                                                    @if($check)
                                                    <a href="{{ route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,1])]) }}" target="_blank" data-toggle="tooltip" title="EPPS"><i class="fa fa-file-text-o"></i></a>
                                                    &nbsp;<a href="{{ route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,2])]) }}" target="_blank" data-toggle="tooltip" title="PAPI Kostick"><i class="fa fa-file-text-o"></i></a>
                                                    &nbsp;<a href="{{ route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,3])]) }}" target="_blank" data-toggle="tooltip" title="DISC"><i class="fa fa-file-text-o"></i></a>
                                                    @if(!empty($soal[0]->jawaban1))<br/><a href="{{ asset('assets/recruitment/upload/'.$soal[0]->jawaban1) }}" target="_blank" data-toggle="tooltip" title="Pohon"><i class="fa fa-file-image-o"></i></a>@endif
                                                    @if(!empty($soal[1]->jawaban1))&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$soal[1]->jawaban1) }}" target="_blank" data-toggle="tooltip" title="Manusia"><i class="fa fa-file-image-o"></i></a>@endif
                                                    @endif
                                                </td>
                                                <td>{{ $recruitment->_pekerjaan->pekerjaan }}</td>
                                                <td>{{ $data->nama }}</td>
                                                {{-- <td>{{ $data->email }}</td> --}}
                                                <td><a href="{{ asset('assets/recruitment/upload/'.$data->cv) }}" target="_blank" data-toggle="tooltip" title="File CV"><i class="fa fa-file-pdf-o"></i></a></td>
                                                <td>{{ $data->check_in }}</td>
                                                <td>{{ $data->check_out }}</td>
                                                <td>
                                                    @if(!empty($data->interpretasi))<a href="{{ asset('assets/recruitment/upload/'.$data->interpretasi) }}" target="_blank" data-toggle="tooltip" title="File Interpretasi"><i class="fa fa-file-text-o"></i>@endif
                                                    @if(!empty($data->negosiasi))&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$data->negosiasi) }}" target="_blank" data-toggle="tooltip" title="File Negosiasi"><i class="fa fa-file-text-o"></i>@endif
                                                </td>
                                                <td>@if(!empty($log)){{ $log->_status->status }}@endif</td>
                                                <td>{{ $data->created_at }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{--  --}}
                        <div class="tab-pane fade" id="pills-interview" role="tabpanel" aria-labelledby="pills-interview-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive">
                                    <table class="display" id="_datatable1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>#ID</th>
                                                <th>Action</th>
                                                <th>Tipe</th>
                                                <th>Nama</th>
                                                {{-- <th>Email</th> --}}
                                                <th>Pewawancara</th>
                                                <th>Metode</th>
                                                <th>Check_in</th>
                                                <th>Check_out</th>
                                                <th>File</th>
                                                <th>Status</th>
                                                <th>Created_at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($trx1 as $data)
                                            @php
                                                $identitas = App\Models\TrxRecruitmentIdentitas::find($data->id_identitas);
                                                $nomor = $identitas->id_kebutuhan.'/'.$identitas->id;
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $nomor }}</td>
                                                <td><a href="{{ route('recruitment.interview_item', [Crypt::encrypt($data->id)]) }}" data-toggle="tooltip" title='Process'><i class="icon-pencil-alt"></i></a></td>
                                                <td class="text-uppercase">{{ $data->jenis }}</td>
                                                <td>{{ $identitas->nama }}</td>
                                                {{-- <td>{{ $identitas->email }}</td> --}}
                                                <td>
                                                    @foreach(explode(';', $data->interviewer) as $_data)
                                                        @php
                                                        $karyawan = App\Models\Karyawan::where(['nik'=>$_data])->first();
                                                        @endphp
                                                        {{ $karyawan->nama }}/{{ $_data }}<br/>
                                                    @endforeach
                                                </td>
                                                <td>{{ $data->metode }}</td>
                                                <td>{{ $data->check_in }}</td>
                                                <td>{{ $data->check_out }}</td>
                                                <td>@if(!empty($data->file_interview))<a href="{{ asset('assets/recruitment/upload/'.$data->file_interview) }}" target="_blank" data-toggle="tooltip" title="File Interview"><i class="fa fa-file-text-o"></i>@endif</td>
                                                <td>{{ $data->_status->status }}</td>
                                                <td>{{ $data->created_at }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                    </div>
                    {{--  --}}
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
$('#_datatable').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
// 
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
</script>
@endsection