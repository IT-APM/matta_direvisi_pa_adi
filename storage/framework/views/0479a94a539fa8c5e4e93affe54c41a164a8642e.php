<?php $__env->startSection('title', 'Biaya Perjalanan Dinas'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>
    <?php if($trxMaster->luar_dalam == 'ca_dalam'): ?> Cash Advance Perjalanan Dinas Dalam Negeri
    <?php elseif($trxMaster->luar_dalam == 'ca_luar'): ?> Cash Advance Perjalanan Dinas Luar Negeri
    <?php elseif($trxMaster->luar_dalam == 'reimburse'): ?> Reimbursement
    <?php endif; ?>
</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <?php if(Session::has('msg')): ?>
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <h5>Pelaksana Perjalanan Dinas</h5>
                    <?php
                    $pengaju_property = json_decode($trxMaster->pengaju_property, true);
                    $atasan_property = json_decode($trxMaster->atasan_property, true);
                    ?>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Nama / NIP Karyawan</label>
                            <input class="form-control" value="<?php echo e($pengaju_property['nama']); ?> / <?php echo e($pengaju_property['nik']); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Nama / NIP Atasan Langsung</label>
                            <input class="form-control" value="<?php echo e($atasan_property['nama']); ?> / <?php echo e($atasan_property['nik']); ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Level</label>
                            <input class="form-control" value="<?php echo e($pengaju_property['id_level']); ?> - <?php echo e($pengaju_property['level']); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Jabatan</label>
                            <input class="form-control" value="<?php echo e($pengaju_property['jabatan']); ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Perusahaan</label>
                            <input class="form-control" value="<?php echo e($pengaju_property['perusahaan']); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Direktorat</label>
                            <input class="form-control" value="<?php echo e($pengaju_property['divisi']); ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Divisi</label>
                            <input class="form-control" value="<?php echo e($pengaju_property['dep']); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Departemen</label>
                            <input class="form-control" value="<?php echo e($pengaju_property['sub_dep']); ?>">
                        </div>
                    </div>
                    <hr />
                    <h5>Informasi Perjalanan Dinas</h5>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="form-label">Tahun Pengajuan</label>
                            <input class="form-control" value="<?php echo e($trxMaster->periode); ?>">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Golongan</label>
                            <input class="form-control" value="<?php if($trxMaster->is_leader==1): ?> Leader <?php elseif($trxMaster->is_leader==2): ?> Member <?php elseif($trxMaster->is_leader==3): ?> Management <?php endif; ?>">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Kelompok Perjalanan Dinas</label>
                            <input class="form-control" value="<?php if($trxMaster->is_project==1): ?>Project: <?php else: ?> Corporate: <?php endif; ?> <?php echo e($trxMaster->_kelompok->kelompok); ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8">
                            <label class="form-label">Uraian Tugas Perjalanan Dinas</label>
                            <textarea class="form-control" rows="2"><?php echo e($trxMaster->uraian); ?></textarea>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Lampiran</label><br />
                            <a href="<?php echo e(asset('assets/bpd/'.$trxMaster->lampiran)); ?>" target="_blank"><i class="icon-file"></i>&nbsp;Download Lampiran</a>
                        </div>
                    </div>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_info">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tujuan</th>
                                            <th scope="col">Tanggal dari</th>
                                            <th scope="col">Tanggal sampai</th>
                                            <th scope="col">Jumlah hari</th>
                                            <th scope="col">Uang saku</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $trxDetailInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(++$no_info); ?></td>
                                            <?php if($trxMaster->luar_dalam=='ca_dalam'): ?>
                                            <td><?php echo e($data->_tujuan1_dalam->propinsi); ?> - <?php echo e($data->_tujuan2_dalam->kota); ?></td>
                                            <?php elseif($trxMaster->luar_dalam=='reimburse'): ?>
                                            <td><?php echo e($data->_tujuan1_dalam->propinsi); ?> - <?php echo e($data->_tujuan2_dalam->kota); ?></td>
                                            <?php elseif($trxMaster->luar_dalam=='ca_luar'): ?>
                                            <td><?php echo e($data->_tujuan1_luar->negara); ?></td>
                                            <?php endif; ?>
                                            <td><?php echo e(\Carbon\Carbon::parse($data->tanggal_mulai)->format('d-m-Y')); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($data->tanggal_akhir)->format('d-m-Y')); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($data->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($data->tanggal_akhir))+1); ?></td>
                                            <td><?php if($data->is_approve==1): ?> Ada <?php else: ?> Tidak ada <?php endif; ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-2">
                                <label class="form-label">Jenis BPD</label>
                                <input class="form-control" <?php if($trxMaster->is_project == 1): ?> value="Project" <?php else: ?> value="Corporate" <?php endif; ?>">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Project ID</label>
                                <input class="form-control" value="<?php echo e($trxMaster->_project->project_id); ?> / <?php echo e($trxMaster->_project->name); ?> / <?php echo e($trxMaster->_project->group); ?>">
                            </div>
                            <?php if($trxMaster->is_project == 1): ?>
                            <?php
                            $koordinator_property = json_decode($trxMaster->koordinator_property, true);
                            ?>
                            <div class="col-sm-4">
                                <label class="form-label">Coordinator</label>
                                <input class="form-control" value="<?php echo e($koordinator_property['nama']); ?> / <?php echo e($koordinator_property['nik']); ?>">
                            </div>
                            <?php endif; ?>
                        </div>
                        <hr />
                        <h5>Biaya Perjalanan Dinas</h5>
                        <?php
                        $total_p_idr = 0;
                        $total_p_usd = 0;
                        ?>
                        <?php if(count($trxDetailBiayaPersonel)>0): ?>
                        <h6>Biaya Personel</h6>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_biaya_coa_1">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Kode COA</th>
                                            <th scope="col" width="40%">Keterangan</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Nominal</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $trxDetailBiayaPersonel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $kode_coa = explode('-', $data->kode_coa);
                                        if($data->satuan == 'IDR'){
                                            $total_p_idr += $data->nominal * $data->jumlah;
                                        }else if($data->satuan == 'USD'){
                                            $total_p_usd += $data->nominal * $data->jumlah;
                                        }
                                        ?>
                                        <form method="post" enctype="multipart/form-data" action="<?php echo e(route('bpd.update_biaya', Crypt::encrypt($trxMaster->id))); ?>" target="_self"
                                        class="needs-validation" novalidate=""
                                        onsubmit="return confirm('Are you sure want to update this data?');">
                                        <?php echo method_field('POST'); ?>
                                        <?php echo csrf_field(); ?>
                                        <tr>
                                            <td><?php echo e(++$no_biaya_info); ?></td>
                                            <td><?php echo e($kode_coa[0]); ?></td>
                                            <?php if($trxMaster->luar_dalam == 'ca_dalam'): ?>
                                            <td><?php echo e($kode_coa[1]); ?> <?php if(!empty($data->id_info)): ?> (<?php echo e($data->_info->_tujuan1_dalam->propinsi); ?>) <?php endif; ?></td>
                                            <?php elseif($trxMaster->luar_dalam == 'reimburse'): ?>
                                            <td><?php echo e($kode_coa[1]); ?> <?php if(!empty($data->id_info)): ?> (<?php echo e($data->_info->_tujuan1_dalam->propinsi); ?>) <?php endif; ?></td>
                                            <?php elseif($trxMaster->luar_dalam == 'ca_luar'): ?>
                                            <td><?php echo e($kode_coa[1]); ?> <?php if(!empty($data->id_info)): ?> (<?php echo e($data->_info->_tujuan1_luar->negara); ?>) <?php endif; ?></td>
                                            <?php endif; ?>
                                            <td><?php echo e($data->jumlah); ?></td>
                                            <td>
                                                <?php
                                                    $nominal = App\Http\Controllers\OpenDataController::getUangSaku($data->id);
                                                ?>
                                                <select class="form-select col-sm-12" id="nominal_<?php echo e($data->id); ?>" name="nominal_<?php echo e($data->id); ?>">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    <?php for($x = 0; $x < count($nominal); $x++): ?> {
                                                    <option value="<?php echo e($nominal[$x]); ?>" <?php if($nominal[$x]==$data->nominal): ?> selected <?php endif; ?> ><?php echo e(number_format($nominal[$x], 0)); ?></option>
                                                    }
                                                    <?php endfor; ?>
                                                </select>
                                            </td>
                                            <td><?php echo e($data->satuan); ?></td>
                                            <td><?php echo e(number_format($data->nominal * $data->jumlah, 0)); ?></td>
                                            <td>
                                                <button type="submit" name="id_data" class="btn btn-success" value="<?php echo e($data->id); ?>"><i class="fa fa-save"></i></button>
                                            </td>
                                        </tr>
                                        </form>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">Total Biaya Personel</th>
                                            <th scope="col">IDR<br/>USD</th>
                                            <th scope="col"><?php echo e(number_format($total_p_idr, 0)); ?><br/><?php echo e(number_format($total_p_usd, 0)); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php
                        $total_l_idr = 0;
                        $total_l_usd = 0;
                        ?>
                        <?php if(count($trxDetailBiaya)>0): ?>
                        <h6>Biaya Lain-lain</h6>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_biaya_coa_2">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Kode COA</th>
                                            <th scope="col" width="40%">Keterangan</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col" width="15%">Nominal</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $trxDetailBiaya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $kode_coa = explode('-', $data->kode_coa);
                                        if($data->satuan == 'IDR'){
                                            $total_l_idr += $data->nominal * $data->jumlah;
                                        }else if($data->satuan == 'USD'){
                                            $total_l_usd += $data->nominal * $data->jumlah;
                                        }
                                        ?>
                                        <form method="post" enctype="multipart/form-data" action="<?php echo e(route('bpd.update_biaya', Crypt::encrypt($trxMaster->id))); ?>" target="_self"
                                        class="needs-validation" novalidate=""
                                        onsubmit="return confirm('Are you sure want to update this data?');">
                                        <?php echo method_field('POST'); ?>
                                        <?php echo csrf_field(); ?>
                                        <tr>
                                            <td><?php echo e(++$no_biaya); ?></td>
                                            <td><?php echo e($kode_coa[0]); ?></td>
                                            <td><?php echo e($kode_coa[1]); ?></td>
                                            <td><?php echo e($data->jumlah); ?></td>
                                            <!-- <td><?php echo e(number_format($data->nominal, 0)); ?></td> -->
                                            <td>
                                                <input onfocus="use_number(this)" onblur="use_text(this)" min=1 type="number" name="nominal_<?php echo e($data->id); ?>" id="nominal_<?php echo e($data->id); ?>" class="form-control numbers" required="" value="<?php echo e($data->nominal); ?>">
                                            </td>
                                            <td><?php echo e($data->satuan); ?></td>
                                            <td><?php echo e(number_format($data->nominal * $data->jumlah, 0)); ?></td>
                                            <td>
                                                <button type="submit" name="id_data" class="btn btn-success" value="<?php echo e($data->id); ?>"><i class="fa fa-save"></i></button>
                                            </td>
                                        </tr>
                                        </form>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">Total Cash Advance</th>
                                            <th scope="col">IDR<br/>USD</th>
                                            <th scope="col"><?php echo e(number_format($total_p_idr+$total_l_idr, 0)); ?><br/><?php echo e(number_format($total_p_usd+$total_l_usd, 0)); ?></th>
                                        </tr>
                                    </tfoot>
                                    <tfoot>
                                        <tr>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">Total Biaya Lain-lain</th>
                                            <th scope="col">IDR<br/>USD</th>
                                            <th scope="col"><?php echo e(number_format($total_l_idr, 0)); ?><br/><?php echo e(number_format($total_l_usd, 0)); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body typography text-center">
                    <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review ini.</h6>
                    <a href="https://alita.id/"><img src="<?php echo e(asset('assets/images/matta/Alita-New-Log.png')); ?>" height="40" alt="Alita" /></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/sweet-alert/app.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script type="text/javascript">
    // 
    function use_number(node) {
        var empty_val = false;
        const value = node.value;
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
            node.value = value.toLocaleString('en'); // or other formatting
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/bpd/edit.blade.php ENDPATH**/ ?>