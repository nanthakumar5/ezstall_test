<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<div class="page-action mb-4 m-0" align="left">
	<a href="<?php echo base_url(); ?>/myaccount/events" class="btn btn-dark">Back</a>
</div>
<section class="container-lg">
	<div class="row">
		<div class="col-12">
			<div class="border rounded pt-5 ps-3 pe-3">
				<div class="row">
					<h1>Current Inventories</h1>
					<?php foreach ($product as $product) { 
						if($product['type']=='1'){?>
							<table>
								<h3>Feed</h3>
								<tr>
									<td>Product Name: </td> <td><?php echo $product['name']?></td>
								</tr>
								<tr>
									<td>Product Quantity: </td> <td><?php echo $product['quantity']?></td>
								</tr>
								<tr>
									<td>Product Price: </td> <td><?php echo $product['price']?></td>
								</tr>
							</table>
						<?php } else if($product['type']=='2') { ?>
							<table>
								<h3>Shavings</h3>
								<tr>
									<td>Product Name: </td> <td><?php echo $product['name']?></td>
								</tr>
								<tr>
									<td>Product Quantity: </td> <td><?php echo $product['quantity']?></td>
								</tr>
								<tr>
									<td>Product Price: </td> <td><?php echo $product['price']?></td>
								</tr>
							</table>

						<?php }
					}?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php $this->endSection() ?>