<?php get_header(); ?>

<section id="pitch">
	<h1><?php echo site_slogan(); ?></h1>
	<h2><?php echo site_description(); ?></h2>
</section>	


<div class="third">
	<h3>Consulting and Planning</h3>
	<p>An effective website requires a clear definition of goals, comprehensive planning, and expert execution. For sites that have not yet been built or are ready to undergo a transformation, we can provide information on architecture planning. Contact us for a free consultation.</p>
</div>
<div class="third">
	<h3>Architecture and Design</h3>
	<p>While constantly communicating with the client, we organize all aspects of the project to set paths for further design and development process. We create smart and usable interfaces with a strong accent on accessibility and semantically correct markup.</p>
</div>
<div class="third last">
	<h3>Application Development</h3>
	<p>Regardless of the project size, we offer robust and sophisticated "out of the box" or custom built solutions. From a typical content management system to specifically targeted web applications, we produce highly usable, modular and search engine optimized solutions.</p>
</div>
<div class="line"></div>



<div class="left">
	<?php $article = entry('about'); ?>
	<h4><?php title($article); ?></h4>
	<?php content($article); ?>
</div>

<div class="right">
	<?php $articles = entries('articles', 3); ?>
	<?php if ($articles) : foreach ($articles as $article) : ?>
		<h3><?php echo date('Y/m/d H:i', $article->published_at); ?></h3>
		<h4><?php echo permalink($article, title($article, true)); ?></h4>
		<p><?php summary($article, 100); ?></p>
		<br>
	<?php endforeach; endif; ?>
</div>

<?php get_footer(); ?>