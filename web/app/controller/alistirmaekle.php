<?php
ob_start();
require_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/utils.php");
//loadModel("pgn.php");
loadModel("DBAlistirma.php");
loadModel("DBKategori.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);

$controller = isset($_POST['islem']) ? $_POST['islem'] : "";
//var_dump($_POST);
//echo $controller;
if(	$controller === "kaydet") {
	if(sanityCheck($_POST['fen'], "string", 5, 100) &&
		sanityCheck($_POST['sinir'], "numeric", 1, 3) &&
		sanityCheck($_POST['aciklama'], "string", 0, 1000) &&
		sanityCheck($_POST['zorluk'], "numeric", 0, 2) &&
		sanityCheck($_POST['kategori'], "numeric", 0, 10)
		
		){
		$db = new DBAlistirma();
		$dbK = new DBKategori();
		if(($id = $db->alistirmaEkle($_POST['fen'], $_POST['sinir'], $_POST['aciklama'])) > -1){
			//echo "OK";
			$dbK->elemanEkle($_POST['kategori'], $_POST['zorluk'], 1, $id);
		} else {
			//echo "ERROR";
		}
	} else {
		//echo "error";
	}
	//exit();
}
//echo "$controller";
?>
<div class="row">
	<div class="col-md-2">
<?php
loadView("sidebar.php");
?>
		</div>



<?php

$db = new DBKategori();
$kategoriler = $db->kategorileriGetir(0, true);
$ktgr = array();
foreach ($kategoriler as $key => $value) {
	$ktgr[$key] = $value[3];
}

$args = array("id" => $_SESSION['id'], "kategoriler" => $ktgr);

?>
<div class="col-md-8">
<?php
loadView("alistirma/exerciseWizard.php", $args);
?>
</div>

</div>


<?php
loadView("footer.php");
ob_end_flush();
?>
