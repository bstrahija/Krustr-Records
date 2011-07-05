<?php if (config_item("multilang")) : ?>
	<div class="widget lang">
		<h2>Language</h2>
		<?php $this->load->view('inc/default/lang'); ?>
	</div>
	<!-- /.lang -->
<?php endif; ?>