<?php partial('header'); ?>

<article>
	{{#page}}
		<h2>{{title}}</h2>
		{{{body}}}
	{{/page}}
</article>

<?php partial('footer'); ?>