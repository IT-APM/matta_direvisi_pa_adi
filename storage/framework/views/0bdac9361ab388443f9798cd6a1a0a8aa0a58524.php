<!DOCTYPE html>
<html>

<style>
.center {
  line-height: 55px;
  text-align: center;
}
</style>
<head>
    <title>Matta Reborn</title>
</head>
<!-- start -->
<body>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
            <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="25%">&nbsp;<img src="<?php echo e(public_path('assets/images/matta/alita_new.png')); ?>" alt="Logo Alita"></td>
                        <td width="50%"><div class="center" style="font-weight: bold; font-size: 12px">FORM EVALUASI PELATIHAN</div></td>
                        <td width="25%"><div class="center">No: <?php echo e($trx->id); ?></div></td>
                    </tr>
                </table>
                <?php
                    $data = $materi->firstWhere('urutan', 1);
                    $nik_property = json_decode($_trx->nik_property, true);
                ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong><?php echo e($data->label); ?>. <?php echo e($data->materi1); ?></strong></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="30%">Nama Peserta</td>
                        <td width="2%">:</td>
                        <td width="68%"><?php echo e($nik_property['nama']); ?></td>
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
                        <td><?php echo e($_int_ext); ?></td>
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
                        $tanggal_range = json_decode($trx->tanggal_range, true);
                        $formatted_ranges = array_map(function($range) {
                            [$mulai, $selesai] = array_map('trim', explode('s/d', $range));
                            return Carbon\Carbon::createFromFormat('Y-m-d', $mulai)->format('d-m-Y') . ' s/d ' . Carbon\Carbon::createFromFormat('Y-m-d', $selesai)->format('d-m-Y');
                        }, $tanggal_range);
                    ?>
                    <tr>
                        <td>Tanggal Pelatihan</td>
                        <td>:</td>
                        <td><?php $__currentLoopData = $formatted_ranges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_tanggal_range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($_tanggal_range); ?> <?php if(!$loop->last): ?> <br/> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                    </tr>
                </table>
                <hr/>
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
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong><?php echo e($data->label); ?>. <?php echo e($data->materi1); ?></strong></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <th width="4%">No</th>
                        <th width="50%">Pernyataan</th>
                        <th width="46%">Jawaban</th>
                    </tr>
                    <?php $__currentLoopData = $_materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($_data->nomor); ?></td>
                        <td><?php echo e($_data->materi2); ?></td>
                        <td>
                            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="radio" name="jawaban_<?php echo e($_data->id); ?>" id="jawaban_<?php echo e($_data->id); ?>" value="<?php echo e($val); ?>" <?php if($val == $_data->jawaban): ?>checked="checked"<?php endif; ?>>
                                <label for="jawaban_<?php echo e($_data->id); ?>"><?php echo e($text); ?></label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
                <hr/>
                <?php
                    $data = $materi->firstWhere('urutan', 8);
                    $_materi = $materi->where('label', 'C')->sortBy('nomor')->values();
                ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong><?php echo e($data->label); ?>. <?php echo e($data->materi1); ?></strong></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <th width="4%">No</th>
                        <th width="50%">Pernyataan</th>
                        <th width="46%">Jawaban</th>
                    </tr>
                    <?php $__currentLoopData = $_materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($_data->nomor); ?></td>
                        <td><?php echo e($_data->materi2); ?></td>
                        <td>
                            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="radio" name="jawaban_<?php echo e($_data->id); ?>" id="jawaban_<?php echo e($_data->id); ?>" value="<?php echo e($val); ?>" <?php if($val == $_data->jawaban): ?>checked="checked"<?php endif; ?>>
                                <label for="jawaban_<?php echo e($_data->id); ?>"><?php echo e($text); ?></label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
                <hr/>
                <?php
                    $data = $materi->firstWhere('urutan', 13);
                    $_materi = $materi->where('label', 'D')->sortBy('nomor')->values();
                ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong><?php echo e($data->label); ?>. <?php echo e($data->materi1); ?></strong></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <th width="4%">No</th>
                        <th width="50%">Pernyataan</th>
                        <th width="46%">Jawaban</th>
                    </tr>
                    <?php $__currentLoopData = $_materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($_data->nomor); ?></td>
                        <td><?php echo e($_data->materi2); ?></td>
                        <td>
                            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="radio" name="jawaban_<?php echo e($_data->id); ?>" id="jawaban_<?php echo e($_data->id); ?>" value="<?php echo e($val); ?>" <?php if($val == $_data->jawaban): ?>checked="checked"<?php endif; ?>>
                                <label for="jawaban_<?php echo e($_data->id); ?>"><?php echo e($text); ?></label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
                
            </td>
        </tr>
    </table>
</body>
<!-- finish -->
</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/evaluasi_pdf.blade.php ENDPATH**/ ?>