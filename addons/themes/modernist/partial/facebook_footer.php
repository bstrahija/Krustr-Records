<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
	FB.init({
		appId: '<?php echo fb_app_id(); ?>', 
		status: true, 
		cookie: true,
		xfbml: true
	});
	
	// Login / logout events
	FB.Event.subscribe("auth.sessionChange", function(response) {
		$("#layout").fadeTo(200, .5);
		
		if (response.session) {
			window.location.reload();
		} else {
			$("#auth .fb-logout").fadeOut();
			window.location = "<?php echo site_url('odjava'); ?>";
		}
	});
};
(function() {
	var e = document.createElement('script'); e.async = true;
	    e.src = document.location.protocol +
	            '//connect.facebook.net/en_US/all.js';
	document.getElementById('fb-root').appendChild(e);
	
}());
</script>