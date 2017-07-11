<?php
include_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/utils.php");
loadModel("DBAlistirma.php");

$controller = isset($_GET['islem']) ? $_GET['islem'] : "";
$sonuc = isset($_GET['sonuc']) ? $_GET['sonuc'] : "";
$sure = isset($_GET['sure']) ? $_GET['sure'] : "";
$id = isset($_GET['id']) ? $_GET['id'] : "";
$alistirmaId = isset($_GET['id']) ? $_GET['id'] : "";
/*
echo "<pre>";
var_dump($_GET);
echo "</pre>";
*/


$db = new DBAlistirma();

if($controller === "kaydet" &&
	sanityCheck($sonuc, "string", 1,1) &&
	sanityCheck($sure, "numeric", 0, 10) &&
	$sonuc === "b"){
	session_start();
	$sure = floor($sure);

	$db->istatistikEkle($_SESSION['id'], $id, $sure);
	echo "ok";
	exit();
}

loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_UYE, YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI, YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);


?>

<div class="row">
	<div class="col-md-2">
	</div>

	<?php
	if($controller === "oyun" && strlen($_GET['id'])>0){
		$args = $db->alistirmaGetir($_GET['id']);
		$kategoriID = $db->kategoriIDGetir($_GET["id"]);
		$alistirmalar = $db->ToplamAlistirmalariGetir($kategoriID);

		$deger = array("alistirmalar" => $alistirmalar,"args"=>$args,"alistirmaID"=>$_GET["id"]);

		if(is_array($args)){
		?>
			<div class="col-md-8">
				<?php  loadView("alistirma/board.php", $deger); ?>
			</div>
		<?php
		} else {
			echo "Böyle bir alıştırma mevcut değil.";
		}
	}
	?>
</div>

<?php
include_once("../view/footer.php");
?>
