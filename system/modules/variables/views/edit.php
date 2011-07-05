<hgroup class="pg"><div class="w">
	<h1><em class="picto inbox"></em> <?php echo Backend::get_title(); ?></h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('variables'); ?>" class=""><em class="picto back"></em> Go back</a></li>
		<li><a href="<?php echo admin_url('variables/add'); ?>" class="add"><em class="picto plus"></em> Add New Variable</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc mainsingle">
	<div class="block pages">
		<?php echo @$errors; ?>
		<?php echo @$form; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->


