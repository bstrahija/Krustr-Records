<hgroup class="pg"><div class="w">
	<h1><em class="picto user"></em> <?php echo Backend::get_title(); ?></h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('users/all'); ?>" class=""><em class="picto back"></em> Go back</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc">
	<div class="block users">
		<?php echo @$errors; ?>
		<?php echo @$form; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->