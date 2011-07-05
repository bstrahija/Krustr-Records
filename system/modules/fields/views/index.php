<hgroup class="pg"><div class="w">
	<h1><em class="picto relation"></em> Channels &amp; Fields</h1>
	<h2>Manage content channels, field groups and fields</h2>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('channels/add'); ?>" class="add"><em class="picto plus"></em> Add new channel</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc">
	<?php /*$this->load->view('inc/sidebar_channel_nav'); ?>
	<?php $this->load->view('inc/sidebar_fields');*/ ?>
	
	<?php
		// Get field icons
		$icons = $this->config->item('icons', 'fields');
	?>
	
	<div class="block fields">
		<ul class="tabs tabs-l panel-tabs" style="width: <?php echo 126 * count($channels) + 3; ?>px">
			<?php foreach ($channels as $channel) : ?>
				<li><a href="#" rel="<?php echo $channel->id; ?>"><em class="picto <?php echo $channel->icon; ?>"></em> <?php echo $channel->title; ?></a></li>
			<?php endforeach; ?>
		</ul>
		
		<?php if (isset($channels) and $channels) : ?>
			<ul class="panel-divs">
				<?php foreach ($channels as $channel) : ?>
					<li class="panel-div panel-div-<?php echo $channel->id; ?>">
						<blockquote class="panel-bubble">
							<h4><em>Channel: </em> <i>"<?php echo $channel->title; ?>"</i></h4>
							<?php echo admin_anchor('channels/edit/'.$channel->id, 	'<em class="picto pencil"></em> Edit channel', 	'class="add"'); ?>
							<?php echo admin_anchor('fields/groups/add/'.$channel->id, 	'<em class="picto plus"></em> Add Group', 	'class="add"'); ?>
						</blockquote>
						
						<?php foreach ($channel->field_groups as $group) : ?>
							<div class="group">
								<?php if ($group->type == 'default_group') : ?>
									<h3><a><em>Group:</em> <?php echo $group->title; ?></a></h3>
									<div class="actions">
										<?php echo admin_anchor('fields/add/'.$channel->id.'/'.$group->id, 	'<em class="picto plus"></em> Add Field', 	'class="add"'); ?>
									</div>
								
								<?php else : ?>
									<h3><a href="" class="group-toggle"><em>Group:</em> <?php echo $group->title; ?></a></h3>
									<div class="actions">
										<?php echo admin_anchor('fields/add/'.$channel->id.'/'.$group->id, 			'<em class="picto plus"></em> Add Field', 	'class="add"'); ?>
										<?php echo admin_anchor('fields/groups/edit/'.$channel->id.'/'.$group->id, 	'<em class="picto pencil"></em> Edit', 		'class="edit"'); ?>
										<?php echo admin_anchor('fields/groups/delete/'.$channel->id.'/'.$group->id, 	'<em class="picto remove"></em> Delete', 		'class="delete"'); ?>
									</div>
								
								<?php endif; ?>
								
								
								<div class="all-fields">
									<ul>
											<?php if ($group->type == 'default_group') : ?>
												<li class="default">
													<div class="sort"><p>&nbsp;</p></div>
													<div class="field-id"><p>&nbsp;</p></div>
													<div><p class="title type-text textarea"><em class="picto text"></em> <span>Title</span></p></div>
													<div class="type"><p>Text</p></div>
													<div><p>0</p></div>
													<div class="act"><p>&nbsp;</p></div>
													<div class="act"><p>&nbsp;</p></div>
												</li>
												<li class="default">
													<div class="sort"><p>&nbsp;</p></div>
													<div class="field-id"><p>&nbsp;</p></div>
													<div><p class="title type-text textarea"><em class="picto text textarea"></em> <span>Body</span></p></div>
													<div class="type"><p>Text</p></div>
													<div><p>0</p></div>
													<div class="act"><p>&nbsp;</p></div>
													<div class="act"><p>&nbsp;</p></div>
												</li>
												<li class="default">
													<div class="sort"><p>&nbsp;</p></div>
													<div class="field-id"><p>&nbsp;</p></div>
													<div><p class="title type-text textarea"><em class="picto text textarea"></em> <span>Summary</span></p></div>
													<div class="type"><p>Text</p></div>
													<div><p>0</p></div>
													<div class="act"><p>&nbsp;</p></div>
													<div class="act"><p>&nbsp;</p></div>
												</li>
											<?php endif; ?>
												
											<?php if ($group->fields) : ?>
												<?php foreach ($group->fields as $field) : ?>
													
													<?php
														$extra_class = '';
														if ($field->type == 'text' && $field->extra_options = 'multiline') $extra_class = 'textarea';
													?>
													
													<li>
														<div class="sort"><p><a href="#" class="handle"><em class="picto list"></em></a></p></div>
														<div class="field-id"><p><?php echo $field->id; ?></p></div>
														<div><p class="title"><em class="<?php echo @$icons[$field->type].' '.$extra_class; ?>"></em> <span><?php echo admin_anchor('fields/edit/'.$field->id, $field->title); ?></span></td></div>
														<div class="type"><p><?php echo ucfirst($field->type); ?></p></div>
														<div><p class="order-key"><?php echo ucfirst($field->order_key); ?></p></div>
														<div class="act"><p><?php echo admin_anchor('fields/edit/'.$field->id, 		'<em class="picto pencil"></em> Edit', 		'class="act edit"'); ?></p></div>
														<div class="act"><p><?php echo admin_anchor('fields/delete/'.$field->id, 		'<em class="picto remove"></em> Delete', 	'class="act delete"'); ?></p></div>
													</li>
													
												<?php endforeach; ?>
											<?php endif; ?>
									</ul>
								</div>
								<!-- /.all-fields -->
							</div>
							<!-- /.group -->
						<?php endforeach; ?>
					
					</li>
				<?php endforeach; ?>
			</ul>
			
			<script>
			$(function() {
				$(".panel-div").hide();
				
				var current_channel_id 		= -1;
				var channel_action_add 		= "";
				var channel_action_edit 	= "";
				var channel_action_delete 	= "";
				
					// Channels
					$(".panel-div").hide();
					if (hash) {
						$(".panel-div-"+hash).show();
						$(".panel-tabs li a[rel="+hash+"]").parent("li").addClass("on");
					} else {
						$(".panel-div:eq(0)").show();
						$(".panel-tabs li:eq(0)").addClass("on");
					} //end if
					
					// Sidebar panels
					$(".panel-tabs li a").click(function() {
						var $el = $(this);
						var gid = $el.attr("rel");
						var old_gid = $(".panel-tabs li.on a").attr("rel");
						
						// Set active state
						$(".panel-tabs li").removeClass("on");
						$el.parent("li").addClass("on");
						
						// Set URL hash
						document.location.hash = "#"+gid;
						update_channel_actions(gid);
						
						// Show group
						if (gid != old_gid) {
							$(".panel-div").hide();
							$(".panel-div-"+gid).fadeIn(400);
						}
						
						return false;
					});
					
					// Get action URL's
					channel_action_add 	= $(".widget.panel-actions a.add").attr("href");
					channel_action_edit 	= $(".widget.panel-actions a.edit").attr("href");
					channel_action_delete = $(".widget.panel-actions a.delete").attr("href");
					
					// Update actions
					update_channel_actions(hash);
					
				
				
				function update_channel_actions(channel_id) {
					/*current_channel_id = channel_id;
					$(".widget.panel-actions a.add").attr("href", channel_action_add);
					$(".widget.panel-actions a.edit").attr("href", channel_action_edit+"/"+channel_id);
					$(".widget.panel-actions a.delete").attr("href", channel_action_delete+"/"+channel_id);*/
					
					
				} //end update_channel_actions()

				
				/*$("table.sortable tbody").sortable({
					 helper: 	function(e, ui) { ui.children().each(function() { $(this).width($(this).width()); }); return ui; }
					,start: 	function(e, ui) { $(ui.item).addClass("sorting"); }
					,stop: 		function(e, ui) { $(ui.item).removeClass("sorting"); }
					,handle: 	'a.handle'
					,update: 	function(e, ui) {
						// Get table and all rows
						var $table 	= $(ui.item).parent("tbody").parent("table");
						var $rows 	= $(ui.item).parent("tbody").children("tr:not(.default)");
						
						// Get panel and group id
						var channel_id = current_channel_id;
						var group_id = $table.attr("rel");
						
						// Array for all field ID's
						var ids = new Array();
						
						// Zebra stripes
						$("tbody tr:even").removeClass("odd");
						$("tbody tr:odd").addClass("odd");
						
						// Add id's to array
						$rows.each(function(i, val) {
							$(this).children(".order-key").text(i+1);
							ids.push($(this).children("td.entry-id").text());
						});
						
						$.ajax({
							 type: 		"POST"
							,url: 		"<?php echo admin_url('fields/update_field_sort'); ?>/"+channel_id+"/"+group_id
							,data: 		{
								ci_csrf_token: 	$.cookie("csrf_cookie_name"),
								ids:            ids
							}
							,dataType: 	"html"
							,success: 	function(data) { }
							,error: 	function(data) { alert("Error!"); }
						});
						
					}
				}).disableSelection();
				
				$(".group a.group-toggle").click(function() {
					$(this).parent().parent().children(".all-fields").slideToggle("fast");
					return false;
				});*/
			});
			
			// Return a helper with preserved width of cells
			/*var fixHelper = function(e, ui) {
			    ui.children().each(function() {
			        $(this).width($(this).width());
			    });
			    return ui;
			};*/
			//]]>
			</script>
			
		<?php endif; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->