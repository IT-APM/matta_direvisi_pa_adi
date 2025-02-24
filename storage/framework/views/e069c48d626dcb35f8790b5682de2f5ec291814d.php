<?php $__env->startSection('title', 'Recruitment'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<style>
    button.dt-button {
    float:none;
    text-align:center;
    color: black;
    background: white;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>List Of Applicant</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Recruitment</li>
<li class="breadcrumb-item active">List Of Applicant</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if(Session::has('msg')): ?>
                    <div class="alert alert-success dark alert-dismissible fade show" role="alert">
                        <h6><?php echo e(session('msg')); ?></h6>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="pills-psychotest-tab" data-bs-toggle="pill" href="#pills-psychotest" role="tab" aria-controls="pills-psychotest" aria-selected="false">Psychotest</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-interview-tab" data-bs-toggle="pill" href="#pills-interview" role="tab" aria-controls="pills-interview" aria-selected="true">Interview</a></li>
                        <li class="nav-item"><a class="nav-link" id="pills-negotiation-tab" data-bs-toggle="pill" href="#pills-negotiation" role="tab" aria-controls="pills-negotiation" aria-selected="true">Negotiation</a></li>
                    </ul>
                    <div class="tab-content" id="pills-warningtabContent">
                        
                        <div class="tab-pane fade show active" id="pills-psychotest" role="tabpanel" aria-labelledby="pills-psychotest-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>#ID</th>
                                                <th>Action</th>
                                                <th>&nbsp;Hasil&nbsp;</th>
                                                <th>Posisi</th>
                                                <th>Nama</th>
                                                
                                                <th>CV</th>
                                                <th>Check_in</th>
                                                <th>Check_out</th>
                                                <th>Interpretasi</th>
                                                <th>Offering Letter</th>
                                                <th>Upload</th>
                                                <th>Status</th>
                                                <th>Created_at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; ?>
                                            <?php $__currentLoopData = $trx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $recruitment = App\Models\TrxKebutuhanMaster::find($data->id_kebutuhan);
                                                $nomor = $data->id_kebutuhan.'/'.$data->id;
                                                $where = ['id_identitas' => $data->id, 'jenis' => 4];
                                                $pohon = App\Models\TrxRecruitmentPsychotest::where($where)->where('nomor', '>', 0)->get();
                                                $where = ['id_identitas' => $data->id, 'jenis' => 5];
                                                $manusia = App\Models\TrxRecruitmentPsychotest::where($where)->where('nomor', '>', 0)->get();
                                                // cek apakah sudah ada soal psikotes
                                                $check1 = App\Models\TrxRecruitmentPsychotest::where(['id_identitas' => $data->id])->exists();
                                                // cek apakah masih ada yang belum selesai
                                                $check2 = App\Models\TrxRecruitmentPsychotest::where(['id_identitas' => $data->id])->whereNull('check_out')->exists();
                                                // jika ada soal dan sudah selesai maka tampilkan hasilnya
                                                $check = $check1 && !$check2;
                                                // $status = App\Models\TrxRecruitmentIdentitas::find($data->id);
                                                $log = App\Models\TrxRecruitmentLog::where(['id_identitas'=>$data->id])->pluck('id_status')->toArray();
                                                // dd($log);
                                            ?>
                                            <tr>
                                                <td><?php echo e(++$no); ?></td>
                                                <td><?php echo e($nomor); ?></td>
                                                <td>
                                                    <a href="<?php echo e(route('recruitment.applicant_process', [Crypt::encrypt($data->id)])); ?>" data-toggle="tooltip" title='Process'><i class="icon-pencil-alt"></i></a>
                                                    <br/><a href="<?php echo e(route('recruitment.applicant_item', [Crypt::encrypt($data->id)])); ?>" data-toggle="tooltip" title='Form' target="_blank"><i class="fa fa-file-text-o" style="color:green"></i></a>
                                                    &nbsp;<a href="<?php echo e(route('recruitment.psychotest', [Crypt::encrypt(array($data->id, 'psychotest'))])); ?>" data-toggle="tooltip" title='Psychotest' target="_blank"><i class="fa fa-file-text-o" style="color:green"></i></a>
                                                </td>
                                                <td>
                                                    
                                                    <?php if($check): ?>
                                                    <a href="<?php echo e(route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,1])])); ?>" target="_blank" data-toggle="tooltip" title="EPPS"><i class="fa fa-file-text-o"></i></a>
                                                    &nbsp;<a href="<?php echo e(route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,2])])); ?>" target="_blank" data-toggle="tooltip" title="PAPI Kostick"><i class="fa fa-file-text-o"></i></a>
                                                    &nbsp;<a href="<?php echo e(route('recruitment.psychotestexcel', [Crypt::encrypt([$data->id,3])])); ?>" target="_blank" data-toggle="tooltip" title="DISC"><i class="fa fa-file-text-o"></i></a>
                                                    <?php if(!empty($pohon[0]->jawaban1)): ?><br/><a href="<?php echo e(asset('assets/recruitment/upload/'.$pohon[0]->jawaban1)); ?>" target="_blank" data-toggle="tooltip" title="BAUM"><i class="fa fa-file-image-o"></i></a><?php endif; ?>
                                                    <?php if(!empty($manusia[0]->jawaban1)): ?>&nbsp;<a href="<?php echo e(asset('assets/recruitment/upload/'.$manusia[0]->jawaban1)); ?>" target="_blank" data-toggle="tooltip" title="DAP"><i class="fa fa-file-image-o"></i></a><?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo e($recruitment->_pekerjaan->pekerjaan); ?></td>
                                                <td><?php echo e($data->nama); ?></td>
                                                
                                                <td><a href="<?php echo e(asset('assets/recruitment/upload/'.$data->cv)); ?>" target="_blank" data-toggle="tooltip" title="File CV"><i class="fa fa-file-pdf-o"></i></a></td>
                                                <td><?php if(!empty($data->check_in)): ?><?php echo e(\Carbon\Carbon::parse($data->check_in) ->format('d-m-Y H:i:s')); ?><?php endif; ?></td>
                                                <td><?php if(!empty($data->check_out)): ?><?php echo e(\Carbon\Carbon::parse($data->check_out) ->format('d-m-Y H:i:s')); ?><?php endif; ?></td>
                                                <td>
                                                    
                                                    <?php if(in_array(16, $log)): ?><a href="<?php echo e(route('recruitment.get_interpretation', [Crypt::encrypt([$data->id, 'view'])])); ?>" target="_blank" data-toggle="tooltip" title="Interpretation"><i class="fa fa-file-text-o"></i></a><?php endif; ?>
                                                    
                                                </td>
                                                <td>
                                                    
                                                    <?php if(in_array(12, $log)): ?><a href="<?php echo e(route('recruitment.get_offering', [Crypt::encrypt([$data->id, 'view'])])); ?>" target="_blank" data-toggle="tooltip" title="Offering Letter"><i class="fa fa-file-text-o"></i></a><?php endif; ?>
                                                </td>
                                                <td>
                                                    
                                                    <?php if(in_array(20, $log)): ?><a href="<?php echo e(route('recruitment.upload_dokumen_get', [Crypt::encrypt([$data->id, 'view'])])); ?>" target="_blank" data-toggle="tooltip" title="File Upload"><i class="fa fa-file-text-o"></i></a><?php endif; ?>
                                                </td>
                                                
                                                <td><?php echo e($data->_status->status); ?> <?php if(!empty($data->id_join)): ?><br/>(<?php echo e(implode(',', App\Models\MtrJoin::whereIn('id', explode(',', $data->id_join))->orderBy('nama', 'asc')->pluck('nama')->toArray())); ?>) <?php endif; ?></td>
                                                <td><?php echo e($data->created_at); ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        
                        <div class="tab-pane fade" id="pills-interview" role="tabpanel" aria-labelledby="pills-interview-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>#ID</th>
                                                <th>Action</th>
                                                <th>Tipe</th>
                                                <th>Nama</th>
                                                
                                                <th>Pewawancara</th>
                                                <th>Metode</th>
                                                <th>Check_in</th>
                                                <th>Check_out</th>
                                                <th>Interview</th>
                                                <th>Status</th>
                                                <th>Created_at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; ?>
                                            <?php $__currentLoopData = $trx1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $identitas = App\Models\TrxRecruitmentIdentitas::find($data->id_identitas);
                                                $nomor = $identitas->id_kebutuhan.'/'.$identitas->id;
                                            ?>
                                            <tr>
                                                <td><?php echo e(++$no); ?></td>
                                                <td><?php echo e($nomor); ?></td>
                                                <td><a href="<?php echo e(route('recruitment.interview_item', [Crypt::encrypt($data->id)])); ?>" data-toggle="tooltip" title='Process'><i class="icon-pencil-alt"></i></a></td>
                                                <td class="text-uppercase"><?php echo e($data->jenis); ?></td>
                                                <td><?php echo e($identitas->nama); ?></td>
                                                
                                                <td>
                                                    <?php $__currentLoopData = explode(';', $data->interviewer); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                        $karyawan = App\Models\Karyawan::where(['nik'=>$_data])->first();
                                                        ?>
                                                        <?php echo e($karyawan->nama); ?>/<?php echo e($_data); ?><br/>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </td>
                                                <td><?php echo e($data->metode); ?></td>
                                                <td><?php if(!empty($data->check_in)): ?><?php echo e(\Carbon\Carbon::parse($data->check_in) ->format('d-m-Y H:i:s')); ?><?php endif; ?></td>
                                                <td><?php if(!empty($data->check_out)): ?><?php echo e(\Carbon\Carbon::parse($data->check_out) ->format('d-m-Y H:i:s')); ?><?php endif; ?></td>
                                                <td><?php if(!empty($data->file_interview)): ?><a href="<?php echo e(asset('assets/recruitment/upload/'.$data->file_interview)); ?>" target="_blank" data-toggle="tooltip" title="File Interview"><i class="fa fa-file-text-o"></i></a><?php endif; ?></td>
                                                <td><?php echo e($data->_status->status); ?></td>
                                                <td><?php echo e($data->created_at); ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        
                        <div class="tab-pane fade" id="pills-negotiation" role="tabpanel" aria-labelledby="pills-negotiation-tab">
                            <p class="mb-0 m-t-10">
                                <div class="table-responsive text-nowrap">
                                    <table class="display" id="_datatable2">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>#ID</th>
                                                <th>PIC</th>
                                                <th>THP</th>
                                                <th>Benefit</th>
                                                <th>Mulai Bergabung</th>
                                                <th>Pencatatan Kepegawaian</th>
                                                <th>Status Kepegawaian</th>
                                                <th>Masa Perjanjian Kerja</th>
                                                <th>Catatan</th>
                                                <th>Lampiran</th>
                                                <th>Status</th>
                                                <th>Created_at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=0; ?>
                                            <?php $__currentLoopData = $trx2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $identitas = App\Models\TrxRecruitmentIdentitas::find($data->id_identitas);
                                                $nomor = $identitas->id_kebutuhan.'/'.$identitas->id;
                                                $benefit_utama = App\Models\MtrRecruitmentBenefit::whereIn('id', explode(',', $data->benefit_utama))->orderBy('benefit', 'asc')->pluck('benefit')->toArray();
                                                $benefit_utama = implode('<br/>', $benefit_utama);
                                            ?>
                                            <tr>
                                                <td><?php echo e(++$no); ?></td>
                                                <td><?php echo e($nomor); ?></td>
                                                <td><?php echo e($data->pic); ?></td>
                                                <td>Rp. <?php echo e(number_format($data->thp, 2)); ?></td>
                                                <td><?php echo $benefit_utama; ?>

                                                    <?php if(!empty($data->benefit_lainnya)): ?><br/><?php echo e($data->benefit_lainnya); ?><?php endif; ?>
                                                </td>
                                                <td><?php echo e(Carbon\Carbon::parse($data->tgl_bergabung)->format('d-m-Y')); ?></td>
                                                <td><?php echo e($data->_catat_pegawai->pencatatan); ?></td>
                                                <td><?php echo e($data->_status_pegawai->status); ?></td>
                                                <td><?php echo e($data->masa_kerja); ?> bulan</td>
                                                <td><?php echo e($data->catatan); ?></td>
                                                <td><?php if(!empty($data->lampiran)): ?><a href="<?php echo e(asset('assets/recruitment/upload/'.$data->lampiran)); ?>" target="_blank" data-toggle="tooltip" title="File Lampiran"><i class="fa fa-file-text-o"></i></a><?php endif; ?></td>
                                                <td><?php echo e($data->_status->status); ?></td>
                                                <td><?php echo e($data->created_at); ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.print.min.js')); ?>"></script>
<script type="text/javascript">
$('#_datatable').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
// 
$('#_datatable1').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
// 
$('#_datatable2').DataTable( {
	// dom: 'lBfrtip',
    dom:"<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'rt>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'copy', 'csv', 'excel', 'pdf', 'print'
	],
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500,  "All"]]
} );
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/recruitment/applicant_list.blade.php ENDPATH**/ ?>