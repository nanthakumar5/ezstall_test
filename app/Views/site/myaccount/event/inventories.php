<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<div class="page-action mb-4 m-0" align="left">
	<a href="<?php echo base_url(); ?>/myaccount/events" class="btn btn-dark">Back</a>
</div>
<section class="container-lg">
	<div class="row">
		<div class="col-12">
			<div class="border rounded pt-3 ps-3 pe-3">
				<div class="row">
					<h2 class="fw-bold">Current Inventories</h2>
					<?php if(isset($product)){
					 foreach ($product as $product) { 
						if($product['type']=='1'){?>
							<table class="table-hover table-striped table-light table">
								<h5 class="fw-bold text-muted">Feed</h5>
								<thead>
									<tr>
										<th>Product Name</th>
										<th>Product Quantity</th>
										<th>Product Price</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo $product['name']?></td>
										<td><?php echo $product['quantity']?></td>
										<td><?php echo $product['price']?></td>
									</tr>
								</tbody>
							</table>
						<?php } else if($product['type']=='2') { ?>
							<table class="table-hover table-striped table-light table">
								<h5 class="fw-bold text-muted mt-4">Shavings</h5>
								<thead>
									<tr>
										<th>Product Name</th>
										<th>Product Quantity</th>
										<th>Product Price</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo $product['name']?></td>
										<td><?php echo $product['quantity']?></td>
										<td><?php echo $product['price']?></td>
									</tr>
								</tbody>
							</table>

						<?php } else{ ?>
							No Inventories.
						<?php } } }?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php $this->endSection() ?>