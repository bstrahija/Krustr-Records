<?php $notices = Notice::get_all(); ?>

<div id="notifications">
	<div id="notification-message" class="notification-message“">
		<a class="ui-notify-close ui-notify-cross" href="#">x</a>
		<h1>#{title}</h1>
		<p>#{text}</p>
	</div>
	<!-- /#notification-message -->

	<div id="notification-error" class="notification-error">
		<a class="ui-notify-close ui-notify-cross" href="#">x</a>
		<h1>#{title}</h1>
		<p>#{text}</p>
	</div>
	<!-- /#notification-error -->
</div>
<!-- /#notifications -->

<script>
function create_notification( template, vars, opts ){
	return $notifications.notify("create", template, vars, opts);
}

$(function() {
	$notifications = $("#notifications").notify();
	
	<?php if ($notices and ! empty($notices)) : ?>
		<?php foreach ($notices as $type=>$messages) : ?>
			<?php if ($messages and ! empty($messages)) : ?>
				<?php foreach ($messages as $message) : ?>
					create_notification("notification-<?php echo $type; ?>", { title: 'Message', text:'<?php echo $message; ?>' }, { expires:10000 });
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
});
</script>
