var hash = document.location.hash.replace('#', '');

$(function() {
	
	log("App initialized.");
	
	
	// !✰ Debug functions
	$(".view-data a.toggle").click(function() {
		$(this).next().toggle();
		return false;
	});
	$(".profiler a.toggle").click(function() {
		$("#codeigniter_profiler").toggle();
		return false;
	});
	
	
	// !✰ Remove autocomplete style in Chrome
	if (navigator.userAgent.toLowerCase().indexOf("chrome") >= 0) {
	$(window).load(function(){
	    $('input:-webkit-autofill').each(function(){
	        var text = $(this).val();
	        var name = $(this).attr('name');
	        $(this).after(this.outerHTML).remove();
	        $('input[name=' + name + ']').val(text);
	    });
	});}
	
	
	// !✰ Submit forms on enter in IE
	if ($.browser.msie || $.browser.webkit) {
		$('input').keydown(function(e){
			if (e.keyCode == 13) {
				$(this).parents('form').submit();
				return false;
			} // end if
		});
	} // end if
	
	$("input[type=text], input[type=password]").addClass("txt");
	
	
	// !✰ Lighbox madness
	$("a.youtube").click(function(e) {
		$.fancybox({
			'padding'		: 0,
			'autoScale'		: false,
			'transitionIn'	: 'none',
			'transitionOut'	: 'none',
			'title'			: this.title,
			'width'		    : 680,
			'height'		: 495,
			'href'			: this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
			'type'			: 'swf',
			'swf'			: {
			   	 'wmode'		: 'transparent',
				'allowfullscreen'	: 'true'
			}
		});
	
		e.preventDefault();
	});
	$(".gallery a").fancybox({
		transitionIn:  'elastic',
		transitionOut: 'elastic'
	});
	$(".iframe").fancybox({
		 width: 580
		,height: 420
		,padding: 0
	});
	 
	 
	 
	 
	

});