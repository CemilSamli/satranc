<?php
ob_start();
include_once("../model/utils.php");
loadModel("DBOgretmen.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_OGRETMEN_OGRENCI,YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);

?>
<div class="row">
	<div class="col-md-2">

</div>

<?php
class KullaniciEkle{

	function yeniKullaniciEkle($yetki){
		$adSoyad=ucwords(strtolower(trim($_POST["adsoyad"])));
		$kullaniciAdi=strtolower(trim($_POST["kullaniciadi"]));
		$tarihDogum=$_POST["dogumtarih"];
		$email=strtolower(trim($_POST["email"]));
		$tarihKayit=date("Y:m:d H:i:s");

		$hata=false;
		$hatalar=array();

		if(empty($adSoyad) || empty($kullaniciAdi) || empty($tarihDogum) || empty($email)){
			$hata = true;
			$hatalar[] = "Alanlar Boş Olamaz!";
		}
		if(!preg_match("/^([A-Za-z])+$/",$kullaniciAdi) === 0){
			$hata = true;
			$hatalar[] = "Kullanıcı Adı Hatalı Girdiniz!";
		}
		if($kullaniciAdi === "admin"){
			$hatalar[]="Kullanıcı Adı Yanlış Girdiniz!";
			$hata=true;
		}
		if(preg_match("/^[^@]+@[^@]+\.[^@]+$/", $email) === 0){
			$hata = true;
			$hatalar[] = "Eposta Adresi Hatalı Girdiniz!";
		}
		$object=new DBOgretmen();
		if($object->uyekontrol($kullaniciAdi,$email) === false){
			$hata=true;
			$hatalar[] = "Girilen Eposta veya Kullanıcı Adı Başka Bir Kullanıcı Tarafından Kullanıyor!";
		}
		if(strtotime($tarihDogum)>strtotime(date("Y:m:d H:i:s"))){
			$hata = true;
			$hatalar[] = "Doğum Tarihi Yanlış Girildi!";
		}

		if($hata === false){
			$passHash=substr(md5(rand()),0,8);
			$mesaj="Merhaba Sayın : ".$adSoyad." http://www.satrancegitim.com adresine ".$_SESSION["adSoyad"]."
			Satranç Hocamız Tarafından Kaydınız Yapılmıştır..<br>Kullanıcı Adınız:".$kullaniciAdi."<br>
			Şifreniz:".$passHash." tir..<br>http://www.satrancegitim.com sitesinden Profilinizi düzenleyebilirsiniz...";

			$object=new DBOgretmen();
			$object->yeniUyeEkle($adSoyad,$kullaniciAdi,$tarihDogum,$email,$tarihKayit,$yetki,$mesaj,$passHash);
		}
		else{
			loadView("hatalar.php",$hatalar);
		}

	}
}


$object=new KullaniciEkle();
if(!empty($_GET["islem"])){
	if($_GET["islem"]=="kullaniciekle"){
		if($_SESSION["yetki"]=="16" || $_SESSION["yetki"]=="32" || $_SESSION["yetki"]=="2"){
		$yetki="4";

		?>
		<div class="col-md-8"><div class="well well-lg">
		 <?php   loadView("Ogretmen/kullaniciekleme.php"); ?>
		</div></div>


		<?php
			if($_POST){
			$object->yeniKullaniciEkle($yetki);
			}
		}
		else{
			header("/web/app/controller/giris.php");
		}
	}
	else if($_GET["islem"]=="ogretmenekle"){

		if($_SESSION["yetki"]=="2"){
			$yetki="1";
			?>
			<div class="col-md-8"><div class="well well-lg">
			 <?php   loadView("Ogretmen/kullaniciekleme.php"); ?>
			</div></div>


	<?php
			if($_POST){
			$object->yeniKullaniciEkle($yetki);
			}
		}
		else{
			header("/web/app/controller/giris.php");
		}
	}
	else{
		header("/web/app/controller/giris.php");
	}
}else{
	header("/web/app/controller/giris.php");
}



echo "</div>";
loadView("footer.php");
ob_end_flush();
?>
