@extends('layouts.errors.master')
@section('title', 'Formulir Reimbursement')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4><img src="{{ asset('assets/images/matta/form-icon-25.jpg') }}" height="30" alt="Alita" />&nbsp;Formulir Reimbursement</h4>
                </div>
            </div>
        </div>
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
        <div class="col-sm-12 col-xl-6">
            <div class="card">
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
                            <tr>
                                <td>Nominal Klaim Pada Pengajuan ini</td>
                                <td>Rp. {{ number_format($trxM->klaim_kesehatan, 2) }} (Kesehatan)
                                    <br />Rp. {{ number_format($trxM->klaim_non_kesehatan, 2) }} (Non Kesehatan)
                                </td>
                            </tr>
                            <tr>
                                <td>Saldo</td>
                                <td>Rp. {{ number_format($trxM->sisa_plafon_2, 2) }} (Sisa Plafon - Klaim Kesehatan)
                                </td>
                            </tr>
                            <tr>
                                <td>Lampiran</td>
                                <td>
                                    @foreach ($lampiran as $value)
                                    <a href="{{ asset('assets/reimburse/'.$value->filename) }}"
                                        target="_blank">{{ $value->_id_lampiran->lampiran }}</a><br />
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                                <td>{{ $value->tanggal }}</td>
                                <td>Rp. {{ number_format($value->jumlah_klaim, 2) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- finish -->
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @csrf
                    <button type="submit" name="status_pengajuan" value="2" class="btn btn-success">Approve</button>
                    <button type="submit" name="status_pengajuan" value="3" class="btn btn-danger">Reject</button>
                </form>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Reimbursement
                        ini.</h6>
                    <a href="https://alita.id/"><img src="{{ asset('assets/images/matta/Alita-New-Log.png') }}"
                            height="40" alt="Alita" /></a><br />
                    <h6>&reg; PT Alita Praya Mitra - 2021</h6>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
@endsection

@section('script')

@endsection