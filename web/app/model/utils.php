<?php
// view eklemek için kullanılacak. $args değişkeni aracılığı ile yüklenecek view'e dizi gönderilebilir.
function loadView($view, $args=null){
	include($_SERVER['DOCUMENT_ROOT']."/web/app/view/$view");
}

// model eklemek için kullanılacak. $args değişkeni aracılığı ile yüklenecek view'e dizi gönderilebilir.
function loadModel($view, $args=null){
	include_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/$view");
}

// controller eklemek için kullanılacak. $args değişkeni aracılığı ile yüklenecek view'e dizi gönderilebilir.
function loadController($view, $args=null){
	include($_SERVER['DOCUMENT_ROOT']."/web/app/controller/$view");
}

function sanityCheck($variable, $type, $minlength, $maxlength){
	$func = "is_".$type;
	$debug = false;
	if(!$func($variable)){
		if($debug === true){
			echo "Type is wrong.";
		}
		return false;
	/*} else if(empty($variable)){
		if($debug === true){
			echo "Variable is empty.";
		}
		return false;*/
	} else if(strlen($variable) < $minlength){
		if($debug === true){
			echo "Variable is too tiny";
		}
		return false;
	} else if(strlen($variable) > $maxlength){
		if($debug === true){
			echo "Variable is too large";
		}
		return false;
	}
	return true;
}

function sayfalama($sayfa, $limit,$adet){
		$ksayisi = $adet;
		$ssayisi = ceil($ksayisi/$limit);
		if(empty($sayfa) || !is_numeric($sayfa)){
			$sayfa = 1;
		}if($sayfa > $ssayisi){
			$sayfa = $ssayisi;
		}if($sayfa < 1){
			$sayfa = 1;
		}
		$baslangic   = ($sayfa-1) * $limit;
		$sayfa_degerleri = array("baslangic"=>$baslangic,"ssayisi"=>$ssayisi);
		return $sayfa_degerleri;
}

/*
$cfg = array(
	"uyeYetkisi" => isset($_SESSION['id']) ? $_SESSION['id'] : -1,
	"seviye" => array (0,1,2,4)
);*/

define("YETKI_UYE_DEGIL", 0);
define("YETKI_OGRETMEN", 1);
define("YETKI_ADMIN", 2);
define("YETKI_OGRETMEN_DERS", 8);
define("YETKI_UYE", 4);
define("YETKI_OGRETMEN_OGRENCI", 16);
define("YETKI_OGRETMEN_DERS_OGRENCI", 32);

function yetkiKontrol($cfg = array()){
	$cfg['seviye'][] = YETKI_ADMIN;
/*	$cfg[] = YETKI_ADMIN;
	if($cfg['giris'] === true) {
		$oturum = isset($_SESSION['oturum']) ? $_SESSION['oturum'] : false;
		if($oturum === false){
			echo "ife girdi";
			//header("Location: /");
		}
	} else */
	if(!(in_array($cfg['uyeYetkisi'], $cfg['seviye']))){
/*		foreach ($cfg['seviye'] as $key => $value) {
			echo "$value<br>";
		}
		echo $cfg['uyeYetkisi']."<br><br>";
			foreach ($_SESSION as $key => $value) {
				echo "$key => $value<br>";
			}
*/
		header("Location: /");
		exit();
	}
}
?>
