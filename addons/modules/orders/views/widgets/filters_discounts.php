<?php if ( ! $this->input->is_ajax_request()) : ?>
	<div class="content-filter"><?php echo form_open(current_url(), array('method'=>'get')); ?>
		<ul>
			<!-- By keywords -->
			<li class="filter_keywords">
				<label>Keywords</label>
				<input type="search" name="filter_keywords" autofocus>
			</li>
			
			<!-- Result number / limit -->
			<li class="filter_num_results">
				<label>Result number</label>
				<select name="filter_limit">
					<option value="20">20</option>
					<option value="50">50</option>
					<option value="100">100</option>
					<option value="500" selected="selected">500</option>
					<option value="1000">1000</option>
					<option value="999999">All</option>
				</select>
			</li>
			
			<!-- By status -->
			<li class="filter_status">
				<label>Status</label>
				<select name="filter_status">
					<option value="all" selected="selected">Any</option>
					<option value="used">Used</option>
					<option value="free">Free</option>
				</select>
			</li>
			
			<!-- Go! -->
			<li class="filter_submit">
				<input type="submit" value="Filter">
			</li>
		</ul>
	<?php echo form_close(); ?></div>
	<!-- /.content-filter -->
<?php endif; ?>