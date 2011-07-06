<?php partial('header'); ?>

<article>
	{{#content.entry}}
	<header>
		<h2>{{title}}</h2>
	</header>
	
	
	<section class="mixed clearfix">
		{{#f_thumb}}
			<div class="artist-art"><img src="{{*f_thumb action='thumb' width='200' height='200'}}" alt="" width="200" /></div>
		{{/f_thumb}}
		
		{{{body}}}
	</section>
	
	
	<?php if (isset($content->entry->f_video) and $content->entry->f_video) : ?>
		<section class="video">
			<h3>Video</h3>
			<div class="video-container">
				<a class="youtube" href="<?php echo $content->entry->f_video; ?>" target="_blank"><img src="<?php echo $this->video_embed->get_video_thumb($content->entry->f_video); ?>" width="193" alt=""></a>
			</div>
		</section>
	<?php endif; ?>
	
	
	<?php /*if (@$entry->fields['video']) : ?>
	<section class="video">
		<h3>Video</h3>
		<div class="video-container">
			<?php $video = explode('::', $entry->fields['video']); $video = $video[1]; ?>
			<?php echo show_video($video, 530, 380); ?>
		</div>
	</section>
	<?php endif;*/ ?>
	
	
	<?php
		// Get related albums
		/*$albums = related($entry, 'albums', array('get_fields'=>TRUE));
	?>
	<?php if ($albums) : ?>
	<section class="related">
		<h3>Albums</h3>
		<ol class="artist-album-list">
			<?php foreach ($albums as $album) : ?>
				<li class="clearfix">
					<?php if (field('cover', $album)) : ?>
					<div class="img"><a href="<?php echo get_permalink($album); ?>"><img src="<?php echo image_thumb(field('cover', $album), 50, 50); ?>" alt="" width="50" height="50" /></a></div>
					<?php endif; ?>
					
					<h4>
						<?php //echo $this->cms->placeholder('title', $album); ?>
						<?php echo permalink($album, title($album)); ?>
					</h4>
					<p>
						<?php //echo $this->cms->placeholder('excerpt', $album); ?>
						<?php echo excerpt($album, FALSE, 100); ?>
					</p>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>
	<?php endif;*/ ?>
	
	
	<? /*<section class="meta">
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
	</section> */ ?>
	
	
	{{/content.entry}}
</article>

<?php partial('footer'); ?>