var hash = document.location.hash.replace('#', '');

jQuery(function($) {
	initialize();
		
});


function initialize() {
	// !/==> Lightboxes
	$("a.lightbox").fancybox();
	$("a.lightbox-ajax").fancybox({
		'type': 'ajax'
	});
	$("a.act.preview, .widget.publisher a.preview").fancybox({
		'type':           'iframe',
		'width':          '100%',
		'height':         '100%',
		'centerOnScroll': true
	});
	
	
	
	// !/==> Enable copying content from another lang
	if (multilang) {
		$(".content-form input[type=text], .content-form textarea").each(function() {
			var $el      = $(this);
			var id       = $el.attr("rel");
			var $row     = $el.parent(".row");
			var entry_id = $(".content-form input[name=entry_id]").val();
			var $trigger = $row.append('<a href="'+admin_url+'content/copy_from_lang/'+entry_id+'/'+id+'" class="copy-from-lang"><em class="picto">T</em></a>');
			$row.find(".copy-from-lang").fancybox({
				'type': 'ajax'
			})
		});
	}
	
	
	
	// !/==> Slugs
	$("#content-form input[name=title]").after('<a href="#" class="toggle-slug"><em class="mnml">G</em></a>');
	$("#content-form .slug").closest("p").hide();
	$(".toggle-slug").click(function(e) {
		$("#content-form .slug").closest("p").toggle();
		e.preventDefault();
	});
	$(".add-entry #content-form input[name=title]").bind("keyup blur", function() {
		var slug = liveUrlTitle($(this).val());
		$("input[name=slug]").val(slug);
	});

	
	
	
	// !/==> Setup required labels
	$(".mainc label.required em").text('').addClass("picto").addClass("star");
	
	
	// !/==> Replace legend tags
	$('legend').not("#codeigniter_profiler legend").each(function(index) {
		$(this).replaceWith('<h3 class="legend">' + $(this).text() + '</h3>');
	});


	
	
	
	
	
	// !/==> Click confirmation
	$('a[data-confirm], input[data-confirm], button[data-confirm]').not("[date-remote]").live('click', function(e) {
		var $el = $(this);
		
		apprise($el.attr('data-confirm'), {'verify':true}, function(r) {
			if (r) {
				document.location = $el.attr("href");
			} // if
		});
		
		e.preventDefault();
	});
	
	
	
	// !/==> Unobtrusive click actions
	$('a[data-remote],input[data-remote]').unbind("click").live('click', function (e) {
		var $link    = $(this),
		    remote   = $link.attr('href'),
		    data     = $link.attr('data-remote'),
		    $refresh = $($link.attr('data-refresh')),
		    confirm  = $link.attr('data-confirm');
		
		// Define remote action
		var remote_action = function() {
			$refresh.stop().fadeTo(1,.5);
			$.ajax({
				 type: 		"post"
				,url: 		remote
				,data: 		{ data: data }
				,dataType: 	"html"
				,success: 	function(data) {
					log(data);
					$refresh.html(data);
					$refresh.fadeTo(200,1);
				}
				,error: 	function(data) {
					log("Error!");
					$refresh.fadeTo(200,1);
				}
			});
		}
		
		// Check if confirmation is needed
		if (confirm) {
			apprise(confirm, {'verify':true}, function(r) {
				if (r) remote_action();
			});
		}
		else {
			remote_action();
		}
		    
		e.preventDefault();
	});
	
	
	
	// Some methods
	$('a[data-method]:not([data-remote])').unbind("click").live('click', function (e){
		/*var $link           = $(this),
		    href            = $link.attr('href'),
		    method          = $link.attr('data-method'),
		    $form           = $('<form method="post" action="'+href+'">'),
		    $metadata_input = '<input name="_method" value="'+method+'" type="hidden" />';
		
		if (csrf_token_name && csrf_hash) {
			$metadata_input += '<input name="'+csrf_token_name+'" value="'+csrf_hash+'" type="hidden" />';
		} // end if
		
		$form.hide()
		    .append($metadata_input)
		    .appendTo('body');
		
		e.preventDefault();
		$form.submit();
		
		*/
	});
	
	
	
	
	
	// !/==> Help text
	$("textarea[data-help], input[data-help]").each(function() {
		var $el  = $(this);
		var $row = $el.closest(".row");
		var help = unescape($el.attr('data-help'));
		help = help.replace(/\+/gi, " ");
		if (help) $row.append('<span class="help">'+help+'</span>');
	});
	
	
	// !/==> Login page
	$("#login input[type=text]:first").focus();
	$("#login input.txt").each(function() {
		$(this).parent().append('<span class="sh sh1">1</span><span class="sh sh2">2</span>');
	});
	$("#login #wrap").hide().delay(200).fadeIn(1000, function() {
		("$login input[type=text]:first").focus();
	});
	
	
	// !/==> Debug functions
	$(".view-data a.toggle").click(function() {
		$(this).next().toggle();
		return false;
	});
	
	$("footer.debug a.toggle").click(function() {
		$("#codeigniter_profiler, .view-data").toggle();
		return false;
	});
	
	
	// !/==> Navigation drop down
	$("nav > ul > li").hover(
		function() { $(this).addClass("hover").children(".dd").show(); },
		function() { $(this).removeClass("hover").children(".dd").hide(); }
	);
	
	// !/==> Uniform
	$("select, input:checkbox, input:radio").uniform();
	
	
	// !/==> Form help
	$(".block form a.help").click(function(e) {
		e.preventDefault();
		$(this).closest("li").find("p.help").slideToggle(200);
	});
	
	
	// !/==> Notices
	$(".notice a.close").click(function(e) {
		e.preventDefault();
		$(this).closest(".notice").fadeOut();
	});
	
	
	// !/==> Tooltips
	$("a.help, a.tip, a.tooltip, em[title], a[title]").tipTip({
		defaultPosition: "top"
	});
	
	
	// !/==> Rich text editor 
	loadEditors();
	
	
	// !/==> Date picker
	$("input.date-picker, input.datepicker").datepicker({
		 dateFormat: 	'yy/mm/dd'
		,defaultDate: 	+1
		,showAnim: 		'fade'
		,firstDay: 		1
		/*,buttonImage: 	app_url+'/assets/images/bg_calendar.gif'
		,buttonImageOnly: true*/
		//,showOn: 'button'
		,changeMonth: true
		,changeYear: true
	});
	$("input.timepicker").timepicker({
		showPeriodLabels: false,
	});
	
	
	// !/==> Stats charts
	initialize_stats();
	
	
} // end initialize()


$(window).load(function() {
	$("#page-loader").hide().remove();
});


/* ------------------------------------------------------------------------------------------ */

function loadEditors()
{
	// JWysiwyg
    if (rich_editor == 'jwysiwyg') {
	    $("textarea.rich").wysiwyg({
	    	css: app_url+'/assets/css/editor.css',
			rmUnusedControls: true,
			resizeOptions: {},
			formHeight: 600,
			initialContent: '',
			rmUnwantedBr: true,
			controls: {
				bold:           { visible : true },
				italic:         { visible : true },
				underline:      { visible : true },
				strikeThrough:  { visible: true },
				
				justifyLeft   : { visible : true },
				justifyCenter : { visible : true },
				justifyRight  : { visible : true },
				justifyFull   : { visible : true },
				
				insertOrderedList    : { visible : true },
				insertUnorderedList  : { visible : true },
				insertHorizontalRule : { visible : true },
				
				createLink    : { visible : true },
				/*insertImage   : { visible : true },*/
				removeFormat  : { visible : true },
				
				h2: { visible : true },
				h3: { visible : true },
				h4: { visible : true },
				h5: { visible : true },
				h6: { visible : true },
				
				html  : { visible: true },
			}
		});
	
	}
	
	// CK Editor
	else if (rich_editor == 'ckeditor') {
		var $editors = $("textarea.rich");
	    if ($editors.length) {
	        $editors.each(function() {
	            var editorID = $(this).attr("name");
	            var instance = CKEDITOR.instances[editorID];
	            if (instance) { CKEDITOR.remove(instance); }
	            CKEDITOR.replace(editorID, {
	            	toolbar                   : 'Krustr',
	            	filebrowserBrowseUrl      : admin_url+'/dashboard',
					filebrowserUploadUrl      : admin_url+'/dashboard',
					filebrowserImageBrowseUrl : admin_url+'/dashboard',
					filebrowserImageUploadUrl : admin_url+'/dashboard',
					filebrowserWindowWidth    : 800,
					filebrowserWindowHeight   : 500
	            });
	        });
	    }
	}
	
	// Markdown
	else if (rich_editor == 'markdown') {
		$("textarea.rich").markItUp(mySettings);
	}
}


/* ------------------------------------------------------------------------------------------ */

function initialize_stats() {
	// !Web stats
	$('table.stats').each(function() {
		
		if($(this).attr('rel')) {
			var statsType = $(this).attr('rel');
		} else {
			var statsType = 'area';
		}
		
		var chart_width = ($(this).closest('.cont').width()) - 60;
		
		
		if(statsType == 'line' || statsType == 'pie' || statsType == 'area') {		
			$(this).hide().visualize({
				type: statsType,	// 'bar', 'area', 'pie', 'line'
				width: chart_width,
				height: '340px',
				colors: ['#8C9840', '#BE5D40', '#82B6E7', '#ddd74c'],
				
				lineDots: 'double',
				interaction: true,
				multiHover: 5,
				tooltip: true,
				tooltiphtml: function(data) {
					var html ='';
					for(var i=0; i<data.point.length; i++){
						html += '<p class="chart_tooltip"><strong>'+data.point[i].value+'</strong> '+data.point[i].yLabels[0]+'</p>';
					}	
					return html;
				}
			});
		} else {
			$(this).hide().visualize({
				type: statsType,	// 'bar', 'area', 'pie', 'line'
				width: chart_width,
				height: '240px',
				colors: ['#8C9840', '#BE5D40', '#82B6E7', '#ddd74c'],
			});
		}
	});
}