<?php get_header(); ?>

<h1>Deals</h1>
<?php $articles = entries('articles'); ?>
<?php if ($articles) : foreach ($articles as $article) : ?>
	<hr>
	<h3><?php echo date('Y/m/d H:i', $article->published_at); ?></h3>
	<h4><?php echo permalink($article, title($article, true)); ?></h4>
	<p><?php summary($article, 100); ?></p>
<?php endforeach; endif; ?>



<?php get_footer(); ?>