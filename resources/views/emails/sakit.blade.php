@extends('layouts.errors.master')
@section('title', 'Formulir Permohonan Izin Sakit')

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
                    <h4><img src="{{ asset('assets/images/matta/form-icon-25.jpg') }}" height="30" alt="Alita" />&nbsp;Formulir Permohonan Izin Sakit</h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>DIAJUKAN OLEH</h5>
                </div>
                <div class="card-header">
                    <a href="{{ asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$data['nik'].'/'.$data['nik'].'.jpg') }}" alt="user"></a>
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
                <!--  -->
                <div class="card-header">
                    <h5>IZIN SAKIT</h5>
                </div>
                <div class="card-body typography">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Tahun Izin Sakit</td>
                                <td width="70%">{{ $trxSakit->periode_sakit }}</td>
                            </tr>
                            <tr>
                                <td>Mengajukan izin sakit pada</td>
                                <td>{{ $arrTanggal['tanggal_mulai'] }}<br />sampai dengan<br />{{ $arrTanggal['tanggal_akhir'] }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal izin sakit</td>
                                <td>
                                    @foreach ($arrTanggal['arrTanggalSakit'] as $value)
                                    {{ $value }}<br />
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>Masuk bekerja kembali</td>
                                <td>{{ $arrTanggal['tanggal_masuk'] }}</td>
                            </tr>
                            <tr>
                                <td>Alasan pengajuan izin sakit</td>
                                <td>{{ $trxSakit->alasan }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>{{ $trxSakit->alamat }}</td>
                            </tr>
                            <tr>
                                <td>Nomor telepon</td>
                                <td>{{ $trxSakit->telepon }}</td>
                            </tr>
                            <tr>
                                <td>Lampiran</td>
                                <td><a href="{{ asset('assets/sakit/'.$trxSakit->lampiran) }}" target="_blank"><i class="icon-file"></i>&nbsp;Download</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--  -->
            </div>
        </div>
        <!--  -->
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>PERSONEL PENGGANTI</h5>
                </div>
                @foreach ($arrPicProperty as $value)
                <div class="card-header">
                    <a href="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}" target="_blank"><img class="img-thumbnail" width="20%" src="{{ asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg') }}" alt="user"></a>
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
                                <td>Email</td>
                                <td>{{ $value['email'] }}</td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td>{{ $value['telepon1'] }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>{{ $value['jabatan'] }}</td>
                            </tr>
                            <tr>
                                <td>Direktorat / Divisi / Departemen</td>
                                <td>{{ $value['divisi'] }} / {{ $value['dep'] }} / {{ $value['sub_dep'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
        </div>
        <!--  -->
        @if($pic == 'pic_pengganti')
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                    <input type="hidden" id="pic" name="pic" value="{{ $pic }}">
                    <button type="submit" name="status_sakit" value="2" class="btn btn-success">Approve</button>
                    <button type="submit" name="status_sakit" value="7" class="btn btn-danger">Reject</button>
                </form>
                </div>
            </div>
        </div>
        @elseif($pic == 'nik_atasan')
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography">
                <form id="myform" name="myform" method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self" onsubmit="return confirm('Are you sure want to submit this data?');">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                    <input type="hidden" id="pic" name="pic" value="{{ $pic }}">
                    <button type="submit" name="status_sakit" value="4" class="btn btn-success">Approve</button>
                    <button type="submit" name="status_sakit" value="5" class="btn btn-danger">Reject</button>
                </form>
                </div>
            </div>
        </div>
        @endif
        <!--  -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review Formulir Permohonan Izin Sakit ini.</h6>
                    <a href="https://alita.id/"><img src="{{ asset('assets/images/matta/Alita-New-Log.png') }}" height="40" alt="Alita" /></a><br />
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