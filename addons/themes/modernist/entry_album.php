<?php get_header(); ?>

<article>
	<?php
		// Find related artist
		$artist = belongs_to('albums', array('get_fields'=>TRUE));
	?>
	<header>
		<h2><?php echo title(); ?></h2>
		<?php if ($artist) : ?>
			<p>by <?php echo permalink($artist); ?></p>
		<?php endif; ?>
	</header>
	
	
	<section class="mixed clearfix">
		<?php if (field('cover')) : ?>
			<div class="album-art"><img src="<?php echo image_thumb(field('cover'), 200, 200); ?>" alt="" width="200" /></div>
		<?php endif; ?>
		<?php echo content(); ?>
	</section>
	
	
	<?php if (field('video')) : ?>
		<section class="video">
			<h3>Video</h3>
			<div class="video-container">
				<?php $video = explode('::', field('video')); $video = $video[1]; ?>
				<?php echo show_video($video, 530, 390); ?>
			</div>
		</section>
	<?php endif; ?>
	
	
	<?php $gallery_images = $this->cms->gallery(field('other-artwork')); ?>
	<?php if ($gallery_images) : ?>
		<section class="gallery">
			<h3>Other Artwork</h3>
			<ul>
				<?php foreach ($gallery_images as $image) : ?>
					<li><a href="<?php echo site_url($image->file_path); ?>"><img src="<?php echo image_thumb($image->file_path, 100, 100); ?>" alt="" /></a></li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>
	
	
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