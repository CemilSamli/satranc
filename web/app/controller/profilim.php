<?php
ob_start();
include_once("../model/utils.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_UYE, YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI,YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);



echo "<div class=container-fluid>";
?>
<div class="row">
	<div class="col-md-2">

	</div>

<?php

class Profilim{

	/* profil duzenle formundan gelen bilgilere gore (bilgilerin kontrol edilmesi;adsoyad,mail)veritabanındaki verileri duzenler */
	function profilduzenle(){
		$adsoyad=trim($_POST["adsoyad"]);
		$eposta=trim($_POST["eposta"]);
		$resim=$_FILES["resim"]["name"];
		$tmpresim=$_FILES["resim"]["tmp_name"];
		$uzanti=end(explode(".",$resim));
		$type=$_FILES["resim"]["type"];

		$profilduzenle=new DBKullanici();
		$veriler=$profilduzenle->girisYapanKullaniciBilgileri();
		$yol="/web/app/assets/image/kullaniciavatar/";
		$konum=$yol.$veriler["kullaniciAdi"].".".$uzanti;

		$hata = false;
		$hatalar = array();
		if($profilduzenle->emailAdresiFarklimi($eposta) === false){
			$hatalar[] = "Aynı eposta adresi baska biri kullanıyor <br> Lütfen Baska bir eposta adresi deneyin.!";
			$hata = true;
		}
		if(empty($adsoyad) || empty($eposta)){
			$hatalar[] =  "Lütfen Gerekli Alanları Doldurun";
			$hata = true;
		}
		if(preg_match("/^[^@]+@[^@]+\.[^@]+$/", $eposta) === 0){
			$hatalar[] =  "Epostayı Hatalı Girdiniz!";
			$hata = true;
		}

		if(!empty($resim) && ($type !== "image/png" && $type !== "image/jpeg")){
			$hatalar[] =  "Sadece png,jpg ve jpeg tipinde Resim Yükleyebilirsiniz!";
			$hata = true;
		}

		if($hata === false){
			if(!empty($resim)){
				if(file_exists($veriler["avatar"]) !== false){//uye olurken resim yuklediyse
					unlink($veriler["avatar"]);//onceki resmi sil
				}
				$kayitlar=array("tmpresim"=>$tmpresim,"type"=>$type,"konum"=>$konum);
				loadController("grafik.php",$kayitlar);
			}
			else{
				if($veriler["avatar"] != ""){
					$konum = $veriler["avatar"];
				}
				else{
					$konum = "";
				}
			}

			$profilduzenle->profiliDuzenle($adsoyad,$eposta,$konum);

           // header("Location:profilim.php?islem=profilim");

		} else {
			$profil=new DBKullanici();
			$veriler=$profil->profilValueDegerGetir();
			if($veriler !== false){
				if($veriler !== 0){
					loadView("Kullanici/profilduzenle.php",$veriler);
				}
				else{
					$hatalar[] = "Sistemde Hata Olustu.Lütfen Tekrar Deneyin!";
				}
			}
			else{
				$hatalar[] = "Sistemde Hata Olustu.Lütfen Tekrar Deneyin!";
			}

			loadView("hatalar.php",$hatalar);
		}
	}

	function degistir(){//sifre degistirme
		$sifre=new DBKullanici();
		$veriler=$sifre->sifreKullaniciBilgiler();
		$eskisifre=md5(sha1(md5($_POST["eskisifre"])));

		$hata = false;
		$hatalar = array();

		if(empty($_POST["eskisifre"]) || empty($_POST["yenisifre"]) || empty($_POST["yenisifretekrar"])){
			$hatalar[] = "- Lütfen Gerekli Alanları Boş Bırakmayın!";
			$hata=true;
		}
		if($eskisifre !== $veriler["passHash"]){
			$hatalar[] = "- Eski Şifreyi Yanlış Girdiniz!";
			$hata=true;
		}
		if($_POST["yenisifre"] !== $_POST["yenisifretekrar"]){
			$hatalar[] = "- Şifreler Birbiriyle Uyuşmuyor";
			$hata=true;
		}

		if($hata === false){
			$yenisifre=md5(sha1(md5($_POST["yenisifre"])));
			$sifre->sifreDegistir($yenisifre);
			//header("Location:profilim.php?islem=profilim");
		}
		else{
			loadView("hatalar.php",$hatalar);
		}
	}
}


$profilim = new Profilim();
$profil=new DBKullanici();

if(!empty($_GET["islem"])){
	/*  profil bilgilerin gosterirdigi sayfa */
	if($_GET["islem"]=="profilim"){
		
		$veriler=$profil->profiliGoruntule();
		//$ogretmenler = $profil->ogretmenleriBul();
		
		if($veriler !== false){
			if($veriler !== 0){ ?>
				<div class="col-md-7">
				 <?php   loadView("Kullanici/profil.php",$veriler); ?>
				</div>
				<?php

			}
			else{
				echo "Sistemde Hata Olustu.Lütfen Tekrar Deneyin!";
			}
		}
		else{
			echo "Sistemde Hata Olustu.Lütfen Tekrar Deneyin!";
		}
	}
	/* profil duzenleme formunun gosterildigi sayfa */
	else if($_GET["islem"]=="profilduzenleme_formu"){
		$veriler=$profil->profilValueDegerGetir();
		if($veriler !== false){
			if($veriler !== 0){
				?>
				<div class="col-md-7">
				<?php    loadView("Kullanici/profilduzenle.php",$veriler); ?>
				</div>
				<?php
			}
			else{
				echo "Sistemde Hata Olustu.Lütfen Tekrar Deneyin!";
			}
		}
		else{
			echo "Sistemde Hata Olustu.Lütfen Tekrar Deneyin!";
		}
	}
	/* profil duzenle formundan gelen bilgilere gore profil duzenleme fonksiyonu cagrılır  */
	else if($_GET["islem"]=="profilduzenle"){
		if($_POST){
			$profilim->profilduzenle();
		}

	}
	else if($_GET["islem"]=="sifredegistir"){
		loadView("Kullanici/sifredegistir.php");
	}
	else if($_GET["islem"]=="degistir"){
		loadView("Kullanici/sifredegistir.php");
		if($_POST){
			$profilim->degistir();
		}
	}
	else{
		$veriler=$profil->profiliGoruntule(); ?>
			 <div class="col-md-7">
				<?php	loadView("Kullanici/profil.php",$veriler); ?>
			</div>
	<?php
	}
}
else{
	$veriler=$profil->profiliGoruntule();  ?>
	<div class="col-md-7">
	  <?php  loadView("Kullanici/profil.php",$veriler); ?>
	</div>
	<?php
}
?>
</div>
</div>
<?php
loadView("footer.php");
ob_end_flush();
?>
