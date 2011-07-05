<?php if (isset($page_entry_tree) && $page_entry_tree) : ?>
	<div class="widget pages">
		<h2>Parent Entry</h2>
		
		<select name="parent_id" id="side-parentpage">
			<option value="">-</option>
			<?php foreach ($page_entry_tree as $page) : ?>
				<option value="<?php echo $page->id; ?>" class="level-<?php echo $page->offset ?>" <?php echo ($page->id == @$entry->parent_id or $page->id == @$child_of_id) ? 'selected="selected"' : ''; ?>>
				<?php echo repeater('&mdash;', (int) $page->offset); ?>
				<?php echo $page->title; ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
	<!-- /.categories -->
	
	<script type="text/javascript" charset="utf-8">
	//<![CDATA[
	$(".widget.pages select").change(function() {
		// Update hidden field
		$(".content-form input[name=parent_id]").val( $(this).val() );
	});
	
	<?php if (@$child_of_id) : ?>
	$(function() {
		$("#content-form input[name=parent_id]").val("<?php echo @$child_of_id; ?>");
	});
	<?php endif; ?>
	//]]>
	</script>
<?php endif; ?>