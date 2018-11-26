<?php

$visualDebug = false;
$twineDebug = false;
$publish = true;

include("./graph.php");
$root = $_SERVER['DOCUMENT_ROOT'];
$string = file_get_contents($root . "/divers/cyoa/data.json");
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
	include("./backgrounds.php");
	if($twineDebug){
		header('Content-disposition: attachment; filename=' . $filename . '.html');
		header('Content-type: text/html');
	}else if($publish){
		include_once("./header.php");
	}
	function GetPositions($key){
		$initialX = 1000;
		$initialY = 100;
		$xArray4 = array(-400, -200, 200, 400);
		$xArray8 = array(-800, -600, -400, -200, 200, 400, 600, 800);
		$positionX = 0;
		$positionY = 0;
		switch ($key) {
			case 0:
			$positionX = $initialX;
			$positionY = $initialY;
			break;
			case 1:
			$positionX = $initialX - 200;
			$positionY = $initialY + 200;
			break;
			case 2:
			$positionX = $initialX + 200;
			$positionY = $initialY + 200;
			break;
			default:
			$positionInLevel = ($key - 3) % 12;
			$positionX = $initialX;
			if($positionInLevel < 4){
				$positionX += $xArray4[$positionInLevel];
				$positionY = $initialY + 400 + ((floor(($key - 3) / 12) * 2) *200);
			}else{
				$positionX += $xArray8[$positionInLevel - 4];
				$positionY = $initialY + 400 + (((floor(($key - 3) / 12) * 2) + 1) *200);
			}
			break;
		}
		return (object)array("x" => $positionX, "y" => $positionY);
	}
	?>

	<tw-storydata name="<?php echo $filename; ?>" startnode="1" creator="Twine" creator-version="2.2.1" ifid="CD3FE479-0DA0-43AD-8F87-75DBDE871DD4" zoom="1" format="SugarCube" format-version="2.21.0" options="" hidden>
	<style role="stylesheet" id="twine-user-stylesheet" type="text/twine-css">
	body {
		color: #000;
		background-color: unset;
	}
	#ui-bar{
		display: none;
	}
	#story{
		margin: 2vw;
		margin-right: 50vw;
		background-color: rgba(255,255,255,.8);
		padding: 1em;
		box-shadow: 1px 2px 5px black;
	}
	#passages .passage img{
		position: absolute;
		max-width: 45vw;
		max-height: 40vh;
		right: 2vw;
	}
	#passages .passage img.image0{
		top: 1em;
	}
	#passages .passage img.image1{
		top: 45vh;
	}
	<?php 
	foreach ($backgrounds as $key => $value) {
		foreach ($value as $number => $image) {
			echo 'html[data-tags~="'.$key .'_' . $number .'"] {background-image:url('. $image .');background-size:cover;background-repeat: no-repeat;}';
		}
	}
	?>
	</style>
	<script role="script" id="twine-user-script" type="text/twine-javascript">
	localStorage.clear();
	sessionStorage.clear();
	</script>
	<?php
	foreach ($pages as $key => $page) {
		$positions = GetPositions($key);
		$tag = $page->area . "_" . array_rand($backgrounds[$page->area]);
		echo '<tw-passagedata pid="'. ($key+1) .'" name="'. $page->name .'" tags="'. $tag . '" position="'. $positions->x . ',' . $positions->y. '" size="100,100">';
		echo $page->title;
		echo "\n\n";
		echo $page->area;
		echo "\n\n";
		if($page->nextPages != null){
			foreach ($page->nextPages as $next) {
				echo "Go [[". $next->area ."|" . $next->name . "]]";
				echo "\n\n";
			}
		}
		if($page->animal != null){
			$animal = $page->animal;
			foreach ($animal["images"] as $imgNb => $img) {
				echo '&lt;img src=&quot;' . $img .'&quot; alt=&quot;' . $animal["name"] .' - Source: Wikipedia&quot; class=&quot;image'. $imgNb .'&quot;&gt;';
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