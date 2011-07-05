<hgroup class="pg"><div class="w">
	<h1><em class="picto relation"></em> <?php echo Backend::get_title(); ?></h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('fields'); ?>" class="back"><em class="picto back"></em> Back</a></li>
	</ul></div>
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
