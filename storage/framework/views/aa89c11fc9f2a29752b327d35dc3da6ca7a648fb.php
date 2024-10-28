<!DOCTYPE html>
<html>

<head>
    <title><?php if(!empty($_trx)): ?><?php echo e($trx->_pekerjaan->pekerjaan); ?><?php else: ?> Job Vacancy <?php endif; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    /* body {
        background-image: url('<?php echo e(asset('assets/recruitment/background_page-0001.jpg')); ?>');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        background-size: 1020px;
    } */
</style>

<body>
    <?php if(!empty($_trx)): ?>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <img class="img-fluid" style="height: 90px" loading="lazy" src="<?php echo e(asset('assets/recruitment/header_left.png')); ?>" alt="image">
            </div>
            <div class="col-6 text-end">
                <img class="img-fluid" style="height: 90px" loading="lazy" src="<?php echo e(asset('assets/recruitment/header_right.png')); ?>" alt="image">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p>&nbsp;</p>
                <?php echo $_trx->job_description; ?>

                <div class="text-center">
                    <h5>Scan here for apply</h5>
                    <?php echo e(QrCode::size(100)->generate(route('recruitment.apply', [Crypt::encrypt(array($_trx->id, 'apply'))]))); ?>

                    
                </div>
            </div>
            <div class="col-12 text-center mt-5" style="font-size:20px">
                <a href="https://alita.id/" target="_blank">www.alita.id</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/posting.blade.php ENDPATH**/ ?>