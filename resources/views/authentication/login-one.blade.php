@extends('layouts.authentication.master')
@section('title', 'Login-one')

@section('css')
@endsection

@section('style')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-7 d-flex align-items-center justify-content-center">
            <img style="width:80%;float:none;" src="{{asset('assets/images/login/logo_matta.png')}}" alt="looginpage">
        </div>
        <div class="col-xl-5 p-0">
            <div class="login-card">
                <div>
                    @if(Session::has('msg_error'))
                    <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                        <strong>{{ session('msg_error') }}</strong>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="login-main border">
                        <!-- <form class="theme-form"> -->
                        <form class="theme-form" action="{{ route('loginapp') }}" method="post" enctype="multipart/form-data">
                        @csrf
                            <h4>Sign in to account</h4>
                            <p>Enter your NIP & Password to login</p>
                            <div class="form-group">
                                <label class="col-form-label" for="nik">NIP</label>
                                <input class="form-control" type="text" name="nik" id="nik" required="" placeholder="example: 0123456789" autofocus>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="password">Password</label>
                                <input class="form-control" type="password" name="password" id="password" required="" placeholder="*********">
                                <input type="checkbox" onclick="show_pwd()"> Show Password &nbsp; <a href="{{ route('login-one-reset') }}" class="link-primary" target="_blank">Reset password?</a>
                            </div>
                            <div class="form-group mt-0">
                                <!-- <div class="checkbox p-0">
                                    <input id="checkbox1" type="checkbox">
                                    <label class="text-muted" for="checkbox1">Remember password</label>
                                </div> -->
                                <button class="btn btn-primary btn-block" type="submit">Login</button>
                                <!-- <a class="btn btn-primary btn-block" href="{{ route('index') }}">Login</a> -->
                            </div>
                            <div class="form-group mt-0">
                                <a href="{{asset('assets/apk/app-release.apk')}}" class="link-primary" target="_blank">Click here to get Android MATTA Reborn</a>
                                <br/><a href="http://matta-reborn.alita.id/download" class="link-primary" target="_blank">Click here to get User Manual</a>
                                <!-- <br/><a href="{{asset('assets/pdf/user_manual.pdf')}}" class="link-primary" target="_blank">Click here to get User Manual</a> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
function show_pwd() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
@endsection