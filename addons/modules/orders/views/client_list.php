<hgroup class="pg"><div class="w">
	<h1><em class="picto briefcase"></em> Client List</h1>
	<h2>This should be sent to the partner</h2>
</div></hgroup>

<section class="mainc">
	<?php $this->load->view('widgets/filters_client_list'); ?>
	
	<div class="block orders">
		<?php if (isset($orders) && $orders) : ?>
			<h3><?php echo $deal->title; ?></h3>
			
			<table class="grid zebra">
				<thead>
					<tr>
						<th scope="col" width="1%">#</th>
						<?php /*<th scope="col">Track ID</th>*/ ?>
						<th scope="col">Oznaka kupona</th>
						<th scope="col">Nositelj kupona</th>
						<?php /*<th scope="col">Email</th>*/ ?>
						<th scope="col">Iznos</th>
						<th scope="col">Datum</th>
						<?php /*<th scope="col" class="c">*</th>*/ ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($orders as $key=>$order) : ?>
					<tr>
						<td class="rgt"><?php echo $key+1; ?></td>
						<?php /*<td><?php echo $order->track_id; ?></td>*/ ?>
						<td><strong style="color: #0a0;"><?php echo $order->coupon_code; ?></strong></td>
						
						<?php if ($order->gift) : ?>
							<td><?php echo $order->gift_to_name; ?></td>
						<?php else : ?>
							<td><?php echo $order->first_name.' '.$order->last_name; ?></td>
						<?php endif; ?>
						
						<?php /*<td><?php echo $order->email; ?></td>*/ ?>
						<td><?php echo $order->amount; ?> kn</td>
						<td><?php echo date('Y/m/d H:i', $order->created_at); ?></td>
						<?php /*<td class="actions c" width="10"><a href="<?php echo site_url('backend/orders/info/'.$order->id); ?>" class="act edit popframe">Info</a></td>*/ ?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		
		<?php else : ?>
			<p class="nothing-found"><em class="picto alert"></em> Did you select a deal?</p>
			
		<?php endif; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->
