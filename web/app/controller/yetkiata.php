<?php
ob_start();
include_once("../model/utils.php");
loadModel("DBAdmin.php");
loadView("header.php");

$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array ()
);
yetkiKontrol($cfg);

?>
<div class="row">
	<div class="col-md-2">

	 </div>

	<?php
class YetkiAta{

	/* users fonksiyonu sistemdeki tum kullanıcıları getirir ve istenilen kullanıcının yetki seviyesini degistirir */
	function users(){

		/* uyenin yetki seviyesini degistirmek icin mevcut yetki seviyesi ve adminin yapacagı yetkiler gorulur */
		/* Sistemdeki kullanıcıların sıra numarası ile kullanıcıadı, adsoyad,yetkiseviyesi ve
		durumu(engellimi aktif mi) oldugunu veritabanından ceker */
		$object=new DBAdmin();

		$adet = $object->kullaniciAdedi();
		if($adet !== false){
		$sayfa = isset($_GET["s"]) ? $_GET["s"] : 0;
		$limit = 20;
		$sayfa_degerleri = sayfalama($sayfa,$limit,$adet);
		
		$baslangic = $sayfa_degerleri["baslangic"];
		$ssayisi = $sayfa_degerleri["ssayisi"];

		$hata = false;
		$hatalar = array();
		?>
			<div class="col-md-12">
		<?php
		
		
		$dizi=$object->getUsers($baslangic,$limit);
		$sayac=1;
		if($dizi !== false){
			if($dizi !== 0){
				for($i=0;$i<count($dizi);$i++){
					$kayitlar=array("bilgi"=>$dizi[$i],"sayac"=>$sayac++,"toplamsayac"=>count($dizi),"ssayisi"=>$ssayisi);
					 loadView("Admin/yetkiata.php",$kayitlar);
				} 
				?>
				</div>
				<?php

			}
			else{
				$hatalar[] = "Sistemde Hiç Kullanıcı Yok";
				$hata = true;
			}
		}
		else{
			$hatalar[] = "Sistemde Hata Oluştu.Lütfen Daha Sonra Tekrar Deneyiniz!";
			$hata = true;
		}

		if($hata === true){
			loadView("hatalar.php",$hatalar);
		}
	}
}
}
if($_SESSION["yetki"]=="2"){
$object=new YetkiAta();
$object->users();
if(!empty($_GET["islem"])){
	$adminYetkiAta=new DBAdmin();
	if($_GET["islem"]=="adminyetkiata"){
		$yetki=2;
		$adminYetkiAta->adminYetkiAta($_GET["id"],$yetki);
		header("Location:yetkiata.php");
	}
	else if($_GET["islem"]=="ogryetkiata"){
		$yetki=1;
		$adminYetkiAta->adminYetkiAta($_GET["id"],$yetki);
		header("Location:yetkiata.php");
	}
	else if($_GET["islem"]=="normalyetkiata"){
		$yetki=4;
		$adminYetkiAta->adminYetkiAta($_GET["id"],$yetki);
		header("Location:yetkiata.php");
	}
}
}
?>


</div>
<?php loadView("footer.php");
ob_end_flush();
?>
