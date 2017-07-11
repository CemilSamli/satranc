<?php
ob_start();
include_once("../model/utils.php");
include_once("ipbul.php");
loadView("header.php");
loadModel("DBOnline.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_UYE, YETKI_UYE_DEGIL, YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI, YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);

?>
<div class="container-fluid">
<?php

	class KarsilikliOyun{

		function eskiMaclar(){
			$oyun = new DBOnline();
			$dizi = $oyun->eskiMaclar($_SESSION["id"],-1);
			$hata = false;

			$sayac = 1;
			if($dizi !== false){

				if($dizi != 0){
					for($i=0;$i<count($dizi);$i++){
						$kayitlar = array("bilgi"=>$dizi[$i]);
						$fenString = $oyun->oyunSirasiGetir($kayitlar["bilgi"]["id"]);
						$parca = explode(" ",$fenString);

						$kayitlar = array("bilgi"=>$dizi[$i],"renk"=>$parca[1],"sayac"=>$sayac,"toplamsayac"=>count($dizi));

						loadView("karsiliklioyun/eskimaclar.php",$kayitlar);
						$sayac++;
					}
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

		function oyuncular(){
			$kisiler = new DBOnline();
			$dizi = $kisiler->kullanicilar();
			$hatalar = array();
			$hata = false;
			$sayac = 1;
			if($dizi !== false){
				if($dizi !== 0){
					for($i=0;$i<count($dizi);$i++){
						$kayitlar=array("bilgi"=>$dizi[$i],"sayac"=>$sayac,"toplamsayac"=>count($dizi));
						loadView("karsiliklioyun/oyuncular.php",$kayitlar);
						$sayac++;
					}
				}
				else{
					$hatalar[] = "Sistemde Hiç Kullanıcı Yok!";
					$hata = true;
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

	$oyun = new KarsilikliOyun();
	?> <div class="col-md-12"><div class="col-md-8"> <?php
	$oyun->eskiMaclar();
	?> </div><div class="col-md-4"> <?php
	$oyun->oyuncular();
	?> </div></div><?php

	/*  meydan okunan kisiyi veritabanına ekle */
	if(!empty($_GET["id"])){
		if(is_numeric($_GET["id"])){
			$nesne = new DBOnline();
			$meydanokuyanIP = GetIP();
			$nesne->ekle($_GET["id"],$meydanokuyanIP);
			header("Location:karsiliklioyun.php");
		}
	}

?>
</div>
<?php
loadView("footer.php");
ob_end_flush();
?>
