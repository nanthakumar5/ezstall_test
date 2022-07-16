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
					<li class="breadcrumb-item active">Update Comment</li>
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
			<h3 class="card-title">Update Comment</h3>
		</div>
		<div class="card-body">
			<div class="commentsection">
				<form method="post" id="form" class="comment_form" action="<?php echo getAdminUrl(); ?>/comments/action" autocomplete="off">
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
									<textarea class="form-control" name="comment" placeholder="Add Your Comment" id="comment" rows="3"><?php echo $comment;?></textarea>
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
						<div class="col-md-12 mt-4">
							<input type="hidden" name="actionid" value="<?php echo $id;?>">
							<input type="hidden" name="eventid" value="<?php echo $eventid;?>">
							<input type="hidden" name="userid" 	value="<?php echo $userid;?>">
							<input type="hidden" name="communication" value="<?php echo $communication;?>">
							<input type="hidden" name="cleanliness" value="<?php echo $cleanliness;?>">
							<input type="hidden" name="friendliness" value="<?php echo $friendliness;?>">
							<input type="hidden" name="status" value="1">
							<input type="submit" id ="commentSubmit" class="btn btn-danger" value="Submit">
						</div>
					</div>
				</form>
			</div>
			<div class="replysection">
				<?php if(!empty($replycomments)){ ?>
					<h4>Replies:</h4>
					<form method="post" id="form" class="reply_form" action="<?php echo getAdminUrl(); ?>/comments/action" autocomplete="off">
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
											<textarea class="form-control" name="comment" placeholder="Add Your Comment" id="comment" rows="3"><?php echo $data['reply'];?></textarea>
										</div>
									</div>
									<input type="hidden" name="actionid" value="<?php echo $data['replyid'];?>">
								</div>
							<?php }?>
							<div class="col-md-12 mt-4">
								<input type="hidden" name="eventid" value="<?php echo $eventid;?>">
								<input type="hidden" name="userid" 	value="<?php echo $userid;?>">
								<input type="hidden" name="comment_id" value="<?php echo $id;?>">
								<input type="submit" id ="replySubmit" class="btn btn-danger" value="Submit">
								<a href="<?php echo base_url(); ?>/comments" class="btn btn-dark">Back</a>
							</div>
						</div>
					</form>
				<?php } ?>
			</div>
		</div>
	</div>
</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<script> 
	$(function(){
	  	validation(
	    '#form',
	    {
	      comment      : {
	        required  :   true
	      }
	    },
	    { 
	     comment      : {
	        required    : "Please Enter Comment."
	      }
	    }
	  );
	});
$(".commentratings").rate({ initial_value: 0, max_value: 5 });
</script>
<?php echo $this->endSection() ?>