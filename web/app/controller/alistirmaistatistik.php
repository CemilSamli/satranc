<?php
ob_start();
include_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/utils.php");
loadModel("DBAlistirma.php");
loadModel("DBGrup.php");

$db = new DBAlistirma();
$dbGrup = new DBGrup();

$controller = isset($_GET['islem']) ? $_GET['islem'] : "";
$grupid = isset($_GET['grupid']) ? $_GET['grupid'] : "";
$uyeid = isset($_GET['uyeid']) ? $_GET['uyeid'] : "";
$sayfa = isset($_GET['sayfa']) ? $_GET['sayfa'] : 1;
$sayfaic = isset($_GET['sayfaic']) ? $_GET['sayfaic'] : 1;

//?islem=icerik&grupid=18&uyeid=1&sayfa=1&sayfaic=1
if($controller == "icerik"){
	session_start();
	if (sanityCheck($uyeid, "numeric", 1, 10) &&
		sanityCheck($sayfaic, "numeric", 1, 10) &&
		sanityCheck($grupid, "numeric", 1, 10) &&
		$dbGrup->ogrencisimi($grupid, $uyeid, $_SESSION['id'])){
		$veriler = $db->kisiButunIstatistikleriGetir($uyeid);
		$args = array("istatistikler" => $veriler, "sayfa" => $sayfaic-1, "sayfaBasinaSatir" => 5, "uyeID" => $uyeid);
		loadView("istatistikIcerik.php", $args);
	} else {
		echo "ERROR";
	}
	/*echo "<pre>";
	print_r($_GET);
	echo "</pre>";*/
	exit();
}
loadView("header.php");
$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI, YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);

if(!isset($_COOKIE['gid'])){
	setcookie("gid", $grupid, time() + 60*60*24, "/");
} else if($_COOKIE['gid'] !== $grupid){
	setcookie("gid", $grupid,  time() + 60*60*24, "/");
}
if(!isset($_COOKIE['sayfa'])){
	setcookie("sayfa", $sayfa, time() + 60*60*24, "/");
} else if($_COOKIE['sayfa'] !== $sayfa){
	setcookie("sayfa", $sayfa,  time() + 60*60*24, "/");
}

?>
<div class="row">
	<div class="col-md-2">
	</div>

	<div class="col-md-8">
	<?php

	$cvp = $dbGrup->uyeListesiGetir($grupid);
	$args = array("uyeler" => $cvp);
	loadView("istatistikSayfa.php", $args);
	//print_r($veriler);
	?>

	</div>
</div>

<?php
loadView("footer.php");
ob_end_flush();
?>
