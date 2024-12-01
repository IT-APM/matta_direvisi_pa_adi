<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelengkapan Dokumen Karyawan Baru</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <section class="p-2 p-md-2 p-xl-2">
        <div class="container">
            <div class="card border-light-subtle shadow-sm">
                <div class="row g-0 text-end">
                    <div class="col-12 col-md-12"><img class="img-fluid rounded-start w-194 h-73 object-fit-cover" loading="lazy" src="<?php echo e(asset('assets/images/matta/alita.png')); ?>" alt="Alita"></div>
                </div>
                <hr class="mt-2 mb-2" />
                <?php if(Session::has('msg')): ?>
                <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                    <h5><?php echo e(session('msg')); ?></h6>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                <?php if(count($errors) > 0): ?>
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                    <h6>Upload file is ERROR</h6>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                <div class="row g-0 text-center">
                    <h3>Kelengkapan Dokumen Karyawan Baru</h3>
                </div>
                <hr class="mt-2 mb-2" />
                <div class="row g-0">
                    <div class="col-12 col-md-12">
                        <h4 class="text-center">IDENTITTAS</h4>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="nama">Nama</label>
                                <input class="form-control" name="nama" id="nama" type="text" readonly="" value="<?php echo e($_trx->nama); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="tempat_tanggal_lahir">Tempat/Tanggal Lahir</label>
                                <input class="form-control" name="tempat_tanggal_lahir" id="tempat_tanggal_lahir" type="text" readonly="" value="<?php echo e($_trx->tempat_lahir); ?>/<?php echo e(Carbon\Carbon::parse($_trx->tanggal_lahir)->format('d-m-Y')); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="ktp">No KTP</label>
                                <input class="form-control" name="ktp" id="ktp" type="text" readonly="" value="<?php echo e($_trx->ktp); ?>">
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="col-12 col-md-12 text-center">
                        <h4>UPLOAD<span class="text-danger">*</span></h4>
                        <?php if($_trx->id_status == 19): ?>
                        <label class="form-label">*Maksimal 5 MB dengan format pdf/image(jpg, jpeg, png)</label>
                        <?php endif; ?>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <form action="<?php echo e(route('recruitment.upload_dokumen_post')); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="id" name="id" value="<?php echo e(Crypt::encrypt($_trx->id)); ?>">
                            
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_sertifikat_keahlian">Upload Sertifikat Keahlian</label>
                                        <?php if($_trx->id_status == 19): ?>
                                        <input name="file_sertifikat_keahlian" id="file_sertifikat_keahlian" class="form-control" type="file" >
                                        <?php endif; ?>
                                        <?php if(!empty($_trx->file_sertifikat_keahlian)): ?><br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_sertifikat_keahlian)); ?>" target="_blank">Download Sertifikat Keahlian</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_ktp">Upload KTP<span class="text-danger">*</span></label>
                                        <?php if($_trx->id_status == 19): ?>
                                        <input name="file_ktp" id="file_ktp" class="form-control" type="file" required="">
                                        <?php endif; ?>
                                        <?php if(!empty($_trx->file_ktp)): ?><br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_ktp)); ?>" target="_blank">Download KTP</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_kk">Upload Kartu Keluarga<span class="text-danger">*</span></label>
                                        <?php if($_trx->id_status == 19): ?>
                                        <input name="file_kk" id="file_kk" class="form-control" type="file" required="">
                                        <?php endif; ?>
                                        <?php if(!empty($_trx->file_kk)): ?><br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_kk)); ?>" target="_blank">Download Kartu Keluarga</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_npwp">Upload NPWP</label>
                                        <?php if($_trx->id_status == 19): ?>
                                        <input name="file_npwp" id="file_npwp" class="form-control" type="file">
                                        <?php endif; ?>
                                        <?php if(!empty($_trx->file_npwp)): ?><br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_npwp)); ?>" target="_blank">Download NPWP</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_bpjs_tk">Upload BPJS Ketenagakerjaan</label>
                                        <?php if($_trx->id_status == 19): ?>
                                        <input name="file_bpjs_tk" id="file_bpjs_tk" class="form-control" type="file">
                                        <?php endif; ?>
                                        <?php if(!empty($_trx->file_bpjs_tk)): ?><br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_bpjs_tk)); ?>" target="_blank">Download BPJS Ketenagakerjaan</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_bpjs_kes">Upload BPJS Kesehatan</label>
                                        <?php if($_trx->id_status == 19): ?>
                                        <input name="file_bpjs_kes" id="file_bpjs_kes" class="form-control" type="file">
                                        <?php endif; ?>
                                        <?php if(!empty($_trx->file_bpjs_kes)): ?><br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_bpjs_kes)); ?>" target="_blank">Download BPJS Kesehatan</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_buku_tabungan">Upload Buku Tabungan<span class="text-danger">*</span></label>
                                        <?php if($_trx->id_status == 19): ?>
                                        <input name="file_buku_tabungan" id="file_buku_tabungan" class="form-control" type="file" required="">
                                        <?php endif; ?>
                                        <?php if(!empty($_trx->file_buku_tabungan)): ?><br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_buku_tabungan)); ?>" target="_blank">Download Buku Tabungan</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_ket_sehat">Upload Surat Keterangan Sehat<span class="text-danger">*</span></label>
                                        <?php if($_trx->id_status == 19): ?>
                                        <input name="file_ket_sehat" id="file_ket_sehat" class="form-control" type="file" required="">
                                        <?php endif; ?>
                                        <?php if(!empty($_trx->file_ket_sehat)): ?><br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_ket_sehat)); ?>" target="_blank">Download Keterangan Sehat</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_skck">Upload SKCK<span class="text-danger">*</span></label>
                                        <?php if($_trx->id_status == 19): ?>
                                        <input name="file_skck" id="file_skck" class="form-control" type="file" required="">
                                        <?php endif; ?>
                                        <?php if(!empty($_trx->file_skck)): ?><br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_skck)); ?>" target="_blank">Download SKCK</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_ijazah">Upload Ijazah<span class="text-danger">*</span></label>
                                        <?php if($_trx->id_status == 19): ?>
                                        <input name="file_ijazah" id="file_ijazah" class="form-control" type="file" required="">
                                        <?php endif; ?>
                                        <?php if(!empty($_trx->file_ijazah)): ?><br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_ijazah)); ?>" target="_blank">Download Ijazah</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_covid">Upload Sertifikat Vaksin Covid 19</label>
                                        <?php if($_trx->id_status == 19): ?>
                                        <input name="file_covid" id="file_covid" class="form-control" type="file">
                                        <?php endif; ?>
                                        <?php if(!empty($_trx->file_covid)): ?><br/><i class="fa fa-file-text-o"></i></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_covid)); ?>" target="_blank">Download Sertifikat Vaksin Covid 19</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        <label class="form-label" for="file_dokumen_ttd">Upload Pedoman Perilaku<span class="text-danger">*</span></label>
                                        <?php if($_trx->id_status == 19): ?>
                                        <input name="file_dokumen_ttd" id="file_dokumen_ttd" class="form-control" type="file" required="">
                                        <?php endif; ?>
                                        <?php if(!empty($_trx->file_dokumen_ttd)): ?><br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_dokumen_ttd)); ?>" target="_blank">Download Pedoman Perilaku</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                                        <?php if($_trx->id_status == 19): ?>
                                        <label class="form-label">*Mohon untuk mengunduh, membaca, memahami dan menandatangani Pedoman Perilaku terlampir dan di-upload bersama dokumen lainnya sebagai kelengkapan administrasi.</label>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if($_trx->id_status == 19): ?>
                            <div class="row text-center">
                                <div class="col-12 col-md-4">&nbsp;</div>
                                <div class="col-12 col-md-4">
                                    <div class="col-12 mb-2">
                                        
                                        <button type="submit" name="id_status" value="20" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">&nbsp;</div>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
            <p>&nbsp;</p>
        </div>
    </section>
</body>
<script type="text/javascript">
// 
</script>

</html>
<?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/upload_dokumen.blade.php ENDPATH**/ ?>