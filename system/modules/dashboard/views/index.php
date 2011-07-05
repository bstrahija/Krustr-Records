<hgroup class="pg"><div class="w">
	<h1><em class="picto star"></em> Shortcuts</h1>
	<h2>Get there faster...</h2>
</div></hgroup>

<section class="mainc">
	<div class="block no-min start">
		<div class="col">
			<h3><em class="picto write"></em> Create</h3>
			<ul class="links">
				<?php foreach ($channels as $channel) : ?>
					<li><a href="<?php echo admin_url('content/add/'.$channel->slug); ?>" title="New <?php echo $channel->title_singular; ?>"><em class="picto <?php echo $channel->icon; ?>"></em> New <?php echo strtolower($channel->title_singular); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<!-- /.col -->
		<div class="col">
			<h3><em class="picto write"></em> Manage</h3>
			<ul class="links">
				<?php foreach ($channels as $channel) : ?>
					<li><a href="<?php echo admin_url('content/all/'.$channel->slug); ?>" title="Manage <?php echo $channel->title; ?>"><em class="picto <?php echo $channel->icon; ?>"></em> <?php echo $channel->title; ?></a></li>
				<?php endforeach; ?>

				<?php if (is_superadmin()) : ?>
					<li class="sep"><a href="<?php echo admin_url('categories'); ?>" title="Manage categories"><em class="picto list"></em> Categories</a></li>
					<li><a href="<?php echo admin_url('variables'); ?>" title="Manage veriables"><em class="picto inbox"></em> Variables</a></li>
				<?php endif; ?>
				
				<?php if (is_superadmin()) : ?>
					<li><a href="<?php echo admin_url('fields'); ?>" title="Manage channels and fields"><em class="picto relation"></em> Channels &amp; Fields</a></li>
				<?php endif; ?>
			</ul>
		</div>
		<!-- /.col -->
		<div class="col">
			<h3><em class="picto cog"></em> Tools</h3>
			<ul class="links">
				<li><a href="<?php echo site_url();                      ?>" title="View the site"><em class="picto globe"></em> View site</a></li>
				<li><a href="<?php echo admin_url('users');              ?>" title="Manage users"><em class="picto user"></em> Manage users</a></li>
				<li><a href="<?php echo admin_url('system/settings');    ?>" title="System settings"><em class="picto tools"></em> Settings</a></li>
				<li><a href="<?php echo admin_url('system/maintenance'); ?>" title="Edit mantenance options"><em class="picto off"></em> Maintenance</a></li>
				<li><a href="<?php echo admin_url('users');              ?>" title="Log out"><em class="picto lock"></em> Log out</a></li>
			</ul>
		</div>
		<!-- /.col -->
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->



<hgroup class="pg"><div class="w">
	<h1><em class="picto stats-alt"></em> Stats</h1>
	<h2>Straight from the Google Analytics account. <a href="#" class="help" title="The stats are cached and refreshed every 3 hours."><em class="picto">?</em></a></h2>
</div></hgroup>

<section class="mainc">
	<div class="block no-min analytics">
		<div class="cont">
			<div class="chart">
				<?php if (isset($analytic_views) and $analytic_views and isset($analytic_visits) and $analytic_visits) : ?>
					<?php $this->load->view('analytics'); ?>
				
				<?php else : ?>
					Loading...
					<script>
					$(function() {
						$.ajax({
							 type: 		"post"
							,url: 		"<?php echo admin_url('dashboard/ga'); ?>"
							,dataType: 	"html"
							,success: 	function(data) {
								$(".chart").html(data);
								initialize_stats();
							}
							,error: 	function(data) {
								//alert("Error!");
							}
						});
					});
					</script>
				
				<?php endif; ?>
			</div>
			<!-- /.chart -->
		</div>
		<!-- /.cont -->
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->


