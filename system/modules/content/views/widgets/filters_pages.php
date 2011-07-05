<?php if ( ! $this->input->is_ajax_request()) : ?>
	<div class="content-filter"><?php echo form_open(current_url(), array('method'=>'get')); ?>
		<ul>
			<!-- By keywords -->
			<li class="filter_keywords">
				<label>Keywords</label>
				<input type="search" name="filter_keywords" autofocus>
			</li>
			
			<!-- By category -->
			<?php if (isset($category_tree) and $category_tree) : ?>
			<li class="filter_category">
				<label>Category</label>
				<select name="filter_category">
					<option value="" selected="selected">All</option>
					<?php foreach ($category_tree as $category) : ?>
						<option value="<?php echo $category->id; ?>"><?php echo repeater('&mdash;', (int) $category->offset); ?> <?php echo $category->title; ?></option>
					<?php endforeach; ?>
				</select>
			</li>
			<?php endif; ?>
			
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
			
			<!-- By status -->
			<li class="filter_status">
				<label>Status</label>
				<select name="filter_status">
					<option value="all">Any</option>
					<option value="published-draft" selected="selected">Published and Draft</option>
					<option value="published">Published</option>
					<option value="draft">Draft</option>
					<option value="trashed">Trash</option>
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