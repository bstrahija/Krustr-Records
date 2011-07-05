<hgroup class="pg"><div class="w">
	<h1><em class="picto category"></em> Categories</h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('categories/add'); ?>" class="add"><em class="picto plus"></em> Add new category</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc">
	<div class="block categories">
		<ul class="tabs tabs-l panel-tabs" style="width: <?php echo 126 * count($channels) + 3; ?>px">
			<?php foreach ($channels as $channel) : ?>
				<li><a href="#" rel="<?php echo $channel->id; ?>"><em class="picto <?php echo $channel->icon; ?>"></em> <?php echo $channel->title; ?></a></li>
			<?php endforeach; ?>
		</ul>
		
		
		<?php if (isset($channels) and $channels) : ?>
			<ul class="category-divs">
				<?php foreach ($channels as $channel) : ?>
					
					<li class="panel-div panel-div-<?php echo $channel->id; ?>">
						<?php if (isset($categories[$channel->id]) and $categories[$channel->id]) : ?>
							<h3><?php echo $channel->title; ?></h3>
							
							<table class="grid">
								<thead>
									<tr>
										<th scope="col" class="tight">#</th>
										<th scope="col">Title</th>
										<th scope="col" width="20%">When</th>
										<th scope="col" colspan="3" class="c"><em class="picto cog"></em></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($categories[$channel->id] as $category) : ?>
										<tr>
											<td><?php echo $category->id; ?></td>
											<td class="title"><a href="<?php echo admin_url('comments/edit/'.$category->id); ?>"><?php echo $category->title; ?></a></td>
											<td><?php echo date('Y/m/d H:i', $category->created_at); ?></td>
											
											<td class="actions tight"><a href="<?php echo admin_url('content/edit/'.$category->id); ?>" class="act edit"><em class="picto pencil"></em> Edit</a></td>
											<td class="actions tight"><a href="<?php echo admin_url('content/delete/'.$category->id); ?>" class="act delete"><em class="picto remove"></em> Delete</a></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php else : ?>
							<p class="nothing-found"><em class="picto alert"></em> No categories found.</p>
							
						<?php endif; ?>
					</li>
				
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->