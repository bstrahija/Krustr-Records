jQuery(function($) {
	
	// Initialize some actions
	grid_init();
	
	
	
	// Lock column widths
	$("table.grid thead th").each(function() { $(this).css("width", $(this).width()+"px"); });
	
	// Init sorting (will be a plugin in the end just like the pager)
	$("a.grid-sort").unbind("click").click(function() {
		var $el = $(this);
		var $table = $el.closest("table");
		$table.stop().delay(200).fadeTo(200, .5);
		$("a.grid-sort").not(this).removeClass("grid-sort-asc").removeClass("grid-sort-desc");
		
		var dir = "ASC";
		if ($(this).hasClass("grid-sort-desc") || ! $(this).hasClass("grid-sort-asc")) {
			$(this).addClass("grid-sort-asc").removeClass("grid-sort-desc");
			dir = "ASC";
		} else {
			$(this).addClass("grid-sort-desc").removeClass("grid-sort-asc");
			dir = "DESC";
		} // end if
		
		$.ajax({
			 type: 		"POST"
			,url: 		$(this).attr("href")
			,dataType: 	"html"
			,data: 		{
				 ci_csrf_token: 	$.cookie("csrf_cookie_name")
				,grid_order_by: 	$(this).attr("rel")
				,grid_order_dir: 	dir
			}
			,success: 	function(html) {
				$("table.grid tbody").html(html);
				$table.dequeue().stop().fadeTo(200, 1);
			}
			,error: 	function(data) {
				alert("Error!");
				$table.stop().fadeTo(200, 1);
			}
		});
		
		return false;
	});
	
	
	/* ---------------------------------- */
	/* !Filters */
	/* ---------------------------------- */
	
	// Reset
	$(".content-filter").find("form").each(function() {
		this.reset();
	});
	
	// Trigger
	var filter_timeout = false;
	var filter_keyword = '';
	$(".content-filter").find("input, select").not("input[name=filter_keywords]").change(function() {
		clearTimeout(filter_timeout);
		$(".content-filter").find("form").trigger("submit");
	});
	$(".content-filter input[name=filter_keywords]").bind("keyup click blur", function() {
		clearTimeout(filter_timeout);
		var $el = $(this);
		var val = $el.val().toLowerCase();
		filter_timeout = setTimeout(function() {
			if (val != filter_keyword) {
				$(".content-filter").find("form").trigger("submit");
				filter_keyword = val;
			} // end if
		}, 1000);
	});
	
	// Request
	$(".content-filter").find("form").submit(function(e) {
		clearTimeout(filter_timeout);
		var $form = $(this);
		var post_data = $form.serialize();
		post_data.ci_csrf_token = $.cookie("csrf_cookie_name");
		$(".content-filter").fadeTo(50, .4);
		
		$.ajax({
			 type: 		"get"
			,url: 		current_url
			,data: 		post_data
			,dataType: 	"html"
			,success: 	function(data) {
				clearTimeout(filter_timeout);
				var $data = $(data).find(".block");
				$("#scene .block").html($data.html());
				$(".content-filter").fadeTo(50, 1, function() { $(this).removeAttr("style"); });
				if (function_exists("init_pages")) init_pages();
			}
			,error: 	function(data) {
				clearTimeout(filter_timeout);
				$(".content-filter").fadeTo(50, 1, function() { $(this).removeAttr("style"); });
				alert("Error!");
			}
		});
		e.preventDefault();
	});
	
	
});



function grid_init()
{
	// Zebra
	$("tbody tr:odd").addClass("odd");
	
	// Delete confirmation
	/*$("table a.delete, table td.delete a").unbind("click").bind("click", function() {
		var $tr = $(this).closest("tr");
		var $url = $(this).attr("href");
		
		$tr.stop().dequeue().animate({ backgroundColor: "#fee1e1" }, 200, function() {
			if ( confirm("Are you sure you want to delete this item?") ) {
				$.ajax({
					 type: 		"GET"
					,url: 		$url
					,dataType: 	"html"
					,success: 	function(data) {
						$tr.fadeOut(500, function() { $(this).remove(); grid_init(); });
						show_notice(data);
					} //end success()
					,error: 	function(data) {
						alert("Error!");
					} //end error()
				});
			} else {
				$tr.css("backgroundColor", "");
			} //end if
		}); //end animate()
		
		return false;
	});*/
	
	
}