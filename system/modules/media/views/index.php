<hgroup class="pg"><div class="w">
	<h1><em class="picto photos"></em> <?php echo Backend::get_title(); ?></h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('media/add'); ?>" class="add"><em class="picto plus"></em> Upload new media</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc">
	<div class="block no-min start">
		<div class="item-browser">
			<h3>Search</h3>
			<form>
				<input type="search" name="s" value="" placeholder="Enter phrase">
			</form>
			
			<h3>Channel</h3>
			<ul>
				<?php foreach ($channels as $channel) : ?>
					<li><a href="#"><?php echo $channel->title; ?></a></li>
				<?php endforeach; ?>
			</ul>
			
			<h3>File type</h3>
			<ul>
				<li><a href="#"><em class="picto photo"></em> Images</a></li>
				<li><a href="#"><em class="picto video"></em> Video</a></li>
				<li><a href="#"><em class="picto music"></em> Audio</a></li>
				<li><a href="#"><em class="picto">W</em> Documents</a></li>
			</ul>
		</div>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->
