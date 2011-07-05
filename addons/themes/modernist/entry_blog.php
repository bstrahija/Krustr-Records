<?php get_header(); ?>

<article>
	<header>
		<h2><?php echo title(); ?></h2>
		<p class="when">on <em><?php echo entry_date(NULL, 'Y/m/d H:i'); ?></em></p>
	</header>
	
	
	<section class="mixed clearfix">
		<?php echo content(); ?>
	</section>
	
	
	<section class="meta">
		<ul>
			<li>
				<a href="<?php echo get_permalink(); ?>#add-comment">Leave your comment</a>
				Tagged as: &bull; <a href="#">Some tag</a>, <a href="#">Another tag</a>, <a href="#">And one more</a>
			</li>
			<li>
				Share on 	<a href="http://twitter.com/home?status=Currently reading: <?php echo title(); ?> <?php echo get_permalink(); ?>">Twitter</a>, 
							<a href="http://www.facebook.com/share.php?u=<?php echo get_permalink(); ?>&amp;t=<?php echo title(); ?>">Facebook</a>, 
							<a href="http://del.icio.us/post?v=4;url=<?php echo get_permalink(); ?>">Delicious</a>, 
							<a href="http://digg.com/submit?url=<?php echo get_permalink(); ?>">Digg</a>, 
							<a href="http://www.reddit.com/submit?url=<?php echo get_permalink(); ?>&amp;title=<?php echo title(); ?>">Reddit</a>
			</li>
			<li><a href="#">Edit this post</a></li>
		</ul>
	</section>
</article>

<?php get_footer(); ?>