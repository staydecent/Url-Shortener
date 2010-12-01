<div class="body">
	<div class="container clearFix">
		<div class="col floatl">
			<h3 class="sifr-white">Latest Public Conversations</h3>
			<span class="small sifr-grey">Chym In to real time discussions.</span>
			<ul class="latest-urls">
			<?php if($latest_urls): foreach($latest_urls as $url): ?>
				<li class="item-url clearFix">
					<div class="date floatl">
						<?php echo getRelativeTime($url['date_created']); ?>
					</div>
					<div class="floatl">
						<a href="<?php echo $base . $url['short'] ?>" title="<?php echo $url['title'] ?>">
							<?php echo excerpt($url['title'], '30'); ?>
						</a>
					</div>
				</li>
			<?php endforeach; endif; ?>
			</ul>
			<div class="img-replace view_more">
				<a href="" title="View More" class="block">View More</a>
			</div>
		</div>
		<div class="col floatl">
			<h3 class="sifr-white">Most Popular Conversations</h3>
			<span class="small sifr-grey">Chym In to current hot topics.</span>
			<ul class="latest-urls">
			<?php if($popular_urls): foreach($popular_urls as $url): ?>
				<li class="item-url clearFix">
					<div class="date floatl">
						<?php echo $url['num_comments']; ?> Chyms
					</div>
					<div class="floatl">
						<a href="<?php echo $base . $url['short'] ?>" title="<?php echo $url['title'] ?>">
							<?php echo excerpt($url['title'], '30'); ?>
						</a>
					</div>
				</li>
			<?php endforeach; endif; ?>
			</ul>
			<div class="img-replace view_more">
				<a href="" title="View More" class="block">View More</a>
			</div>
		</div>
		<div id="about-col" class="last col floatl">
			<div id="about-menu">
				<img src="<?php echo $base; ?>assets/img/wwh.gif" width="133" height="74" alt="wwh" />
			</div>
			<div class="subscribe clearFix">
				<h3 class="sifr-white">Get insider info on new features</h3>
				<span class="small sifr-grey">Opt into our subscriber list here.</span>
				<form method="post" action="testing.php" id="test" name="test">
					<div class="floatl">
						<input type="text" name="subscribe-email" value="email"	class="subscribe" />
					</div>
					<div id="button-subscribe" class="floatl">
						<button class="subscribe"></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>