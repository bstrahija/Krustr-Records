{{> header}}

<h1><?php title(); ?></h1>
<?php content(); ?>

<?php echo form_open(current_url(), null, array('action'=>'login')); ?>
	<?php echo validation_errors(); ?>
	
	<ol>
		<li><?php echo form_label('Email', 'email'); ?>
			<?php echo form_input('email'); ?></li>
		<li><?php echo form_label('Password', 'password'); ?>
			<?php echo form_password('password'); ?></li>
		
		<li class="btns"><?php echo form_submit('login', 'Login'); ?></li>
	</ol>
<?php echo form_close(); ?>

{{> aside}}

{{> footer}}