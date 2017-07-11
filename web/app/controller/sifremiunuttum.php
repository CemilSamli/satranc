<?php
ob_start();
include_once("../model/utils.php");
loadModel("DBKullanici.php");
loadView("header.php");

class sifremiunuttum{
	function sifre(){

		if($_POST){
			loadView("Kullanici/sifremiunuttumformu.php");
			$sifre=new DBKullanici();
			$donen=$sifre->sifremiUnuttum($_POST["email"]);

			$hata = false;
			$hatalar = array();

			if($donen === false){
				$hatalar[] = "Sistemde Hata Olustu! Lütfen Daha Sonra Deneyin!";
				$hata = true;

			}
			if(empty($_POST["email"])){
				$hatalar[] = "Email Boş Olamaz!";
				$hata = true;
			}

			if($donen === 0){
				$hatalar[] = "Email Adresini Hatalı Girdiniz!";
				$hata = true;
			}

			if($hata === false){
				$kime = $_POST["email"];
				$konu = "Sifre Sıfırlama";
				$kod = Sha1(rand(0,1000000));
				$kod = substr($kod, Rand(0,35), 6);
				$mesaj = "Merhaba Sifre Sıfırlama Talebiniz Tarafımıza ulaşmıştır.<br>Lütfen Şifrenizi Sıfırlamak için Şu Doğrulama Kodunu Girin...<br>DOGRULAMA KODU:".$kod ;
				$kimden = "http://www.nurullah-isik.16mb.com";
				$mail = mail($kime, $konu, $mesaj, $kimden);

				//if($mail){
					$hatalar[] = "Dogrulama Kodu Gönderildi! Lütfen Mailinizi Kontrol Edin...";
					loadView("hatalar.php",$hatalar);
					$sifre->dogrulamaKodGuncelle($donen,"1=1");//$donen,$kod
					header("Location:sifremiunuttum.php?islem=aktivasyon&altislem=".$donen);
				/*}
				else{
					$hatalar[] = "Doğrulama Kodu Gönderilemedi! Lütfen Daha Sonra Tekrar Deneyin!";
					loadView("hatalar.php",$hatalar);
				}*/
			}
			else{
				loadView("hatalar.php",$hatalar);
			}
		}
		else{
			loadView("Kullanici/sifremiunuttumformu.php");
		}
	}
	function aktivasyon($id){

		if($_POST){
			loadView("Kullanici/aktivasyon.php");
			$kod=$_POST["dogrulamakodu"];
			$aktive=new DBKullanici();
			$donen=$aktive->aktivasyonKodunuAl($id);

			$hata = false;
			$hatalar = array();

			if($donen === false){
				$hatalar[] = "Sistemde Hata Olustu! Lütfen Daha Sonra Deneyin!";
				$hata = true;
			}

			if($donen["aktivasyon"] === 0){
				$hatalar[] = "Aktivasyon Kodu Gönderilemedi!";
				$hata = true;
			}
			if($kod !== $donen["aktivasyon"]){
				$hatalar[] = "Aktivasyon Kodunu Yanlis Girdiniz!";
				$hata = true;
			}


			if($hata === false){
				$sifredegistir=new DBKullanici();
				$sifredegistir->aktivasyonKodGuncelle($id,"0");
				header("Location:sifremiunuttum.php?islem=yenisifre&altislem=".$id);
			}
			else{
				loadView("hatalar.php",$hatalar);
			}
		}
		else{
			loadView("Kullanici/aktivasyon.php");
		}
	}

	function yeniSifre($id){

		if($_POST){
			loadView("Kullanici/yenisifre.php");
			$sifre=$_POST["sifre"];
			$sifretekrar=$_POST["sifretekrar"];

			$hata = false;
			$hatalar = array();

			if(empty($sifre) && empty($sifretekrar)){
				$hata = true;
				$hatalar[] = "Alanlar Boş Olamaz!";
			}
			if($sifre !== $sifretekrar){
				$hata = true;
				$hatalar[] = "Şifreler Aynı Değil!";
			}


			if($hata === false){
				$sifredegistir=new DBKullanici();
				$sifredegistir->yeniSifreAl($id,md5(sha1(md5($sifre))));
				$kod = Sha1(rand(0,1000000));
				$kod = substr($kod, Rand(0,35), 6);
				$sifredegistir->aktivasyonKodGuncelle($id,$kod);
				header("Location:giris.php");

			}
			else{
				loadView("hatalar.php",$hatalar);
			}
		}
		else{
			loadView("Kullanici/yenisifre.php");
		}
	}
}

$hata = false;
$hatalar = array();

if(!empty($_GET["islem"])){

	$sifre=new sifremiunuttum();

	if($_GET["islem"] === "parolamiunuttum"){
		$sifre->sifre();
	}
	else if($_GET["islem"] === "aktivasyon" && !empty($_GET["altislem"]) && is_numeric($_GET["altislem"])){
		$object = new DBKullanici();
		$kullaniciVarmi = $object->kullaniciVarmi($_GET["altislem"]);
		if($kullaniciVarmi !== false){
			if($kullaniciVarmi === 1){
				$sifre->aktivasyon($_GET["altislem"]);
			}
			else{
				$hatalar[] = "Birşeyler Ters Gidiyor Sanki!";
				$hata = true;
			}
		}
		else{
			$hatalar[] = "Sistemde Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin!";
			$hata = true;
		}

	}
	else if($_GET["islem"] === "yenisifre" && !empty($_GET["altislem"]) && is_numeric($_GET["altislem"])){
		$object = new DBKullanici();
		$kullaniciVarmi = $object->kullaniciVarmi($_GET["altislem"]);
		if($kullaniciVarmi !== false){
			if($kullaniciVarmi === 1){
				$aktive=new DBKullanici();
				$donen=$aktive->aktivasyonKodunuAl($_GET["altislem"]);
				if($donen["aktivasyon"] === "0"){
					$sifre->yeniSifre($_GET["altislem"]);
				}
				else{
					header("Location:giris.php");
				}
			}
			else{
				$hatalar[] = "Birşeyler Ters Gidiyor Sanki!";
				$hata = true;
			}
		}
		else{
			$hatalar[] = "Sistemde Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin!";
			$hata = true;
		}
	}
	else{
		header("Location:giris.php");
	}

	if($hata === true){
		loadView("hatalar.php",$hatalar);
	}

}
else{
	header("Location:giris.php");
}


loadView("footer.php");
ob_end_flush();
?>
