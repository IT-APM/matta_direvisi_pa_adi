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
                        <li class="nav-item"><a class="nav-link active" id="pills-psychotest-tab" data-bs-toggle="pill" href="#pills-psychotest" role="tab" aria-controls="pills-psychotest" aria-selected="false">Psychotest</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-interview-tab" data-bs-toggle="pill" href="#pills-interview" role="tab" aria-controls="pills-interview" aria-selected="true">Interview</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-negotiation-tab" data-bs-toggle="pill" href="#pills-negotiation" role="tab" aria-controls="pills-negotiation" aria-selected="true">Negotiation</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        {{-- Psychotest --}}
                        <div class="tab-pane fade show active" id="pills-psychotest" role="tabpanel" aria-labelledby="pills-psychotest-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive text-nowrap">
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
                                                <th>Interpretasi</th>
                                                <th>Upload</th>
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
                                                $pohon = App\Models\TrxRecruitmentPsychotest::where($where)->where('nomor', '>', 0)->get();
                                                $where = ['id_identitas' => $data->id, 'jenis' => 5];
                                                $manusia = App\Models\TrxRecruitmentPsychotest::where($where)->where('nomor', '>', 0)->get();
                                                // cek apakah sudah ada soal psikotes
                                                $check1 = App\Models\TrxRecruitmentPsychotest::where(['id_identitas' => $data->id])->exists();
                                                // cek apakah masih ada yang belum selesai
                                                $check2 = App\Models\TrxRecruitmentPsychotest::where(['id_identitas' => $data->id])->whereNull('check_out')->exists();
                                                // jika ada soal dan sudah selesai maka tampilkan hasilnya
                                                $check = $check1 && !$check2;
                                                // $status = App\Models\TrxRecruitmentIdentitas::find($data->id);
                                                $log = App\Models\TrxRecruitmentLog::where(['id_identitas'=>$data->id])->pluck('id_status')->toArray();
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
                                                    @if(!empty($pohon[0]->jawaban1))<br/><a href="{{ asset('assets/recruitment/upload/'.$pohon[0]->jawaban1) }}" target="_blank" data-toggle="tooltip" title="Pohon"><i class="fa fa-file-image-o"></i></a>@endif
                                                    @if(!empty($manusia[0]->jawaban1))&nbsp;<a href="{{ asset('assets/recruitment/upload/'.$manusia[0]->jawaban1) }}" target="_blank" data-toggle="tooltip" title="Manusia"><i class="fa fa-file-image-o"></i></a>@endif
                                                    @endif
                                                </td>
                                                <td>{{ $recruitment->_pekerjaan->pekerjaan }}</td>
                                                <td>{{ $data->nama }}</td>
                                                {{-- <td>{{ $data->email }}</td> --}}
                                                <td><a href="{{ asset('assets/recruitment/upload/'.$data->cv) }}" target="_blank" data-toggle="tooltip" title="File CV"><i class="fa fa-file-pdf-o"></i></a></td>
                                                <td>{{ $data->check_in }}</td>
                                                <td>{{ $data->check_out }}</td>
                                                <td>
                                                    {{-- 16=Interpretation --}}
                                                    @if(in_array(16, $log))<a href="{{ route('recruitment.get_interpretation', [Crypt::encrypt([$data->id, 'view'])]) }}" target="_blank" data-toggle="tooltip" title="Interpretation"><i class="fa fa-file-text-o"></i></a>@endif
                                                    {{-- @if(!empty($data->interpretasi))<a href="{{ asset('assets/recruitment/upload/'.$data->interpretasi) }}" target="_blank" data-toggle="tooltip" title="File Interpretasi"><i class="fa fa-file-text-o"></i></a>@endif --}}
                                                </td>
                                                <td>
                                                    {{-- 20=COC and File Upload Done --}}
                                                    @if(in_array(20, $log))<a href="{{ route('recruitment.upload_dokumen_get', [Crypt::encrypt([$data->id, 'view'])]) }}" target="_blank" data-toggle="tooltip" title="File Upload"><i class="fa fa-file-text-o"></i></a>@endif
                                                </td>
                                                {{-- <td>{{ $data->_status->status }} @if(!empty($data->id_join)) (@foreach (explode(',', $data->id_join) as $_data){{ App\Models\MtrJoin::find($_data)->nama }}, @endforeach) @endif</td> --}}
                                                <td>{{ $data->_status->status }} @if(!empty($data->id_join))<br/>({{ implode(',', App\Models\MtrJoin::whereIn('id', explode(',', $data->id_join))->orderBy('nama', 'asc')->pluck('nama')->toArray()) }}) @endif</td>
                                                <td>{{ $data->created_at }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- Interview --}}
                        <div class="tab-pane fade" id="pills-interview" role="tabpanel" aria-labelledby="pills-interview-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive text-nowrap">
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
                                                <th>Interview</th>
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
                                                <td>@if(!empty($data->file_interview))<a href="{{ asset('assets/recruitment/upload/'.$data->file_interview) }}" target="_blank" data-toggle="tooltip" title="File Interview"><i class="fa fa-file-text-o"></i></a>@endif</td>
                                                <td>{{ $data->_status->status }}</td>
                                                <td>{{ $data->created_at }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        {{-- Negotiation --}}
                        <div class="tab-pane fade" id="pills-negotiation" role="tabpanel" aria-labelledby="pills-negotiation-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable2">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>#ID</th>
                                                <th>PIC</th>
                                                <th>THP</th>
                                                <th>Benefit</th>
                                                <th>Mulai Bergabung</th>
                                                <th>Pencatatan Kepegawaian</th>
                                                <th>Status Kepegawaian</th>
                                                <th>Masa Perjanjian Kerja</th>
                                                <th>Catatan</th>
                                                <th>Lampiran</th>
                                                <th>Status</th>
                                                <th>Created_at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=0; @endphp
                                            @foreach ($trx2 as $data)
                                            @php
                                                $identitas = App\Models\TrxRecruitmentIdentitas::find($data->id_identitas);
                                                $nomor = $identitas->id_kebutuhan.'/'.$identitas->id;
                                                $benefit_utama = App\Models\MtrRecruitmentBenefit::whereIn('id', explode(',', $data->benefit_utama))->orderBy('benefit', 'asc')->pluck('benefit')->toArray();
                                                $benefit_utama = implode('<br/>', $benefit_utama);
                                            @endphp
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $nomor }}</td>
                                                <td>{{ $data->pic }}</td>
                                                <td>Rp. {{ number_format($data->thp, 2) }}</td>
                                                <td>{!! $benefit_utama !!}
                                                    @if(!empty($data->benefit_lainnya))<br/>{{ $data->benefit_lainnya }}@endif
                                                </td>
                                                <td>{{ Carbon\Carbon::parse($data->tgl_bergabung)->format('d-m-Y') }}</td>
                                                <td>{{ $data->_catat_pegawai->pencatatan }}</td>
                                                <td>{{ $data->_status_pegawai->status }}</td>
                                                <td>{{ $data->masa_kerja }} bulan</td>
                                                <td>{{ $data->catatan }}</td>
                                                <td>@if(!empty($data->lampiran))<a href="{{ asset('assets/recruitment/upload/'.$data->lampiran) }}" target="_blank" data-toggle="tooltip" title="File Lampiran"><i class="fa fa-file-text-o"></i></a>@endif</td>
                                                <td>{{ $data->_status->status }}</td>
                                                <td>{{ $data->created_at }}</td>
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
// 
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
</script>
@endsection