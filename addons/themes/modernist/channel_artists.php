<?php partial('header'); ?>

<section class="articles">
	<h1>Artists</h1>
	
	{{#entries}}
		<article>
			<header>
				<h2><a href="{{permalink}}">{{title}}</a></h2>
			</header>
			
			{{#f_thumb}}
				<div class="thumb"><a href="{{permalink}}"><img src="{{*f_thumb action='thumb' width='120' height='120'}}" alt="" width="120" /></a></div>
			{{/f_thumb}}
			
			{{{summary}}}
			
			<section class="meta">
				<ul>
					<li>
						<a href="{{permalink}}#add-comment">Leave your comment</a>
						Tagged as: &bull; <a href="#">Some tag</a>, <a href="#">Another tag</a>, <a href="#">And one more</a>
					</li>
					<li>
						Share on 	<a href="http://twitter.com/home?status=Currently reading: {{title}} {{permalink}}">Twitter</a>, 
									<a href="http://www.facebook.com/share.php?u={{permalink}}&amp;t={{title}}">Facebook</a>, 
									<a href="http://del.icio.us/post?v=4;url={{permalink}}">Delicious</a>, 
									<a href="http://digg.com/submit?url={{permalink}}">Digg</a>, 
									<a href="http://www.reddit.com/submit?url={{permalink}}&amp;title={{title}}">Reddit</a>
					</li>
					<li><a href="#">Edit this post</a></li>
				</ul>
			</section>
			
			
			<?php /*if (field('thumb', $entry)) : ?>
				<div class="thumb"><a href="<?php echo get_permalink($entry); ?>"><img src="<?php echo image_thumb(field('thumb', $entry), 120, 120); ?>" alt="" width="120" /></a></div>
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
			*/ ?>
		</article>
	{{/entries}}
</section>
<!-- /#main -->

<?php partial('footer'); ?>