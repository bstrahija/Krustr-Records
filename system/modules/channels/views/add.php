<hgroup class="pg"><div class="w">
	<h1><em class="picto relation"></em> Add New Channel</h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('channels'); ?>" class="back"><em class="picto back"></em> Back to channels</a></li>
		<li><a href="<?php echo admin_url('fields'); ?>" class="back"><em class="picto back"></em> Back to fields</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc">
	<div class="block comments">
		<?php
			if (isset($errors)) echo $errors;
			if (isset($form)) 	echo $form;
		?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->