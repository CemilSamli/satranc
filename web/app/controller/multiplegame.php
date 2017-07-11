<?php

ob_start();
include_once("../model/utils.php");
include_once("ipbul.php");

loadView("header.php");
loadModel("DBOnline.php");
loadModel("DBOyun.php");


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

	$oyun = new DBOnline();
	$db = new DBOyun();

	if(!empty($_GET["id"]) && !empty($_GET["islem"])){ // oyunu olustulur
		if(is_numeric($_GET["id"]) && $_GET["islem"] == "oyna"){
			$durum = 1;
			$tarihKabul = date("Y-m-d H:i:s");
			$meydanOkunanIP = GetIP();

			$meydanOkuyanID = $oyun->meydanOkuyanIDBul($_GET["id"]);

			$oyunTuru = 1;
			$ilkFen = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";

			//echo $meydanOkuyanID." - ".$_SESSION["id"]." - ".$oyunTuru." - ".$tarihKabul;
			$oyun->oyunOlustur($meydanOkuyanID,$_SESSION["id"],$oyunTuru,$tarihKabul,$_GET["id"]);//beyazID,siyahID,oyunturu,oyunolusturmatarihi

			$oyunID = $oyun->oyunIDBul($_GET["id"]);
			$oyun->ilkHamleOlustur($oyunID);
			$oyun->ilkFenStringEkle($ilkFen,$oyunID);
			$oyun->oyunIslemTamamla($_GET["id"],$oyunID,$durum,$tarihKabul,$meydanOkunanIP);
			header("Location:bildirim.php");
		}
	}
	else if(!empty($_GET["id"])){ // kabul edilen istegin oyununa gider renk ve fen veriabanıdan cek

		// sayfa her yenilendiğinde tekrardan veritabanına ekliyor bunu onle multigame sayfasına yonlendir oorada oylat

		$fenString = $oyun->fenStringCek($_GET["id"]);
		$renk = $oyun->renkBul($_GET["id"]);
		$fen = rawurlencode ($fenString);

		$oyuncuisimleri = $oyun->oyuncuIsimGetir($_GET["id"]);

		$sonRenk = "Beyaz";
		if($fenString != ""){
			$parca = explode(" ",$fenString);
			$sonRenk = $parca[1];
		}
		setCookie("meydanOkuyan", $oyuncuisimleri[0], time()+(60*60*24));
		setCookie("meydanOkunan", $oyuncuisimleri[1], time()+(60*60*24));
		setCookie("meydanOkuyanID",$oyuncuisimleri[2],time()+(60*60*24));
		setCookie("meydanOkunanID",$oyuncuisimleri[3],time()+(60*60*24));


		setCookie("fen", $fen, time()+(60*60*24));
		setCookie("renk", $renk, time()+(60*60*24));

		$dizi = $oyun->oyunHamleleri($_GET["id"]);//once split parcala sonra parcaladıgın diziye at
		$kazananID = $db->oyunuKazanan($_GET["id"]);

		$kazanan = -1;
		if($kazananID == $_SESSION["id"]){
			$kazanan = 1;
		}else if($kazananID == 0){
			$kazanan = 2;
		}else{
			$kazanan = 0;
		}

		$macBilgileri = $oyun->eskiMaclar($_SESSION["id"],$_GET["id"]);

		setCookie("puanBeyaz",$macBilgileri[0]["puanBeyaz"],time()+(60*60*24));
		setCookie("puanSiyah",$macBilgileri[0]["puanSiyah"],time()+(60*60*24));
		setCookie("sureBeyaz",$macBilgileri[0]["sureBeyaz"],time()+(60*60*24));
		setCookie("sureSiyah",$macBilgileri[0]["sureSiyah"],time()+(60*60*24));

		for($i=0;$i<count($dizi);$i++){
			$kayitlar=array("macBilgileri"=>$macBilgileri[$i],"bilgi"=>$dizi[$i],"meydanOkuyan"=>$oyuncuisimleri[0],"meydanOkunan"=>$oyuncuisimleri[1],"renk"=>$sonRenk,"kazananID"=>$kazanan);
		}
		loadView("karsiliklioyun/pvpboard.php",$kayitlar);
	}

?>
</div>
</div>
<?php
include_once("../view/footer.php");
ob_end_flush();
?>
