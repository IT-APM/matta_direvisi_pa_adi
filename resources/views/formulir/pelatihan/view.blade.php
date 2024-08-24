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
            @if(Session::has('msg'))
            <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                <h6>{{ session('msg') }}</h6>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
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
                                        <td>Rp. {{ number_format($trx->biaya, 2) }} @can('hc')<a data-bs-toggle="modal" data-bs-target="#modal_biaya" href="#modal_biaya" title="Edit"><i class="icon-pencil-alt"></i></a>@endcan</td>
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
<div class="modal fade" id="modal_biaya" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
        <form action="{{ route('pelatihan.save_update', [$trx->id, 'data_type' => 'biaya', 'back_url' => URL::full()]) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
        @csrf
          <div class="modal-header">
             <h5 class="modal-title" id="exampleModalLabel2">Edit</h5>
             <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
                <div class="mb-0">
                   <label class="col-form-label" for="recipient-name">Sebelum</label>
                   <input type="text" name="biaya_sebelum" id="biaya_sebelum" class="form-control numbers" readonly value="Rp. {{ number_format($trx->biaya, 2) }}">
                </div>
                <div class="mb-0">
                   <label class="col-form-label" for="message-text">Sesudah</label>
                   <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="biaya" id="biaya" class="form-control numbers" required="">
                </div>
          </div>
          <div class="modal-footer">
             <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
             <button class="btn btn-primary" type="submit">Submit</button>
          </div>
        </form>
       </div>
    </div>
 </div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script type="text/javascript">
function use_number(node) {
  var empty_val = false;
  const value = node.value;
//   if(value==0){
//     alert('Jumlah tidak boleh 0!');
//   }
  if (node.value == '')
    empty_val = true;
  node.type = 'number';
  if (!empty_val)
    node.value = Number(value.replace(/,/g, '')); // or equivalent per locale
}
// 
function use_text(node) {
  var empty_val = false;
  const value = Number(node.value);
  if (node.value == '')
    empty_val = true;
  node.type = 'text';
  if (!empty_val)
    node.value = value.toLocaleString('en');  // or other formatting
}
</script>
@endsection