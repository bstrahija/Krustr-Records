<hgroup class="pg"><div class="w">
	<h1><em class="picto">%</em> Discount Codes</h1>
	<h2>Everything is up for sale!</h2>
</div></hgroup>

<section class="mainc">
	<?php $this->load->view('widgets/filters_discounts'); ?>
	
	<div class="block orders">
		<?php if (isset($codes) and $codes) : ?>
			<h2>Codes displayed: <strong><?php echo count($codes); ?></strong></h2>
			
			<table class="grid">
				<thead>
					<tr>
						<th scope="col" width="1%">#</th>
						<th scope="col">Code</th>
						<th scope="col">Discount</th>
						<th scope="col">Slots</th>
						<th scope="col">Used</th>
						<th scope="col">Used Data</th>
						<th scope="col">Expires</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($codes as $num=>$code) : ?>
					<tr>
						<td><?php echo $num+1; ?></td>
						<td><?php echo $code->code; ?></td>
						<td><?php echo $code->discount; ?>%</td>
						<td><?php echo $code->slots; ?></td>
						<td><?php echo $code->used; ?></td>
						<td>
							<?php if ($code->used) : ?>
								<a href="<?php echo admin_url('users/edit/'.$code->user_id); ?>"><?php echo $code->display_name; ?> [<?php echo $code->email; ?>]</a>
							<?php else : ?>
								-
							<?php endif; ?>
						</td>
						<td><?php echo date('Y/m/d H:i', $code->expires_at); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		
		<?php else : ?>
			<p class="nothing-found"><em class="picto alert"></em> No entries found.</p>
			
		<?php endif; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->
