<div class="relations">
	<p class="btn"><a href="#" class="relation-toggle btn"><strong>+</strong> Pick related entry</a></p>
	<div class="relation-scroller">
		<div class="filter"><h4>Filter</h4><input type="text" value="" name="filter-txt" /></div>
		<?php if ($entries) : ?>
			<ol>
				<?php foreach ($entries as $entry) : ?>
				<li class="chk">
					<input id="relation_<?php echo $field->id; ?>_<?php echo $entry->id; ?>" type="checkbox" value="<?php echo $entry->id; ?>" name="relation[<?php echo $field->id ?>][]" <?php echo (in_array($entry->id, $relation_ids)) ? ' checked="checked"' : ''; ?> />
					<label for="relation_<?php echo $field->id; ?>_<?php echo $entry->id; ?>"><?php echo $entry->title, ' <em>[', $entry->id, ']</em>'; ?></label>
				</li>
				<?php endforeach; ?>
			</ol>
		<?php endif; ?>
	</div>
	
	<h4>Existing relations</h4>
	<?php if (isset($relations) && $relations) : ?>
		<table class="grid">
			<thead>
				<tr>
					<th scope="col" width="1%">#</th>
					<th scope="col">Title</th>
					<th scope="col" width="1%" class="c">★</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($relations as $relation) : ?>
				<tr>
					<td><?php echo $relation->related_id; ?></td>
					<td><?php echo $relation->entry_data->title; ?></td>
					<td><a href="<?php echo admin_url('content/remove_relation/'.$relation->id); ?>" class="delete act" data-confirm="Are you sure?">Remove</a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		<p>No entries found.</p>
	<?php endif; ?>
	
	<!-- <p class="btn"><?php echo admin_anchor('content/add_relation/'.$entry_id, '<strong>+</strong> Add new relation', 'class="btn relation-trigger"'); ?></p> -->
</div>
<script>
$(function() {
	// !/===> Relations
	$("a.relation-toggle").click(function() {
		$(this).parent().parent().find(".relation-scroller").toggle();
		return false;
	});
	
	
	// Filter relation entries
	$(".relation-scroller").each(function() {
		var $el = $(this);
		var $inp = $el.find("input[name=filter-txt]");
		var $ul = $el.find("ul");
		var $lis = $el.find("li");
		
		$inp.keyup(function () {
		    var filter = $(this).val(), count = 0;
		    $lis.each(function () {
		        if ($(this).text().search(new RegExp(filter, "i")) < 0) {
		            $(this).hide();
		        } else {
		            $(this).show();
		            count++;
		        }
		    });
		    $("#filter-count").text(count);
		});
		
	});

});
</script>