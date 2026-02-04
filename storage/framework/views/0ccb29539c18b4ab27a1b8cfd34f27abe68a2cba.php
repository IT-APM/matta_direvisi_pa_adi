<!DOCTYPE html>
<html lang="en">

<head>
    <title>Survey PORA 2026</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    
        <div class="row p-3">
            <h4 class="text-center">SURVEY CABANG OLAHRAGA PORA 2026</h4>
            <hr />
            <div class="col-12">
                <div style="text-align: justify;font-size: 15px;">
                    <p>Dear Insan ALITA,<br />Semangat Pagi!!!</p>
                    <p>Menjelang gelaran acara Pekan Olahraga Alita (PORA) 2026, mohon partisipasi dari seluruh Insan
                        ALITA untuk mengikuti survey pemilihan 4 (empat) cabang olahraga yang akan diadakan dalam PORA
                        2026. Survey ini akan dibuka dari <b>Senin 02 Februari s/d Kamis 05 Februari
                            2026</b>.<br />Terimakasih atas partisipasinya.</p>
                </div>
            </div>
            <div class="col-12">
                <div style="text-align: justify;font-size: 15px;">
                    <form id="survey_pora" action="<?php echo e(route('survey_pora')); ?>" method="post"
                        enctype="multipart/form-data"
                        onsubmit="return confirm('Are you sure want to submit this data?');">
                        <?php echo csrf_field(); ?>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td width="5%"><b>1</b></td>
                                    <td><b>Cabor GROUP (Pilih 2)</b><br />
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="futsal"
                                                id="opsi1[]" name="opsi1[]">Futsal (5 vs 5)
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="voli"
                                                id="opsi1[]" name="opsi1[]">Voli (6 vs 6)
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="basket"
                                                id="opsi1[]" name="opsi1[]">Basket (3 on 3)
                                        </div>
                                        <small id="error_opsi1" class="text-danger" style="display:none;">Wajib Pilih
                                            2</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>2</b></td>
                                    <td><b>Cabor INDIVIDU / DUO (Pilih 1)</b><br />
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="badminton"
                                                id="opsi2[]" name="opsi2[]">Badminton (Ganda Putra & Ganda Putri)
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="tenis"
                                                id="opsi2[]" name="opsi2[]">Tenis Meja (Ganda Putra)
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="renang"
                                                id="opsi2[]" name="opsi2[]">Renang (Individu Putra & Putri)
                                        </div>
                                        <small id="error_opsi2" class="text-danger" style="display:none;">Wajib Pilih
                                            1</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>3</b></td>
                                    <td><b>Cabor E-SPORT (Pilih 1)</b><br />
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="ml"
                                                id="opsi3[]" name="opsi3[]">Mobile Legend (5 vs 5)
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="fifa"
                                                id="opsi3[]" name="opsi3[]">FIFA 26 (2 vs 2)
                                        </div>
                                        <small id="error_opsi3" class="text-danger" style="display:none;">Wajib Pilih
                                            1</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <input type="hidden" id="back_url" name="back_url" value="<?php echo e(URL::full()); ?>">
                        <button class="btn btn-warning mt-2" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    

</body>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
document.getElementById('survey_pora').addEventListener('submit', function(e){
    e.preventDefault();
    const checked_opsi1 = document.querySelectorAll('input[name="opsi1[]"]:checked');
    const checked_opsi2 = document.querySelectorAll('input[name="opsi2[]"]:checked');
    const checked_opsi3 = document.querySelectorAll('input[name="opsi3[]"]:checked');
    let valid = true;
    if(checked_opsi1.length !== 2){
        document.getElementById('error_opsi1').style.display = 'inline';
        valid = false;
    } else {
        document.getElementById('error_opsi1').style.display = 'none';
    }
    if(checked_opsi2.length !== 1){
        document.getElementById('error_opsi2').style.display = 'inline';
        valid = false;
    } else {
        document.getElementById('error_opsi2').style.display = 'none';
    }
    if(checked_opsi3.length !== 1){
        document.getElementById('error_opsi3').style.display = 'inline';
        valid = false;
    } else {
        document.getElementById('error_opsi3').style.display = 'none';
    }
    if(valid){
        e.target.submit();
    }
});
</script>
</html>
<?php /**PATH C:\xampp\htdocs\matta\resources\views/dashboard/survey_pora_mobile.blade.php ENDPATH**/ ?>