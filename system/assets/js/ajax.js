var hash = document.location.hash.replace('#', '');


// Init and change handlers
/*$.address.change(function(event) {  
	log(event);
});*/



jQuery(function($) {
	// !/===> Ajax Links
	/*$("nav.main a").not(".nojax").unbind("click").bind("click", function(e) {
		e.preventDefault();
		var $el = $(this);
		var $li = $el.parent();
		var href = $el.attr("href");
		var uri = str_replace(site_url, "#!/", href);
		document.location = site_url+uri;
		
		// Mark it
		$("nav.main li").removeClass("on");
		$li.addClass("on");
		
		//$("#scene").load(href);
		$("footer#f1").fadeTo(200, .5);
		$("#layout").fadeTo(200, .5, function() {
			$("#scene").load(href, function() { $("#layout").fadeTo(200, 1); $("footer#f1").fadeTo(200, 1); ajax_initialize(); });
		
		});
	});*/
	
	
	
	
	// !Tabbed
	/*$(".block.tabbed").each(function() {
		var $el = $(this);
		var $tabs = $el.find(".tabs a");
		var $lis = $el.find(".tabs li");
		var $tc = $el.find(".tc");
		
		// Hide all tabs except 1st one
		$lis.eq(0).addClass("on");
		$tc.not(":first").hide();
		
		// Clicks
		$tabs.click(function(e) {
			e.preventDefault();
			var $tab = $(this);
			var index = $(this).attr("rel");
			
			// Show / hide
			$tc.not(".tc"+index).hide();
			$(".tc"+index).show();
			
			// On / off
			$lis.removeClass("on");
			$tab.parent("li").addClass("on");
			
		});
	});*/
	
	
	
	
	// !/===> Inital ajax load
	/*if (hash) {
		uri = site_url + hash.replace('!/', '');
		$("#scene").load(uri, function() { ajax_initialize(); });
	} else {
		$("#scene").load(site_url+'dashboard', function() { ajax_initialize(); });
	} // end if*/
	
	
	
	/*$.address.change(function(event) {  
		var uri = site_url+event.path;
		
		$("footer#f1").fadeTo(200, .5);
		$("#layout").fadeTo(200, .5, function() {
			$("#scene").load(uri, function() { mark_nav(); $("#layout").fadeTo(200, 1); $("footer#f1").fadeTo(200, 1); ajax_initialize(); });
			;
		});
		
		
		
	});
	mark_nav();*/
	
	
	/*$('nav.main a').address(function(e) {
		var $el = $(this);
		var $li = $el.parent();
		var href = $el.attr("href");
		var uri = str_replace(site_url, "", href);
		
		$("footer#f1").fadeTo(200, .5);
		$("#layout").fadeTo(200, .5, function() {
			$("#scene").load(href, function() { $("#layout").fadeTo(200, 1); $("footer#f1").fadeTo(200, 1); ajax_initialize(); });
		
		});
		
		return uri;
		//var uri = str_replace(site_url, "#!/", href);
		//document.location = site_url+uri;
	}); */
});




function mark_nav() {
	$("nav.main li").removeClass("on");
	$("nav.sub li").removeClass("on");
	//$('nav.main a[rel="address:'+path+'"]').parent().addClass("on");
	//alert(path);
	
	if (nav_mark_1) { $("nav.main li.nav-"+nav_mark_1).addClass("on"); }
	
}