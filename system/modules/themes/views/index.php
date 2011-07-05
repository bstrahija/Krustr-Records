<?php if (isset($themes) AND $themes) : ?>
<ul class="themes">
	<?php foreach ($themes as $theme) : ?>
	<li<?php echo ($current_theme == $theme->folder) ? ' class="current"' : ''; ?>>
		
		<h3><?php echo $theme->name; ?></h3>
		<h4><a href="<?php echo $theme->website; ?>">by <?php echo $theme->author; ?></a></h4>
		
		<?php if (is_file($theme->screenshot)) : ?>
			<div class="img"><img src="<?php echo image_thumb($theme->screenshot, 296, 296, TRUE); ?>" alt="Screenshot for <?php echo $theme->name; ?>"></div>
		<?php endif; ?>
		
		<p><?php echo $theme->description ?></p>
		
		<?php if ($current_theme != $theme->folder) : ?>
			<a href="<?php echo admin_url('themes/activate/'.$theme->folder); ?>" class="btn"><em class="iconic"></em> Activate</a>
		<?php endif; ?>
	</li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>

<script>$(function() {
	$("ul.themes li a.btn").click(function() {
		if ( confirm("Are you sure you want to activate this theme?") ) {
			return true;
		} // end if
		return false;
	});
});</script>