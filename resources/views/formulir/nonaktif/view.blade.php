@extends('layouts.simple.master')
@section('title', 'Formulir Karyawan Non Aktif')

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>View</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Karyawan Non Aktif</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Karyawan</h5>
                            </div>
                            <div class="card-header">
                                <a href="{{ asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg') }}"
                                    target="_blank"><img class="img-thumbnail" width="20%"
                                        src="{{ asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg') }}"
                                        alt="user"></a>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Nama</td>
                                            <td width="70%">{{ $pengaju['nama'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>NIP</td>
                                            <td>{{ $pengaju['nik'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td>{{ $pengaju['jabatan'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Direktorat / Divisi / Departemen</td>
                                            <td>{{ $pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep'] }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Non Aktif</h5>
                            </div>
                            <div class="card-body typography">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="40%">Alasan Non Aktif</td>
                                            <td width="60%">{{ $trxM->_alasan->alasan }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Bergabung</td>
                                            <td>@if(!empty($karyawan_properties->tanggal_bergabung)) {{ \Carbon\Carbon::parse($karyawan_properties->tanggal_bergabung)->format('d-m-Y') }} @endif</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Berakhir</td>
                                            <td>@if(!empty($trxM->tanggal)) {{ \Carbon\Carbon::parse($trxM->tanggal)->format('d-m-Y') }} @endif</td>
                                        </tr>
                                        <tr>
                                            <td>Keterangan</td>
                                            <td>{!! $trxM->keterangan !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Serah Terima</h5>
                            </div>
                            @foreach ($trxD as $value)
                            <div class="card-body typography">
                                @if($value->_into_ == 'di')
                                <h6>Direktorat / Divisi / Departemen</h6>
                                @elseif($value->_into_ == 'ga')
                                <h6>General Affairs</h6>
                                @elseif($value->_into_ == 'it')
                                <h6>IT</h6>
                                @elseif($value->_into_ == 'hc')
                                <h6>Human Capital</h6>
                                @elseif($value->_into_ == 'ko')
                                <h6>Koperasi Karyawan</h6>
                                @endif
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%">Obyek</td>
                                            <td width="70%">{{ $value->_obyek->obyek }}</td>
                                        </tr>
                                        <tr>
                                            <td>Keterangan</td>
                                            <td>{!! $value->keterangan !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endforeach
                        </div><a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
                    </div>
                </div>
                <!--  -->
                <div class="col-xxl-6 notification box-col-6">
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
                                        <h6>{{ $_history->_status->status }}<span class="dot-notification"></span>
                                        </h6>
                                        <p class="f-light">Oleh {{ $_history->_pic->nama }} /
                                            {{ $_history->nik_pic }}</p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection