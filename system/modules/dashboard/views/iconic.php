<hgroup class="pg"><div class="w">
	<h1><em class="picto star"></em> Iconic Icons</h1>
	<h2>Iconize it</h2>
</div></hgroup>

<section class="mainc">
	<div class="block no-min start">
		<style>
		.icon-overview { width: 100%; overflow: hidden; color: #333; font-size: 30px; }
		.icon-overview ul { list-style-type: none; }
		.icon-overview li { float: left; margin: 0 5px 5px 0; border: 1px solid #ccc; width: 50px; height: 50px; text-align: center; line-height: 30px; position: relative; }
		.icon-overview span { position: absolute; bottom: 3px; right: 3px; font-size: 10px; color: #777; line-height: 12px; }
		</style>
		
		<div class="icon-overview">
		<ul>
			<?php
				for ($i = 33; $i < 127; $i++) {
					echo '<li><em class="iconic">', chr($i), '</em><span>', chr($i), '</span></li>';
				} // end for
			?>
		</ul>
		</div>
	
	</div>
</section>