<?php 

function GetPageIntro($area){
	global $pageIntros;
	$areasIntros = $pageIntros[$area];
	return $areasIntros[array_rand($areasIntros)];
}

function GetSameAnimalText($animal){
	global $sameAnimal;
	$text = $sameAnimal[array_rand($sameAnimal)];
	$text = '"' . str_replace("{animal}", "''" . $animal["name"] . "''", $text) . '", says Robert.';
	return $text;
}

function GetAnimalText($animal){
	global $animalIntros, $sectionTransitions, $verbTalking;
	$intro = $animalIntros[array_rand($animalIntros)];
	$text = '"' . str_replace("{animal}", "''" . $animal["name"] . "''", $intro) . '", says Robert.';
	if(strlen($animal["intro"]) > 0)
		$text .= ' "' . GetRandomNumberOfSentences($animal["intro"]) . '"';
	if(strlen($animal["description"]) > 0){
		$transition = '"' . $sectionTransitions[array_rand($sectionTransitions)] . '", he ' . $verbTalking[array_rand($verbTalking)] . ". ";
		$text .= "\n\n" . $transition . '"' . GetRandomNumberOfSentences($animal["description"]) . '"';
	}
	if(strlen($animal["behaviour"]) > 0){
		$transition = '"' . $sectionTransitions[array_rand($sectionTransitions)] . '", ' . $verbTalking[array_rand($verbTalking)] . " Robert. ";
		$text .= "\n\n". $transition . '"' . GetRandomNumberOfSentences($animal["behaviour"]) . '"';
	}
	if(strlen($animal["habitat"]) > 0){
		$transition = '"' . $sectionTransitions[array_rand($sectionTransitions)] . '", he ' . $verbTalking[array_rand($verbTalking)] . ". ";
		$text .= "\n\n". $transition . '"' . GetRandomNumberOfSentences($animal["habitat"]) . '"';
	}
	return $text;
}

function GetNextPageText($area){
	global $pageNext;
	$areasNext = $pageNext[$area];
	return $areasNext[array_rand($areasNext)];
}

function GetRandomNumberOfSentences($text){
	$text = preg_replace('/\n/', " ", $text);
	$sentences = explode(". ", $text);
	$sentences = array_slice($sentences, 0, mt_rand(1, 2));
	return implode(". ", $sentences) . ".";
}

function escapeHTML($html){
	$html = preg_replace('/</', '&lt;', $html);
	$html = preg_replace('/>/', '&gt;', $html);
	$html = preg_replace('/"/', '&quot;', $html);
	return $html;
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