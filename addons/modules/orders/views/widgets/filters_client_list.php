<?php if ( ! $this->input->is_ajax_request()) : ?>
	<div class="content-filter"><?php echo form_open(current_url(), array('method'=>'get')); ?>
		<ul>
			<!-- By entry -->
			<li class="filter_entry filter_400">
				<label>Deal</label>
				<select name="filter_entry">
					<option value="">Pick one</option>
					<?php foreach ($deals as $deal) : ?>
						<option value="<?php echo $deal->id; ?>">[<?php echo $deal->id; ?>] <?php echo $deal->title; ?></option>
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