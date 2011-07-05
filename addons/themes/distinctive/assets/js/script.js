jQuery(function($) {
	log(123);
	
	$("footer .toggle").click(function(e) {
		e.preventDefault()
		$("#codeigniter_profiler").toggle();
	});
});