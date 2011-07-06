<?php if (is_superadmin()) : ?>
<div class="view-data">
	<a href="#" class="toggle">View Data &raquo;</a>
	<div class="cont">
		<?php echo '<pre>'; print_r(CMS::$front_data); echo '</pre>'; ?>
		<?php //dump(CMS::$front_data); ?>
		<?php //echo '<pre>'; print_r($footer_nav); echo '</pre>'; ?>
	</div>
</div>

<div class="profiler">
	<a href="#" class="toggle">Profiler &bull; {elapsed_time} &bull; Memory: {memory_usage}</a>
	<?php $this->output->enable_profiler(true); ?>
</div>

<br>

<?php else : ?>
	<?php $this->output->enable_profiler(false); ?>

<?php endif; ?>
