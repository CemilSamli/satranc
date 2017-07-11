<?php

ob_start();
include_once("../model/utils.php");
loadView("header.php");

?>
<div class="container-fluid">
<?php
class giris{
	/*  Giris Formundan gelen kullaniciadi ve sifresini kontrol ederek
	eger veritabanında karsılıgı varsa sisteme giris yapar  */
	function girisyap(){
		if(empty($_SESSION["oturum"])){
			if(!empty($_GET["a"])){
			   if($_GET["a"] == "mobil"){
					loadView("Kullanici/girisformu_mobil.php");
					$mobil = 1;
				}
            }
            else{
                loadView("Kullanici/girisformu.php");
            }
		}
        $hata = false;
        $hatalar = array();
		
		if($_POST){

			$username=htmlentities($_POST["username"]);
			$sifre=htmlentities($_POST["pass"]);
			$pass=md5(sha1(md5($sifre)));
			if($_POST["guvenlikkodu"] === $_SESSION["gercekkod"]){
				if(!empty($username) && !empty($pass))
				{
					$giris=new DBKullanici();
					$ip=$this->GetIP();
					$donen = $giris->kullaniciDogrumu($username,$pass,$ip);
					if($donen === false){
						$hatalar[] = "Sistemde Hata Olustu Lütfen Daha Sonra Tekrar Deneyin!";
						$hata = true;
					}
					if($donen === 0){
						$hatalar[] = "Kullanıcı Adı veya Şifreyi Yanlıs Girdiniz!";
						$hata = true;
					}
				}
				else {
                    $hatalar[] = "Kullanıcı veya Şifre Boş Olamaz!";
                    $hata = true;

                }
			}
			else{
                $hatalar[] = "Güvenlik Kodu Hatalı!";
                $hata = true;
			}

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}

		}
	}

		/* cikis yapar  */
	function cikisyap(){
		$sifirla=new DBKullanici();
		$sifirla->cikis();
		$_SESSION["oturum"] = false;
		$_SESSION=array();
		session_destroy();
			if(!empty($_GET["a"])){
				if($_GET["a"] == "mobil"){
					header("Location:giris.php?a=mobil");
				}
			}else{
				header("Location:giris.php");
			}
		}

	function GetIP(){
		if(getenv("HTTP_CLIENT_IP")) {
			 $ip = getenv("HTTP_CLIENT_IP");
		 } elseif(getenv("HTTP_X_FORWARDED_FOR")) {
			 $ip = getenv("HTTP_X_FORWARDED_FOR");
			 if (strstr($ip, ',')) {
				 $tmp = explode (',', $ip);
				 $ip = trim($tmp[0]);
			 }
		 } else {
		 $ip = getenv("REMOTE_ADDR");
		 }
		return $ip;
	}
}


$giris=new giris();
/*  eger GET[islem] fonksiyonu ile gelen giris ise sisteme giris fonksiyonu cagrılır */
if(!empty($_GET["islem"])){
	if($_GET["islem"]=="giris"){
		$giris->girisyap();
	}
	/* eger GET[islem] fonksiyonu ile gelen cikis ise cikis fonksiyonu cagrılır */
	else if($_GET["islem"]=="cikis"){
		$giris->cikisyap();
	}
	else{
		$giris->girisyap();
	}
}
else{
	$giris->girisyap();
}
?>


</div>
<?php
include_once("../view/footer.php");
ob_end_flush();
?>
