<!DOCTYPE html>
<html lang="en">

<head>
    <title>Offering Letter</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
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
                    <h3>FORMULIR PENAWARAN</h3>
                </div>
                <hr class="mt-2 mb-2" />
                <div class="row g-0">
                    <div class="col-12 col-md-12">
                        <h4 class="text-center">Personal</h4>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="nama">Nama Lengkap</label>
                                <input class="form-control" name="nama" id="nama" type="text" readonly="" value="<?php echo e($_trx->nama); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="alamat_ktp">Alamat</label>
                                <input class="form-control" name="alamat_ktp" id="alamat_ktp" type="text" readonly="" value="<?php echo e($_trx->alamat_ktp); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="tempat_tanggal_lahir">Tempat/Tanggal Lahir</label>
                                <input class="form-control" name="tempat_tanggal_lahir" id="tempat_tanggal_lahir" type="text" readonly="" value="<?php echo e($_trx->tempat_lahir); ?>/<?php echo e(Carbon\Carbon::parse($_trx->tanggal_lahir)->format('d-m-Y')); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="pendidikan">Pendidikan</label>
                                <input class="form-control" name="pendidikan" id="pendidikan" type="text" readonly="" value="<?php echo e($_trx->pendidikan); ?>">
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="col-12 col-md-12">
                        <h4 class="text-center">Kelengkapan Dokumen</h4>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_sertifikat_keahlian">Upload Sertifikat Keahlian</label>
                                <?php if(!empty($_trx->file_sertifikat_keahlian)): ?><br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_sertifikat_keahlian)); ?>" target="_blank">Download Sertifikat Keahlian</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_ktp">Upload KTP<span class="text-danger">*</span></label>
                                <?php if(!empty($_trx->file_ktp)): ?><br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_ktp)); ?>" target="_blank">Download KTP</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_kk">Upload Kartu Keluarga<span class="text-danger">*</span></label>
                                <?php if(!empty($_trx->file_kk)): ?><br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_kk)); ?>" target="_blank">Download Kartu Keluarga</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_npwp">Upload NPWP</label>
                                <?php if(!empty($_trx->file_npwp)): ?><br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_npwp)); ?>" target="_blank">Download NPWP</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_bpjs_tk">Upload BPJS Ketenagakerjaan</label>
                                <?php if(!empty($_trx->file_bpjs_tk)): ?><br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_bpjs_tk)); ?>" target="_blank">Download BPJS Ketenagakerjaan</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_bpjs_kes">Upload BPJS Kesehatan</label>
                                <?php if(!empty($_trx->file_bpjs_kes)): ?><br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_bpjs_kes)); ?>" target="_blank">Download BPJS Kesehatan</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_buku_tabungan">Upload Buku Tabungan<span class="text-danger">*</span></label>
                                <?php if(!empty($_trx->file_buku_tabungan)): ?><br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_buku_tabungan)); ?>" target="_blank">Download Buku Tabungan</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_ket_sehat">Upload Surat Keterangan Sehat<span class="text-danger">*</span></label>
                                <?php if(!empty($_trx->file_ket_sehat)): ?><br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_ket_sehat)); ?>" target="_blank">Download Keterangan Sehat</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_skck">Upload SKCK<span class="text-danger">*</span></label>
                                <?php if(!empty($_trx->file_skck)): ?><br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_skck)); ?>" target="_blank">Download SKCK</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_ijazah">Upload Ijazah<span class="text-danger">*</span></label>
                                <?php if(!empty($_trx->file_ijazah)): ?><br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_ijazah)); ?>" target="_blank">Download Ijazah</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_covid">Upload Sertifikat Vaksin Covid 19</label>
                                <?php if(!empty($_trx->file_covid)): ?><br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_covid)); ?>" target="_blank">Download Sertifikat Vaksin Covid 19</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="file_dokumen_ttd">Upload Pedoman Perilaku<span class="text-danger">*</span></label>
                                <?php if(!empty($_trx->file_dokumen_ttd)): ?><br/><i class="fa fa-file-text-o"></i>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$_trx->file_dokumen_ttd)); ?>" target="_blank">Download Pedoman Perilaku</a> <?php else: ?> <br/>-Tidak Ada- <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="col-12 col-md-12">
                        <h4 class="text-center">Organisasi</h4>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="posisi">Posisi / Jabatan</label>
                                <input class="form-control" name="posisi" id="posisi" type="text" readonly="" value="<?php echo e($trx->posisi); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="area_kerja">Area Kerja</label>
                                <input class="form-control" name="area_kerja" id="area_kerja" type="text" readonly="" value="<?php echo e($trx->area_kerja); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="divisi">Direktorat / Divisi / Departemen</label>
                                <input class="form-control" name="divisi" id="divisi" type="text" readonly="" value="<?php echo e($trx->divisi); ?>">
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="col-12 col-md-12">
                        <h4 class="text-center">Benefit</h4>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="thp">Take Home Pay (THP)</label>
                                <input class="form-control" name="thp" id="thp" type="text" readonly="" value="Rp. <?php echo e(number_format($trx->thp, 2)); ?>">
                            </div>
                        </div>
                        <?php
                        $benefit_utama = App\Models\MtrRecruitmentBenefit::whereIn('id', explode(',', $trx->benefit_utama))->orderBy('benefit', 'asc')->pluck('benefit')->toArray();
                        $benefit_utama = implode('&#13;&#10;', $benefit_utama);
                        ?>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="benefit_utama">Benefit</label>
                                <textarea class="form-control" rows="5" id="benefit_utama" name="benefit_utama" readonly=""><?php echo $benefit_utama; ?></textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="benefit_lainnya">Benefit lainnya</label>
                                <input class="form-control" name="benefit_lainnya" id="benefit_lainnya" type="text" readonly="" value="<?php echo e($trx->benefit_lainnya); ?>">
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="col-12 col-md-12">
                        <h4 class="text-center">Kepegawaian</h4>
                    </div>
                    <hr class="mt-2 mb-2" />
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="tgl_bergabung">Tanggal Mulai Bergabung</label>
                                <input class="form-control" name="tgl_bergabung" id="tgl_bergabung" type="text" readonly="" value="<?php echo e(Carbon\Carbon::parse($trx->tgl_bergabung)->format('d-m-Y')); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="id_catat_pegawai">Pencatatan Kepegawaian</label>
                                <input class="form-control" name="id_catat_pegawai" id="id_catat_pegawai" type="text" readonly="" value="<?php echo e($trx->_catat_pegawai->pencatatan); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="id_status_pegawai">Status Kepegawaian</label>
                                <input class="form-control" name="id_status_pegawai" id="id_status_pegawai" type="text" readonly="" value="<?php echo e($trx->_status_pegawai->status); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="masa_kerja">Masa Perjanjian Kerja (bulan)</label>
                                <input class="form-control" name="masa_kerja" id="masa_kerja" type="text" readonly="" value="<?php echo e($trx->masa_kerja); ?>">
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hc')): ?>
            <div class="col-12 m-2 text-center">
                <a target="_blank" href="<?php echo e(route('recruitment.download_offering', Crypt::encrypt($_trx->id))); ?>" class="btn btn-primary" type="button"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
            </div>
            <?php endif; ?>
        </div>
    </section>
</body>
<script type="text/javascript">
// 
</script>

</html>
<?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/get_offering.blade.php ENDPATH**/ ?>