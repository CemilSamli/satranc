<?php
include_once("../model/utils.php");
loadModel("DBOnline.php");
loadModel("DBOyun.php");
// oyunidye gore feni  kaydet

$oyunid = $_GET['id'];
$fen = $_GET['fenk'];
$hamle = $_GET['hamle'];
$sb = $_GET["sb"];
$sw = $_GET["sw"];
$puan = $_GET["puan"];
$renk = $_GET["renk"];
$kid = isset($_GET['kid']) ? $_GET['kid']:"";

$oyun = new DBOnline();
$oyun->fenKaydet($oyunid,$fen,$sb,$sw,$puan,$renk);

$db = new DBOyun();
$db->tekHamleEkle($oyunid, $hamle);

if($kid != ""){

	$kazananID = -1;
	$meydanOkuyan = $_COOKIE["meydanOkuyanID"];
	$meydanOkunan = $_COOKIE["meydanOkunanID"];

	if($_GET["kid"] == 'b'){
		$kazananID = $meydanOkunan;
	}
	else {
		$kazananID = $meydanOkuyan;
	}
	$db->kazananEkle($_GET['id'], $kazananID);
	echo "OK";

}

?>
