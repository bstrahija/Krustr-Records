<hgroup class="pg"><div class="w">
	<h1><em class="picto comment"></em> Comments</h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('comments/add'); ?>" class="add"><em class="picto plus"></em> Add new comment</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc">
	<div class="block comments">
		<?php if (isset($comments) and $comments) : ?>
			<table class="grid">
				<thead>
					<tr>
						<th scope="col" class="tight">#</th>
						<th scope="col">Title</th>
						<th scope="col" width="20%">When</th>
						<th scope="col" width="20%">By</th>
						<th scope="col" colspan="3" class="c"><em class="picto cog"></em></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($comments as $comment) : ?>
						<tr>
							<td><?php echo $comment->id; ?></td>
							<td class="title"><a href="<?php echo admin_url('comments/edit/'.$comment->id); ?>"><?php echo $comment->title; ?></a></td>
							<td><?php echo date('Y/m/d H:i', $comment->published_at); ?></td>
							<td><a href="<?php echo admin_url('users/'.$comment->user_id); ?>"><?php echo $comment->user_first_name, ' ', $comment->user_last_name; ?></a></td>
							
							<td class="actions tight"><a href="<?php echo admin_url('content/edit/'.$comment->id); ?>" class="act edit"><em class="picto pencil"></em> Edit</a></td>
							<td class="actions tight"><a href="<?php echo admin_url('content/delete/'.$comment->id); ?>" class="act delete"><em class="picto remove"></em> Delete</a></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<p class="nothing-found"><em class="picto alert"></em> No comments found.</p>
			
		<?php endif; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->