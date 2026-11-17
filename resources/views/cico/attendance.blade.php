@extends('layouts.simple.master')
@section('title', 'Attendance Clock-in Clock-out')

@section('css')
<!--  -->
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Attendance Clock-in Clock-out</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Clock-in Clock-out</li>
<li class="breadcrumb-item active">Attendance</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <!-- <div class="card-body text-center my-auto"> -->
                <div class="card-body">
                    @if(Session::has('msg'))
                    <!-- <div class="alert alert-success">{{Session::get('msg')}}</div> -->
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="40%">Server datetime</td>
                                <td width="60%">{{ date('d-m-Y', strtotime(data_get($arr, '3.tgl', date('Y-m-d')))) }} {{ data_get($arr, '3.jam', date('H:i:s')) }}</td>
                            </tr>
                            <tr>
                                <td>Type/WG</td>
                                <td>{{ data_get($arr, '0.is_reguler', '-') }}/{{ data_get($arr, '0.wg_id', '-') }}</td>
                            </tr>
                            <tr>
                                <td>Working hour</td>
                                <td>{{ data_get($arr, '0.clockin', '-') }} sd {{ data_get($arr, '0.clockout', '-') }}</td>
                            </tr>
                            <tr>
                                <td>Nama/NIP</td>
                                <td>{{ optional($karyawan)->nama ?? '-' }}/{{ optional($karyawan)->nik ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Perusahaan / Direktorat / Divisi / Departemen</td>
                                <td>{{ optional(optional($karyawan)->_perusahaan)->nama ?? '-' }} / {{ optional(optional($karyawan)->_divisi)->nama ?? '-' }} / {{ optional(optional($karyawan)->_departemen)->nama ?? '-' }} / {{ optional(optional($karyawan)->_subdepartemen)->nama ?? '-' }}</td>
                            </tr>
                            @if (data_get($arr, '2.n_clockin', 0) > 0)
                            <tr>
                                <td>Clock in</td>
                                <td>{{ date('d-m-Y H:i:s', strtotime(data_get($arr, '5.dtClockin', 'now'))) }}</td>
                            </tr>
                            @endif
                            @if (data_get($arr, '2.n_clockout', 0) > 0)
                            <tr>
                                <td>Clock out</td>
                                <td>{{ date('d-m-Y H:i:s', strtotime(data_get($arr, '5.dtClockout', 'now'))) }}</td>
                            </tr>
                            @endif
                            @if (data_get($arr, '2.n_checkin', 0) > 0)
                            <tr>
                                <td>Check in</td>
                                <td>
                                    @foreach ((array)data_get($arr, '5.dtCheckin', []) as $data)
                                    {{ date('d-m-Y H:i:s', strtotime(data_get($data, 'att_timestamp', 'now'))) }}<br />
                                    @endforeach
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <form action="{{ route('cico_att.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to Clock-in?');">
                            @csrf
                            <input name="att_type" id="att_type_clockin" type="hidden" value="Clock-in">
                            <input name="att_lat" class="geo_lat" type="hidden" value="">
                            <input name="att_long" class="geo_long" type="hidden" value="">
                            <button class="btn btn-success px-4 py-2" type="submit">
                                <i class="fa fa-sign-in me-1" aria-hidden="true"></i> Clock In
                            </button>
                        </form>

                        <form action="{{ route('cico_att.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to Check-in?');">
                            @csrf
                            <input name="att_type" id="att_type_checkin" type="hidden" value="Check-in">
                            <input name="att_lat" class="geo_lat" type="hidden" value="">
                            <input name="att_long" class="geo_long" type="hidden" value="">
                            <button class="btn btn-warning text-white px-4 py-2" type="submit">
                                <i class="fa fa-clock-o me-1" aria-hidden="true"></i> Check In
                            </button>
                        </form>

                        <form action="{{ route('cico_att.store') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to Clock-out?');">
                            @csrf
                            <input name="att_type" id="att_type_clockout" type="hidden" value="Clock-out">
                            <input name="att_lat" class="geo_lat" type="hidden" value="">
                            <input name="att_long" class="geo_long" type="hidden" value="">
                            <button class="btn btn-primary px-4 py-2" type="submit">
                                <i class="fa fa-sign-out me-1" aria-hidden="true"></i> Clock Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            $('.geo_lat').val(position.coords.latitude);
            $('.geo_long').val(position.coords.longitude);
        }, function(error) {
            console.log('Geolocation not available or permission denied:', error.message);
        });
    }
</script>
@endsection
