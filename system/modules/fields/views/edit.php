<hgroup class="pg"><div class="w">
	<h1><em class="picto relation"></em> <?php echo Backend::get_title(); ?></h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('fields/add/'.$channel->id.'/'.$group->id); ?>" class="add"><em class="picto plus"></em> Add New Field</a></li>
		<li><a href="<?php echo admin_url('fields'); ?>" class="back"><em class="picto back"></em> Back</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc wside">
	<aside class="sidebar default ontop">
		<?php $this->load->view('fields/widgets/fields'); ?>
	</aside>
	<!-- /.sidebar -->
	
	<div class="block fields">
		<?php
			if (isset($errors)) echo $errors;
			if (isset($form)) 	echo $form;
		?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->
