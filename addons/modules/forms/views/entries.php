<hgroup class="pg"><div class="w">
	<h1><em class="picto inbox2"></em> Form entries</h1>
	<h2>All the incoming entries</h2>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('forms/add'); ?>" class="add"><em class="picto plus"></em> Add new form</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc">
	<div class="block form-entries">
		<?php if (isset($entries) and $entries) : ?>
			<table class="grid">
				<thead>
					<tr>
						<th scope="col" class="tight">#</th>
						<th scope="col">Name</th>
						<th scope="col">Name sing.</th>
						<th scope="col">Slug</th>
						<th scope="col">Slug sing.</th>
						<th scope="col">When</th>
						<th scope="col" colspan="3" class="c"><em class="picto cog"></em></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($forms as $form) : ?>
						<tr>
							<td><?php echo $form->id; ?></td>
							<td class="title"><a href="<?php echo admin_url('forms/edit/'.$form->id); ?>"><?php echo $form->title; ?></a></td>
							<td><a href="<?php echo admin_url('forms/edit/'.$form->id); ?>"><?php echo $form->title_singular; ?></a></td>
							<td><?php echo $form->slug; ?></td>
							<td><?php echo $form->slug_singular; ?></td>
							<td><?php echo date('Y/m/d H:i', $form->created_at); ?></td>
							
							<td class="actions tight"><a href="<?php echo admin_url('forms/edit/'.$form->id); ?>" class="act edit"><em class="picto pencil"></em> Edit</a></td>
							<td class="actions tight"><a href="<?php echo admin_url('forms/delete/'.$form->id); ?>" class="act delete"><em class="picto remove"></em> Delete</a></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<p class="nothing-found"><em class="picto alert"></em> No entries found.</p>
			
		<?php endif; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->