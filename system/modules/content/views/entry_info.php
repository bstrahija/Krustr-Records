<?php if (isset($entry) and $entry) : ?>
	<h3><?php echo $entry->title; ?></h3>
	
	<div class="col">
		<h4>Entry details</h4>
		<dl>
			<dt>ID:</dt>
				<dd><?php echo $entry->id; ?></dd>
			<dt>Status:</dt>
				<dd><?php echo $entry->status; ?></dd>
			<dt>Order key:</dt>
				<dd><?php echo $entry->order_key; ?></dd>
			<dt>URL:</dt>
				<dd><a href="<?php echo site_url($entry->slug); ?>" target="_blank"><?php echo site_url($entry->slug); ?></a></dd>
		</dl>
		
		<h4>Publish details</h4>
		<dl>
			<dt>Published at:</dt>
				<dd><?php echo date('Y/m/d H:i', $entry->published_at); ?></dd>
			<dt>Created by:</dt>
				<dd><?php echo $entry->user_first_name, ' ', $entry->user_last_name; ?></dd>
			<dt>Last change:</dt>
				<dd><?php echo date('Y/m/d H:i', $entry->updated_at), ' by ', $user_change->display_name; ?></dd>
		</dl>
		
		<h4>Meta data</h4>
		<dl>
			<dt>Meta title:</dt>
				<dd><?php echo ($entry->meta_title) ? $entry->meta_title : '-'; ?></dd>
			<dt>Meta keyword:</dt>
				<dd><?php echo ($entry->meta_keywords) ? $entry->meta_keywords : '-'; ?></dd>
			<dt>Meta description:</dt>
				<dd><?php echo ($entry->meta_description) ? $entry->meta_description : '-'; ?></dd>
		</dl>
		
		<div class="btns">
			<a href="<?php echo admin_url('content/edit/'.$entry->channel.'/'.$entry->id);      ?>" class="btn edit">Edit</a>
			<a href="<?php echo admin_url('content/add/'.$entry->channel.'/child/'.$entry->id); ?>" class="btn child">Add child</a>
			<a href="<?php echo admin_url('content/delete/'.$entry->channel.'/'.$entry->id);    ?>" class="btn deleta" data-confirm="Are you sure you want to delete the entry <b>[<?php echo $entry->id; ?>]</b>?">Delete</a>
		</div>
	</div>
	<!-- /.col -->
<?php else : ?>
	<p>No entry selected.</p>
	
<?php endif; ?>