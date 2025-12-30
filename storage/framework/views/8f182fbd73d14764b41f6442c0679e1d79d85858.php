<!DOCTYPE html>
<html lang="en">

<head>
    <title>Formulir Evaluasi Pelatihan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <form action="<?php echo e(route('pelatihan.update', (new App\Http\Controllers\OpenDataController)->encryptWithKey($_trx->id))); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure want to submit this data?');">
        <?php echo method_field('PATCH'); ?>
        <?php echo csrf_field(); ?>
        <input type="hidden" id="type" name="type" value="doc_penilaian">
        <div class="col-md-12 mt-2">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-start">
                            <h4><img src="<?php echo e(asset('assets/images/matta/form-icon-25.jpg')); ?>" height="30" alt="Alita"/>&nbsp;Formulir Evaluasi Pelatihan</h4>
                        </div>
                        
                    </div>
                </div>
                <hr class="m-2" />
                <?php
                    $data = $materi->firstWhere('urutan', 1);
                    $nik_property = json_decode($_trx->nik_property, true);
                ?>
                <div class="card-body">
                    <h5 class="ms-1"><?php echo e($data->label); ?>. <?php echo e($data->materi1); ?></h5>
                    
                    
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <th width="30%">Keterangan</th>
                            <th width="1%">&nbsp;</th>
                            <th width="69%">Isi</th>
                        </tr>
                        <tr>
                            <td>Nama Peserta</td>
                            <td>:</td>
                            <td><?php echo e($nik_property['nama']); ?></td>
                        </tr>
                        <tr>
                            <td>NIP</td>
                            <td>:</td>
                            <td><?php echo e($nik_property['nik']); ?></td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td><?php echo e($nik_property['jabatan']); ?></td>
                        </tr>
                        <tr>
                            <td>Direktorat / Divisi / Departemen</td>
                            <td>:</td>
                            <td><?php echo e($nik_property['divisi']); ?> / <?php echo e($nik_property['dep']); ?> / <?php echo e($nik_property['sub_dep']); ?></td>
                        </tr>
                        <tr>
                            <td>Jenis Pelatihan</td>
                            <td>:</td>
                            <td><?php $__currentLoopData = $arrJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($value->jenis); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                        </tr>
                        <?php if(!empty($trx->int_ext)): ?>
                        <tr>
                            <td>Mode Pelatihan</td>
                            <td>:</td>
                            <td>
                                <?php $__currentLoopData = $int_ext; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($key == $trx->int_ext): ?> <?php echo e($value); ?> <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td>Judul Pelatihan</td>
                            <td>:</td>
                            <td><?php echo e($trx->judul); ?></td>
                        </tr>
                        <tr>
                            <td>Penyelenggara</td>
                            <td>:</td>
                            <td><?php echo e($trx->penyelenggara); ?></td>
                        </tr>
                        <?php
                        // $tanggal_range = [];
                        // if(!empty($trx->tanggal_range)){
                            $tanggal_range = json_decode($trx->tanggal_range, true);
                            $formatted_ranges = array_map(function($range) {
                                [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                                return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                            }, $tanggal_range);
                        // }
                        ?>
                        <tr>
                            <td>Tanggal Pelatihan</td>
                            <td>:</td>
                            <td><?php $__currentLoopData = $formatted_ranges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_tanggal_range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($_tanggal_range); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                        </tr>
                    </table>
                </div>
                <hr class="m-2" />
                <?php
                    $data = $materi->firstWhere('urutan', 2);
                    $_materi = $materi->where('label', 'B')->sortBy('nomor')->values();
                    $options = [
                        4 => 'Sangat Baik',
                        3 => 'Baik',
                        2 => 'Cukup',
                        1 => 'Kurang',
                    ];
                ?>
                <div class="card-body">
                    <h5><?php echo e($data->label); ?>. <?php echo e($data->materi1); ?></h5>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <th width="2%">No</th>
                            <th width="29%">Pernyataan</th>
                            <th width="69%">Jawaban</th>
                        </tr>
                        <?php $__currentLoopData = $_materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($_data->nomor); ?></td>
                            <td><?php echo e($_data->materi2); ?></td>
                            <td>
                                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jawaban_<?php echo e($_data->id); ?>" id="jawaban_<?php echo e($_data->id); ?>" value="<?php echo e($val); ?>" required>
                                        <label class="form-check-label" for="jawaban_<?php echo e($_data->id); ?>"><?php echo e($text); ?></label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </div>
                <hr class="m-2" />
                <?php
                    $data = $materi->firstWhere('urutan', 8);
                    $_materi = $materi->where('label', 'C')->sortBy('nomor')->values();
                ?>
                <div class="card-body">
                    <h5><?php echo e($data->label); ?>. <?php echo e($data->materi1); ?></h5>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <th width="2%">No</th>
                            <th width="29%">Pernyataan</th>
                            <th width="69%">Jawaban</th>
                        </tr>
                        <?php $__currentLoopData = $_materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($_data->nomor); ?></td>
                            <td><?php echo e($_data->materi2); ?></td>
                            <td>
                                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jawaban_<?php echo e($_data->id); ?>" id="jawaban_<?php echo e($_data->id); ?>" value="<?php echo e($val); ?>" required>
                                        <label class="form-check-label" for="jawaban_<?php echo e($_data->id); ?>"><?php echo e($text); ?></label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </div>
                <hr class="m-2" />
                <?php
                    $data = $materi->firstWhere('urutan', 13);
                    $_materi = $materi->where('label', 'D')->sortBy('nomor')->values();
                ?>
                <div class="card-body">
                    <h5><?php echo e($data->label); ?>. <?php echo e($data->materi1); ?></h5>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <th width="2%">No</th>
                            <th width="29%">Pernyataan</th>
                            <th width="69%">Jawaban</th>
                        </tr>
                        <?php $__currentLoopData = $_materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($_data->nomor); ?></td>
                            <td><?php echo e($_data->materi2); ?></td>
                            <td>
                                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jawaban_<?php echo e($_data->id); ?>" id="jawaban_<?php echo e($_data->id); ?>" value="<?php echo e($val); ?>" required>
                                        <label class="form-check-label" for="jawaban_<?php echo e($_data->id); ?>"><?php echo e($text); ?></label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </div>
                <hr class="m-2" />
                <?php
                    $data = $materi->firstWhere('urutan', 18);
                    $_materi = $materi->where('label', 'E')->sortBy('nomor')->values();
                ?>
                <div class="card-body">
                    <h5><?php echo e($data->label); ?>. <?php echo e($data->materi1); ?></h5>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <th width="2%">No</th>
                            <th width="29%">Pernyataan</th>
                            <th width="69%">Jawaban</th>
                        </tr>
                        <?php $__currentLoopData = $_materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($_data->nomor); ?></td>
                            <td><?php echo e($_data->materi2); ?></td>
                            <td>
                                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jawaban_<?php echo e($_data->id); ?>" id="jawaban_<?php echo e($_data->id); ?>" value="<?php echo e($val); ?>" required>
                                        <label class="form-check-label" for="jawaban_<?php echo e($_data->id); ?>"><?php echo e($text); ?></label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </div>
                <hr class="m-2" />
                <?php
                    $data = $materi->firstWhere('urutan', 22);
                    $_materi = $materi->where('label', 'F')->sortBy('nomor')->values();
                ?>
                <div class="card-body">
                    <h5><?php echo e($data->label); ?>. <?php echo e($data->materi1); ?></h5>
                    <?php $__currentLoopData = $_materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="form-label" for="jawaban_<?php echo e($_data->id); ?>"><?php echo e($_data->nomor); ?>. <?php echo e($_data->materi2); ?></label>
                    <textarea class="form-control mb-2" id="jawaban_<?php echo e($_data->id); ?>" name="jawaban_<?php echo e($_data->id); ?>" rows="4" required></textarea>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <hr class="m-2" />
                <?php
                    $data = $materi->firstWhere('urutan', 25);
                    $_materi = $materi->where('label', 'G')->sortBy('nomor')->values();
                ?>
                <div class="card-body">
                    <h5><?php echo e($data->label); ?>. <?php echo e($data->materi1); ?></h5>
                    
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <?php $__currentLoopData = $_materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td width="31%"><?php echo e($_data->materi2); ?></td>
                            <td width="69%">
                                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jawaban_<?php echo e($_data->id); ?>" id="jawaban_<?php echo e($_data->id); ?>" value="<?php echo e($val); ?>" required>
                                        <label class="form-check-label" for="jawaban_<?php echo e($_data->id); ?>"><?php echo e($text); ?></label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-2 mb-2">
            
                <div class="card-body">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            
        </div>
        </form>
        <div class="col-md-12 mt-2 mb-2">
            
                <div class="card-body text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya dalam pengisian Formulir Evaluasi Pelatihan ini.</h6>
                    <a href="https://alita.id/"><img src="<?php echo e(asset('assets/images/matta/Alita-New-Log.png')); ?>" height="40" alt="Alita" /></a><br/>
                    
                    <h6>&reg;PT Alita Praya Mitra - <?php echo e(\Carbon\Carbon::now()->year); ?></h6>
                </div>
            
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
// 
</script>
</html>
<?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/penilaian.blade.php ENDPATH**/ ?>