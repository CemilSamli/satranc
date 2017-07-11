<?php
//do�rulama kodunun t�r�n� belirtiyoruz
header("Content-Type: image/jpeg");

//do�rulama kodunun boyutunu giriyoruz
$width = 154;
$height = 33;

//resimi olu�turuyoruz
$image = ImageCreate($width, $height);

//kullanaca��m�z renkleri olu�turuyoruz
$siyah = ImageColorAllocate($image, 120, 120, 120);
$gri = imagecolorallocate($image, 128, 128, 128);
$beyaz = ImageColorAllocate($image, 255, 255, 255);
$sari = ImageColorAllocate($image, 0, 255, 0);

//arkapalan� yap�yoruz
ImageFill($image, 0, 0, $siyah);

//resmin uzerine yazaca��m�z kodu yap�yoruz
$dk = Sha1(rand(0,1000000));
$dk = substr($dk, Rand(0,29), 2);

//rastgele de�erimizi resmin �zerine koyuyoruz
ImageString($image, 5, 30, 3, $dk, $beyaz);

 session_start();
 $_SESSION["gercekkod"] = $dk;

//resme �izgi atarak k�r�lmas�n� zorla�t�r�yoruz
ImageRectangle($image,0,0,$width-1,$height-1,$gri);
imageline($image, $width/2, 0, $width, $height/2, $beyaz);
imageline($image, 0, $height/2, $width, $height/2, $beyaz);
imageline($image, $width/2,  $height, $width, $height/2, $beyaz);
imageline($image, $width/2,  $height/3, $width, $height/3, $sari);
//resmi olu�turuyoruz
ImageJpeg($image);

//kayna��m�z� temizliyoruz
ImageDestroy($image);
?>
