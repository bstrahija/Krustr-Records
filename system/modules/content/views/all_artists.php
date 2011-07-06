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
						<th scope="col" width="50%">Title</th>
						<th scope="col" width="10%">When</th>
						<th scope="col" width="15%">Category</th>
						<th scope="col" width="10%">By</th>
						<th scope="col" colspan="3" class="c"><em class="picto cog"></em></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($entries as $entry) : ?>
						<tr class="<?php echo $entry->status; ?>">
							<td class="status"><em class="picto globe c" title="<?php echo ucfirst($entry->status); ?>"></em></td>
							<td><?php echo $entry->id; ?></td>
							<td class="title"><a href="<?php echo admin_url('content/edit/'.$entry->channel.'/'.$entry->id); ?>"><?php echo $entry->title; ?></a></td>
							<td class="when"><?php echo ($entry->published_at) ? date('Y/m/d H:i', $entry->published_at) : date('Y/m/d H:i', $entry->created_at); ?></td>
							<td><?php echo $entry->category_name_links; ?></td>
							<td><a href="<?php echo admin_url('users/'.$entry->user_id); ?>"><?php echo $entry->user_first_name, ' ', $entry->user_last_name; ?></a></td>
							
							<td class="actions tight"><a href="<?php echo site_url('preview/show/'.$entry->id.'/'.Tinyo::toTiny($entry->id)); ?>" class="act preview" target="_blank"><em class="picto">s</em></a></td>
							
							<td class="actions tight"><a href="<?php echo admin_url('content/edit/'.$entry->channel.'/'.$entry->id); ?>" class="act edit"><em class="picto pencil"></em> Edit</a></td>
							
							<td class="actions tight">
								<?php if ($entry->status == 'trashed') : ?>
									<a href="<?php echo admin_url('content/restore/'.$entry->channel.'/'.$entry->id); ?>" class="act restore" title="Restore from trash"><em class="mnml">P</em> Restore</a>
								<?php else : ?>
									<a href="<?php echo admin_url('content/delete/'.$entry->channel.'/'.$entry->id); ?>" class="act delete" title="Put into trash" data-confirm="Are you sure you want to delete the entry <b>[<?php echo $entry->id; ?>]</b>?"><em class="picto remove"></em> Delete</a>
								<?php endif; ?>
							</td>
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