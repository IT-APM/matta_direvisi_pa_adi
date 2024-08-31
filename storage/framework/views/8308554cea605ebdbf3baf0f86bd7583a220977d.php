<?php $__env->startSection('title', 'Formulir Pengajuan Pelatihan'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>View</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Formulir</li>
<li class="breadcrumb-item active">Pengajuan Pelatihan</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-sm-12">
            <?php if(Session::has('msg')): ?>
            <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                <h6><?php echo e(session('msg')); ?></h6>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            <div class="row">
                <!--  -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Pengaju</h5>
                        </div>
                        <div class="card-header">
                            <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>"
                                target="_blank"><img class="img-thumbnail" width="20%"
                                    src="<?php echo e(asset('assets/images/matta/karyawan/'.$pengaju['nik'].'/'.$pengaju['nik'].'.jpg')); ?>"
                                    alt="user"></a>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="30%">Nama</td>
                                        <td width="70%"><?php echo e($pengaju['nama']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td><?php echo e($pengaju['nik']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td><?php echo e($pengaju['jabatan']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Direktorat / Divisi / Departemen</td>
                                        <td><?php echo e($pengaju['divisi'] . '/' . $pengaju['dep'] . '/' . $pengaju['sub_dep']); ?>

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
                        <?php $__currentLoopData = $arrPeserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card-header">
                            <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                target="_blank"><img class="img-thumbnail" width="20%"
                                    src="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                    alt="user"></a>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="30%">Nama</td>
                                        <td width="70%"><?php echo e($value['nama']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td><?php echo e($value['nik']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td><?php echo e($value['jabatan']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Direktorat / Divisi / Departemen</td>
                                        <td><?php echo e($value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep']); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <!-- end -->
                        <!-- start -->
                        <div class="card-header">
                            <h5>Atasan Peserta</h5>
                        </div>
                        <?php $__currentLoopData = $arrAtasanPeserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card-header">
                            <a href="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                target="_blank"><img class="img-thumbnail" width="20%"
                                    src="<?php echo e(asset('assets/images/matta/karyawan/'.$value['nik'].'/'.$value['nik'].'.jpg')); ?>"
                                    alt="user"></a>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="30%">Nama</td>
                                        <td width="70%"><?php echo e($value['nama']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td><?php echo e($value['nik']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td><?php echo e($value['jabatan']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Direktorat / Divisi / Departemen</td>
                                        <td><?php echo e($value['divisi'] . '/' . $value['dep'] . '/' . $value['sub_dep']); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="d-flex">
                                    <?php if($loop->first): ?>
                                    <div class="activity-dot-success"></div>
                                    <?php elseif($loop->last): ?>
                                    <div class="activity-dot-warning"></div>
                                    <?php else: ?>
                                    <div class="activity-dot-primary"></div>
                                    <?php endif; ?>
                                    <div class="ms-3">
                                        <p class="d-flex justify-content-between mb-2"><span
                                                class="date-content light-background"><?php echo e($_history->created_at); ?></span>
                                        </p>
                                        <h6><?php echo e($_history->_status->status_pengajuan); ?><span
                                                class="dot-notification"></span></h6>
                                        <p class="f-light">Oleh <?php echo e($_history->_pic->nama); ?> /
                                            <?php echo e($_history->nik_pic); ?></p>
                                    </div>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                        <!-- end -->
                        <!-- start -->
                        <div class="card-header">
                            <h5>Pelatihan <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?><a data-bs-toggle="modal" data-bs-target="#modal_edit" href="#modal_edit" title="Edit"><i class="icon-pencil-alt"></i></a><?php endif; ?></h5>
                        </div>
                        <div class="card-body typography">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td width="30%">Pelatihan ke</td>
                                        <td width="70%"><?php echo e($trx->ke); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tahun</td>
                                        <td><?php echo e($trx->tahun); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jenis</td>
                                        <td>
                                            <?php $__currentLoopData = $arrJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo e($value->jenis); ?><br />
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Judul</td>
                                        <td><?php echo e($trx->judul); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Penyelenggara</td>
                                        <td><?php echo e($trx->penyelenggara); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Pelaksanaan</td>
                                        <td><?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_mulai)->format('d-m-Y')); ?>

                                            sampai
                                            <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d', $trx->tanggal_akhir)->format('d-m-Y')); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tempat</td>
                                        <td><?php echo e($trx->tempat); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Biaya</td>
                                        <td>Rp. <?php echo e(number_format($trx->biaya, 2)); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Benefit</td>
                                        <td>
                                            <?php $__currentLoopData = $arrBenefit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo e($value->benefit); ?><br />
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Anggaran</td>
                                        <td><?php echo e($trx->_anggaran->anggaran); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan</td>
                                        <td><?php echo $trx->keterangan; ?></td>
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
                        <a href="<?php echo e(URL::previous()); ?>" class="btn btn-warning" type="button">Back</a>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
        <form action="<?php echo e(route('pelatihan.save_update', [$trx->id, 'data_type' => 'biaya', 'back_url' => URL::full()])); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
        <?php echo csrf_field(); ?>
          <div class="modal-header">
             <h5 class="modal-title" id="exampleModalLabel2">Edit</h5>
             <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
                <div class="mt-0">
                    <label class="form-label" for="tahun">Tahun*</label>
                    <input min=0 type="number" name="tahun" id="tahun" class="form-control numbers" required="" value="<?php echo e($trx->tahun); ?>">
                </div>
                <div class="mt-2">
                    <label class="form-label" for="jenis[]">Jenis Pelatihan*</label>
                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="jenis[]" name="jenis[]" required="">
                        <?php $__currentLoopData = $jenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, explode(';', $trx->jenis))): ?> selected <?php endif; ?>><?php echo e($data->jenis); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="invalid-feedback txt-danger">Jenis Pelatihan is required.</div>
                </div>
                <div class="mt-2">
                    <label class="form-label" for="judul">Judul Pelatihan*</label>
                    <input name="judul" id="judul" class="form-control" type="text" required="" value="<?php echo e($trx->judul); ?>">
                </div>
                <div class="mt-2">
                    <label class="form-label" for="penyelenggara">Penyelenggara*</label>
                    <input name="penyelenggara" id="penyelenggara" class="form-control" type="text" required="" value="<?php echo e($trx->penyelenggara); ?>">
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="form-label" for="tanggal_mulai">Tanggal Mulai*</label>
                        <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required="" value="<?php echo e($trx->tanggal_mulai); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="tanggal_akhir">Tanggal Akhir*</label>
                        <input class="form-control" name="tanggal_akhir" id="tanggal_akhir" type="date" required="" value="<?php echo e($trx->tanggal_akhir); ?>">
                    </div>
                </div>
                <div class="mt-2">
                    <label class="form-label" for="tempat">Tempat*</label>
                    <textarea class="form-control" id="tempat" name="tempat" rows="4" required=""><?php echo e($trx->tempat); ?></textarea>
                </div>
                <div class="mt-2">
                    <label class="form-label" for="biaya">Biaya (Termasuk PPN, jika ada)*</label>
                    <input min=0 type="number" oninput="validity.valid||(value='');" onfocus="use_number(this)" onblur="use_text(this)" name="biaya" id="biaya" class="form-control numbers" required="" value="<?php echo e($trx->biaya); ?>">
                </div>
                <div class="mt-2">
                    <label class="form-label" for="benefit[]">Benefit*</label>
                    <select class="form-select js-example-basic-multiple col-sm-12" multiple="multiple" id="benefit[]" name="benefit[]" required="">
                        <?php $__currentLoopData = $benefit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($data->id); ?>" <?php if(in_array($data->id, explode(';', $trx->benefit))): ?> selected <?php endif; ?>><?php echo e($data->benefit); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="invalid-feedback txt-danger">Benefit is required.</div>
                </div>
                <div class="mt-2">
                    <label class="form-label" for="anggaran">Anggaran*</label>
                    <select class="form-select col-sm-12" id="anggaran" name="anggaran" required="">
                        <option selected="" disabled="" value="">Pilih...</option>
                        <?php $__currentLoopData = $anggaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($data->id); ?>" <?php if($data->id==$trx->anggaran): ?> selected <?php endif; ?>><?php echo e($data->anggaran); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mt-2">
                    <label class="form-label" for="keterangan">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="4" ><?php echo e($trx->keterangan); ?></textarea>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/view.blade.php ENDPATH**/ ?>