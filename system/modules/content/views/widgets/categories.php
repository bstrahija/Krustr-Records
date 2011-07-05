<?php if (isset($category_tree) and ! empty($category_tree)) : ?>
	<div class="widget categories">
		<h2>Categories</h2>
		<?php if (isset($category_tree) and ! empty($category_tree)) : ?>
			<ul class="checks">
				<?php foreach ($category_tree as $category) : ?>
					<li>
						<?php if ($category->offset) : ?>
							<em><?php echo repeater('&mdash;', (int) $category->offset); ?></em>
						<?php endif; ?>
						
						<input type="checkbox" value="<?php echo $category->id; ?>" name="categories" id="chk-category-<?php echo $category->id; ?>"<?php echo (in_object_list($in_categories, $category->id, 'category_id')) ? ' checked="checked"' : ''; ?>>
						<label for="chk-category-<?php echo $category->id; ?>"><?php echo $category->title; ?></label>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p>No categories found.</p>
		
		<?php endif; ?>
	</div>
	<!-- /.categories -->
	
	<script type="text/javascript" charset="utf-8">
	//<![CDATA[
	$(".widget.categories li input[type=checkbox]").change(function() {
		// Get ID's of selected categories
		var vals = new Array();
		$(".widget.categories li input[type=checkbox]:checked").each(function() {
			vals.push($(this).val());
		});
		vals = vals.join(",");
		
		// Update hidden field
		$("input[name=in_categories]").val(vals);
		
	});
	//]]>
	</script>
<?php endif; ?>