<hgroup class="pg"><div class="w">
	<h1><em class="picto relation"></em> <?php echo Backend::get_title(); ?></h1>
	<h2><?php echo Backend::get_data('subtitle'); ?></h2>
</div></hgroup>

<section class="mainc">
	<div class="block fields">
		<?php
			if (isset($errors)) echo $errors;
			if (isset($form)) 	echo $form;
		?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->
