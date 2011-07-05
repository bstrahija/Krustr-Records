	</div>
	<!-- #main -->

	<footer id="f1">
		<p>
			&copy; Copyright <?php echo date('Y'); ?> Boris Strahija &minus; <a href="http://www.creolab.hr" title="">Creo</a>
			&minus; {memory_usage} &bull; {elapsed_time}ms &bull; <a href="#" class="toggle"></a>
		</p>
	</footer>
</div>
<!-- /#layout -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.js"></script>
<script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo theme_url(); ?>/assets/js/libs/jquery-1.5.0.js"%3E%3C/script%3E'))</script>

<script src="<?php echo theme_url(); ?>/assets/js/plugins.js"></script>
<script src="<?php echo theme_url(); ?>/assets/js/script.js"></script>

<?php $this->output->enable_profiler(true); ?>

<?php /*echo '<pre>'; print_r(CMS::$front_data); echo '</pre>';*/ ?>

</body>
</html>