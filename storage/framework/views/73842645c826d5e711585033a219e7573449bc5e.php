<?php $__env->startSection('title', 'Add Post'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/dropzone.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Data Master</li>
<li class="breadcrumb-item">Feeds</li>
<li class="breadcrumb-item active"><?php if($status == 'edit'): ?> Edit <?php else: ?> Add <?php endif; ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body add-post">
					<?php if(Session::has('msg')): ?>
					<div class="alert alert-success"><?php echo e(Session::get('msg')); ?></div>
					<?php endif; ?>
					<!-- <form class="row needs-validation" novalidate=""> -->
					<?php if($status == 'edit'): ?>
					<form action="<?php echo e(route('feeds.update', $feed->id)); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
						<?php echo method_field('PATCH'); ?>
						<?php else: ?>
						<form action="<?php echo e(route('feeds.store')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" onsubmit="return confirm('Are you sure want to submit this data?');">
							<?php endif; ?>
							<?php echo csrf_field(); ?>
							<div class="col-sm-12">
								<div class="mb-3">
									<label for="title">Title</label>
									<input class="form-control" name="title" id="title" type="text" placeholder="Title" required="" <?php if($status=='edit' ): ?> value="<?php echo e($feed->title); ?>" <?php endif; ?>>
									<div class="valid-feedback">Looks good!</div>
								</div>
								<div class="mb-3">
									<label class="form-label" for="is_active">Active</label>
									<div class="media-body text-start icon-state">
										<label class="switch">
											<input name="is_active" id="is_active" type="checkbox" <?php if($status=='edit' ): ?> <?php if($feed->is_active == 1): ?> checked="" <?php endif; ?> <?php endif; ?>><span class="switch-state"></span>
										</label>
									</div>
								</div>
								<div class="mb-3">
									<label class="form-label" for="is_notif">Notif</label>
									<div class="media-body text-start icon-state">
										<label class="switch">
											<input name="is_notif" id="is_notif" type="checkbox" <?php if($status=='edit' ): ?> <?php if($feed->is_notif == 1): ?> checked="" <?php endif; ?> <?php endif; ?>><span class="switch-state"></span>
										</label>
									</div>
								</div>
								<div class="email-wrapper">
									<div class="theme-form">
										<div class="mb-3">
											<label>Content</label>
											<textarea class="form-control" id="text-box" name="content" cols="10" rows="2"><?php if($status == 'edit'): ?> <?php echo $feed->content; ?> <?php endif; ?></textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="btn-showcase text-end">
								<button class="btn btn-primary" type="submit">Post</button>
								<a href="<?php echo e(route('feeds.index')); ?>" class="btn btn-light" type="button">Discard</a>
							</div>
						</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/editor/ckeditor/ckeditor.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/editor/ckeditor/adapters/jquery.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/dropzone/dropzone.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/dropzone/dropzone-script.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/email-app.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matta\resources\views/feeds/upsert.blade.php ENDPATH**/ ?>