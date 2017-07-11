<?php
ob_start();
include_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/utils.php");
loadModel("pgn.php");
loadModel("DBDers.php");
loadModel("PgnParser/PgnParser.php");
loadModel("PgnParser/Game.php");
loadModel("PgnParser/Util.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);

?>
<div class="row">
	<div class="col-md-2">
	</div>

<?php

if(isset($_POST['c'])){
	$controller = $_POST['c'];
} else {
	$controller = "";
}

$dbK = new DBKategori();

switch($controller){
	case "tahtaYukleme" : {
		$hamleler = isset($_POST["pgn"]) ? $_POST["pgn"] : "";

		$db = new DBDers();
		$id = $db->dersEkle($_POST["fen"], "", substr($_POST["aciklama"], 0, 1000));

		$db->hamleEkle($id, $hamleler);
		echo $_POST["kategori"]." - ".$_POST["zorluk"];
		$dbK->elemanEkle($_POST['kategori'], $_POST['zorluk'], 0, $id);
		header("location: dersekle.php");
		break;
	} case "pgnYukleme" : {
		$parser = new PgnParser($_FILES["pgnDosya"]["tmp_name"]);
		$games = $parser->getGames();
		$i =0;
		foreach ($games as $game) {
			$db = new DBDers();
			$id = $db->dersEkle("rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1", "");
			$args["games"][$id] = $game;
			$moves = $game->getMoves();
			$moves = explode(" ", $moves);
			$db->hamleEkle($id, implode(";;;", $moves));
		}

		$kategoriler = $dbK->kategorileriGetir(0, true);
		$ktgr = array();
		foreach ($kategoriler as $key => $value) {
			$ktgr[$key] = $value[3];
		}
		$args["kategoriler"] = $ktgr;
		loadView("ders/detayBilgi.php", $args);
		break;
	} case "pgnDetay" : {
		$db = new DBDers();
		foreach ($_POST['ekle'] as $key => $value) {
			if($value == 1){
				$dbK->elemanEkle($_POST['kategori'][$key], $_POST['zorluk'][$key], 0, $key);
			} else {
				$db->dersSil($key);
				$db->hamleleriSil($key);
			}
		}

		header("location: dersekle.php");
		break;
	} default : {
		?>
			<div class="col-md-8">
		<?php
		$db = new DBKategori();
		$kategoriler = $db->kategorileriGetir(0, true);
		$ktgr = array();
		foreach ($kategoriler as $key => $value) {
			$ktgr[$key] = $value[3];
		}

		$args = array("id" => $_SESSION['id'], "kategoriler" => $ktgr);


		loadView("ders/creationPage.php",$args); ?>
			</div>
		<?php
	}
}
?>

</div>


<?php
loadView("footer.php");
ob_end_flush();
?>
