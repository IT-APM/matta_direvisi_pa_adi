<?php $__env->startSection('title', 'Biaya Perjalanan Dinas'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>
    <?php if($trxMaster->luar_dalam == 'ca_dalam'): ?> Realisasi Perjalanan Dinas Dalam Negeri
    <?php elseif($trxMaster->luar_dalam == 'ca_luar'): ?> Realisasi Perjalanan Dinas Luar Negeri
    <?php endif; ?>
</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-xl-12">
            <div class="card">
            <?php if(Session::has('error')): ?>
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                    <?php echo session('error'); ?>

                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                <?php if(count($errors) > 0): ?>
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert">
                    <h6>Upload image is ERROR</h6>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                <div class="card-body">
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
                    <hr/>
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
                            <input class="form-control" <?php if(empty($trxMaster->kelompok_id)): ?> value="Project: <?php echo e($kelompok['project']); ?> / Corporate: <?php echo e($kelompok['nonproject']); ?>" <?php else: ?> value="<?php if($trxMaster->is_project==1): ?>Project: <?php else: ?> Corporate: <?php endif; ?> <?php echo e($trxMaster->_kelompok->kelompok); ?>" <?php endif; ?>>
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
                    <?php
                    $total_p_idr = 0;
                    $total_p_usd = 0;
                    $total_l_idr = 0;
                    $total_l_usd = 0;
                    $total_p_idr_realisasi = 0;
                    $total_p_usd_realisasi = 0;
                    $total_l_idr_realisasi = 0;
                    $total_l_usd_realisasi = 0;
                    ?>
                    <!-- 18=Realisasi CA, 19=Realisasi Disetujui Admin, 22=Realisasi Disetujui PC, 24=Realisasi Disetujui L1, 26=Realisasi Disetujui L2, 28=Realisasi Disetujui HCO -->
                    <?php if($trxMaster->status_id > 13): ?>
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
                        <hr/>
                        <!-- start ca -->
                        <h5>Cash Advance Biaya Perjalanan Dinas</h5>
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
                                        <tr>
                                            <td><?php echo e(++$no_biaya_info); ?></td>
                                            <td><?php echo e($kode_coa[0]); ?></td>
                                            <?php if($trxMaster->luar_dalam == 'ca_dalam'): ?>
                                            <td><?php echo e($kode_coa[1]); ?> <?php if(!empty($data->id_info)): ?> (<?php echo e($data->_info->_tujuan1_dalam->propinsi); ?>) <?php endif; ?></td>
                                            <?php elseif($trxMaster->luar_dalam == 'ca_luar'): ?>
                                            <td><?php echo e($kode_coa[1]); ?> <?php if(!empty($data->id_info)): ?> (<?php echo e($data->_info->_tujuan1_luar->negara); ?>) <?php endif; ?></td>
                                            <?php endif; ?>
                                            <td><?php echo e($data->jumlah); ?></td>
                                            <td><?php echo e(number_format($data->nominal, 0)); ?></td>
                                            <td><?php echo e($data->satuan); ?></td>
                                            <td><?php echo e(number_format($data->nominal * $data->jumlah, 0)); ?></td>
                                        </tr>
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
                                            <th scope="col">Nominal</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Total</th>
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
                                        <tr>
                                            <td><?php echo e(++$no_biaya); ?></td>
                                            <td><?php echo e($kode_coa[0]); ?></td>
                                            <td><?php echo e($kode_coa[1]); ?></td>
                                            <td><?php echo e($data->jumlah); ?></td>
                                            <td><?php echo e(number_format($data->nominal, 0)); ?></td>
                                            <td><?php echo e($data->satuan); ?></td>
                                            <td><?php echo e(number_format($data->nominal * $data->jumlah, 0)); ?></td>
                                        </tr>
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
                        <!-- finish ca -->
                        <hr/>
                        <!-- start realisasi -->
                        <h5>Realisasi Biaya Perjalanan Dinas</h5>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_info_realisasi">
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
                                        <?php $__currentLoopData = $trxDetailInfoRealisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(++$no_info_realisasi); ?></td>
                                            <?php if($trxMaster->luar_dalam=='ca_dalam'): ?>
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
                        <?php if(count($trxDetailBiayaPersonelRealisasi)>0): ?>
                        <h6>Biaya Personel</h6>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table class="table" id="table_biaya_coa_3">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Kode COA</th>
                                            <th scope="col" width="40%">Keterangan</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Nominal</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $trxDetailBiayaPersonelRealisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $kode_coa = explode('-', $data->kode_coa);
                                        if($data->satuan == 'IDR'){
                                            $total_p_idr_realisasi += $data->nominal * $data->jumlah;
                                        }else if($data->satuan == 'USD'){
                                            $total_p_usd_realisasi += $data->nominal * $data->jumlah;
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo e(++$no_biaya_info_realisasi); ?></td>
                                            <?php if(count($kode_coa)>1): ?>
                                                <td><?php echo e($kode_coa[0]); ?></td>
                                                <?php if($trxMaster->luar_dalam == 'ca_dalam'): ?>
                                                <td><?php echo e($kode_coa[1]); ?> <?php if(!empty($data->id_info)): ?> (<?php echo e($data->_info->_tujuan1_dalam->propinsi); ?>) <?php endif; ?></td>
                                                <?php elseif($trxMaster->luar_dalam == 'ca_luar'): ?>
                                                <td><?php echo e($kode_coa[1]); ?> <?php if(!empty($data->id_info)): ?> (<?php echo e($data->_info->_tujuan1_luar->negara); ?>) <?php endif; ?></td>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <td>&nbsp;</td>
                                                <td><?php echo e($data->biaya); ?></td>
                                            <?php endif; ?>
                                            <td><?php echo e($data->jumlah); ?></td>
                                            <td><?php echo e(number_format($data->nominal, 0)); ?></td>
                                            <td><?php echo e($data->satuan); ?></td>
                                            <td><?php echo e(number_format($data->nominal * $data->jumlah, 0)); ?></td>
                                        </tr>
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
                                            <th scope="col"><?php echo e(number_format($total_p_idr_realisasi, 0)); ?><br/><?php echo e(number_format($total_p_usd_realisasi, 0)); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>
                        <!-- jika ada trxDetailBiayaRealisasi -->
                        <?php if(count($trxDetailBiayaRealisasi)>0): ?>
                            <h6>Biaya Lain-lain</h6>
                            <div class="row mb-3">
                                <div class="table-responsive">
                                    <table class="table" id="table_biaya_coa_4">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Bukti</th>
                                                <th scope="col">Kode COA</th>
                                                <th scope="col">Keterangan</th>
                                                <th scope="col">Qty</th>
                                                <th scope="col">Nominal</th>
                                                <th scope="col">Satuan</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $trxDetailBiayaRealisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                            $kode_coa = explode('-', $data->kode_coa);
                                            if($data->satuan == 'IDR'){
                                                $total_l_idr_realisasi += $data->nominal * $data->jumlah;
                                            }else if($data->satuan == 'USD'){
                                                $total_l_usd_realisasi += $data->nominal * $data->jumlah;
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo e(++$no_biaya_realisasi); ?></td>
                                                <td><a href="<?php echo e(asset('assets/bpd/'.$data->lampiran)); ?>" target="_blank"><i class="icon-file"></i>&nbsp;</a></td>
                                                <td><?php echo e($kode_coa[0]); ?></td>
                                                <td><?php echo e($kode_coa[1]); ?></td>
                                                <td><?php echo e($data->jumlah); ?></td>
                                                <td><?php echo e(number_format($data->nominal, 0)); ?></td>
                                                <td><?php echo e($data->satuan); ?></td>
                                                <td><?php echo e(number_format($data->nominal * $data->jumlah, 0)); ?></td>
                                                <td><?php if($data->is_ca == 1): ?> Realisasi <?php elseif($data->is_ca == 2): ?> Reimbursement <?php endif; ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">Total Realisasi</th>
                                                <th scope="col">IDR<br/>USD</th>
                                                <th scope="col"><?php echo e(number_format($total_p_idr_realisasi+$total_l_idr_realisasi, 0)); ?><br/><?php echo e(number_format($total_p_usd_realisasi+$total_l_usd_realisasi, 0)); ?></th>
                                            </tr>
                                        </tfoot>
                                        <tfoot>
                                            <tr>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">Total Biaya Lain-lain</th>
                                                <th scope="col">IDR<br/>USD</th>
                                                <th scope="col"><?php echo e(number_format($total_l_idr_realisasi, 0)); ?><br/><?php echo e(number_format($total_l_usd_realisasi, 0)); ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <hr/>
                        <?php endif; ?>
                        <!--  -->
                            <h5>Kesimpulan</h5>
                            <div class="row mb-3">
                                <div class="table-responsive">
                                    <table class="table" id="kesimpulan">
                                        <thead>
                                            <tr>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">Total Biaya Personel</th>
                                                <th scope="col">Total Biaya Lain-lain</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="col">Cash Advance</th>
                                                <th scope="col">IDR <?php echo e(number_format($total_p_idr, 0)); ?><br/>USD <?php echo e(number_format($total_p_usd, 0)); ?></th>
                                                <th scope="col">IDR <?php echo e(number_format($total_l_idr, 0)); ?><br/>USD <?php echo e(number_format($total_l_usd, 0)); ?></th>
                                                <th scope="col">IDR <?php echo e(number_format($total_p_idr+$total_l_idr, 0)); ?><br/>USD <?php echo e(number_format($total_p_usd+$total_l_usd, 0)); ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Realisasi</th>
                                                <th scope="col">IDR <?php echo e(number_format($total_p_idr_realisasi, 0)); ?><br/>USD <?php echo e(number_format($total_p_usd_realisasi, 0)); ?></th>
                                                <th scope="col">IDR <?php echo e(number_format($total_l_idr_realisasi, 0)); ?><br/>USD <?php echo e(number_format($total_l_usd_realisasi, 0)); ?></th>
                                                <th scope="col">IDR <?php echo e(number_format($total_p_idr_realisasi+$total_l_idr_realisasi, 0)); ?><br/>USD <?php echo e(number_format($total_p_usd_realisasi+$total_l_usd_realisasi, 0)); ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Diterima / Dikembalikan</th>
                                                <th scope="col">IDR <?php echo e(number_format($total_p_idr-$total_p_idr_realisasi, 0)); ?><br/>USD <?php echo e(number_format($total_p_usd-$total_p_usd_realisasi, 0)); ?></th>
                                                <th scope="col">IDR <?php echo e(number_format($total_l_idr-$total_l_idr_realisasi, 0)); ?><br/>USD <?php echo e(number_format($total_l_usd-$total_l_usd_realisasi, 0)); ?></th>
                                                <th scope="col">IDR <?php echo e(number_format($total_p_idr+$total_l_idr-$total_p_idr_realisasi-$total_l_idr_realisasi, 0)); ?><br/>USD <?php echo e(number_format($total_p_usd+$total_l_usd-$total_p_usd_realisasi-$total_l_usd_realisasi, 0)); ?></th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr/>
                        <!--  -->
                        <!-- finish realisasi -->
                        <form method="post" enctype="multipart/form-data" action="<?php echo e($linkAction); ?>" target="_self"
                            class="needs-validation" novalidate=""
                            onsubmit="return confirm('Are you sure want to submit this data?');">
                            <?php echo method_field('PATCH'); ?>
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="nik" name="nik" value="<?php echo e($nik); ?>">
                            <h5>Catatan</h5>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <textarea class="form-control" id="catatan" name="catatan" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <button type="submit" name="status_id" class="btn btn-success" value="<?php echo e($approve); ?>">Approve</button>
                                    <button type="submit" name="status_id" class="btn btn-danger" value="<?php echo e($reject); ?>">Reject</button>
                                    <!-- <a class="btn btn-warning" href="<?php echo e(route('bpd.index')); ?>">Back</a> -->
                                </div>
                            </div>
                        </form>
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
        <!--  -->
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
    $(document).ready(function () {
        // Biaya Perjalanan Dinas
        var counter_biaya = 1;
        $("#insertRowbiaya").on("click", function (event) {
            event.preventDefault();
            var newRow = $('<tr id="tr_biaya">');
            var id = $("#is_project").val();
            var cols = '';
            // Table columns
            cols += '<th scrope="row">' + counter_biaya + '</th>';
            cols += '<td><input name="lampiran_' + counter_biaya + '" id="lampiran_' + counter_biaya + '" class="form-control" type="file" required=""></td>';
            <?php if($trxMaster->is_project == 1): ?>
                cols += '<td><select class="form-select col-sm-12" id="biaya[]" name="biaya[]" required=""><option selected="" disabled="" value="">Pilih...</option><?php $__currentLoopData = $coa->coa_1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($data->id); ?>"><?php echo e($data->account); ?>-<?php echo e($data->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>';
            <?php elseif($trxMaster->is_project == 0): ?>
                cols += '<td><select class="form-select col-sm-12" id="biaya[]" name="biaya[]" required=""><option selected="" disabled="" value="">Pilih...</option><?php $__currentLoopData = $coa->coa_0; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($data->id); ?>"><?php echo e($data->account); ?>-<?php echo e($data->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>';
            <?php endif; ?>
            cols += '<td><select class="form-select col-sm-12" id="satuan[]" name="satuan[]" required=""><option selected="" disabled="" value="">Pilih...</option><option value="IDR">IDR</option><option value="USD">USD</option></select></td>';
            cols += '<td><input min=1 type="number" name="jumlah[]" id="jumlah[]" class="form-control numbers" required=""></td>';
            cols += '<td><input onfocus="use_number(this)" onblur="use_text(this)" min=1 type="number" name="nominal_' + counter_biaya + '" id="nominal_' + counter_biaya + '" class="form-control numbers" required=""></td>';
            cols += '<td><button class="btn btn-danger" id ="deleteRowbiaya"><i class="fa fa-trash"></i></button></td>';
            // Insert the columns inside a row
            newRow.append(cols);
            // Insert the row inside a table
            $("#table_biaya").append(newRow);
            // Increase counter_biaya after each row insertion
            counter_biaya++;
        });
        // Remove row when delete btn is clicked
        $("#table_biaya").on("click", "#deleteRowbiaya", function (event) {
            $(this).closest("tr").remove();
            // counter_biaya -= 1
        });
    });
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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/bpd/realisasi_approval.blade.php ENDPATH**/ ?>