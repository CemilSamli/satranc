<?php
ob_start();
include_once("../model/utils.php");
loadModel("DBKullanici.php");
session_start();
$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_UYE, YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI,YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);

/*  Gelen kutusu ve Gonderilen mesajlardan sil linkine tıklandıgında secili olan mesajın id sini veritabanından siler */
class mesajsil{
	function sil(){
		if(!empty($_GET["id"])){
			if(preg_match("/^[0-9]+$/",$_GET["id"])){
				$silmesaj=new DBKullanici();
				$silmesaj->mesajSil($_GET["id"]);
				if($_GET["islem"] === "gonderilen_mesajlar"){
					header("Location:mesaj_kutusu.php?islem=gonderilen_mesajlar");
				}
				else if($_GET["islem"] === "gelen_mesajlar"){
					header("Location:mesaj_kutusu.php?islem=gelen_mesajlar");
				}
			}
		}
	}
}
$mesajsil=new mesajsil();
$mesajsil->sil();
ob_end_flush();
?>
