<hgroup class="pg"><div class="w">
	<h1><em class="picto star"></em> Add-ons</h1>
	<h2>You can switch these on or off, but they make for a better experience</h2>
</div></hgroup>

<section class="mainc">
	<div class="block no-min modules">
		<div class="cont">
			<table class="grid">
				<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Description</th>
						<th scope="col">Version</th>
						<th scope="col" colspan="2" class="c"><em class="picto cog"></em></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($modules as $module) : ?>
						<?php if($module->is_core) continue; ?>
						<tr>
							<td class="title"><?php echo $module->title; ?></td>
							<td><?php echo $module->description; ?></td>
							<td><?php echo $module->version; ?></td>
							
							<?php if ($module->installed) : ?>
								<?php if ($module->active) : ?>
									<td class="actions tight" width="10" colspan="2"><a href="<?php echo admin_url('modules/deactivate/'.$module->slug); ?>" class="act delete"><em class="picto switch"></em> Deactivate</a></td>
								<?php else : ?>
									<td class="actions tight" width="10"><a href="<?php echo admin_url('modules/activate/'.$module->slug); ?>" class="act edit"><em class="picto switch"></em> Activate</a></td>
									<td class="actions tight" width="10"><a href="<?php echo admin_url('modules/uninstall/'.$module->slug); ?>" class="act delete"><em class="picto remove"></em> Uninstall</a></td>
								<?php endif; ?>
							
							<?php else : ?>
								<td class="actions tight" width="10" colspan="2"><a href="<?php echo admin_url('modules/install/'.$module->slug); ?>" class="act edit"><em class="picto plus"></em> Install</a></td>
							
							<?php endif; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<!-- /.cont -->
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->


<hgroup class="pg"><div class="w">
	<h1><em class="picto star"></em> Core Modules</h1>
	<h2>Small applications that make the system great</h2>
</div></hgroup>
			
<section class="mainc">
	<div class="block modules">
		<div class="cont">		
			<table class="grid">
				<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Description</th>
						<th scope="col">Version</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($modules as $module) : ?>
						<?php if( ! $module->is_core) continue; ?>
						<tr>
							<td class="title"><?php echo $module->title; ?></td>
							<td><?php echo $module->description; ?></td>
							<td><?php echo $module->version; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<!-- /.cont -->
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->


