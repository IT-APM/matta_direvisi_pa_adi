@extends('layouts.simple.master')
@section('title', 'Biaya Perjalanan Dinas')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>
    @if ($trxMaster->luar_dalam == 'ca_dalam') Cash Advance Perjalanan Dinas Dalam Negeri
    @elseif ($trxMaster->luar_dalam == 'ca_luar') Cash Advance Perjalanan Dinas Luar Negeri
    @elseif ($trxMaster->luar_dalam == 'reimburse') Reimbursement Perjalanan Dinas Dalam Negeri
    @elseif ($trxMaster->luar_dalam == 'reimburse_luar') Reimbursement Perjalanan Dinas Luar Negeri
    @endif
</h3>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-6 col-md-6 appointment-sec box-col-6">
                    <div class="appointment">
                        <div class="card">
                            <div class="card-header">
                                <h5>Para Pihak</h5>
                            </div>
                            @php
                            $pengaju_property = json_decode($trxMaster->pengaju_property, true);
                            $atasan_property = json_decode($trxMaster->atasan_property, true);
                            $admin_property = json_decode($trxMaster->admin_property, true);
                            $koordinator_property = json_decode($trxMaster->koordinator_property, true);
                            $layer1_property = json_decode($trxMaster->layer1_property, true);
                            $layer2_property = json_decode($trxMaster->layer2_property, true);
                            $hco_property = json_decode($trxMaster->hco_property, true);
                            @endphp
                            <div class="card-body pt-0">
                                <div class="appointment-table table-responsive">
                                    <table class="table table-bordernone">
                                        <tbody>
                                            <tr>
                                                <td><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$trxMaster->pengaju.'/'.$trxMaster->pengaju.'.jpg') }}" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Karyawan</h6>
                                                    <span class="f-light m-10">{{ $pengaju_property['nama'] }} / {{ $pengaju_property['nik'] }}<br />{{ $pengaju_property['jabatan'] }}<br />{{ $pengaju_property['divisi'] }} / {{ $pengaju_property['dep'] }} / {{ $pengaju_property['sub_dep'] }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$trxMaster->atasan.'/'.$trxMaster->atasan.'.jpg') }}" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Atasan langsung karyawan</h6>
                                                    <span class="f-light m-10">{{ $atasan_property['nama'] }} / {{ $atasan_property['nik'] }}<br />{{ $atasan_property['jabatan'] }}<br />{{ $atasan_property['divisi'] }} / {{ $atasan_property['dep'] }} / {{ $atasan_property['sub_dep'] }}</span>
                                                </td>
                                            </tr>
                                            @if(!empty($trxMaster->admin))
                                            <tr>
                                                <td><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$trxMaster->admin.'/'.$trxMaster->admin.'.jpg') }}" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Project Admin</h6>
                                                    <span class="f-light m-10">{{ $admin_property['nama'] }} / {{ $admin_property['nik'] }}<br />{{ $admin_property['jabatan'] }}<br />{{ $admin_property['divisi'] }} / {{ $admin_property['dep'] }} / {{ $admin_property['sub_dep'] }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                            @if(!empty($trxMaster->koordinator))
                                            <tr>
                                                <td><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$trxMaster->koordinator.'/'.$trxMaster->koordinator.'.jpg') }}" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Coordinator</h6>
                                                    <span class="f-light m-10">{{ $koordinator_property['nama'] }} / {{ $koordinator_property['nik'] }}<br />{{ $koordinator_property['jabatan'] }}<br />{{ $koordinator_property['divisi'] }} / {{ $koordinator_property['dep'] }} / {{ $koordinator_property['sub_dep'] }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                            @if(!empty($trxMaster->layer1))
                                            <tr>
                                                <td><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$trxMaster->layer1.'/'.$trxMaster->layer1.'.jpg') }}" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Layer 1</h6>
                                                    <span class="f-light m-10">{{ $layer1_property['nama'] }} / {{ $layer1_property['nik'] }}<br />{{ $layer1_property['jabatan'] }}<br />{{ $layer1_property['divisi'] }} / {{ $layer1_property['dep'] }} / {{ $layer1_property['sub_dep'] }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                            @if(!empty($trxMaster->layer2))
                                            <tr>
                                                <td><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$trxMaster->layer2.'/'.$trxMaster->layer2.'.jpg') }}" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">Layer 2</h6>
                                                    <span class="f-light m-10">{{ $layer2_property['nama'] }} / {{ $layer2_property['nik'] }}<br />{{ $layer2_property['jabatan'] }}<br />{{ $layer2_property['divisi'] }} / {{ $layer2_property['dep'] }} / {{ $layer2_property['sub_dep'] }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                            @if(!empty($trxMaster->hco))
                                            <tr>
                                                <td><img class="img-thumbnail" src="{{ asset('assets/images/matta/karyawan/'.$trxMaster->hco.'/'.$trxMaster->hco.'.jpg') }}" alt="user"></td>
                                                <td class="img-content-box">
                                                    <h6 class="m-10">{{ App\Models\MtrUtility::find(51)->informasi }}</h6>
                                                    <span class="f-light m-10">{{ $hco_property['nama'] }} / {{ $hco_property['nik'] }}<br />{{ $hco_property['jabatan'] }}<br />{{ $hco_property['divisi'] }} / {{ $hco_property['dep'] }} / {{ $hco_property['sub_dep'] }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><a href="{{ URL::previous() }}" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
                    </div>
                </div>
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
                                        <p class="d-flex justify-content-between mb-2"><span class="date-content light-background">{{ $_history->created_at }}</span></p>
                                        <h6>{{ $_history->_status->status }}<span class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh: {{ $_history->_pic->nama }} / {{ $_history->nik_pic }}</p>
                                        Catatan: {{ $_history->keterangan }}
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection
