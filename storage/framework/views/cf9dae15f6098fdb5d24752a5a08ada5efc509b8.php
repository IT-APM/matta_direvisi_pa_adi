<?php $__env->startSection('title', 'Formulir Karyawan Non Aktif'); ?>
<?php $__env->startSection('content'); ?>

<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="<?php echo e(route('nonaktif.save_edit', [$trxD->id, 'back_url' => $back_url])); ?>"
                method="POST" enctype="multipart/form-data"
                onsubmit="return confirm('Are you sure want to submit this data?');">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Edit</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <?php if($trxD->_into_ == 'di'): ?>
                                
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h6>Kepada (PIC Handover)</h6>
                                            </th>
                                            <th>
                                                <h6>Obyek</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-select" id="kepada" name="kepada" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    <?php $__currentLoopData = $karyawan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($data->nik); ?>" <?php if($trxD->kepada == $data->nik): ?> selected <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" id="obyek" name="obyek" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    <?php $__currentLoopData = $obyek_di; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($data->id); ?>" <?php if($trxD->obyek == $data->id): ?> selected <?php endif; ?>><?php echo e($data->obyek); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <br />
                                                <label class="form-label" for="keterangan">Keterangan</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"><?php echo e($trxD->keterangan); ?></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <?php elseif($trxD->_into_ == 'ga'): ?>
                                
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h6>Kepada</h6>
                                            </th>
                                            <th>
                                                <h6>Obyek</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-select" id="kepada" name="kepada" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    <?php $__currentLoopData = $pic_ga; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($data->nik); ?>" <?php if($trxD->kepada == $data->nik): ?> selected <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" id="obyek" name="obyek" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    <?php $__currentLoopData = $obyek_ga; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($data->id); ?>" <?php if($trxD->obyek == $data->id): ?> selected <?php endif; ?>><?php echo e($data->obyek); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <br />
                                                <label class="form-label" for="keterangan">Keterangan</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"><?php echo e($trxD->keterangan); ?></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <?php elseif($trxD->_into_ == 'it'): ?>
                                
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h6>Kepada</h6>
                                            </th>
                                            <th>
                                                <h6>Obyek</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-select" id="kepada" name="kepada" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    <?php $__currentLoopData = $pic_it; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($data->nik); ?>" <?php if($trxD->kepada == $data->nik): ?> selected <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" id="obyek" name="obyek" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    <?php $__currentLoopData = $obyek_it; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($data->id); ?>" <?php if($trxD->obyek == $data->id): ?> selected <?php endif; ?>><?php echo e($data->obyek); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <br />
                                                <label class="form-label" for="keterangan">Keterangan</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"><?php echo e($trxD->keterangan); ?></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <?php elseif($trxD->_into_ == 'hc'): ?>
                                
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h6>Kepada</h6>
                                            </th>
                                            <th>
                                                <h6>Obyek</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-select" id="kepada" name="kepada" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    <?php $__currentLoopData = $pic_hc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($data->nik); ?>" <?php if($trxD->kepada == $data->nik): ?> selected <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" id="obyek" name="obyek" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    <?php $__currentLoopData = $obyek_hc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($data->id); ?>" <?php if($trxD->obyek == $data->id): ?> selected <?php endif; ?>><?php echo e($data->obyek); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <br />
                                                <label class="form-label" for="keterangan">Keterangan</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"><?php echo e($trxD->keterangan); ?></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <?php elseif($trxD->_into_ == 'ko'): ?>
                                
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h6>Kepada</h6>
                                            </th>
                                            <th>
                                                <h6>Obyek</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-select" id="kepada" name="kepada" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    <?php $__currentLoopData = $pic_ko; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($data->nik); ?>" <?php if($trxD->kepada == $data->nik): ?> selected <?php endif; ?>><?php echo e($data->nama); ?> - <?php echo e($data->nik); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" id="obyek" name="obyek" required="">
                                                    <option selected="" disabled="" value="">Pilih...</option>
                                                    <?php $__currentLoopData = $obyek_ko; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($data->id); ?>" <?php if($trxD->obyek == $data->id): ?> selected <?php endif; ?>><?php echo e($data->obyek); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <br />
                                                <label class="form-label" for="keterangan">Keterangan</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"><?php echo e($trxD->keterangan); ?></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" href="<?php echo e($back_url); ?>">Cancel</a>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#modal_edit").modal('show');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/formulir/nonaktif/edit.blade.php ENDPATH**/ ?>