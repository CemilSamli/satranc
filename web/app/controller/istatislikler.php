<?php
ob_start();
include_once("../model/utils.php");
loadModel("DBIstatislik.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_UYE, YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI,YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);

?>
	<div class="row">
	<div class="col-md-2">

	</div>


<?php

class Istatislik{

	/* giris yapan hocanın grubundaki ogrencileri gosterir */
	function grubundami(){
		$object=new DBIstatislik();
		$dizi=$object->grupdami($_GET["id"]);

		$hata = false;
		$hatalar = array();

		?>
		<div class="col-md-8">
			<?php
			if($dizi !== false){
				if($dizi !== 0){
					$sayac=1;
					for($i=0;$i<count($dizi);$i++){
						$kayitlar=array("kullanici"=>$dizi[$i],"sayac"=>$sayac++);
						loadView("Ogretmen/kullanicilariGoster.php",$kayitlar);
					}
				}
				else{
					$hatalar[] = "Hiç Öğrenciniz Yok";
					$hata = true;
				}
			}
			else{
				$hatalar[] = "Sistemde Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin!";
				$hata = true;
			}
			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}

			?>
		</div>
	<?php

	}
	function kullanicilarGetir(){
		$kullanicilar=new DBIstatislik();

		$kullaniciAdedi = $kullanicilar->kullaniciAdedi();
		$adet = $kullaniciAdedi;
		if($adet !== false){
		$sayfa = isset($_GET["s"]) ? $_GET["s"] : 0;
		$limit = 20;
		$sayfa_degerleri = sayfalama($sayfa,$limit,$adet);

		$baslangic = $sayfa_degerleri["baslangic"];
		$ssayisi = $sayfa_degerleri["ssayisi"];

		$hatalar = array();
		$hata = false;

		$dizi=$kullanicilar->kullanicilariGetir($baslangic,$limit);
		$sayac=1;
		?>

		<div class="col-md-8">
		<?php


		if($dizi !== false){
			if($dizi !== 0){
				for($i=0;$i<count($dizi);$i++){
					$kayitlar=array("kullanici"=>$dizi[$i],"sayac"=>$sayac++,"ssayisi"=>$ssayisi,"toplamsayac"=>count($dizi));
					loadView("istatislik/kullanicilariGoster.php",$kayitlar);
				}
			}
			else{
				$hatalar[] = "Hiç Kullanıcı Yok!";
				$hata = true;
			}
		}
		else{
			$hatalar[] = "Sistemde Hata Oluştu! lütfen Daha Sonra Tekrar Deneyin!";
			$hata = true;
		}

		if($hata === true){
			loadView("hatalar.php",$hatalar);
		}

	 ?>

	</div>
		<?php
		if(!empty($_GET["kulid"]) && !empty($_GET["id"]) && !empty($_GET["islem"])){
			header("Location:IstatislikleriGor.php?id=".$_GET["id"]."&kulid=".$_GET["kulid"]."&islem=".$_GET["islem"]."&alt_islem=oyun");
		}
	}
	}
}

$object=new Istatislik();
if(!empty($_GET["id"])){

	if($_SESSION["yetki"] === "2" ||
		$_SESSION["id"] === $_GET["id"]){//admin yada kendi sayfası ise
		if(!empty($_GET["islem"])){
			if($_GET["islem"]==="bilgisayarkarsi" || $_GET["islem"]==="kullaniciyakarsi"){
				if($_SESSION["yetki"]=="2"){
					$object->kullanicilarGetir();
				}
			}

			if($_SESSION["yetki"]=="4"){

				header("Location:IstatislikleriGor.php?id=".$_GET["id"]."&islem=".$_GET["islem"]."&alt_islem=oyun");
			}
			if($_SESSION["yetki"] === "1" || $_SESSION["yetki"] === "8" || $_SESSION["yetki"] === "16" || $_SESSION["yetki"]=="32"){
				$object->grubundami();
			}
		}
	}
}



echo "</div>";
loadView("footer.php");
ob_end_flush();
?>
