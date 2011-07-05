var hash = document.location.hash.replace('#', '');

$(function() {
	$("input[type=text], input[type=password]").addClass("txt");
	
	// Toggle profiler
	$("footer .show-profiler").click(function() {
		$("#codeigniter_profiler").toggle();
		return false;
	});
	
	// Colorbox
	 $('.gallery a, a.lightbox, a.colorbox').colorbox({
	 	opacity: 0.7
	 });
	

});