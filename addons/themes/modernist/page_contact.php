<?php get_header(); ?>

<article>
	<h2><?php echo title(); ?></h2>
	<?php echo content(); ?>
	
	<?php echo form_open(current_url()); ?>
		<?php echo form_hidden('action', 'contact'); ?>
		<ol class="form">
			<li>
				<?php echo form_label('Your name <em>*</em>', 'inp-name'); ?>
				<?php echo form_input('inp-name', '', 'id="inp-name"'); ?>
			</li>
			<li>
				<?php echo form_label('Your email <em>*</em>', 'inp-email'); ?>
				<?php echo form_input('inp-email', '', 'id="inp-email"'); ?>
			</li>
			<li>
				<?php echo form_label('Message <em>*</em>', 'inp-message'); ?>
				<?php echo form_textarea('inp-message', '', 'id="inp-message"'); ?>
			</li>
			<li class="btn">
				<?php echo form_submit('submit', 'Send', 'class="btn"'); ?>
			</li>
		</ol>
	<?php echo form_close(); ?>
	
</article>

<?php get_footer(); ?>

<?php get_footer(); ?>