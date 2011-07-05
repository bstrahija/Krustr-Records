	</div>
	<!-- /#main -->
	
	<hr>
	
	<?php partial('aside'); ?>
	
	<footer>
		<p>
			&copy; <?php echo date('Y'); ?> <a href="http://www.creolab.hr">Creo</a>
			<em>&bull;</em> Built with <a href="http://www.krustr.net/" target="_blank">Krustr&deg;</a> &amp; <a href="http://www.codeigniter.com/" target="_blank">CodeIgniter</a>
			<em>&bull;</em> Hosted on <a href="http://www.webfaction.com/" target="_blank">WebFaction</a>
			<?php if ($this->auth->is_admin()) : ?>
			<a href="#" class="show-profiler">☢</a>
			<?php endif; ?>
		</p>
    </footer>
</div>
<!-- /#wrap -->
<?php partial('assets_js'); ?>
<?php //krustr(); ?>

<?php //echo fb_footer(); ?>
</body>
</html>