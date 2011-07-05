<?php
	$views = $analytic_views;
	$visits = $analytic_visits;
?>

<table class="stats" rel="line" cellpadding="0" cellspacing="0" width="100%">
	<thead>
		<tr>
			<td>&nbsp;</td>
			<?php foreach ($views as $view) : if ($view[1]) : ?>
				<th scope="col"><?php echo $view[0]; ?></th>
			<?php endif; endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th scope="row">Views</th>
			<?php foreach ($views as $view) : if ($view[1]) : ?>
				<td><?php echo $view[1]; ?></td>
			<?php endif; endforeach; ?>
		</tr>
		<tr>
			<th scope="row">Visitors</th>
			<?php foreach ($visits as $visit) : if ($visit[1]) : ?>
				<td><?php echo $visit[1]; ?></td>
			<?php endif; endforeach; ?>
		</tr>
	</tbody>
</table>