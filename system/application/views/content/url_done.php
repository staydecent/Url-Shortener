<?php $this->load->view('inc/header'); ?>
	<div class="body">
		<div class="container">
			<input type="text" id="url" class="input-big" value="<?php echo $short; ?>" readonly="readonly" />
			<h2><?php echo $title; ?></h2>
			<span class="old-url">(<?php echo $url; ?>)</span>
		</div>
	</div>
<?php $this->load->view('inc/footer'); ?>