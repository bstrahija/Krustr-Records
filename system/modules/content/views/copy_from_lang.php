<div class="popup">
	<h2>"<?php echo $entry->title; ?>" <em>In other languages</em></h2>
	<form name="fields-other-lang" action="" method="post">
		<p>
			<select name="select-lang">
				<?php foreach ($this->config->item('langs') as $lang_key=>$lang_name) : ?>
					<option <?php echo (LANG == $lang_key) ? ' selected="selected"' : ''; ?> value="<?php echo $lang_key; ?>"><?php echo $lang_name; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	</form>
	<article>
		<?php echo $entry->body; ?>
	</article>
</div>