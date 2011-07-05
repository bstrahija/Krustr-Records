<hgroup class="pg"><div class="w">
	<h1><em class="picto cart"></em> Orders</h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('orders/add'); ?>" class="add"><em class="picto plus"></em> Add New Order</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc">
	<?php $this->load->view('widgets/filters'); ?>
	
	<div class="block orders">
		<?php if (isset($orders) and $orders) : ?>
			<h2>Orders displayed: <strong><?php echo count($orders); ?></strong></h2>
			
			<table class="grid">
				<thead>
					<tr>
						<th scope="col" class="tight"><em class="picto globe"></em></th>
						<th scope="col" width="1%"><em class="picto">%</em></td>
						<th scope="col" class="tight">#</th>
						<th scope="col" width="10%">Track ID</th>
						<th scope="col" width="10%">User</th>
						<th scope="col" width="30%">Deal</th>
						<th scope="col" width="1%">Payment</th>
						<th scope="col" width="1%">Amount</th>
						<th scope="col" width="1%">%</th>
						<th scope="col" width="1%" class="tight c"><em class="mnml">@</em></th>
						<th scope="col" width="10%">When</th>
						<th scope="col" colspan="3" class="c" width="1%"><em class="picto cog"></em></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($orders as $order) : ?>
						<tr class="<?php echo $order->status; ?>">
							<td class="status"><em class="picto globe c" title="<?php echo ucfirst($order->status); ?>"></em></td>
							<td class="c"><em class="picto <?php
								if ($order->payment_type == 'bank') echo 'star';
								else echo '';
							?>"></em></td>
							<td><?php echo $order->id; ?></td>
							<td><?php echo $order->track_id; ?></td>
							<td><a href="<?php echo admin_url('users/'.$order->user_id); ?>"><?php echo $order->first_name, ' ', $order->last_name; ?></a></td>
							<td class="title"><a href="<?php echo admin_url('content/edit/deal/'.$order->entry_id); ?>"><?php echo character_limiter($order->deal_title, 70); ?></a></td>
							<td><?php echo $order->payment_type; ?></td>
							<td><?php echo $order->amount; ?></td>
							<td>-</td>
							
							<?php if ($order->coupon_sent) : ?>
								<td class="c sent-status sent-status-resend"><a href="<?php echo admin_url('orders/resend_coupon/'.$order->id); ?>" data-confirm="Are you sure you want to resend this coupon? It has already been sent." title="Resend the coupons. WARNING, it has been sent already!"><em class="mnml">@</em></a></td>
							<?php else : ?>
								<td class="c sent-status"><a href="<?php echo admin_url('orders/send_coupon/'.$order->id); ?>" data-confirm="Are you sure you want to send this coupon?" title="Send the coupon to the users email."><em class="picto">R</em></a></td>
							<?php endif; ?>
							
							<td class="when"><?php echo date('Y/m/d H:i', $order->purchased_at); ?></td>
							
							<td class="actions tight"><a href="<?php echo admin_url('orders/edit/'.$order->id); ?>" class="act edit"><em class="picto pencil"></em> Edit</a></td>
							<td class="actions tight">
								<?php if ($order->status == 'trashed') : ?>
									<a href="<?php echo admin_url('orders/restore/'.$order->id); ?>" class="act restore" title="Changed status to PG Success" data-confirm="Are you sure you want to restore this coupon?"><em class="mnml">P</em> Restore</a>
								<?php else : ?>
									<a href="<?php echo admin_url('orders/delete/'.$order->id); ?>" class="act delete" title="Put into trash" data-confirm="Are you sure you want to delete this entry?"><em class="picto remove"></em> Delete</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<p class="nothing-found"><em class="picto alert"></em> No orders found.</p>
			
		<?php endif; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->