<?php 

function getCURLOutput($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36');
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

function getDOMXPath($page){
	$dom = new DOMDocument;
	$page = mb_convert_encoding($page, 'HTML-ENTITIES', "UTF-8");
	@$dom->loadHTML($page);
	$xpath = new DOMXPath($dom);
	return $xpath;
}

function striposa($haystack, $needles=array()) {
	$chr = array();
	foreach($needles as $needle) {
		$res = stripos($haystack, $needle);
		if ($res !== false) $chr[$needle] = $res;
	}
	if(empty($chr)) return false;
	return min($chr);
}

?>