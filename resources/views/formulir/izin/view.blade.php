@extends('layouts.simple.master')

@section('title', 'Formulir Izin')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>View</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Izin</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <!-- start -->
                <div class="col-xxl-4 col-md-4 appointment-sec box-col-4">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Para Pihak</h5>
                            </div>
                            <div class="card-header">
                                <h5>Karyawan</h5><br/>
                                <a href="{{ asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg') }}"
                                    target="_blank"><img class="img-thumbnail" width="20%"
                                        src="{{ asset('assets/images/matta/karyawan/'.$karyawan['nik'].'/'.$karyawan['nik'].'.jpg') }}"
                                        alt="user"></a>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Nama</td>
                                            <td width="70%">{{ $karyawan['nama'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>NIP</td>
                                            <td>{{ $karyawan['nik'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td>{{ $karyawan['jabatan'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Direktorat / Divisi / Departemen</td>
                                            <td>{{ $karyawan['divisi'] . '/' . $karyawan['dep'] . '/' . $karyawan['sub_dep'] }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-header">
                                <h5>Layer 1</h5><br/>
                                <a href="{{ asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg') }}"
                                    target="_blank"><img class="img-thumbnail" width="20%"
                                        src="{{ asset('assets/images/matta/karyawan/'.$atasan['nik'].'/'.$atasan['nik'].'.jpg') }}"
                                        alt="user"></a>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Nama</td>
                                            <td width="70%">{{ $atasan['nama'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>NIP</td>
                                            <td>{{ $atasan['nik'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td>{{ $atasan['jabatan'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Direktorat / Divisi / Departemen</td>
                                            <td>{{ $atasan['divisi'] . '/' . $atasan['dep'] . '/' . $atasan['sub_dep'] }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @if(!empty($layer2))
                            <div class="card-header">
                                <h5>Layer 2</h5><br/>
                                <a href="{{ asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg') }}"
                                    target="_blank"><img class="img-thumbnail" width="20%"
                                        src="{{ asset('assets/images/matta/karyawan/'.$layer2['nik'].'/'.$layer2['nik'].'.jpg') }}"
                                        alt="user"></a>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Nama</td>
                                            <td width="70%">{{ $layer2['nama'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>NIP</td>
                                            <td>{{ $layer2['nik'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td>{{ $layer2['jabatan'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Direktorat / Divisi / Departemen</td>
                                            <td>{{ $layer2['divisi'] . '/' . $layer2['dep'] . '/' . $layer2['sub_dep'] }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- finish -->
                <!-- start -->
                <div class="col-xxl-4 col-md-4 appointment-sec box-col-4">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Izin</h5>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="40%">Tipe Izin</td>
                                            <td width="60%">{{ $trx->_tipe->tipe }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal</td>
                                            <td>{{ App\Http\Controllers\TrxIjinController::getTanggalIndonesia($trx->tanggal) }}</td>
                                        </tr>
                                        @if($trx->tipe == 1 || $trx->tipe == 2)
                                        <tr>
                                            <td>Jam Mulai</td>
                                            <td>{{ $trx->jam_awal }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jam Selesai</td>
                                            <td>{{ $trx->jam_akhir }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td>Alasan</td>
                                            <td>{!! $trx->alasan !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- finish -->
                <!-- start -->
                <div class="col-xxl-4 notification box-col-4">
                    <div class="card height-equal">
                        <div class="card-header card-no-border">
                            <div class="header-top">
                                <h5 class="m-0">History</h5>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <ul>
                                @foreach ($history as $_history)
                                <li class="d-flex">
                                    @if ($loop->first)
                                    <div class="activity-dot-success"></div>
                                    @elseif ($loop->last)
                                    <div class="activity-dot-warning"></div>
                                    @else
                                    <div class="activity-dot-primary"></div>
                                    @endif
                                    <div class="ms-3">
                                        <p class="d-flex justify-content-between mb-2"><span
                                                class="date-content light-background">{{ $_history->created_at }}</span>
                                        </p>
                                        <h6>{{ $_history->_status->status }}<span class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh {{ $_history->_pic->nama }} /
                                            {{ $_history->nik_pic }}</p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- finish -->
            </div>
            <a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
            <p>&nbsp;</p>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection