<!DOCTYPE html>
<html>

<head>
    <title><?php if(!empty($_trx)): ?><?php echo e($trx->_pekerjaan->pekerjaan); ?><?php else: ?> Job Vacancy <?php endif; ?></title>
    
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/bootstrap.css')); ?>">
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
    <div class="container" id="content">
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
            <div class="col-12 text-center mt-2" style="font-size:20px">
                <a href="https://alita.id/" target="_blank">www.alita.id</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center mt-2">
            <button class="btn btn-warning" id="downloadButton">Download as PDF</button>
        </div>
    </div>
    <?php endif; ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script>
        document.getElementById('downloadButton').addEventListener('click', function() {
            const element = document.getElementById('content');
            // Convert HTML to PDF and trigger download
            html2pdf(element, {
                filename: 'Job Vacancy_<?php echo e(Carbon\Carbon::now()->timestamp); ?>.pdf',
                margin: 2,
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
            });
        });
    </script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/posting.blade.php ENDPATH**/ ?>