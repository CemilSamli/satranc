<?php
ob_start();
include_once("../model/utils.php");
loadModel("DBAdmin.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array ()
);
yetkiKontrol($cfg);

?>
	<div class="row">
	<div class="col-md-1">

	</div>

<?php

class dersEkleCikarYetkisi{
	function teachers(){
		$object=new DBAdmin();

		/* veritabanından ogretmenleri getirir yetkisini durumunu kullanıcıadını adsoyadını getirir */
		$adet = $object->ogretmenAdedi();
		if($adet !== false){
			$sayfa = isset($_GET["s"]) ? $_GET["s"] : 0;
			$limit = 20;
			$sayfa_degerleri = sayfalama($sayfa,$limit,$adet);
		
			$baslangic = $sayfa_degerleri["baslangic"];
			$ssayisi = $sayfa_degerleri["ssayisi"];

			/* veritabanından ogretmenleri getirir yetkisini durumunu kullanıcıadını adsoyadını getirir */
			$dizi=$object->teachers($baslangic,$limit);
			$sayac=1;

			$hata = false;
			$hatalar = array();

			?>
			<div class="col-md-10">

			<?php
			if($dizi !== false){
				if($dizi !== 0){
					for($i=0;$i<count($dizi);$i++){
						
						$kayitlar=array("veriler"=>$dizi[$i],"sayac"=>$sayac++,"toplam"=>count($dizi),"ssayisi"=>$ssayisi);
						
						loadView("Admin/ogretmenyetkiler.php",$kayitlar);
					}
				}
				else{
					$hatalar[] = "Hiç Öğretmen Yok!";
					$hata = true;
				}
			}
			else{
				$hatalar[] = "Sistemde Hata Oluştu! Lütfen Tekrar Deneyin!";
				$hata = true;
			}

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}

				?>
			</div>
			<?php
		}
	}
}
if($_SESSION["yetki"] == YETKI_ADMIN){
	$object=new dersEkleCikarYetkisi();
	$object->teachers();
	if(!empty($_GET["islem"]) && !empty($_GET["altislem"])){
		$ogrYetkiAta=new DBAdmin();
		if($_GET["islem"]=="eklecikarogr"){
			$yetki=8;
			$ogrYetkiAta->adminYetkiAta($_GET["altislem"],$yetki);
			header("Location:ogretmenyetkiler.php");
		}
		if($_GET["islem"]=="normalogr"){
			$yetki=1;
			$ogrYetkiAta->adminYetkiAta($_GET["altislem"],$yetki);
			header("Location:ogretmenyetkiler.php");
		}
		if($_GET["islem"]=="ogrenciogr"){
			$yetki=16;
			$ogrYetkiAta->adminYetkiAta($_GET["altislem"],$yetki);
			header("Location:ogretmenyetkiler.php");
		}
		if($_GET["islem"]=="ogrencidersogr"){
			$yetki=32;
			$ogrYetkiAta->adminYetkiAta($_GET["altislem"],$yetki);
			header("Location:ogretmenyetkiler.php");
		}
	}
}

echo "</div>";
loadView("footer.php");
ob_end_flush();
?>
