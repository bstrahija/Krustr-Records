	</div>
	<!-- /#main -->
	
	<hr>
	
	<?php partial('aside'); ?>
	
	<footer>
		<p>
			&copy; <?php echo date('Y'); ?> <a href="http://www.creolab.hr">Creo</a>
			<em>&bull;</em> Built with <a href="http://www.krustr.net/" target="_blank">Krustr&deg;</a> &amp; <a href="http://www.codeigniter.com/" target="_blank">CodeIgniter</a>
			<em>&bull;</em> Hosted on <a href="http://www.webfaction.com/?affiliate=creolab" target="_blank">WebFaction</a>
			
			<?php if (is_admin()) : ?>
				<a href="#" class="show-profiler">☢</a> {elapsed_time}ms / {memory_usage}
			<?php endif; ?>
		</p>
    </footer>
</div>
<!-- /#wrap -->

<?php partial('assets_js'); ?>

<?php partial('facebook_footer'); ?>

<?php partial('debug'); ?>

<?php //krustr(); ?>

<?php //echo fb_footer(); ?>
</body>
</html>