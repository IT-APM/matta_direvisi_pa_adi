@extends('layouts.simple.master')

@section('title', 'Formulir Pengajuan Pelatihan')

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
<li class="breadcrumb-item active">Pengajuan Pelatihan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Pengaju</h5>
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
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <!-- start -->
                        <div class="card-header">
                            <h5>Peserta</h5>
                        </div>
                        @foreach ($arrPeserta as $value)
                        <div class="card-header">
                            <a href="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}"
                                target="_blank"><img class="img-thumbnail" width="20%"
                                    src="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}"
                                    alt="user"></a>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="30%">Nama</td>
                                        <td width="70%">{{ $value['nama'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td>{{ $value['nik'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td>{{ $value['jabatan'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Direktorat / Divisi / Departemen</td>
                                        <td>{{ $value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endforeach
                        <!-- end -->
                        <!-- start -->
                        <div class="card-header">
                            <h5>Atasan Peserta</h5>
                        </div>
                        @foreach ($arrAtasanPeserta as $value)
                        <div class="card-header">
                            <a href="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}"
                                target="_blank"><img class="img-thumbnail" width="20%"
                                    src="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}"
                                    alt="user"></a>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="30%">Nama</td>
                                        <td width="70%">{{ $value['nama'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td>{{ $value['nik'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td>{{ $value['jabatan'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Direktorat / Divisi / Departemen</td>
                                        <td>{{ $value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endforeach
                        <!-- end -->
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-4 notification">
                    <div class="card">
                        <!-- start -->
                        <div class="card-header">
                            <h5>History</h5>
                        </div>
                        <div class="card-body">
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
                                        <h6>{{ $_history->_status->status_pengajuan }}<span
                                                class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh {{ $_history->_pic->nama }} /
                                            {{ $_history->nik_pic }}</p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- end -->
                        <!-- start -->
                        <div class="card-header">
                            <h5>Pelatihan</h5>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="30%">Pelatihan ke</td>
                                        <td width="70%">{{ $trx->ke }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tahun</td>
                                        <td>{{ $trx->tahun }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jenis</td>
                                        <td>
                                            @foreach ($arrJenis as $value)
                                            {{ $value->jenis }}<br />
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Judul</td>
                                        <td>{{ $trx->judul }}</td>
                                    </tr>
                                    <tr>
                                        <td>Penyelenggara</td>
                                        <td>{{ $trx->penyelenggara }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Pelaksanaan</td>
                                        <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_mulai)->format('d-m-Y') }}
                                            sampai
                                            {{ Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_akhir)->format('d-m-Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tempat</td>
                                        <td>{{ $trx->tempat }}</td>
                                    </tr>
                                    <tr>
                                        <td>Biaya</td>
                                        <td>Rp. {{ number_format($trx->biaya, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Benefit</td>
                                        <td>
                                            @foreach ($arrBenefit as $value)
                                            {{ $value->benefit }}<br />
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Anggaran</td>
                                        <td>{{ $trx->_anggaran->anggaran }}</td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan</td>
                                        <td>{!! $trx->keterangan !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- end -->
                    </div>
                </div>
                <!--  -->
                <div class="col-12">
                    <div class="appointment">
                        <a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
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
@endsection