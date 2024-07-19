@extends('layouts.simple.master')
@section('title', 'Biaya Perjalanan Dinas')

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
<h3>Biaya Perjalanan Dinas</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Biaya Perjalanan Dinas</li>
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
                    <form method="POST" action="#">
                        <div class="row">
                            @if(count($mgt)>0)
                            <div class="col-md-4">
                                <label class="form-label">Grup Manajemen</label>
                                <select class="form-select col-sm-12" id="mgt" name="mgt" required="">
                                    <option selected="" disabled="" value="0">Pilih...</option>
                                    @foreach ($mgt as $data)
                                    <option value="{{ $data->nik }}">{{ $data->_nik->nama }} / {{ $data->nik }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md-4">
                                <label class="form-label">Jenis Perjalanan Dinas</label>
                                <select class="form-select col-sm-12" id="jenis" name="jenis" required="">
                                    <option selected="" disabled="" value="">Pilih...</option>
                                    <option value="1">CA Dalam Negeri</option>
                                    <option value="2">CA Luar Negeri</option>
                                    <option value="3">Reimbursement</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <br/>
                                <input type="button" class="btn btn-primary" onclick="PilihJenis()" value="Add new" />
                            </div>
                        </div>
                    </form>
                    <!-- <a href="{{ route('bpd.create') }}" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add new</a> -->
                    <br />
                    <div class="table-responsive">
                        <table class="display" id="_datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama / NIP</th>
                                    <th>Pengajuan</th>
                                    <th>Tahun</th>
                                    <th>#ID</th>
                                    <th>Created at</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <!-- hco only -->
                                    @can('hc')<th>HCO</th>@endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trx as $data)
                                @php
                                $pengaju_property = json_decode($data['pengaju_property'], true);
                                $nik = auth()->user()->nik;
                                @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $pengaju_property['nama'] }} / {{ $pengaju_property['nik'] }}</td>
                                    <td>
                                        @if ($data->is_ca == 1 && $data->luar_dalam == 'ca_dalam') CA Dalam Negeri
                                        @elseif ($data->is_ca == 1 && $data->luar_dalam == 'ca_luar') CA Luar Negeri
                                        @elseif ($data->is_ca == 0 && $data->luar_dalam == 'reimburse') Reimbursement
                                        @endif
                                    </td>
                                    <td>{{ $data->periode }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ $data->created_at }}</td>
                                    <td>{{ $data->_status->status }}</td>
                                    <td>
                                        @php
                                        $where = ['nik' => auth()->user()->nik, 'filename' => 'emails.bpd0', 'id_trx' => $data->id];
                                        $link = App\Models\ToDoList::where($where)->orderBy('id', 'desc')->first();
                                        $where = ['filename' => 'emails.bpd0', 'id_trx' => $data->id];
                                        $link_hco = App\Models\ToDoList::where($where)->orderBy('id', 'desc')->first();
                                        @endphp
                                        @if(!empty($link))
                                        <a href="{{ $link->link }}" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @endif
                                        {{--
                                        <!-- start -->
                                        <!-- pengajuan CA -->
                                        <!-- 2 = Pengajuan CA -->
                                        <!-- 6 = Perbaikan CA oleh Pengaju -->
                                        @if(($data->status_id==2 || $data->status_id==6) && $nik==$data->admin)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'detail']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 3 = CA Diproses Admin -->
                                        @elseif($data->status_id==3 && $nik==$data->admin)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'approval_pa']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 9 = CA Disetujui L1 -->
                                        @elseif($data->status_id==9 && $nik==$data->layer2)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'approval_l2']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 11 = CA Disetujui L2 -->
                                        @elseif($data->status_id==11 && $nik==$data->hco)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'approval_hco']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 5 = CA Ditolak Admin -->
                                        @elseif($data->status_id==5 && $nik==$data->pengaju)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reject_pa']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 8 = CA Ditolak PC -->
                                        @elseif($data->status_id==8 && $nik==$data->admin)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reject_pc']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 10 = CA Ditolak L1 -->
                                        @elseif($data->status_id==10 && $nik==$data->admin)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reject_l1']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 12 = CA Ditolak L2 -->
                                        @elseif($data->status_id==12 && $nik==$data->admin)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reject_l2']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 14 = CA Ditolak HCO -->
                                        @elseif($data->status_id==14 && $nik==$data->admin)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reject_hco']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- finish -->
                                        <!-- start -->
                                        <!-- realisasi CA -->
                                        @elseif($data->status_id==13 && $nik==$data->pengaju)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @elseif($data->status_id==18 && $nik==$data->admin)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi_pa']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @elseif($data->status_id==24 && $nik==$data->layer2)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi_l2']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @elseif($data->status_id==26 && $nik==$data->hco)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi_hco']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- finish -->
                                        <!-- start -->
                                        <!-- reimburse -->
                                        <!-- 33 = Pengajuan Reimburse -->
                                        <!-- 37 = Perbaikan Reimburse oleh Pengaju -->
                                        @elseif(($data->status_id==33 || $data->status_id==37) && $nik==$data->admin)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 34 = Reimburse Diproses Admin -->
                                        @elseif($data->status_id==34 && $nik==$data->admin)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse_pa']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 40 = Reimburse Disetujui L1 -->
                                        @elseif($data->status_id==40 && $nik==$data->layer2)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse_l2']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 42 = Reimburse Disetujui L2 -->
                                        @elseif($data->status_id==42 && $nik==$data->hco)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse_hco']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 36 = Reimburse Ditolak Admin -->
                                        @elseif($data->status_id==36 && $nik==$data->pengaju)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reject_reimburse_pa']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 38 = Reimburse Disetujui PC -->
                                        @elseif($data->status_id==38 && $nik==$data->admin)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reject_reimburse_pc']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 41 = Reimburse Ditolak L1 -->
                                        @elseif($data->status_id==41 && $nik==$data->admin)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reject_reimburse_l1']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 43 = Reimburse Ditolak L2 -->
                                        @elseif($data->status_id==43 && $nik==$data->admin)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reject_reimburse_l2']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- 45 = Reimburse Ditolak HCO -->
                                        @elseif($data->status_id==45 && $nik==$data->admin)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reject_reimburse_hco']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @endif
                                        <!-- finish -->
                                        <!--  -->
                                        @if($data->is_project==1)
                                            <!-- 4 = CA Disetujui Admin -->
                                            <!-- 15 = Perbaikan CA oleh Admin -->
                                            @if(($data->status_id==4 || $data->status_id==15) && $nik==$data->koordinator)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'approval_pc']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            <!-- 7 = CA Disetujui PC -->
                                            @elseif($data->status_id==7 && $nik==$data->layer1)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'approval_l1']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            <!-- realisasi -->
                                            @elseif($data->status_id==19 && $nik==$data->koordinator)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi_pc']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            @elseif($data->status_id==22 && $nik==$data->layer1)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi_l1']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            <!-- reimburse -->
                                            <!-- 35 = Reimburse Disetujui Admin -->
                                            <!-- 46 = Perbaikan Reimburse oleh Admin -->
                                            @elseif(($data->status_id==35 || $data->status_id==46) && $nik==$data->koordinator)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse_pc']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            <!-- 38 = Reimburse Disetujui PC -->
                                            @elseif($data->status_id==38 && $nik==$data->layer1)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse_l1']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            @endif
                                        @elseif($data->is_project==0)
                                            <!-- 4 = CA Disetujui Admin -->
                                            <!-- 15 = Perbaikan CA oleh Admin -->
                                            @if(($data->status_id==4 || $data->status_id==15) && $nik==$data->layer1)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'approval_l1']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            <!-- realisasi -->
                                            @elseif($data->status_id==19 && $nik==$data->layer1)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi_l1']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            <!-- reimburse -->
                                            <!-- 35 = Reimburse Disetujui Admin -->
                                            <!-- 46 = Perbaikan Reimburse oleh Admin -->
                                            @elseif(($data->status_id==35 || $data->status_id==46) && $nik==$data->layer1)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse_l1']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            @endif
                                        @endif
                                        --}}
                                        <!--  -->
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'history']) }}"
                                            data-toggle="tooltip" title="History"><i class="icon-eye" style="color:red"></i></a>
                                        <!-- 31 = Selesai Realisasi -->
                                        <!-- 4 = CA Disetujui Admin -->
                                        <!-- 5 = CA Ditolak Admin -->
                                        <!-- 6 = Perbaikan CA oleh Pengaju -->
                                        @if($data->status_id>=4 && $data->status_id<=31 && $data->status_id<>5 && $data->status_id<>6)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'pdf']) }}"
                                            data-toggle="tooltip" title="PDF CA" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        <!-- 2 = Pengajuan CA -->
                                        <!-- 3 = CA Diproses Admin -->
                                        <!-- 5 = CA Ditolak Admin -->
                                        <!-- 6 = Perbaikan CA oleh Pengaju -->
                                        @elseif($data->status_id==2 || $data->status_id==3 || $data->status_id==5 || $data->status_id==6)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'pdf_draft']) }}"
                                            data-toggle="tooltip" title="PDF CA" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @endif
                                        <!-- 13 = CA Disetujui HCO -->
                                        <!-- 15 = Perbaikan CA oleh Admin -->
                                        <!-- 31 = Selesai Realisasi -->
                                        @if($data->status_id>13 && $data->status_id<=31 && $data->status_id<>15)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'pdf_realisasi']) }}"
                                            data-toggle="tooltip" title="PDF Realisasi" target="_blank"><i class="fa fa-file-pdf-o" style="color:red"></i></a>
                                        @endif
                                        <!-- 33 = Pengajuan Reimburse -->
                                        <!-- 34 = Reimburse Diproses Admin -->
                                        <!-- 36 = Reimburse Ditolak Admin -->
                                        <!-- 37 = Perbaikan Reimburse oleh Pengaju -->
                                        @if($data->status_id==33 || $data->status_id==34 || $data->status_id==36 || $data->status_id==37)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'pdf_draft']) }}"
                                            data-toggle="tooltip" title="PDF Reimburse" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @elseif($data->status_id>34 && $data->status_id<>36 && $data->status_id<>37)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'pdf_reimburse']) }}"
                                            data-toggle="tooltip" title="PDF Reimburse" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @endif
                                    </td>
                                    <!-- hco only -->
                                    @can('hc')
                                    <td>
										@if(!empty($link_hco))
                                        <a href="{{ $link_hco->link }}" data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
										@endif
                                        {{--
                                        <!-- pengajuan CA -->
                                        @if($data->status_id==2)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'detail']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @elseif($data->status_id==3)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'approval_pa']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @elseif($data->status_id==9)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'approval_l2']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @elseif($data->status_id==11)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'approval_hco']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- realisasi CA -->
                                        @elseif($data->status_id==13)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @elseif($data->status_id==18)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi_pa']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @elseif($data->status_id==24)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi_l2']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @elseif($data->status_id==26)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi_hco']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        <!-- reimburse -->
                                        @elseif($data->status_id==33)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @elseif($data->status_id==34)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse_pa']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @elseif($data->status_id==40)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse_l2']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @elseif($data->status_id==42)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse_hco']) }}"
                                            data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                        @endif
                                        <!--  -->
                                        @if($data->is_project==1)
                                            @if($data->status_id==4)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'approval_pc']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            @elseif($data->status_id==7)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'approval_l1']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            <!-- realisasi -->
                                            @elseif($data->status_id==19)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi_pc']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            @elseif($data->status_id==22)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi_l1']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            <!-- reimburse -->
                                            @elseif($data->status_id==35)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse_pc']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            @elseif($data->status_id==38)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse_l1']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            @endif
                                        @elseif($data->is_project==0)
                                            @if($data->status_id==4)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'approval_l1']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            <!-- realisasi -->
                                            @elseif($data->status_id==19)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'realisasi_l1']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            <!-- reimburse -->
                                            @elseif($data->status_id==35)
                                            <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'reimburse_l1']) }}"
                                                data-toggle="tooltip" title="Detail"><i class="icon-eye"></i></a>
                                            @endif
                                        @endif
                                        --}}
                                        <!--  -->
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'history']) }}"
                                            data-toggle="tooltip" title="History"><i class="icon-eye" style="color:red"></i></a>
                                        <!-- 31 = Selesai Realisasi -->
                                        <!-- 4 = CA Disetujui Admin -->
                                        <!-- 5 = CA Ditolak Admin -->
                                        <!-- 6 = Perbaikan CA oleh Pengaju -->
                                        @if($data->status_id>=4 && $data->status_id<=31 && $data->status_id<>5 && $data->status_id<>6)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'pdf']) }}"
                                            data-toggle="tooltip" title="PDF CA" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        <!-- 2 = Pengajuan CA -->
                                        <!-- 3 = CA Diproses Admin -->
                                        <!-- 5 = CA Ditolak Admin -->
                                        <!-- 6 = Perbaikan CA oleh Pengaju -->
                                        @elseif($data->status_id==2 || $data->status_id==3 || $data->status_id==5 || $data->status_id==6)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'pdf_draft']) }}"
                                            data-toggle="tooltip" title="PDF CA" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @endif
                                        <!-- 13 = CA Disetujui HCO -->
                                        <!-- 15 = Perbaikan CA oleh Admin -->
                                        <!-- 31 = Selesai Realisasi -->
                                        @if($data->status_id>13 && $data->status_id<=31 && $data->status_id<>15)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'pdf_realisasi']) }}"
                                            data-toggle="tooltip" title="PDF Realisasi" target="_blank"><i class="fa fa-file-pdf-o" style="color:red"></i></a>
                                        @endif
                                        <!-- 33 = Pengajuan Reimburse -->
                                        <!-- 34 = Reimburse Diproses Admin -->
                                        <!-- 36 = Reimburse Ditolak Admin -->
                                        <!-- 37 = Reimburse Ditolak Admin -->
                                        @if($data->status_id==33 || $data->status_id==34 || $data->status_id==36 || $data->status_id==37)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'pdf_draft']) }}"
                                            data-toggle="tooltip" title="PDF Reimburse" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @elseif($data->status_id>34 && $data->status_id<>36 && $data->status_id<>37)
                                        <a href="{{ route('bpd.show', [Crypt::encrypt($data->id), 'view'=>'pdf_reimburse']) }}"
                                            data-toggle="tooltip" title="PDF Reimburse" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                        @endif
                                    </td>
                                    @endcan
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
    // {{ route('bpd.create', ['new'=>'ca_luar']) }}
    function PilihJenis() {
        var mgt = 0;
        @if(count($mgt)>0)
        mgt = document.getElementById("mgt").value;
        @endif
        if (document.getElementById("jenis").value == 1) {
            location.href = "{{ route('bpd.create', ['new'=>'ca_dalam']) }}"+"&mgt="+mgt;
        } else if (document.getElementById("jenis").value == 2) {
            location.href = "{{ route('bpd.create', ['new'=>'ca_luar']) }}"+"&mgt="+mgt;
        } else if (document.getElementById("jenis").value == 3) {
            location.href = "{{ route('bpd.create', ['new'=>'reimburse']) }}"+"&mgt="+mgt;
        } else {
            alert('Pilih Jenis Perjalanan Dinas!');
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
