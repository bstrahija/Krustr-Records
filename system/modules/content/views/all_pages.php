<hgroup class="pg"><div class="w">
	<h1><em class="picto <?php echo $channel->icon; ?>"></em> <?php echo $channel->title; ?></h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('content/add/'.$channel->slug_singular); ?>" class="add"><em class="picto plus"></em> Add new <?php echo $channel->title_singular; ?></a></li>
	</ul></div>
</div></hgroup>

<section class="mainc">
	<?php $this->load->view('widgets/filters_pages'); ?>
	
	<div class="block pages">
		<?php if (isset($entries) and $entries) : ?>
		<div class="pages-advanced">
			<?php
				if ($this->input->is_ajax_request()) {
					echo recordset_to_ul($entries, 'page-navigation');
				}
				else {
					$tree = recordset_to_tree($entries);
					echo tree_to_ul($tree, 'page-navigation');
				} // end if
			?>
			
			<div class="page-info">
				<div class="cont"><h3>Page Info</h3><div class="col"><p>No page selected.</p></div></div>
			</div>
			<!-- /.page-info -->
		</div>
		
		<script>
		$(function() {
			init_pages();
		});
		
		
		
		function init_pages() {
			// Add handle
			$(".page-navigation a.title").before('<em class="handle hact">-</em> ');
			
			// Add actions
			$(".page-navigation .item").each(function() {
				var $el 		= $(this);
				var id 			= $el.attr("rel");
				var $li 		= $el.parent("li");
				var $edit 		= $('<a href="'+admin_url+'content/edit/<?php echo $channel->slug_singular; ?>/'     +id+'" class="edit"><em class="picto pencil"></em> Edit</a>');
				var $add_child 	= $('<a href="'+admin_url+'content/add/<?php echo $channel->slug_singular; ?>/child/'+id+'" class="add_child"><em class="picto plus"></em> Add child</a>');
				var $delete 	= $('<a href="'+admin_url+'content/delete/<?php echo $channel->slug_singular; ?>/'   +id+'" data-confirm="Are you sure you want to delete the entry <b>['+id+']</b>?" class="delete"><em class="picto remove"></em> Delete</a>');
				var $acts 		= $('<div class="hact actions" />');
				$li.attr("id", "entry_id-"+id);
				
				$acts.append($edit);
				$acts.append($add_child);
				$acts.append($delete);
				$el.append($acts);
				
				//delete_confirm();
			});
			
			$(".page-navigation").sortable({
				 containment: 	'parent'
				,forceHelperSize: true
				,handle: 		'.handle'
				,items: 		'li'
				,helper: 		'clone'
				,opacity: 		0.5
				,axis: 			'y'
				,stop: function(e, ui) {
					var sort_ids = new Array();
					$(this).children("li").each(function() {
						sort_ids.push($(this).attr("rel"));
					});
					set_order(sort_ids);
				}
				//,placeholder: 	'ui-state-highlight'
			});
			//$(".page-navigation").disableSelection();
			
			
			$(".page-navigation ul").sortable({
				 containment: 	'parent'
				,forceHelperSize: true
				,handle: 		'.handle'
				,items: 		'li'
				,helper: 		'clone'
				,opacity: 		0.5
				,axis: 			'y'
				,stop: function(e, ui) {
					var sort_ids = new Array();
					$(this).children("li").each(function() {
						sort_ids.push($(this).attr("rel"));
					});
					set_order(sort_ids);
				}
				//,placeholder: 	'ui-state-highlight'
			});
			//$(".page-navigation ul").disableSelection();
			
			$(".page-navigation a.title").click(function(e) {
				e.preventDefault();
				
				var $el = $(this);
				var $item = $el.parent();
				var id = $item.attr("rel");
				
				$(".page-navigation .item").removeClass("on");
				$item.addClass("on");
				$(".page-info").fadeTo(200, .3);
				$(".page-info .cont").load(admin_url+"content/entry_info/"+id, function() {
					$(".page-info").fadeTo(200, 1);
				});
			});
		} // end init_pages();
		
		
		function set_order(order) {
			$(".page-navigation, .page-navigation ul").sortable('disable');
			$(".page-navigation").fadeTo(200, .3);
			$.ajax({
				 type: 		"POST"
				,url: 		admin_url+"content/ajax_order"
				,data: 		{
					ci_csrf_token: 	$.cookie("csrf_cookie_name"),
					entry_id:       order
				}
				,dataType: 	"html"
				,success: 	function(data) {
					$(".page-navigation, .page-navigation ul").sortable('enable');
					$(".page-navigation").fadeTo(200, 1);
					log(data);
				}
				,error: 	function(data) {
					$(".page-navigation, .page-navigation ul").sortable('enable');
					$(".page-navigation").fadeTo(200, 1);
					log("Error!");
				}
			});
			
			
		} // end set_order()
		
		
		
		/*function delete_confirm()
		{
			$(".page-navigation a.delete").unbind("click").click(function() {
				if (confirm("Are you sure?")) {
					return true;
				} // end if
				
				return false;
			});
			
		} // end delete_confirm()*/
		</script>
		
		<?php else : ?>
			<p class="nothing-found"><em class="picto alert"></em> No <?php echo $channel->slug; ?> found.</p>
			
		<?php endif; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->