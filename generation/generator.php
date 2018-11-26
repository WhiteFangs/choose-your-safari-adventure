<?php

$visualDebug = false;
$twineDebug = false;
$publish = true;

include("./graph.php");
$root = $_SERVER['DOCUMENT_ROOT'];
$string = file_get_contents($root . "/divers/cyoa/resources/data.json");
$animals = json_decode($string, true);

$pages = GetPages($animals);

if($visualDebug){
	foreach ($pages as $page) {
		echo $page->name . " (" . $page->area . ")";
		if($page->nextPages != null){
			foreach ($page->nextPages as $next) {
				echo "->" . $next->name . " (" . $next->area . ")";
				echo "<br>";
			}
		}
	}
}else if($twineDebug || $publish){
	$filename = 'Choose-Your-Safari-Adventure_' . uniqid();
	include("../resources/backgrounds.php");
	include("../resources/intros.php");
	include("./utils.php");
	if($twineDebug){
		header('Content-disposition: attachment; filename=' . $filename . '.html');
		header('Content-type: text/html');
	}else if($publish){
		include_once("./header.php");
	}
	?>
	<tw-storydata name="<?php echo $filename; ?>" startnode="1" creator="Twine" creator-version="2.2.1" ifid="CD3FE479-0DA0-43AD-8F87-75DBDE871DD4" zoom="1" format="SugarCube" format-version="2.21.0" options="" hidden>
	<?php
	include_once("./style.php");
	include_once("./script.php");
	foreach ($pages as $key => $page) {
		$replacers = 0;
		$positions = GetPositions($key);
		$tag = $page->area . "_" . array_rand($backgrounds[$page->area]);
		$animal = $page->animal;
		echo '<tw-passagedata pid="'. ($key+1) .'" name="'. $page->name .'" tags="'. $tag . '" position="'. $positions->x . ',' . $positions->y. '" size="100,100">';
		if($key != 0){
			echo GetPageIntro($page->area);
			echo "\n\n";
		}else{
			echo "Welcome to the safari!"; // write intro
			echo "\n\n";
		}
		if($animal != null){
			echo escapeHTML('<span id="animalIntro"><<click "...">><<replace "#animalIntro">>');
			$replacers++;
			echo GetAnimalIntro($animal);
			echo "\n\n";
		}
		if($page->nextPages != null){
			echo escapeHTML('<span id="nextPages"><<click "...">><<replace "#nextPages">>');
			$replacers++;
			foreach ($page->nextPages as $next) {
				echo "Go [[". $next->area ."|" . $next->name . "]]";
				echo "\n\n";
			}
		}
		for ($i=0; $i < $replacers; $i++) { 
			echo escapeHTML('<</replace>><</click>></span>');
		}
		if($animal != null){
			foreach ($animal["images"] as $imgNb => $img) {
				echo escapeHTML('<img src="' . $img .'" alt="' . $animal["name"] .' - Source: Wikipedia" class="image'. $imgNb .'">');
			}
		}
		echo "</tw-passagedata>";
	}
	echo "</tw-storydata>";
	if($publish){
		include_once("./footer.php");
	}
}

?>