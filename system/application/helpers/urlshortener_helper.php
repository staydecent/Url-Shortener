<?php
/**
 * Checks 'http://' and removes some unwanted chars.
 */
function clean_url($url)
{
	$url = strip_tags($url);
	
	// Append 'http://' if not there

	if(!preg_match('#^[^:]+://#', $url))
	{
		$url = 'http://' . $url;
	}
	
	// Is it well-formed?
	
	if(!preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url))
	{
		show_error('The URL you entered is not valid, please check if you typed it correctly: ' . $url);
	}
	else
	{
		return $url;
	}
}

/**
 * Creates a random string of upper & lower case
 * alpha's and numbers.
 */
function generate_string($length = 5)
{
	$string = "";
	$possible = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	$i = 0;

	while($i < $length)
	{
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		$string .= $char;
		$i++;
	}

	return $string;
}

/**
 * Scrapes the external source code for a title.
 * Tries the meta title tag first, then fallsback on
 * the title tag.
 * @return String of found title, or url
 */
function get_external_title($url)
{
	if($raw = file_get_contents($url))
	{
		$newlines = array("\t","\n","\r","\x20\x20","\0","\x0B");
		$source = str_replace($newlines, "", html_entity_decode($raw));
	
		// Get just the head
	
		$h_start = strpos($source, '<head>');
		$h_end = strpos($source, '</head>', $h_start);
		$head = substr($source, $h_start, $h_end - $h_start);
	
		// Try to find a meta title
	
		if(strlen(stristr($head, '<meta name="title"')) > 1)
		{
			$start = stripos($head, '<meta name="title"');
			$end = strpos($head, '" />', $start);
	
			if($end === false){
				$end = strpos($head, '>', $start);
			}
	
			$snippet = substr($head, $start, $end - $start);
			$title = str_ireplace('<meta name="title"', '', $snippet);
			$title = str_ireplace('content="', '', $title);
			$title = rtrim($title, '"');
		}
		// Nope, just use the title tag
		else
		{
			$start = strpos($head, '<title>');
			$end = strpos($head, '</title>', $start);
			$snippet = substr($head, $start, $end - $start);
			$title = str_replace('<title>', '', $snippet);
		}
	
		$title = strip_tags($title);
	
		if(empty($title))
		{
			show_error('The URL you supplied did not appear to be a valid website, please make sure it is correct: ' . $url);
		}
		else
		{
			return $title;
		}
	}
	else
	{
		return $url;
	}
}

/**
 * Tries to read category and tag rel tags, to associate
 * with the shortened url.
 * @return An array of found tags(or empty)
 * @uses simple_html_dom (loaded as a helper)
 */
function get_external_tags($url, $rel = array('tag', 'category', 'keyword'))
{
	$a = array();
	$html = file_get_html($url);

	foreach($rel as $r)
	{
		$selector = 'a[rel*=' . $r . ']';
		foreach($html->find($selector) as $e)
		{
			$a[] = $e->plaintext;
		}
	}
	
	$out = array_unique($a);
	return $out;
}

/**
 * Get relative date
 * Seriously, that's it.
 */
function plural($num)
{
	if ($num != 1)
		return "s";
}
function getRelativeTime($date)
{
	$diff = time() - strtotime($date);
	if ($diff<60)
		return $diff . " sec" . plural($diff) . " ago";
	$diff = round($diff/60);
	if ($diff<60)
		return $diff . " min" . plural($diff) . " ago";
	$diff = round($diff/60);
	if ($diff<24)
		return $diff . " hr" . plural($diff) . " ago";
	$diff = round($diff/24);
	if ($diff<7)
		return $diff . " day" . plural($diff) . " ago";
	$diff = round($diff/7);
	if ($diff<4)
		return $diff . " week" . plural($diff) . " ago";
	return "on " . date("F j, Y", strtotime($date));
}

/**
 * Yeah...char limits
 */
function excerpt($content, $limit = '70')
{
	$excerpt = substr(strip_tags($content), 0, $limit);
	$excerpt = $excerpt . '&#8230;&nbsp;';
	return $excerpt;
}

/**
 * Comparison udf for sorting by # of comments.
 */
function cmp($a, $b) {
	if ($a['num_comments'] == $b['num_comments']) {
		return 0;
	}
	return ($a['num_comments'] < $b['num_comments']) ? 1 : -1;
}
?>