<hgroup class="pg"><div class="w">
	<h1><em class="picto inbox"></em> <?php echo Backend::get_title(); ?></h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('variables/add'); ?>" class="add"><em class="picto plus"></em> Add New Variable</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc mainsingle">
	<div class="block pages">
		
		<?php if ($vars) : ?>
			<table class="grid zebra">
				<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Value</th>
						<th scope="col">Syntax</th>
						<th scope="col" colspan="2" width="1%" class="c"><em class="picto cog"></em></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($vars as $var) : ?>
					<tr>
						<td><?php echo admin_anchor('variables/edit/'.$var->id, $var->title); ?></td>
						<td><?php echo strip_tags($var->value); ?></td>
						<td>{{ variables.<?php echo $var->title; ?> }}</td>
						<td class="actions" width="1%"><a href="<?php echo admin_url('variables/edit/'.$var->id); ?>" class="act edit"><em class="picto pencil"></em> Edit</a></td>
						<td class="actions" width="1%"><a href="<?php echo admin_url('variables/delete/'.$var->id); ?>" class="act delete" data-confirm="Are your sure you want to delete this variable?"><em class="picto remove"></em> Delete</a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		
		<?php else : ?>
			<p><?php echo lang('b.no_entries_found'); ?>.</p>
		
		<?php endif; ?>
	
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->