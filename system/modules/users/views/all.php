<hgroup class="pg"><div class="w">
	<h1><em class="picto user"></em> <?php echo Backend::get_title(); ?></h1>
	
	<div class="btns actions"><ul>
		<li><a href="<?php echo admin_url('users/add'); ?>" class="add"><em class="picto plus"></em> Add New User</a></li>
	</ul></div>
</div></hgroup>

<section class="mainc">
	<?php $this->load->view('widgets/filters'); ?>
	
	<div class="block users">
		<?php if (isset($users) and $users) : ?>
			<table class="grid">
				<thead>
					<tr>
						<th scope="col" class="tight"><em class="picto lock"></em></th>
						<th scope="col" class="tight">#</th>
						<th scope="col">Title</th>
						<th scope="col" width="20%">Type</th>
						<th scope="col" width="20%">When</th>
						<th scope="col" colspan="3" class="c"><em class="picto cog"></em></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($users as $user) : ?>
						<tr>
							<td class="user-status"><em class="picto <?php echo $user->status; ?>"></em></td>
							<td><?php echo $user->id; ?></td>
							<td class="title"><a href="<?php echo admin_url('users/edit/'.$user->id); ?>"><?php echo $user->first_name, ' ', $user->last_name; ?></a></td>
							<td><?php echo $user_roles[$user->level]; ?></td>
							<td><?php echo date('Y/m/d H:i', $user->created_at); ?></td>
							
							<td class="actions tight"><a href="<?php echo admin_url('users/login_as/'.$user->id); ?>" class="act edit"><em class="picto lock"></em> Login</a></td>
							<td class="actions tight"><a href="<?php echo admin_url('users/edit/'.$user->id);     ?>" class="act edit"><em class="picto pencil"></em> Edit</a></td>
							<td class="actions tight"><a href="<?php echo admin_url('users/delete/'.$user->id);   ?>" class="act delete"><em class="picto remove"></em> Delete</a></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<p class="nothing-found"><em class="picto alert"></em> No users found.</p>
			
		<?php endif; ?>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->
