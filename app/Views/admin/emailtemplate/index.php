<?= $this->extend("admin/common/layout/layout2") ?>
	<?php $this->section('content') ?>
		<section class="content-header">		
			<div class="container-fluid">			
				<div class="row mb-2">				
					<div class="col-sm-6">					
						<h1>Email Template</h1>				
					</div>				
					<div class="col-sm-6">					
						<ol class="breadcrumb float-sm-right">						
							<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
							<li class="breadcrumb-item active">Email Templete</li>
						</ol>				
					</div>			
				</div>
			</div>
		</section>
		<section class="content">
			<div class="card">			
				<div class="card-header">
					<h3 class="card-title">Email Template</h3>
				</div>
				<div class="card-body">
					<?php if(!empty($templates)){ ?>
						<div id="emailtemplate">	
							<?php foreach ($templates as $data) { ?>
								<div class="mb-3">
									<label class="form-label">Template Title : </label>
									<p><?php echo $data['name'];?></p>
								</div>
								<div class="mb-3 form-check">
									<label class="form-label">Subject : </label>
									<p><?php echo $data['subject'];?></p>
								</div>
								<div class="mb-3 form-check">
									<label class="form-label">Message : </label>
									<p><?php echo $data['message'];?></p>
								</div>
								<div class="mb-3">
									<a href="<?php echo getAdminUrl().'/emailtemplate/action/'.$data['id'];?>"><button class="btn btn-primary btn btn-danger">Edit Template</button></a>
								</div>
							<?php } ?>
						</div>
					<?php } else{ ?>
						<p>No Records Found!</p>
					<?php } ?>
				</div>
			</div>
		</section>
<?php echo $pager; ?>
<?php $this->endSection(); ?>


