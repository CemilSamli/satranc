<?php
include_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/utils.php");
loadModel("DBDers.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_UYE, YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI, YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);

?>
<div class="row">
	<div class="col-md-2 ">
		<?php loadView("sidebar.php"); ?>
	</div>


	<?php

	if(isset($_GET['c'])){
		$controller = $_GET['c'];
	} else {
		$controller = "";
	}
	$parca = explode(">",$controller);
	if(is_numeric($parca[0])){
		$db = new DBDers();
		$ders = $db->dersGetir($parca[0]);
			//echo "booom<br>";
			//var_dump($ders);
		if($ders !== false){

			$fen = $ders['fen'];
			$hamleler = $db->hamleleriGetir($parca[0]);
			//$aciklama = $db->dersAciklamasi($controller);
			$aciklama = $ders['aciklama'];
			$args = array("hamleler" => $hamleler,
							"aciklama" => $aciklama,
							"fen" => $fen);
		} else {
			$args = array(	"hamleler" => "",
							"aciklama" => "Böyle bir ders yok.",
							"fen" => "start");
		}
		?>
		<div class="col-md-8 ">
			<?php loadView("ders/gamePlayerBoard.php", $args); ?>

		</div>

		<?php
	} else {
		echo "Birşeyler ters gitti gibi duruyo :(";
	}

	?>

</div>

<?php
loadView("footer.php");
?>
