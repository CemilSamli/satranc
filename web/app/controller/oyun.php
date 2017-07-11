<?php
ob_start();
include_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/utils.php");
loadModel("DBOyun.php");
loadModel("DBOnline.php");

loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_UYE, YETKI_UYE_DEGIL, YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI, YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);

?>
<div class="row">
	<div class="col-md-2">
	<?php loadView("sidebar.php"); ?>
</div>



<div class="col-md-8">
	<?php
	$oyunSeviye = -1;
	if(!empty($_GET["l"])){
		if($_GET["l"] == 1 || $_GET["l"] == 2 || $_GET["l"] == 3 || $_GET["l"] == 4 || $_GET["l"] == 0){
			$oyunSeviye = $_GET["l"];
		}
	}
	
	// veritabanında fenstringi cek aioyun.php ye at
	$db = new DBOyun();
	$oyun = new DBOnline();
	
	if(!empty($_GET["oyunid"])){
		$fenString = $oyun->fenStringCek($_GET["oyunid"]);
		$id = $_GET["oyunid"];
		//echo "fen strin = ".$fenString;
		
	}
	else{
		$ilkFen = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";
		if($oyunSeviye != -1){
			$id = $db->oyunEkle($_SESSION["id"],$oyunSeviye);// id-> oyunid, uid->sessionid
		
			//$oyun->ilkHamleOlustur($id);
			$oyun->ilkFenStringEkle($ilkFen,$id);
			$fenString = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";
		}
	}
	
	$fen = rawurlencode ($fenString);
	setCookie("uid",$_SESSION["id"], time()+(60*60*24));
	setCookie("fen", $fen, time()+(60*60*24));
	setCookie("ilkoyunid",$id,time()+(60*60*24));
	
	if(!empty($_GET["oyunid"])){
		$dizi = $oyun->oyunHamleleri($_GET["oyunid"]);//once split parcala sonra parcaladıgın diziye at
		$kazananID = $db->oyunuKazanan($_GET["oyunid"]);
		setCookie("o","o1axb",time()+(60*60*24));
		$kazanan = -1;
		if($kazananID == $_SESSION["id"]){
			$kazanan = 1;
		}else if($kazananID == 0){
			$kazanan = 2;
		}else{
			$kazanan = 0;
		}
		
		for($i=0;$i<count($dizi);$i++){
			$kayitlar=array("seviye"=>$oyunSeviye,"bilgi"=>$dizi[$i],"kazananID"=>$kazanan);
		}
	}else{
		$kayitlar=array("seviye"=>$oyunSeviye,"bilgi"=>-1,"kazananID"=>-1);
	}
	
	if($oyunSeviye != -1){
		loadView("ders/gameAgainstAIBoard.php",$kayitlar);
	}

	?>

	</div>
</div>

<?php
loadView("footer.php");
ob_end_flush();
?>
