<?php if ($this->config->item('multilang')) : ?>
	<div class="langs uniform-agent"><p>
		<?php
			$lang = LANG;
			if ( ! $lang) {
				$lang = KR_LANG;
				change_lang($lang);
			} // end if
		?>
		<select name="select-lang">
			<?php foreach ($this->config->item('langs') as $lang_key=>$lang_name) : ?>
				<option <?php echo ($lang == $lang_key) ? ' selected="selected"' : ''; ?> value="<?php echo $lang_key; ?>"><?php echo $lang_name; ?></option>
			<?php endforeach; ?>
		</select>
	</p></div>
<?php endif; ?>

<script>
$(function() {
	$(".langs select").change(function() {
		$("#scene").fadeTo(10, .4);
		hash = document.location.hash.replace('#', '');
		var loc = admin_url+'/content/change_lang/'+$(this).val();
		if (hash) loc = loc + '/' + hash;
		document.location = loc;
	});
});
</script>