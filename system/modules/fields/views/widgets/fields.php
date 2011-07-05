<div class="widget channels">
	<h2>Fields</h2>
	<ul class="buttons">
		<?php foreach ($fields as $f) : ?>
			<li><a href="<?php echo admin_url('fields/edit/'.$f->id); ?>"<?php echo ($field->id == $f->id) ? ' class="on"' : ''; ?>><?php echo $f->title; ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
<!-- /.field-groups -->