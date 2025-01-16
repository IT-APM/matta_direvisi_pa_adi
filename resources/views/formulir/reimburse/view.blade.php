@extends('layouts.simple.master')

@section('title', 'Formulir Reimbursement')

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
<li class="breadcrumb-item active">Reimbursement</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>Diajukan Oleh</h5>
                </div>
                <div class="card-header">
                    <a href="{{ asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg') }}"
                        target="_blank"><img class="img-thumbnail" width="20%"
                            src="{{ asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg') }}"
                            alt="user"></a>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Nama</td>
                                <td width="70%">{{ $data['nama'] }}</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>{{ $data['nik'] }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>{{ $data['jabatan'] }}</td>
                            </tr>
                            <tr>
                                <td>Direktorat / Divisi / Departemen</td>
                                <td>{{ $data['unit_kerja'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-6 notification">
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
                                <h6>{{ $_history->_id_status->status }}<span class="dot-notification"></span>
                                </h6>
                                <p class="f-light">Oleh {{ $_history->_nik->nama }} / {{ $_history->nik_pic }}
                                </p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-header">
                    <h5>Resume</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="40%">Pengajuan Ke</td>
                                <td width="60%">{{ $data['ke'] }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Pengajuan</td>
                                <td>{{ $trxM->created_at }}</td>
                            </tr>
                            <tr>
                                <td>Tahun</td>
                                <td>{{ $trxM->periode }}</td>
                            </tr>
                            @if($trxM->plafon>0)
                            <tr>
                                <td>Plafon</td>
                                <td>Ada</td>
                            </tr>
                            <tr>
                                <td>Jumlah Plafon</td>
                                <td>Rp. {{ number_format($trxM->plafon, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Plafon Telah Diambil</td>
                                <td>Rp. {{ number_format($trxM->plafon_diambil, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Sisa Plafon</td>
                                <td>Rp. {{ number_format($trxM->sisa_plafon_1, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td>Nominal Klaim Pada Pengajuan ini</td>
                                <td>Rp. {{ number_format($trxM->klaim_kesehatan, 2) }} (Kesehatan)
                                    <br />Rp. {{ number_format($trxM->klaim_non_kesehatan, 2) }} (Non Kesehatan)
                                </td>
                            </tr>
                            @if($trxM->plafon>0)
                            <tr>
                                <td>Saldo</td>
                                <td>Rp. {{ number_format($trxM->sisa_plafon_2, 2) }} (Sisa Plafon - Klaim Kesehatan)
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>Lampiran</td>
                                <td>
                                    @foreach ($lampiran as $value)
                                    <a href="{{ asset('assets/reimburse/'.$value->filename) }}" target="_blank">{{ $value->_id_lampiran->lampiran }}</a><br />
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>{{ $trxM->catatan }}</td>
                            </tr>
                            @php
                                $where = ['id_trx' => $trxM->id, 'nik_pic' => App\Models\MtrUtility::find(12)->informasi];
                                $info = App\Models\TrxReimburseLog::where($where)->orderBy('id', 'desc')->first();
                            @endphp
                            <tr>
                                <td>Catatan Oleh HCO</td>
                                <td>@if(!empty($info)) {{ $info->keterangan }} @endif</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
    <div class="col-sm-12 col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5>Detail Pasien</h5>
            </div>
            <div class="card-body typography">
                <!-- start -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <h6>Nama Pasien</h6>
                                </th>
                                <th>
                                    <h6>Hubungan Dengan Karyawan</h6>
                                </th>
                                <th>
                                    <h6>Layanan</h6>
                                </th>
                                <th>
                                    <h6>Tanggal Layanan</h6>
                                </th>
                                <th>
                                    <h6>Nominal Klaim</h6>
                                </th>
                            </tr>
                        </thead>
                        @foreach ($trxD as $value)
                        <tr>
                            <td>{{ $value->nama_pasien }}</td>
                            <td>{{ $value->_id_hubungan->hubungan }}</td>
                            <td>{{ $value->_id_layanan->layanan }}</td>
                            <td>@if(!empty($value->tanggal)) {{ \Carbon\Carbon::parse($value->tanggal)->format('d-m-Y') }} @endif</td>
                            <td>Rp. {{ number_format($value->jumlah_klaim, 2) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <!-- finish -->
                <br /><a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
            </div>
        </div>
    </div>
    <!--  -->
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection