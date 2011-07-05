<hgroup class="pg"><div class="w">
	<h1><em class="picto <?php echo $channel->icon; ?>"></em> <?php echo Backend::get_title(); ?></h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('content/all/'.$channel->slug); ?>" class=""><em class="picto back"></em> Go back</a></li>
		<li><a href="<?php echo admin_url('content/add/'.$channel->slug_singular); ?>" class="add"><em class="picto plus"></em> Add new <?php echo $channel->slug_singular; ?></a></li>
	</ul></div>
</div></hgroup>

<section class="mainc wside wside2">
	<aside class="sidebar default">
		<?php $this->load->view('content/widgets/field_groups'); ?>
		<?php $this->load->view('content/widgets/lang'); ?>
		<?php $this->load->view('content/widgets/revisions'); ?>
	</aside>
	<!-- /.sidebar -->
	
	<aside class="sidebar alt">
		<?php $this->load->view('content/widgets/publisher'); ?>
		<?php $this->load->view('content/widgets/pages'); ?>
		<?php $this->load->view('content/widgets/categories'); ?>
	</aside>
	<!-- /.sidebar -->
	
	<div class="block <?php echo $channel->slug; ?>" class="edit-entry">
		<?php echo @$errors; ?>
		<?php echo @$form; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->