<?php

$visualDebug = false;
$twinDebug = true;

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
}

if($twinDebug){
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

	<tw-storydata name="Test" startnode="1" creator="Twine" creator-version="2.2.1" ifid="CD3FE479-0DA0-43AD-8F87-75DBDE871DD4" zoom="1" format="SugarCube" format-version="2.21.0" options="" hidden>
	<style role="stylesheet" id="twine-user-stylesheet" type="text/twine-css"></style>
	<script role="script" id="twine-user-script" type="text/twine-javascript"></script>

	<?php
	foreach ($pages as $key => $page) {
		$positions = GetPositions($key);
		?>
		<tw-passagedata pid="<?php echo $key+1; ?>" name="<?php echo $page->name; ?>" tags="" position="<?php echo $positions->x . "," . $positions->y; ?>" size="100,100">
		<?php 
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
		echo "</tw-passagedata>";
	}
	echo "</tw-storydata>";
}

?>