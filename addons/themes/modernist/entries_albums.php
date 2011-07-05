<?php get_header(); ?>

<section class="articles">
	<h1>Releases</h1>
	
	<?php if ($entries) : foreach ($entries as $entry) : ?>
		<article>
			<header>
				<?php
					// Find related artist
					$artist = belongs_to('albums', array('get_fields'=>TRUE), $entry);
				?>
				<h2><?php echo permalink($entry); ?></h2>
				<?php if ($artist) : ?>
					<p>by <?php echo permalink($artist); ?></p>
				<?php endif; ?>
				<p><?php echo entry_date($entry); ?></p>
			</header>
			<?php if (field('cover', $entry)) : ?>
				<div class="thumb"><a href="<?php echo get_permalink($entry); ?>"><img src="<?php echo image_thumb(field('cover', $entry), 120, 120); ?>" alt="" width="120" /></a></div>
			<?php endif; ?>
			<?php echo excerpt($entry); ?>
			<p><?php echo permalink($entry, 'Read the rest of this entry »'); ?></p>
			
			<section class="meta">
				<ul>
					<li>
						<a href="<?php echo get_permalink($entry); ?>#add-comment">Leave your comment</a>
						Tagged as: &bull; <a href="#">Some tag</a>, <a href="#">Another tag</a>, <a href="#">And one more</a>
					</li>
					<li>
						Share on 	<a href="http://twitter.com/home?status=Currently reading: <?php echo title($entry); ?> <?php echo get_permalink($entry); ?>">Twitter</a>, 
									<a href="http://www.facebook.com/share.php?u=<?php echo get_permalink($entry); ?>&amp;t=<?php echo title($entry); ?>">Facebook</a>, 
									<a href="http://del.icio.us/post?v=4;url=<?php echo get_permalink($entry); ?>">Delicious</a>, 
									<a href="http://digg.com/submit?url=<?php echo get_permalink($entry); ?>">Digg</a>, 
									<a href="http://www.reddit.com/submit?url=<?php echo get_permalink($entry); ?>&amp;title=<?php echo title($entry); ?>">Reddit</a>
					</li>
					<li><a href="#">Edit this post</a></li>
				</ul>
			</section>
		</article>
	<?php endforeach; endif; ?>
</section>
<!-- /#main -->

<?php get_footer(); ?>