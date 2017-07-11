<?php

$file = $args["tmpresim"];
list($width, $height,$type) = getimagesize($file);
$modwidth = 160;
$modheight = 160;
$tn= imagecreatetruecolor($modwidth, $modheight);

if($args["type"]=="image/jpeg"){
	$source = imagecreatefromjpeg($file);
	imagecopyresampled($tn, $source, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
	imagejpeg($tn, $args["konum"], 75);
}
else if($args["type"]=="image/png"){
	$source = imagecreatefrompng($file);
	imagecopyresampled($tn, $source, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
	imagepng($tn, $args["konum"], 3);
}

imagedestroy($source);
imagedestroy($tn);

?>
