<?php get_header(); ?>

<article>
	<h2>My Profile</h2>
	
	<?php if (logged_in()) : ?>
		<dl>
			<dt>Name:</dt>
				<dd><?php echo $this->auth->get_display_name(); ?></dd>
			<dt>Email:</dt>
				<dd><?php echo $this->auth->get_email(); ?></dd>
		</dl>
		
	<?php elseif (fb_logged_in()) : ?>
		<?php
			$this->facebook->socialize_user();
			
			$user 		= $this->socializeauth->user();
			$me 		= $this->facebook->get_me();
			$feed_posts = $this->facebook->api('/me/feed');
		?>
		
		<dl>
			<dt>Photo:</dt>
				<dd><a href="<?php echo $me['link']; ?>"><img src="<?php echo facebook_picture(); ?>" class="profile-image" alt="Profile image" width="50" /></a></dd>
			<dt>Name:</dt>
				<dd><a href="<?php echo $me['link']; ?>"><?php echo socialize_name($user); ?></a></dd>
			<dt>E-Mail:</dt>
				<dd><?php echo $me['email']; ?></dd>
			<dt>Gender:</dt>
				<dd><?php echo ucfirst($me['gender']); ?></dd>
		</dl>
		
		<?php if (@$feed_posts['data']) : ?>
			<h3>Latest Posts on Wall</h3>
			<ul class="fb-posts">
				<?php $count = 0; ?>
				<?php foreach ($feed_posts['data'] as $post) : ?>
				<li><?php if (@$post['picture']) : 		?><div class="img"><a href="<?php echo @$post['link']; ?>"><img src="<?php echo $post['picture']; ?>" alt="" /></a></div><?php endif; ?>
					<?php if (@$post['name']) : 		?><h4><?php echo $post['name']; ?></h4><?php endif; ?>
					<?php if (@$post['caption']) : 		?><h5><?php echo $post['caption']; ?></h5><?php endif; ?>
					<?php if (@$post['message']) : 		?><p><?php echo $post['message']; ?></p><?php endif; ?>
					<?php if (@$post['description']) : 	?><p><?php echo $post['description']; ?></p><?php endif; ?>
					<?php if (@$post['attribution']) : 	?><p class="inf"><?php echo $post['attribution']; ?></p><?php endif; ?>
					</li>
					<?php if ($count >= 9) : break; endif; ?>
					<?php $count++; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	<?php endif; ?>
</article>

<?php get_footer(); ?>