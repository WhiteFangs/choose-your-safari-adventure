<?php

function GetAnimalPage($birds, $reptiles, $mammals, $previousPages){
	$random = mt_rand() / mt_getrandmax();
	$rarity = 1;
	if($random < 0.45){
		$animals = $birds;
	}else if($random < 0.9){
		$random = mt_rand() / mt_getrandmax();
		$rarity = $random < 0.6 ? 1 : $random < 0.85 ? 2 : 3;
		$animals = array_filter($mammals, function($a) use ($rarity) {return $a["rarity"] == $rarity;});
	}else{
		$animals = $reptiles;
	}
	$animal = $animals[array_rand($animals)];
	$areas = $animal["areas"];
	return (object) array(
		"name" => $animal["name"] . uniqid(),
		"title" => $animal["name"],
		"animal" => $animal,
		"nextPages" => $previousPages,
		"area" => $areas[array_rand($areas)] // if rarity == 3, 80% chance ranger called
		);
}

function MapPagesToNextPages($page){
	return (object)array("name" => $page->name, "area" => $page->area);
}

function GetPages($animals){
	$birds = array_filter($animals, function($a) {return $a["type"] == 'bird';});
	$reptiles = array_filter($animals, function($a) {return $a["type"] == 'reptile';});
	$mammals = array_filter($animals, function($a) {return $a["type"] == 'mammal';});
	$pages = array();
	$endPage = (object) array(
		"name" => "EndPage",
		"title" => "THE END",
		"animal" => null,
		"nextPages" => null,
		"area" => "building"
		);
	$pages[] = $endPage;
	$previousPages = array_map("MapPagesToNextPages", $pages);

	$graphLevelCounter = 0; // 0 is 2 options, 1 is 4, 2 is 8, 3 is 4. So odd $graphLevelCounter is 4 options and even is 8.
	$totalGraphLevels = 10; // we want to finish with an odd $graphLevelCounter so we choose an even $totalGraphLevels
	for ($graphLevelCounter; $graphLevelCounter < $totalGraphLevels; $graphLevelCounter++) { 
		$previousPagesNb = count($previousPages);
		shuffle($previousPages);
		$newPages = array();
		if($previousPagesNb < 8){
			$newPagesNb = $previousPagesNb * 2;
			for($i = 0; $i < $newPagesNb; $i++){
				// usual case $previousPagesNb == 4, so $newPagesNb == 8, we want to have all possible combinations of $previousPages(A,B,C,D) as 2 $nextPages
				// so 1=>AB, 2=>BC, 3=>CD, 4=>DA, then after 4, 5=>AC, 6=>BD, then fill the rest 7=>CA, 8=>DB
				// special cases are $previousPagesNb == 2 or 1, if 2 we take the 2 available everytime, if 1 just give the only possibility
				$secondPageToChoose = $newPagesNb == 8 ? ($i + ($i < 4 ? 1 : 2)) % $previousPagesNb : ($i + 1) % $previousPagesNb;
				$nextPages = $previousPagesNb > 1 ? array($previousPages[$i % $previousPagesNb], $previousPages[$secondPageToChoose]) : $previousPages;
				$newPages[] = GetAnimalPage($birds, $reptiles, $mammals, $nextPages);
			}
		}else {
			for($i = 0; $i < 4; $i++)
				// we get A=>(0,1), B=>(2,3), C=>(4,5) and D=>(6,7)
				$newPages[] = GetAnimalPage($birds, $reptiles, $mammals, array($previousPages[$i * 2], $previousPages[$i * 2 + 1]));
		}
		$pages = array_merge($pages, $newPages);
		$previousPages = array_map("MapPagesToNextPages", $newPages);
	}

	// create 2 pages to link to 4 last
	$newPages = array();
	$newPages[] = GetAnimalPage($birds, $reptiles, $mammals, array($previousPages[0], $previousPages[1]));
	$newPages[] = GetAnimalPage($birds, $reptiles, $mammals, array($previousPages[2], $previousPages[3]));
	$pages = array_merge($pages, $newPages);
	$previousPages = array_map("MapPagesToNextPages", $newPages);

	// create begin page
	$beginPage = (object) array(
		"name" => "beginPage",
		"title" => "THE BEGINNING",
		"animal" => null,
		"nextPages" => $previousPages,
		"area" => "building"
		);
	$pages[] = $beginPage;

	// reverse pages to start with begin and finish with end
	$pages = array_reverse($pages);
	return $pages;
}

?>