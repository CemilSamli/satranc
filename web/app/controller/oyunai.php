<?php
include_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/utils.php");
loadModel("DBOyun.php");
loadModel("DBOnline.php");


$controller = isset($_GET['c']) ? $_GET['c'] : "";

$dbonline = new DBOnline();

if($controller === "ilkhamle" && empty($_COOKIE["o"])){//ilk hamle olusturuldu, fenstring guncellendi

	$oyunid = $_GET["ilkoyunid"];
	$hamle = $_GET["hamle"];
	$fenk = $_GET["fenk"];
	
	$dbonline->ilkHamleOlustur($oyunid,$hamle);
	$dbonline->ilkFenStringEkle($fenk,$oyunid);
}
else if($controller === "hamle"){

	$dboyun = new DBOyun();
	$dboyun->tekHamleEkle($_GET['oyunID'], urldecode($_GET['hamle']));
	$kazananID = -1;
	$dbonline->fenKaydet($_GET['oyunID'],$_GET['fenk'],0,0,0,0);
	$dboyun->oyunBilgisiEkle($_GET['oyunID'], 0, $_GET['uid'], 0, $kazananID, $_GET['sb'], $_GET['sw'], $_GET['pb'], $_GET['pw']);
	if(!empty($_GET["kid"])){
		if($_GET["kid"] == 'w')
			$kazananID=0;
		else if($_GET["kid"] == 'b')
			$kazananID=$_GET["uid"];
		else{
			$kazananID=-2;//berabere ise veritabanına -2 bilgisi kaydedilecek
			//$dboyun->oyunBilgisiEkle($_GET['oyunID'], 0, $_GET['uid'], 0, $kazananID, $_GET['sb'], $_GET['sw'], $_GET['pb'], $_GET['pw']);
			//exit();
		}
	}
	else
		$kazananID=-1;
	//echo "1->".$_GET["kid"];
	//echo "2->".$kazananID;
	$dboyun->oyunBilgisiEkle($_GET['oyunID'], 0, $_GET['uid'], 0, $kazananID, $_GET['sb'], $_GET['sw'], $_GET['pb'], $_GET['pw']);
	
	/*if(!empty($_GET["kid"])){
		if($_GET["kid"] == 'b'){
			$kazananID = 5;//$_GET["uid"];
		}
		else {
			$kazananID = 3;
		}

		$dboyun->oyunBilgisiEkle($_GET['oyunID'], 0, $_GET['uid'], 0, $kazananID, $_GET['sb'], $_GET['sw'], $_GET['pb'], $_GET['pw']);
		//echo "OK";
		// var_dump($_GET);
		exit();
	 }//else{
		// $kazananID=0;
		// $dboyun->oyunBilgisiEkle($_GET['oyunID'], 0, $_GET['uid'], 0, $kazananID, $_GET['sb'], $_GET['sw'], $_GET['pb'], $_GET['pw']);
	// }
	*/
}
if(!empty($_COOKIE["o"])){
	$dboyun = new DBOyun();
	$dboyun->tekHamleEkle($_GET['oyunID'], $_GET['hamle']);
	$kazananID = -1;
	$dbonline->fenKaydet($_GET['oyunID'],$_GET['fenk'],0,0,0,0);
	$dboyun->oyunBilgisiEkle($_GET['oyunID'], 0, $_GET['uid'], 0, $kazananID, $_GET['sb'], $_GET['sw'], $_GET['pb'], $_GET['pw']);
}


//echo "3->".$controller;

if(!isset($_COOKIE['uid'])){
		setcookie("uid", $_SESSION['id'], time() + 60*60*24, "/");
	} else if($_COOKIE['uid'] !== $_SESSION['id']){
		setcookie("uid", $_SESSION['id'],  time() + 60*60*24, "/");
	}

?>