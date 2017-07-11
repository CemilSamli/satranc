<?php
ob_start();
include_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/utils.php");
loadModel("DBKategori.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array ()
);
yetkiKontrol($cfg);

$controller = isset($_GET['islem']) ? $_GET['islem'] : "";
$isim = isset($_GET['isim']) ? $_GET['isim'] : "";
$tur = isset($_GET['tur']) ? $_GET['tur'] : "";
$id = isset($_GET['id']) ? $_GET['id'] : "";
$sayfa = isset($_GET['sayfa']) ? $_GET['sayfa']-1 : 0;

?>
<div class="row">
<?php

$db = new DBKategori();

$kategoriler = $db->kategorileriGetir(0, true);

?>


<?php

switch ($controller) {
	case 'ekle':
		$tur = $tur === "ders" ? 0 : 1;
		$isim = substr($isim, 0, 99);
		$db->kategoriEkle($isim, $tur);
		header("Location: kategoriYonetimi.php");
		break;
	case 'sil':
		$db->kategoriSil($id);
		header("Location: kategoriYonetimi.php");
		break;
}
$args = array("kategoriler" => $kategoriler,
				"sayfa" => $sayfa,
				"veriSayisi" => 10);
loadView("kategoriYonetimi.php", $args);

?>
</div>
<?php
	loadView("footer.php");
	ob_end_flush();
?>
