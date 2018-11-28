<?php

$twineDebug = false;
$publish = true;

include("./graph.php");
$string = file_get_contents("../resources/data.json");
$animals = json_decode($string, true);

$pages = GetPages($animals); // get story graph

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
	if($key == 0){ // intro page
		echo '!Welcome To The Safari Park!';
		echo "\n";
		echo 'After days of exhausing flights, stopovers and car drives, you\'re finally here: the entrance of the national park. You\'ve made it. But this is only the beginning, your real safari journey will start soon. An endless horizon of \'\'savannah\'\' surrounded by \'\'mountains\'\' and \'\'tropical rainforests\'\' awaits behind this small checkpoint. And who knows how many amazing animals are lying back there?';
		echo "\n\n";
		echo escapeHTML('<span id="intro_1"><<click "[...]">><<replace "#intro_1">>');
		$replacers++;
		echo '"Thousands of different species", Robert tells you. "\'\'Mammals\'\', \'\'birds\'\' and \'\'reptiles\'\'. This park includes the best of east African safaris. It\'s also the biggest in the region." Your guide looks at you as excited as a child despite his mid-forties. "I\'ve done it a thousand times, but even 20 years later I still feel like the first time."';
		echo "\n";
		echo '"Also, feel free to ask me anything", he says, "although I\'ll probably tell you about it first because I talk a lot." It\'s true that he talks a lot, and sometimes in a very peculiar way. He definitely has encyclopedic knowledge about the animals but he\'s also very precise when he tells you about them.';
		echo "\n\n";
		echo escapeHTML('<span id="intro_2"><<click "[...]">><<replace "#intro_2">>');
		$replacers++;
		echo '"If we\'re lucky we\'ll see some big game today! \'\'Lions\'\', \'\'elephants\'\', maybe even \'\'leopards\'\'! But you know, when you go on a safari, you drive a lot through open spaces looking for the big game so fortunately to entertain yourself on the way you have the birds. I\'m a birder myself and I\'m sure we\'ll see many fascinating ones today!" He just finished his sentence when the ranger came back to the car to give him the signed permit to enter.';
		echo "\n";
		echo 'You drive through the portal into this wild area. But where should you go first?';
		echo "\n\n";
		echo escapeHTML('<<initInv>>');
	}else{
		echo GetPageIntro($page->area);
		echo "\n\n";
	}
	if($animal != null){
		echo escapeHTML('<span id="animalIntro"><<click "[...]">><<replace "#animalIntro">>');
		$replacers++;
		echo escapeHTML('<<if $inventory.indexOf("'. $animal["name"] .'") != -1>>');
		echo GetSameAnimalText($animal);
		echo escapeHTML('<<endif>>');
		echo escapeHTML('<<if $inventory.indexOf("'. $animal["name"] .'") == -1>>');
		echo GetAnimalText($animal);
		echo escapeHTML("<<addToInv \"". $animal["name"] ."\">>");
		echo escapeHTML('<<endif>>');
		echo "\n\n";
		foreach ($animal["images"] as $imgNb => $img) {
			echo escapeHTML('<img src="' . $img .'" alt="' . $animal["name"] .' - Source: Wikipedia" class="image'. $imgNb .'">');
		}
	}
	if($page->nextPages != null){
		echo escapeHTML('<span id="nextPages"><<click "[...]">><<replace "#nextPages">>');
		$replacers++;
		foreach ($page->nextPages as $next) {
			echo "* " . GetNextPageText($next->area) . " [[ ▶▶ |" . $next->name . "]] \n";
		}
	}else if($key != 0){ // ending
		echo escapeHTML('<span id="ending_1"><<click "[...]">><<replace "#ending_1">>');
		$replacers++;
		echo '"I\'m really happy to have done this safari with you" says Robert. "I hope you\'re happy too! Look, I took some notes, today we\'ve seen: ';
		echo escapeHTML("''the <<inv>>''.\"");
		echo "\n\n";
		echo "But every journey, even the most ''beautiful'' one, has to come to an end. Your car passes through the checkpoint, behind you the red sun is almost gone. You're leaving the park with unforgettable memories in your mind and one hope: to ''come back'' as soon as possible.";
		echo "\n\n";
		echo "''And the animals?'' Well, they won't be waiting for you. But, as long as their ''environment'' is protected, they will be here and you can always come by and say hi.";
		echo "\n";
		echo '!The end';
	}
	for ($i=0; $i < $replacers; $i++) { 
		echo escapeHTML('<</replace>><</click>></span>');
	}
	echo "</tw-passagedata>";
}
echo "</tw-storydata>";
if($publish){
	include_once("./footer.php");
}

?>