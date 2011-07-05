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
					<option value="20" selected="selected">20</option>
					<option value="50">50</option>
					<option value="100">100</option>
					<option value="500">500</option>
					<option value="999999">All</option>
				</select>
			</li>
			
			<!-- By date -->
			<li class="filter_after">
				<label>Date Period</label>
				<select name="filter_after">
					<option value="">All</option>
					<option value="<?php echo mktime(0,0,0,date("m"),date("d"),date("Y"));     ?>">Today</option>
					<option value="<?php echo mktime(0,0,0,date("m"),date("d")-1,date("Y"));   ?>">Yesterday</option>
					<option value="<?php echo mktime(0,0,0,date("m"),date("d")-7,date("Y"));   ?>">1 Week</option>
					<option value="<?php echo mktime(0,0,0,date("m"),date("d")-30,date("Y"));  ?>">1 Month</option>
					<option value="<?php echo mktime(0,0,0,date("m"),date("d")-180,date("Y")); ?>">6 Months</option>
					<option value="<?php echo mktime(0,0,0,date("m"),date("d")-365,date("Y")); ?>">1 Year</option>
				</select>
			</li>
			
			<!-- By role -->
			<li class="filter_level">
				<label>Role</label>
				<select name="filter_level">
					<option value="">All</option>
					<?php foreach ($this->config->item('user_groups', 'auth') as $role=>$level) : ?>
					<option value="<?php echo $level; ?>"><?php echo ucfirst($role); ?></option>
					<?php endforeach; ?>
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