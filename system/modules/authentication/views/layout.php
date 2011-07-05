<?php $this->load->view('inc/header'); ?>

<?php if (isset($errors) and $errors) echo '<div class="notice error">', $errors, '</div>'; ?>
<?php echo @$yield; ?>

<?php $this->load->view('inc/footer'); ?>