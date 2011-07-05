<hgroup class="pg"><div class="w">
	<h1><em class="picto relation"></em> Channels</h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('channels/add'); ?>" class="add"><em class="picto plus"></em> Add new channel</a></li>
		<li><a href="<?php echo admin_url('fields'); ?>" class="back"><em class="picto back"></em> Back to fields</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc">
	<div class="block channels">
		<?php if (isset($channels) and $channels) : ?>
			<table class="grid">
				<thead>
					<tr>
						<th scope="col" class="tight">#</th>
						<th scope="col">Name</th>
						<th scope="col">Name sing.</th>
						<th scope="col">Slug</th>
						<th scope="col">Slug sing.</th>
						<th scope="col">When</th>
						<th scope="col" colspan="3" class="c"><em class="picto cog"></em></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($channels as $channel) : ?>
						<tr>
							<td><?php echo $channel->id; ?></td>
							<td class="title"><a href="<?php echo admin_url('channels/edit/'.$channel->id); ?>"><?php echo $channel->title; ?></a></td>
							<td><a href="<?php echo admin_url('channels/edit/'.$channel->id); ?>"><?php echo $channel->title_singular; ?></a></td>
							<td><?php echo $channel->slug; ?></td>
							<td><?php echo $channel->slug_singular; ?></td>
							<td><?php echo date('Y/m/d H:i', $channel->created_at); ?></td>
							
							<td class="actions tight"><a href="<?php echo admin_url('channels/edit/'.$channel->id); ?>" class="act edit"><em class="picto pencil"></em> Edit</a></td>
							<td class="actions tight"><a href="<?php echo admin_url('channels/delete/'.$channel->id); ?>" class="act delete"><em class="picto remove"></em> Delete</a></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<p class="nothing-found"><em class="picto alert"></em> No channels found.</p>
			
		<?php endif; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->