<aside>
	<section class="social">
		<h2>Your Account</h2>
		<?php if (fb_logged_in()) : ?>
			<img src="<?php echo fb_picture(); ?>" class="profile-image" alt="Profile image" width="50" />
			<ul class="login">
				<li><?php echo anchor('profile', fb_name($this->facebook->user())); ?></li>
				<?php if (logged_in()) : ?>
				<li><?php echo anchor('link_with_facebook', 'Merge with FaceBook account'); ?></li>
				<?php endif; ?>
				<li><a class="logout" href="<?=site_url('logout')?>">Logout</a></li>
			</ul>
		
		<?php elseif (logged_in()) : ?>
			<img src="<?php echo (@$profile->avatar) ? @$profile->avatar : site_url('system/assets/images/avatar_dummy.png'); ?>" width="50" class="profile-image" alt="Profile image">
			<ul class="login">
				<li><?php echo anchor('profile', user_display_name()); ?></li>
				<li><a class="logout" href="<?=site_url('logout')?>">Logout</a></li>
			</ul>
			
		<?php else : ?>
			
			<?php echo form_open(current_url()); ?>
				<?php echo form_hidden('action', 'login'); ?>
				<ul>
					<li><?php echo form_label('Email', 'inp-email'); ?>
						<?php echo form_input('email', NULL, 'id="inp-email"'); ?></li>
					<li><?php echo form_label('Password', 'inp-password'); ?>
						<?php echo form_password('password', NULL, 'id="inp-password"'); ?></li>
					<li class="btn"><?php echo form_submit('submit', 'Login'); ?></li>
				</ul>
			<?php echo form_close(); ?>
			
		<?php endif;?>
		
		
		<?php if ( ! fb_logged_in() and ! logged_in()) : ?>
			<div class="login clearfix"><?php echo fb_login_button('Facebook Login'); ?></div>
			<p>Or you can login simply with your Facebook account.</p>
		<?php endif; ?>
	</section>
	
	
	<?php if ($this->config->item('multilang', 'krustr') === TRUE) : ?>
	<section>
		<h2>Language</h2>
		<ol>
			<?php foreach ($this->config->item('langs', 'krustr') as $lang_key=>$lang) : ?>
			<li><a href="<?php echo site_url($lang_key); ?>"><?php echo $lang; ?></a></li>
			<?php endforeach; ?>
		</ol>
	</section>
	<?php endif; ?>
	
	
	<section>
		<h2>About</h2>
		<p>This is a demo site for the <strong>Krustr&deg;</strong> content framework. It's built entirely on CodeIgniter, 
		and the theme it uses is the <a href="http://www.rodrigogalindez.com/themes/modernist/">Modernist theme</a> 
		by <a href="http://www.rodrigogalindez.com/">Rodrigo Galindez</a>.</p>
		<p>The theme is modified and is using <a href="http://html5boilerplate.com/">HTML5 Boilerplate</a> as the base for all templates and resources.</p>
		<p>The site is used exclusively as a testing platform for the content framework and could contain bugs in layout and functionality, since it is being constantly developed.</p>
	</section>
	
	
	<section>
		<h2>Search</h2>
		<?php echo form_open(site_url(), array('id'=>'searchform'), array('action'=>'search')); ?>
			<input type="text" name="s" value="" id="s" />
		<?php echo form_close(); ?>
	</section>
	
	
	<section>
		<h2>Categories</h2>
		<ul>
			<li><a href="#">A Category</a></li>
			<li><a href="#">Another Category</a></li>
			<li><a href="#">Lorem ipsum</a></li>
		</ul>
	</section>
</aside>