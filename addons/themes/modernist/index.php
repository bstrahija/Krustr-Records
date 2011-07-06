<?php partial('header'); ?>

<article>
	{{#content.welcome}}
		<h2>{{title}}</h2>
		{{{body}}}
	{{/content.welcome}}
	
</article>

<!-- <nav class="entries">
	<a href="#">&laquo; Older articles</a>
	<a href="#">Newer articles &raquo;</a>
</nav> -->

<?php partial('footer'); ?>