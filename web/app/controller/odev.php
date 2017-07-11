<?php
include_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/utils.php");
loadModel("DBOdev.php");
loadModel("DBGrup.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_UYE, YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI, YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);

?>
<div class="row">
	<div class="col-md-2 ">
	</div>


	<?php

	switch ($_SERVER['REQUEST_METHOD']) {
		case 'GET':
			$controller = isset($_GET['c']) ? $_GET['c'] : "";
			break;
		case 'POST':
			$controller = isset($_POST['c']) ? $_POST['c'] : "";
			break;
	}

	$dbOdev = new DBOdev();
	$dbGrup = new DBGrup();
	$dbKategori = new DBKategori();

	switch($controller){
		case "odevlerim" : {
			$odevler = $dbOdev->ogrenciIcinOdevleriGetir($_SESSION['id']);
			/*
			$gruplarim = $dbGrup->uyesiOlunanGruplar($_SESSION['id']);
			$grupDict = array();
			foreach ($gruplarim as $key => $grup) {
				$grupDict[$grup['id']] = $grup["adi"];
			}
			*/
			foreach ($odevler as $key => $odev) {
				$odevler[$key]["kategori"] = $dbKategori->kategoriGetir($odev['alistirmaKategoriID']);
				if ($odev['zorluk'] == 0) $odevler[$key]["zorluk_str"] = "Kolay";
				if ($odev['zorluk'] == 1) $odevler[$key]["zorluk_str"] = "Orta";
				if ($odev['zorluk'] == 2) $odevler[$key]["zorluk_str"] = "Zor";
				//if (isset($grupDict[$odev['grupID']])) $odevler[$key]["grup"] = $grupDict[$odev['grupID']];

				$yapildimi = $dbOdev->odevYaptimi($odev["id"], $odev["sorumluID"]);
				$curtime = time();
				$tarihBitis = strtotime($odev["tarihBitis"]);
				if($yapildimi == 1){
					$odevler[$key]['durum'] = "success";
				} else if ($curtime > $tarihBitis) {
					$odevler[$key]['durum'] = "danger";
				} else {
					$odevler[$key]['durum'] = "";
				}
			}
			$args['odevler'] = $odevler;
			loadView("Ogretmen/odevListele.php",$args);

			break;
		} case "listele" : {

			$odevler = $dbOdev->odevleriGetir($_GET['tur'], $_GET['sorumluID'], $_GET['grupID']);
			foreach ($odevler as $key => $odev) {
				$odevler[$key]["kategori"] = $dbKategori->kategoriGetir($odev['alistirmaKategoriID']);
				if ($odev['zorluk'] == 0) $odevler[$key]["zorluk_str"] = "Kolay";
				if ($odev['zorluk'] == 1) $odevler[$key]["zorluk_str"] = "Orta";
				if ($odev['zorluk'] == 2) $odevler[$key]["zorluk_str"] = "Zor";

				if($_GET['tur'] == 0) {
					$yapildimi = $dbOdev->odevYaptimi($odev["id"], $odev["sorumluID"]);
					$curtime = time();
					$tarihBitis = strtotime($odev["tarihBitis"]);
					if($yapildimi == 1){
						$odevler[$key]['durum'] = "success";
					} else if ($curtime > $tarihBitis) {
						$odevler[$key]['durum'] = "danger";
					} else {
						$odevler[$key]['durum'] = "";
					}
				}
			}
			$args['odevler'] = $odevler;
			loadView("Ogretmen/odevListele.php",$args);
			break;
		} case "ver" : {
			foreach ($_POST['ekle'] as $key => $value) {
				if ($_POST['ekle'][$key] == 0) continue;
				if ($_POST['tur'] == 1) $_POST['sorumluID'] = -1;
				$dbOdev->odevEkle($_POST['tur'], $_POST['sorumluID'], $_POST['grupId'], $key,
								$_POST['adet'][$key], $_POST['zorluk'][$key], $_POST['gunSiniri'][$key]);
			}
			?>
			<script>
				location.replace("?c=listele&sorumluID=<?php echo $_POST['sorumluID']; ?>&tur=<?php echo $_POST['tur']; ?>&grupID=<?php echo $_POST['grupId']; ?>");
			</script>
			<?php
			break;
		} case "odevVerForm" : { // ver
			$kategoriler = $dbKategori->kategorileriGetir(1);
			$ktgr = array();
			foreach ($kategoriler as $key => $value) {
				$ktgr[$key] = $value;
			}

			$args = array(  "tur" => $_GET['tur'],
							"grupId" => $_GET['grupID'],
							"kategoriler" => $ktgr);
			if($_GET['tur']==0)
				$args['sorumluID'] = $_GET['sorumluID'];


			loadView("Ogretmen/odevVer.php",$args);
			break;
		}  default : {
			?>
			<script> location.replace("/web/app/controller/gruplar.php"); </script>
			<?php
		}
	}
	?>

</div>

<?php
loadView("footer.php");
?>
