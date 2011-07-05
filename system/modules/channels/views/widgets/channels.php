<div class="widget channels">
	<h2>Channels</h2>
	<ul class="buttons">
		<?php foreach ($channels as $ch) : ?>
			<li><a href="<?php echo admin_url('channels/edit/'.$ch->id); ?>"<?php echo ($channel->id == $ch->id) ? ' class="on"' : ''; ?>><?php echo $ch->title; ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
<!-- /.field-groups -->