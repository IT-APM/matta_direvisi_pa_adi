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
                    // $data = $materi->firstWhere('urutan', 2);
                    $_materi = $materi->where('label', 'B')->sortBy('nomor')->values();
                    $options = [
                        4 => 'Sangat Baik',
                        3 => 'Baik',
                        2 => 'Cukup',
                        1 => 'Kurang',
                    ];
                ?>
                
                <?php
                    $data = $materi->firstWhere('urutan', 18);
                    $_materi = $materi->where('label', 'E')->sortBy('nomor')->values();
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
                    $data = $materi->firstWhere('urutan', 22);
                    $_materi = $materi->where('label', 'F')->sortBy('nomor')->values();
                ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong><?php echo e($data->label); ?>. <?php echo e($data->materi1); ?></strong></td>
                    </tr>
                </table>
                <hr/>
                <?php $__currentLoopData = $_materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <th width="54%"><?php echo e($_data->nomor); ?>. <?php echo e($_data->materi2); ?></th>
                        <td width="46%"><?php echo e($_data->jawaban); ?></td>
                    </tr>
                </table>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <hr/>
                <?php
                    $data = $materi->firstWhere('urutan', 25);
                    $_materi = $materi->where('label', 'G')->sortBy('nomor')->values();
                ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="center"><strong><?php echo e($data->label); ?>. <?php echo e($data->materi1); ?></strong></td>
                    </tr>
                </table>
                <hr/>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <?php $__currentLoopData = $_materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td width="54%"><?php echo e($_data->materi2); ?></td>
                        <td width="46%">
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

</html><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/pelatihan/evaluasi_pdf_next.blade.php ENDPATH**/ ?>