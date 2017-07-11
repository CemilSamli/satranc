<?php

// yapay zekaya olan macları goster oyun.php ye gitsin oyunid ile beraber 
// eger oyun.phpde get ile gelen oyunid doluysa feni cek aioyun.php de goster

include_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/utils.php");
loadView("header.php");
loadModel("DBOyun.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_UYE, YETKI_UYE_DEGIL, YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI, YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);
?>
<div class="row">
<div class="col-md-12">
<?php
class AiEskiMaclar{
	function eskiMaclar(){
		
		$oyun = new DBOyun();
		$dizi = $oyun->eskiMaclar($_SESSION["id"]);
		$hatalar = array();
		$hata = false;
		
		if($dizi !== false){
			if($dizi != 0){
				loadView("aieskimaclar.php",$dizi);	
			}
		}
		else{
			$hata = true;
			$hatalar[] = "Sistemde Hata Oluştu.Lütfen Daha Sonra Tekrar Deneyiniz!";
		}
		
		if($hata === true){
			loadView("hatalar.php",$hatalar);
		}
	
	}
}

	$oyun = new AiEskiMaclar();
	$oyun->eskiMaclar();
	?>
		</div></div>
	<?php
	loadView("footer.php");


?>