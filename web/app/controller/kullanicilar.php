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
class Kullanicilar{
	
	function kullanicilariGoruntule(){
		
		$kullanicilar = new DBAdmin();
		
		$kullaniciAdedi = $kullanicilar->kullaniciAdedi();
		$adet = $kullaniciAdedi;
		if($adet !== false){
			$sayfa = isset($_GET["s"]) ? $_GET["s"] : 0;
			$limit = 20;
			$sayfa_degerleri = sayfalama($sayfa,$limit,$adet);
		
			$baslangic = $sayfa_degerleri["baslangic"];
			$ssayisi = $sayfa_degerleri["ssayisi"];
			
			$hatalar = array();
			$hata = false;

			$dizi=$kullanicilar->getUsers($baslangic,$limit);
			$sayac=1;
			?>

			<div class="col-md-8">
			<?php
			
			
			if($dizi !== false){
				if($dizi !== 0){
					for($i=0;$i<count($dizi);$i++){
						$kayitlar=array("kullanici"=>$dizi[$i],"sayac"=>$sayac++,"ssayisi"=>$ssayisi,"toplam"=>count($dizi));
						loadView("Admin/kullanicilariGoster.php",$kayitlar);
					}
				}
				else{
					$hatalar[] = "Hiç Kullanýcý Yok!";
					$hata = true;
				}
			}
			else{
				$hatalar[] = "Sistemde Hata Oluþtu! lütfen Daha Sonra Tekrar Deneyin!";
				$hata = true;
			}
				
			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}
			?> </div> <?php
		}
	}
	
	function kullaniciBilgileriGetir($id){
		$kullanicilar = new DBAdmin();
		$dizi = $kullanicilar->kullaniciBilgileri($id);
		
		$hata = false;
		$hatalar = array();
			?>

			<div class="col-md-8">
			<?php
			if($dizi !== false){
				if($dizi !== 0){
				
					loadView("Admin/kullanicibilgileri.php",$dizi);
					
				}
				else{
					$hatalar[] = "Hiç Kullanýcý Yok!";
					$hata = true;
				}
			}
			else{
				$hatalar[] = "Sistemde Hata Oluþtu! lütfen Daha Sonra Tekrar Deneyin!";
				$hata = true;
			}

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}
		
	}
	
}

$object = new Kullanicilar();

if(!empty($_GET["islem"]) && is_numeric($_GET["islem"])){
	$object->kullaniciBilgileriGetir($_GET["islem"]);
}
else{
	$object->kullanicilariGoruntule();
}


echo "</div>";
loadView("footer.php");
ob_end_flush();
?>
