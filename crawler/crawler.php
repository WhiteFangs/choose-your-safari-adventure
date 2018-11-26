<?php

ini_set('max_execution_time', 10000);
header('Content-Type: application/json; charset=utf-8');

include("./links.php"); // $birds, $mammals and $reptiles links arrays
include("./utils.php");

function getAreas($xpath){
	$text = $xpath->query('//div[@id="bodyContent"]')->item(0)->nodeValue;
	$areas = array();
	$forest = array("forest", "woodland");
	$savannah = array("savannah", "savanna", "grassland", "open woodland", "open grass", "plains", "shrubland", "scrubland", "scrub", "shrub", "brush", "bush");
	$water = array("river", "water", "estuar", "mangrove", "wetland", "lake", "swamp", "marsh", "bog", "fen", "pond");
	if(striposa($text, $forest) !== false)
		$areas[] = "forest";
	if(striposa($text, $savannah) !== false)
		$areas[] = "savannah";
	if(striposa($text, $water) !== false)
		$areas[] = "water";
	return $areas;
}

function getImageLinks($xpath){
	$imageLinks = array();
	$imageNodes = $xpath->query('//div[@id="bodyContent"]//div[@class="mw-parser-output"]/table[@class="infobox biota"]/tbody/tr/td/a[@class="image"]/img');
	foreach ($imageNodes as $key => $node) {
		$url = str_replace("/220px-", "/640px-", $node->getAttribute("src"));
		$imageLinks[] = $url;
	}
	return $imageLinks;
}

function getIntro($bodyNodes){
	$intro = "";
	foreach ($bodyNodes as $key => $node) {
		if($node->tagName == "p" && strlen(trim($node->nodeValue)) > 0){
			$intro .= $node->nodeValue;
			$intro .= "<br>";
		}else if($node->tagName == "div"){
			break;
		}
	}
	$intro = preg_replace('/\[.*?\]/', '', $intro);
	$intro = preg_replace('/\(.*?\)/', '', $intro);
	$intro = preg_replace('/\s,/', ',', $intro);
	return $intro;
}

function getSection($bodyNodes, $needles){
	$section = "";
	$entered = false;
	foreach ($bodyNodes as $key => $node) {
		if(!$entered && $node->tagName == "h2" && striposa($node->nodeValue, $needles) !== false){ // enter the section
			$entered = true;
		}else if($entered){
			if($node->tagName == "p" && strlen(trim($node->nodeValue)) > 0){ // paragraph in the section
				$section .= $node->nodeValue;
				$section .= "<br>";
			}else if($node->tagName == "h2"){ // out
				break;
			}
		}
	}
	$section = preg_replace('/\[.*?\]/', '', $section);
	$section = preg_replace('/\(.*?\)/', '', $section);
	$section = preg_replace('/\s,/', ',', $section);
	return $section;
}

function getAnimalData($type, $url){
	$rarity = $type != "mammal" ? 1 : 0;
	$html = getCurlOutput($url);
	$xpath = getDOMXPath($html);

	$name = $xpath->query('//h1[@id="firstHeading"]')->item(0)->nodeValue;
	$areas = getAreas($xpath);
	$images = getImageLinks($xpath);

	$bodyNodes = $xpath->query('//div[@id="bodyContent"]//div[@class="mw-parser-output"]/*');
	$intro = getIntro($bodyNodes);
	$description = getSection($bodyNodes, array("description", "characteristics"));
	$habitat = getSection($bodyNodes, array("habitat", "distribution"));
	$behaviour = getSection($bodyNodes, array("behaviour", "habits", "ecology"));

	$animal = (object) array(
		"name" => $name,
		"url" => $url,
		"images" => $images,
		"type" => $type,
		"rarity" => $rarity,
		"areas" => $areas,
		"intro" => $intro,
		"description" => $description,
		"habitat" => $habitat,
		"behaviour" => $behaviour
		);
	return $animal;
}

$animals = array();
foreach ($birds as $key => $url) {
	$animals[] = getAnimalData("bird", $url);
	sleep(1);
}
foreach ($reptiles as $key => $url) {
	$animals[] = getAnimalData("reptile", $url);
	sleep(1);
}
foreach ($mammals as $key => $url) {
	$animals[] = getAnimalData("mammal", $url);
	sleep(1);
}

echo json_encode($animals, JSON_PRETTY_PRINT);

?>