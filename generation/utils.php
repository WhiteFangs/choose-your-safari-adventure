<?php 

function GetIntro($area){
	global $intros;
	$areasIntros = $intros[$area];
	return $areasIntros[array_rand($areasIntros)];
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