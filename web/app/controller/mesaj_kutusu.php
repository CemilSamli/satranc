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
class mesajkutusu{

	/* Eger giris yapan normal kullanici ise sistemdeki ogretmenleri gosterir
	Eger giris yapan ogretmen ise sistemdeki normal kullanici ve ogretmenleri gosterir  */
	function kullanicilar()
	{
		$okundumu="hayir";
		$mesajkutusu=new DBKullanici();
		$gelenmesajlar=$mesajkutusu->mesajKutusu($okundumu);

		$adet = $mesajkutusu->mesajKullaniciAdedi();
		if($adet !== false){
		$limit = 20;
		$sayfa_degerleri = sayfalama($gelenmesajlar,$limit,$adet);
		
		$baslangic = $sayfa_degerleri["baslangic"];
		$ssayisi = $sayfa_degerleri["ssayisi"];

		
		
		$hatalar = array();

		if($gelenmesajlar !== false){
			$kisiler=$mesajkutusu->mesajKullanicilar($baslangic,$limit);
			if($kisiler !== false){
				if($kisiler !== 0){
					$sayac=1;
					?>
					 <div class="col-md-8">
					<?php
					for($i=0;$i<count($kisiler);$i++){
						$veriler=array("kisiler"=>$kisiler[$i],"sayac"=>$sayac++,
						"toplamsayac"=>count($kisiler),"gelenmesajlar"=>$gelenmesajlar,"ssayisi"=>$ssayisi);
					     loadView("mesajlar/mesajkutusu.php",$veriler);
					}
					?>
					<?php
				}
				else{
					$hatalar[] = "Hiç Kullanıcı Yok!";
					loadView("hatalar.php",$hatalar);
				}
			}
		}
	}
	}

	/* Kullaniciya gelen mesajları gosterir  */
	function gelen_mesajlar(){
		$okundu="evet";
		$tarih = date("Y.m.d H:i:s");
		$hatalar = array();
		$hata = false;

		$gelenmesajlar=new DBKullanici();

		$adet = $gelenmesajlar->gelenMesajSayisi($_SESSION["id"]);

		if($adet !== false){
			$sayfa = isset($_GET["s"]) ? $_GET["s"] : 0;
			$limit = 20;
			
			$sayfa_degerleri = sayfalama($sayfa,$limit,$adet);
			$baslangic = $sayfa_degerleri["baslangic"];
			$ssayisi = $sayfa_degerleri["ssayisi"];


			$gelen = $gelenmesajlar->gelenMesajlar($okundu,$tarih,$baslangic,$limit);
			if($gelen !== false){
				if($gelen !== 0){
					$sayac=1;
					?>
					<div class="col-md-8">

					<?php

					for($i=0;$i<count($gelen);$i++){
						$veriler=array("kayitlar"=>$gelen[$i],"sayac"=>$sayac++,
						"toplamsayac"=>count($gelen),"ssayisi"=>$ssayisi);
						loadView("mesajlar/mesajlar.php",$veriler);
					}
					?>

				</div>
					<?php
				}
				else{
					$hatalar[] = "Hiç Gelen Mesajınız Yok!";
					$hata = true;
				}
			}
			else{
				$hatalar[] = "Sistemde Hata Olustu Lütfen Daha Sonra Tekrar Deneyin!";
				$hata = true;
			}

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}
	}
	}

	/* Kullanicinin gonderdigi mesajları gosterir */
	function gonderilen_mesajlar(){
		$gonderilenmesajlar=new DBKullanici();

		$hatalar = array();
		$hata = false;

		$adet = $gonderilenmesajlar->gonderilenMesajSayisi();

		if($adet !== false){
			$sayfa = isset($_GET["s"]) ? $_GET["s"] : 0;
			$limit = 20;
			$sayfa_degerleri = sayfalama($sayfa,$limit,$adet);
		
			$baslangic = $sayfa_degerleri["baslangic"];
			$ssayisi = $sayfa_degerleri["ssayisi"];

			$gonderilen = $gonderilenmesajlar->gonderilenMesajlar($baslangic,$limit);

			if($gonderilen !== false){
				if($gonderilen !== 0){
					$sayac=1;
                    ?>
                    <div class="col-md-8">

                    <?php
					for($i=0;$i<count($gonderilen);$i++){
						$veriler=array("kayitlar"=>$gonderilen[$i],"sayac"=>$sayac++,
						"toplamsayac"=>count($gonderilen),"ssayisi"=>$ssayisi);
						?>

						 <?php   loadView("mesajlar/mesajlar.php",$veriler); ?>



		<?php
					} ?>
                    </div>
                                <?php


				}
				else{
					$hatalar[] = "Hiç Göndeilen Mesajınız Yok!";
					$hata = true;
				}
			}
			else{
				$hatalar[] = "Sistemde Hata Oluştu.Lütfen Daha Sonra Tekrar Deneyin!";
				$hata = true;
			}

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}
	}
	}

}
$nesne=new mesajkutusu();
if(!empty($_GET["islem"])){
	if($_GET["islem"]==="mesaj_kutusu"){
		$nesne->kullanicilar();
	}
	else if($_GET["islem"]==="gelen_mesajlar"){
		$nesne->gelen_mesajlar();
	}
	else if($_GET["islem"]==="gonderilen_mesajlar"){
		$nesne->gonderilen_mesajlar();
	}
	else{
		header("Location:mesaj_kutusu.php?islem=mesaj_kutusu");
	}
}
else{
	header("Location:mesaj_kutusu.php?islem=mesaj_kutusu");
}

echo "</div>";
					loadView("footer.php");
					ob_end_flush();
?>
