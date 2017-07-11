<?php
ob_start();
include_once("../model/utils.php");
loadView("header.php");
loadModel("DBOnline.php");
$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_UYE, YETKI_UYE_DEGIL, YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI, YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);
?>
<div class="container-fluid">
<?php

	class Bildirimler{
	
		function gelenIstekler(){
			
			$gelen = new DBOnline();
			$dizi = $gelen->gelenIstekler();
			$hatalar = array();
			$hata = false;
			?>
			<div class="panel panel-primary">
			<div class="panel-heading">GELEN İSTEKLER</div>
            <div class="panel-body" style="overflow-y: scroll; height:420px">
            <table class="table table-hover">
			<?php
			if($dizi !== false){
			
				if($dizi != 0){
					for($i=0;$i<count($dizi);$i++){
						$kayitlar=array("bilgi"=>$dizi[$i]);
						loadView("karsiliklioyun/gelenistekler.php",$kayitlar);
					}
					?>
					</div></div>
					<?php
				}
			}
			else{
				$hata = true;
				$hatalar[] = "Sistemde Hata Oluştu.Lütfen Daha Sonra Tekrar Deneyiniz!";
			}
			
			?> </table></div></div> <?php

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}
			
		}
		
		function gidenIstekler(){
			$giden = new DBOnline();
			$dizi = $giden->gidenIstekler();
			$hatalar = array();
			$hata = false;
			?>
			<div class="panel panel-primary">
			<div class="panel-heading"> GİDEN İSTEKLER </div>
            <div class="panel-body" style="overflow-y: scroll; height:420px">
            <table class="table table-hover">
			<?php
			if($dizi !== false){
			
				if($dizi != 0){
					for($i=0;$i<count($dizi);$i++){
					
						$kayitlar=array("bilgi"=>$dizi[$i]);
						loadView("karsiliklioyun/gidenistekler.php",$kayitlar);
					}
					?></div></div><?php
				}
			}
			else{
				$hata = true;
				$hatalar[] = "Sistemde Hata Oluştu.Lütfen Daha Sonra Tekrar Deneyiniz!";
			}
			
			?> </table></div></div> <?php

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}
		
		}
	}
	
	$oyun = new Bildirimler();
	$istek = new DBOnline();
	?> <div class="col-md-12"> <div class="col-md-6"> <?php
	$oyun->gelenIstekler();
	?> </div><div class="col-md-6"> <?php
	$oyun->gidenIstekler();
	?> </div></div><?php
	
	if(!empty($_GET["id"])){
		if(is_numeric($_GET["id"])){
			if($_GET["islem"] == "iptal"){ // giden oyun istegi iptal et
				$istek->gidenIstekIptal($_GET["id"]);
				header("Location:bildirim.php");
			}
		}
	
	}	
	
?>
</div>
<?php
include_once("../view/footer.php");
ob_end_flush();
?>