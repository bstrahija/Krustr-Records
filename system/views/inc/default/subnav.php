<?php $subnav = backend_sub_navigation(Backend::get_nav_mark(1), false); ?>
<?php if ($subnav) : ?>
	<nav class="sub"><div class="w"><?php echo $subnav; ?></div></nav>
<?php else : ?>
	<hr class="hd">
<?php endif; ?>