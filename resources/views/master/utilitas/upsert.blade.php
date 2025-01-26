@extends('layouts.simple.master')
@section('title', 'Utilitas')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Edit</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item">Utilitas</li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                {{--  --}}
                <div class="card-body">
                    @if(Session::has('msg'))
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6>{{ session('msg') }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <h5>Formulir</h5>
                    <form action="{{ route('utilitas.update', 0) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to save this data?');">
                    @method('PATCH')
                    @csrf
                        <div class="row mt-2">
                            <div class="col-md-3">
                                @php $no=0; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=4; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=5; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=6; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                @php $no=11; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=12; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=13; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=14; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                @php $no=15; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=16; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=19; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=22; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                @php $no=17; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=18; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=20; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=21; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                @php $no=22; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=23; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=24; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=25; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                @php $no=26; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=27; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=28; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=30; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                @php $no=32; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Save</button> | <a href="{{ route('utilitas.index') }}" class="btn btn-warning" type="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                    {{--  --}}
                    <h5 class="mt-3">Surat Teguran dan Peringatan</h5>
                    <form action="{{ route('utilitas.update_sp', 0) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to save this data?');">
                    @method('POST')
                    @csrf
                        <div class="row mt-2">
                            <div class="col-md-3">
                                @php $no=0; @endphp
                                <label class="form-label" for="sp_{{ $no+1 }}">{{ $reminder[$no]->judul }}</label>
                                <input name="sp_{{ $no+1 }}" id="sp_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $reminder[$no]->isi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=2; @endphp
                                <label class="form-label" for="sp_{{ $no+1 }}">{{ $reminder[$no]->judul }}</label>
                                <input name="sp_{{ $no+1 }}" id="sp_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $reminder[$no]->isi }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                @php $no=1; @endphp
                                <label class="form-label" for="sp_{{ $no+1 }}">{{ $reminder[$no]->judul }}</label>
                                <textarea name="sp_{{ $no+1 }}" id="sp_{{ $no+1 }}" class="form-control" rows="4" required="">{{ $reminder[$no]->isi }}</textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Save</button> | <a href="{{ route('utilitas.index') }}" class="btn btn-warning" type="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                    {{--  --}}
                    @can('payroll')
                    <h5 class="mt-3">Payroll</h5>
                    <form action="{{ route('utilitas.update_payroll', 0) }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to save this data?');">
                    @method('POST')
                    @csrf
                        <div class="row mt-2">
                            <div class="col-md-3">
                                @php $no=31; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                            <div class="col-md-3">
                                @php $no=34; @endphp
                                <label class="form-label" for="_{{ $no+1 }}">{{ $utilitas[$no]->keterangan }}</label>
                                <input name="_{{ $no+1 }}" id="_{{ $no+1 }}" class="form-control" type="text" required="" value="{{ $utilitas[$no]->informasi }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Save</button> | <a href="{{ route('utilitas.index') }}" class="btn btn-warning" type="button">Cancel</a>
                            </div>
                        </div>
                    </form>
                    @endcan
                </div>
                {{--  --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
@endsection