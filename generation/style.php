<style role="stylesheet" id="twine-user-stylesheet" type="text/twine-css">
body {
	color: #000;
	background-color: unset;
}
a{
	color: #ba6200;
	font-weight: bold;
}
a:hover{
	color: #c00000;
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