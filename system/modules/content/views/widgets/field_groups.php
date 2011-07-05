<div class="widget field-groups">
	<h2>Fields</h2>
	<ul class="buttons">
		<?php foreach ($field_groups as $group) : ?>
			<li><a href="#<?php echo $group->id; ?>" data-group-id="<?php echo $group->id; ?>"><?php echo $group->title; ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
<!-- /.field-groups -->

<script>
$(function() {
	$(".field-groups a").click(function(e) {
		var $el = $(this);
		var gid = parseInt($el.attr("data-group-id"));
		
		// Mark
		$(".field-groups a").removeClass("on");
		$el.addClass("on");
		
		// Show / hide
		$(".content-form fieldset").not(".actions").hide();
		$(".content-form fieldset#field-group-"+gid).show();
		$(".content-form fieldset.actions").show();
		$("input[name=active_field_group]").val(gid);
		
		//e.preventDefault();
	});
	
	// Init state
	//$(".content-form fieldset").not(".content-form fieldset:eq(0)").not(".actions").hide();
	//$(".content-form fieldset.actions").show();
	$(".content-form fieldset").not(".actions").hide();
	if (hash) {
		$(".content-form fieldset#field-group-"+hash+"").show();
		$(".field-groups a[data-group-id="+hash+"]").addClass("on");
		$("input[name=active_field_group]").val(hash);
	}
	else {
		$(".content-form fieldset:eq(0)").show();
		$(".field-groups a:eq(0)").addClass("on");
	} // end if
	
});
</script>