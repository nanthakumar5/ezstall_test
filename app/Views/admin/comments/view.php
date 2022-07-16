<?= $this->extend("admin/common/layout/layout2") ?>
<?php $this->section('content') ?>
<?php
$id 					= isset($result['id']) ? $result['id'] : '';
$eventid 				= isset($result['event_id']) ? $result['event_id'] : '';
$userid 				= isset($result['user_id']) ? $result['user_id'] : '';
$username 				= isset($result['username']) ? $result['username'] : '';
$eventname 				= isset($result['eventname']) ? $result['eventname'] : '';
$comment 		    	= isset($result['comment']) ? $result['comment'] : '';
$commentid 				= isset($result['comment_id']) ? $result['comment_id'] : '';
$communication 			= isset($result['communication']) ? $result['communication'] : '';
$cleanliness 			= isset($result['cleanliness']) ? $result['cleanliness'] : '';
$friendliness 		    = isset($result['friendliness']) ? $result['friendliness'] : '';
$replycomments 			= isset($result['replycomments']) ? $result['replycomments'] : [];
?>
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Comments</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/comments">Comments</a></li>
					<li class="breadcrumb-item active">View Comment</li>
				</ol>
			</div>
		</div>
	</div>
</section>
<section class="content">
	<div class="page-action">
		<a href="<?php echo getAdminUrl(); ?>/comments" class="btn btn-primary">Back</a>
	</div>
	<div class="card">
		<div class="card-header">
			<h3 class="card-title">View Comment</h3>
		</div>
		<div class="card-body">
			<div class="commentsection">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Eventname</label>
								<p><?php echo $eventname;?></p>								
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Username</label>
								<p><?php echo $username;?></p>								
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Comment</label>
								<p><?php echo $comment;?></p>								
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Communication</label>
								<div class="communicationRating commentratings col-md-6" data-rate-value="<?php echo $communication;?>"></div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Cleanliness</label>
								<div class="cleanlinessRating commentratings col-md-6" data-rate-value="<?php echo $cleanliness;?>"></div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Friendliness</label>
								<div class="friendlinessRating commentratings col-md-6" data-rate-value="<?php echo $friendliness;?>"></div>
							</div>
						</div>										
					</div>
				</div>
			</div>
			<div class="replysection">
				<?php if(!empty($replycomments)){ ?>
					<h4>Replies:</h4>
						<div class="col-md-12">
							<?php foreach ($replycomments as $data ) { ?>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Username</label>
											<p><?php echo $data['username'];?></p>								
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Comment</label>
											<p><?php echo $data['reply'];?></p>								
										</div>
									</div>
								</div>
							<?php }?>
						</div>
				<?php } ?>
			</div>
		</div>
	</div>
</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<script>
	$(".commentratings").rate({ initial_value: 0, max_value: 5 });
</script>
<?php echo $this->endSection() ?>