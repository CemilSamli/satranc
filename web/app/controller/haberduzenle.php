<?php
ob_start();
include_once("../model/utils.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array ()
);
yetkiKontrol($cfg);


?>
<div class="row">
	<div class="col-md-2">


		</div>
	<?php
class Duzenle{

	function haberDuzenle(){
		$object = new DBKullanici();
		$dizi = $object->haberler("haber");
		$hatalar = array();
		$hata = false;

		if($dizi !== false){
			if($dizi !== 0){
				$sayac = 1;
				for($i=0;$i<count($dizi);$i++){
					$kayitlar = array("veriler"=>$dizi[$i],"sayac"=>$sayac++,"toplam"=>count($dizi));
					loadView("haberler/haberduzenle.php",$kayitlar);
				}
			}
			else{
				$hatalar[] = "Hiç Haber Yok!";
				$hata = true;
			}
		}
		else{
			$hata = true;
			$hatalar[] = "Sistemde Hata Olustu! Lütfen Daha Sonra Tekrar Deneyin!";
		}

		if($hata === true){
			loadView("hatalar.php",$hatalar);
		}

	}

	function duyuruDuzenle(){
		$object = new DBKullanici();
		$dizi = $object->duyurular("duyuru");
		$hatalar = array();
		$hata = false;

		if($dizi !== false){
			if($dizi !== 0){
				$sayac = 1;
				for($i=0;$i<count($dizi);$i++){
					$kayitlar = array("veriler"=>$dizi[$i],"sayac"=>$sayac++,"toplam"=>count($dizi));
					loadView("haberler/haberduzenle.php",$kayitlar);
				}
			}
			else{
				$hata = true;
				$hatalar[] = "Hiç Haber Yok!";
			}
		}
		else{
			$hata = true;
			$hatalar[] = "Sistemde Hata Olustu! Lütfen Daha Sonra Tekrar Deneyin!";
		}

		if($hata === true){
			loadView("hatalar.php",$hatalar);
		}
	}
}

$object = new Duzenle();
$haber = new DBKullanici();

if(!empty($_GET["islem"])){
	if($_GET["islem"] == "haber"){
		$object->haberDuzenle();
	}
	else if($_GET["islem"] == "duyuru"){
		$object->duyuruduzenle();
	}

	if(!empty($_GET["islem"]) && !empty($_GET["altislem"]) && !empty($_GET["id"])){
		if($_GET["altislem"] == "duzenle"){ // id yi kontrol ettir oyle bir haber var mı diye
			$dizi = $haber->haberduyurudevam($_GET["id"]);
			if($dizi !== false){
				if($dizi !== 0){
					for($i=0;$i<count($dizi);$i++){
						$kayitlar = array("veriler"=>$dizi[$i]);
						loadView("haberler/haberduzenlemeformu.php",$kayitlar);
					}
				}
			}
		}
		else if($_GET["altislem"] == "sil"){
			$haber->haberSil($_GET["id"]);
			if($_GET["islem"] == "haber"){
				header("Location:/web/app/controller/haberduzenle.php?islem=haber");
			}
			else if($_GET["islem"] == "duyuru"){
				header("Location:/web/app/controller/haberduzenle.php?islem=duyuru");
			}
		}
		else if($_GET["islem"] == "duzenle" && $_GET["altislem"] == "haberduzenle"){

		}
	}

	if($_GET["islem"] == "haberduzenle"){
		if($_POST){
			$resim=$_FILES["resim"]["name"];
			$tmpresim=$_FILES["resim"]["tmp_name"];
			$uzanti=end(explode(".",$resim));
			$type=$_FILES["resim"]["type"];
			$rand=substr(md5(rand()),0,10);
			$uzanti=end(explode(".",$resim));

			$konum = "/web/app/assets/image/haberduyururesimler/".$rand.".".$uzanti;
			$hatalar[] = array();
			$hata = false;
			if(!empty($resim) && ($type !== "image/png" && $type !== "image/jpeg")){
				$hatalar[] =  "Sadece png,jpg ve jpeg tipinde Resim Yükleyebilirsiniz!";
				$hata = true;
			}
			if(empty($_POST["icerik"]) && empty($_POST["baslik"])){
				$hatalar[] =  "Alanlar Boş Olamaz!";
				$hata = true;
			}

			if($hata === false){
				if(!empty($resim)){
					if(move_uploaded_file($tmpresim,$konum)){
					}
					else{
						echo "Resim Yüklenirken Hata Oluştu!";
					}
				}
				else{
					$konum = "";
				}
				$haber->haberduzenle($_GET["id"],$_POST["baslik"],$_POST["icerik"],$konum);
				header("Location:/web/app/controller/haberler.php?islem=haberler");
			}
			else{
				loadView("hatalar.php",$hatalar);
			}
		}
	}
	if($_GET["islem"]=="duyuruduzenle"){
		if($_POST){
			if(!empty($_POST["baslik"]) && !empty($_POST["icerik"])){
				$haber->duyuruDuzenle($_GET["id"],$_POST["baslik"],$_POST["icerik"]);
				header("Location:/web/app/controller/haberler.php?islem=duyurular");
			}
		}
	}

}

ob_end_flush();
?>
