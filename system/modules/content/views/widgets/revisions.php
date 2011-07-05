<?php if (is_superadmin()) : ?>

<div class="widget revisions">
	<h2>Entry Revisions</h2>
	
	<!-- <p><a href="#" onclick="CKEDITOR.instances['body'].insertHtml('<h1>Test 123</h1>'); return false;">Add test</a></p> -->
	
	
	<?php if (isset($revisions) and $revisions) : ?>
		<select name="revision_id">
			<optgroup label="Current">
				<option value="">[<?php echo date('Y/m/d H:i', $entry->updated_at); ?>] by <?php echo $current_revision_user->display_name; ?></option>
			</optgroup>
			<optgroup label="Past">
				<?php foreach ($revisions as $revision) : ?>
					<option value="<?php echo $revision->id; ?>">[<?php echo date('Y/m/d H:i', $revision->created_at); ?>] by <?php echo $revision->display_name; ?></option>
				<?php endforeach; ?>
			</optgroup>
		</select>
		<div class="btns">
			<a href="#" class="btn preview"><em class="picto ">s</em> Preview</a>
			<a href="#" class="btn revert"><em class="picto ">1</em> Revert</a>
		</div>
		
	<?php else : ?>
		<p>No revisions present.</p>
		
	<?php endif; ?>
</div>
<!-- /.revisions -->

<?php endif; ?>