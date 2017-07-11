<?php
ob_start();
include_once("../model/utils.php");
loadModel("DBAdmin.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI,YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);

class GrupDuzenle{

	function duzenle(){
		loadView("Ogretmen/grupduzenle.php");
		if($_POST){

			$hatalar = array();
			$hata = true;

			if(!empty($_POST["grupyeniad"])){
				$grupAdDuzenle=new DBAdmin();
				if($grupAdDuzenle->grupAdDuzenle($_GET["grupid"],$_POST["grupyeniad"])===true){
					header("Location:gruplar.php");
				}
				else{
					$hatalar[] = "Birşeyler Ters Gidiyor Sanki!";
					$hata = true;
				}
			}
			else{
				$hatalar[] = "Grup Adı Boş Olamaz!";
				$hata = true;
			}

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}

		}
	}
}

$hatalar = array();
$hata = true;

// grup id girisyapan  hocanın grubu mu kontrol et
$duzenle=new GrupDuzenle();
if(!empty($_GET["grupid"]))
{
	$grup=new DBAdmin();
	if($grup->grupIDHocaKontrol($_GET["grupid"])){
		$duzenle->duzenle();
	}
}
else{
	$hatalar[] = "Birşeyler Ters Gidiyor Sanki!";
	$hata = true;
}

if($hata === true){
	loadView("hatalar.php",$hatalar);
}


loadView("footer.php");
ob_end_flush();
