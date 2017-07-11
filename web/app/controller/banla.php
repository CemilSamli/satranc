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
	<div class="col-md-2">

</div>
<?php
class Banla{

	/* Sistemdeki tum kullaniciları getirir engelli olup olmadıklarını gosterir */
	function uyeBanla(){
		$object=new DBAdmin();
		$adet = $object->kullaniciAdedi();

		$sayfa = isset($_GET["s"]) ? $_GET["s"] : 0;
		$limit = 20;
		
		$sayfa_degerleri = sayfalama($sayfa,$limit,$adet);
		
		$baslangic = $sayfa_degerleri["baslangic"];
		$ssayisi = $sayfa_degerleri["ssayisi"];
		?>

				<div class="col-md-8">
				<?php
		
		$dizi=$object->getUsers($baslangic,$limit);
		$sayac=1;

		$hatalar = array();
		$hata = false;

		if($dizi !== false){
			if($dizi !== 0){
				for($i=0;$i<count($dizi);$i++){
					$kayitlar=array("bilgi"=>$dizi[$i],"sayac"=>$sayac++,"toplamsayac"=>count($dizi),"ssayisi"=>$ssayisi);
					 loadView("Admin/banla.php",$kayitlar);

				}?>
				</div>
				<?php

			}
			else{
				$hatalar[] = "Sistemde Hiç Kullanıcı Yok!";
				$hata = true;
			}
		}
		else{
			$hata = true;
			$hatalar[] = "Sistemde Hata Oluştu.Lütfen Daha Sonra Tekrar Deneyiniz!";
		}

		if($hata === true){
			loadView("hatalar.php",$hatalar);
		}
	}
}
if($_SESSION["yetki"] == YETKI_ADMIN){
	$object = new Banla();
	$object->uyeBanla();
}



echo "</div>";
loadView("footer.php");
ob_end_flush();
?>
