<?php $this->load->view('inc/header'); ?>
	<div class="body">
		<div class="container">
			<form method="post" action="/p/url/edit/">
				<input type="text" id="url" class="input-big" name="url" value="<?php echo $url; ?>" readonly="readonly" /><br />
				<input type="text" id="short" class="input-big" name="short" value="Enter a custom short-url" /><br />
				<button class="button-shorten">Shorten</button>
			</form>
		</div>
	</div>
<?php $this->load->view('inc/footer'); ?>