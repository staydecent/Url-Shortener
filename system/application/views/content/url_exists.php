<?php $this->load->view('inc/header'); ?>
	<div class="body">
		<div class="container">
			<div class="notice">
				<h4>This URL has already been shortened!</h4>
				<p><a href="/p/url/edit/?url=<?php echo $url; ?>" title="Edit a Short URL">Would you still like to create your own?</a></p>
			</div>
			<input type="text" id="url" class="input-big" value="<?php echo $short; ?>" readonly="readonly" />
			<h2><?php echo $title; ?></h2>
			<span class="old-url">(<?php echo $url; ?>)</span>
		</div>
	</div>
<?php $this->load->view('inc/footer'); ?>