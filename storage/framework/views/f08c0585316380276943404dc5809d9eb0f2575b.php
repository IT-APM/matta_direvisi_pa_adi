<head>
    <title>Perubahan Data Karyawan</title>
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<table class="table">
    <tr>
        <td>
            <h4>Semangat Pagi</h4>
            <h4>Terdapat Perubahan Data Karyawan oleh <?php echo e($data['insert_by']); ?></h4>
            <table class="table" style="width:100%" border="1" cellspacing="0" cellpadding="2">
                <thead>
                    <tr>
                        <th style="width:20%">Karyawan</th>
                        <th style="width:60%">Perubahan Data</th>
                        <th style="width:20%">Waktu Perubahan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $_history = json_decode($data['perubahan'], true); ?>
                    <tr>
                        <td><?php echo e($data['nama']); ?> / <?php echo e($data['nik']); ?></td>
                        <td>
                            <table class="table table-striped" style="width:100%" border="1" cellspacing="0" cellpadding="2">
                                <thead>
                                    <tr>
                                        <th style="width:20%">Kolom</th>
                                        <th style="width:40%">Sebelum</th>
                                        <th style="width:40%">Sesudah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $_history = json_decode($data['perubahan'], true); ?>
                                    <?php $__currentLoopData = $_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($data['lbl_history'][$_data]); ?></td>
                                        <td><?php echo e($val['before']); ?></td>
                                        <td><?php echo e($val['after']); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </td>
                        <td><?php echo e($data['created_at']); ?></td>
                    </tr>
                </tbody>
            </table>
            <p>Best Regards,<br/>Human Capital & Organization<br/><a href="https://alita.id/" target="_blank">www.alita.id</a><br/><strong>"Passion - Integrity - Excellent"</strong></p>
        </td>
    </tr>
</table><?php /**PATH C:\xampp\htdocs\matta\resources\views/emails/data0.blade.php ENDPATH**/ ?>