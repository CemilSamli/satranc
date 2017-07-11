<?php
ob_start();
include_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/utils.php");
loadModel("DBKategori.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_UYE, YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI, YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);

$controller = isset($_GET['islem']) ? $_GET['islem'] : "";

?>

<div class="row">
	<div class="col-md-2">
	   <?php loadView("sidebar.php"); ?>
	</div>

	<div class="col-md-8">
		<?php

		$tur = isset($_GET['tur']) ? $_GET['tur'] : "";
		$tur = $tur=="ders" ? 0 : 1;

		$kategoriID = isset($_GET['id']) ? $_GET['id'] : "-1";
		$zorluk = isset($_GET['zorluk']) ? $_GET['zorluk'] : "-1";

		$db = new DBKategori();
		$yapilanAlistirmalar = $db->yapilanAlistirmaGetir();
		$idler = $db->siteIDleriniGetir($kategoriID, $zorluk, $tur);
		
		$args = array("idler" => $idler, "tur" => $tur,"yapilanlar"=>$yapilanAlistirmalar);
		loadView("icerikListesi.php", $args);

		?>
	</div>
</div>

<?php

include_once("../view/footer.php");
ob_end_flush();
?>
