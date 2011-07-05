<hgroup class="pg"><div class="w">
	<h1><em class="picto <?php echo $channel->icon; ?>"></em> <?php echo $channel->title; ?></h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('content/add/'.$channel->slug_singular); ?>" class="add"><em class="picto plus"></em> Add New <?php echo $channel->title_singular; ?></a></li>
	</ul></div>
</div></hgroup>

<section class="mainc">
	<?php $this->load->view('widgets/filters'); ?>
	
	<div class="block <?php echo $channel->slug; ?>">
		<?php if (isset($entries) and $entries) : ?>
			<table class="grid">
				<thead>
					<tr>
						<th scope="col" class="tight"><em class="picto globe"></em></th>
						<th scope="col" class="tight">#</th>
						<th scope="col">Title</th>
						<th scope="col" width="20%">When</th>
						<th scope="col" width="20%">By</th>
						<th scope="col" colspan="3" class="c"><em class="picto cog"></em></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($entries as $entry) : ?>
						<tr>
							<td class="status <?php echo $entry->status; ?>"><em class="picto globe c"></em></td>
							<td><?php echo $entry->id; ?></td>
							<td class="title"><a href="<?php echo admin_url('content/edit/'.$entry->channel.'/'.$entry->id); ?>"><?php echo $entry->title; ?></a></td>
							<td><?php echo date('Y/m/d H:i', $entry->published_at); ?></td>
							<td><a href="<?php echo admin_url('users/'.$entry->user_id); ?>"><?php echo $entry->user_first_name, ' ', $entry->user_last_name; ?></a></td>
							
							<td class="actions tight"><a href="<?php echo admin_url('content/edit/'.$entry->channel.'/'.$entry->id); ?>" class="act edit"><em class="picto pencil"></em> Edit</a></td>
							<td class="actions tight"><a href="<?php echo admin_url('content/delete/'.$entry->channel.'/'.$entry->id); ?>" class="act delete" data-confirm="Are you sure you want to delete the entry <b>[<?php echo $entry->id; ?>]</b>?"><em class="picto remove"></em> Delete</a></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<p class="nothing-found"><em class="picto alert"></em> No <?php echo $channel->slug; ?> found.</p>
			
		<?php endif; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->