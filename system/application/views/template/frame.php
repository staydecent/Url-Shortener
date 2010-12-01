<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="<?php echo $keywords; ?>" />
	<meta name="title" content="<?php echo $title; ?>">

	<title><?php echo $title; ?> - Chym.In</title>
	<link href="http://google.com/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<link href="<?php echo $base; ?>assets/css/frame.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>	
</head>
<body>
	<div id="container">
		<div id="panel">
			<div class="inner">
				<div class="header seperator clearFix">
					<div class="floatl">
						<h1 class="frame-title img-replace">Chym.In</h1>
					</div>
				</div>
				<div class="clearFix">
					<div class="col65 floatl">
						<h2>
							<?php echo $title; ?>
						</h2>
						Posted <?php echo date('d/m/y', $date_created); ?> - 
						<?php echo count($comments); ?> Comment<?php echo plural(count($comments)); ?>
					</div>
					<div class="floatr">
						<a href="#add-comment" title="Add a comment" class="add-comment">Add Comment</a>
						<a href="#display-comments" title="Display comments" class="display-comments">&larr; Back</a>
					</div>
				</div>
				<div id="scroll">
					<?php if(is_array($comments)): ?>
					<div id="display-comments">
						<?php foreach($comments as $c): ?>
						<div id="comment-<?php echo $c['id']; ?>" class="comment clearFix">
							<div class="meta floatl">
								<div class="author"><span class="name"><?php echo $c['username']; ?></span></div>
								<div class="date">
									<?php 
										$comm_date = strtotime($c['date']);
										echo getRelativeTime($c['date']); 
									?>
								</div>
							</div>
							<div class="body floatr">
								<p><?php echo $c['body']; ?></p>
							</div>
						</div>
						<?php break; endforeach; ?>
						
						<?php
						// Reversed to show newest to oldest
						$stnemmoc = array_reverse($comments);
						?>
						<?php foreach($stnemmoc as $c): ?>
						<?php if($c['id'] <= 0) { continue; } ?>
						<div id="comment-<?php echo $c['id']; ?>" class="comment clearFix">
							<div class="meta floatl">
								<div class="author"><span class="name"><?php echo $c['username']; ?></span></div>
								<div class="date">
									<?php 
										$comm_date = strtotime($c['date']);
										echo getRelativeTime($c['date']); 
									?>
								</div>
							</div>
							<div class="body floatr">
								<p><?php echo $c['body']; ?></p>
							</div>
						</div>
						<?php endforeach; ?>
					<?php else: ?>
						<div class="notice">
							No one has Chymed In, be the first to start the discussion!
						</div>
					<?php endif; ?>
					</div>
					<div id="post-comment">
						<h2>Chym In! Post your comment below</h2>
						<form method="post" action="<?php //echo $short; ?>">
							<textarea name="body" rows="10" cols="30"></textarea>
							<input type="text" name="username" value="name" /><br />
							<input type="text" name="email" value="email" /><br />
							<button>Leave Comment</button>
						</form>
					</div>
					<div id="footer">
						&copy; 2009 Chym.In
					</div>
				</div> <!-- END SCROLL AREA -->
			</div>
		</div>
		<div id="frame">
			<iframe frameborder="0" src="<?php echo $url; ?>" noresize="noresize" width="100%" height="100%" name="ext-site" id="ext-site"></iframe>
		</div>
	</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$("#post-comment").hide();
	$(".display-comments").hide();
	$(".add-comment").click(function(){
		$("#display-comments").hide();
		$(".add-comment").hide();
		$(".display-comments").show();
		$("#post-comment").show();
	});
	$(".display-comments").click(function(){
		$("#display-comments").show();
		$(".add-comment").show();
		$(".display-comments").hide();
		$("#post-comment").hide();
	});
});
//]]>
</script>
</body>
</html>