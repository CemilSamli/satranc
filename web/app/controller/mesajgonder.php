<?php
ob_start();
include_once("../model/utils.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array (YETKI_UYE, YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI,YETKI_OGRETMEN_DERS_OGRENCI)
);
yetkiKontrol($cfg);



?>
<div class="row">
	<div class="col-md-2">

</div>
<?php
class mesajgonder{
	function gonder(){
		$gonderenid=$_SESSION["id"];//gonderenid
		$aliciid=$_GET["id"];
		$mesajgonder=new DBKullanici();

		$hata=false;
		$hatalar=array();
		$kayitlar=$mesajgonder->mesajAliciBilgileri($aliciid);
		if((preg_match("/^[0-9]+$/",$aliciid)) === 0){
			$hata=true;
		}
		if($kayitlar === false){
			$hata=true;
		}
		if(!$_POST){
			$hata=true;
		}
		if($_POST && empty($_POST["konu"])){
			$hatalar[]="Mesajınızı Konu Olmadan Gonderemezsiniz!";
			$hata=true;
		}
		if($_POST && empty($_POST["mesaj"])){
			$hatalar[]="Boş Mesaj Gönderemezsiniz!";
			$hata=true;
		}

		if($hata===false){
			$konu=$_POST["konu"];
			$mesaj=$_POST["mesaj"];
			$tarih = date("Y.m.d H:i:s");
			$okundumu="hayir";
			$mesajgonder->mesajGonder($gonderenid,$aliciid,$konu,$mesaj,$tarih,$okundumu);
			header("Location:mesaj_kutusu.php?islem=gonderilen_mesajlar");

		}
		else{
			if($kayitlar !== false){
				if($kayitlar !== 0){
					$dizi=array("adSoyad"=>$kayitlar[0],"id"=>$_GET["id"]);
					?>
					<div class="col-md-8">
						<div class="well well-lg">
					<?php
					loadView("mesajlar/mesajgonder.php",$dizi);
					for($i=0;$i<count($hatalar);$i++){
						echo $hatalar[$i]."<br>";
					}
					?> </div>  </div> <?php
				}
				else{
					header("Location:mesaj_kutusu.php?islem=mesaj_kutusu");
				}
			}
			else{
				header("Location:mesaj_kutusu.php?islem=mesaj_kutusu");
			}
		}
	}
}

if(!empty($_GET["id"])){
	$idDogrumu = new DBKullanici();
	$donen = $idDogrumu->idDogrumu($_GET["id"]);
	if($donen !== false){
		if($donen !== 0){
			$mesaj=new mesajgonder();
			$mesaj->gonder();
		}
		else{
			header("Location:mesaj_kutusu.php?islem=mesaj_kutusu");
		}
	}
	else{
		header("Location:mesaj_kutusu.php?islem=mesaj_kutusu");
	}
}
else{
	header("Location:mesaj_kutusu.php?islem=mesaj_kutusu");
}
echo "</div>";
loadView("footer.php");
ob_end_flush();
?>
