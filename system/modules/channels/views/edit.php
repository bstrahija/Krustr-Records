<hgroup class="pg"><div class="w">
	<h1><em class="picto relation"></em> Edit Channel <i>"<?php echo $channel->title; ?>"</i></h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('channels/add'); ?>" class="add"><em class="picto plus"></em> Add new channel</a></li>
		<li><a href="<?php echo admin_url('channels'); ?>" class="back"><em class="picto back"></em> Back to channels</a></li>
		<li><a href="<?php echo admin_url('fields'); ?>" class="back"><em class="picto back"></em> Back to fields</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc wside">
	<aside class="sidebar default ontop">
		<?php $this->load->view('channels/widgets/channels'); ?>
	</aside>
	<!-- /.sidebar -->
	
	<div class="block comments">
		<?php
			if (isset($errors)) echo $errors;
			if (isset($form)) 	echo $form;
		?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->