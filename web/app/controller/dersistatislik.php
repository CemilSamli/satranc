<?php
ob_start();
include_once("../model/utils.php");
loadModel("DBIstatislik.php");
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
class DersIstatislik{
	/* giris yapan hocanın grubundaki ogrencileri gosterir */
	function grubundami(){
		$object=new DBIstatislik();
		$dizi=$object->grupdami($_GET["id"]);

		$hata = false;
		$hatalar = array();

		$sayac=1;
		if($dizi !== false){
			if($dizi !== 0){
				for($i=0;$i<count($dizi);$i++){
					$kayitlar=array("kullanici"=>$dizi[$i],"sayac"=>$sayac++,"toplamsayac"=>count($dizi));
					loadView("Ogretmen/kullanicilariGoster.php",$kayitlar);
				}
				if(!empty($_GET["kulid"]) && !empty($_GET["id"]) && !empty($_GET["islem"])){
					header("Location:IstatislikleriGor.php?id=".$_GET["id"]."&kulid=".$_GET["kulid"]."&alt_islem=ders");
				}
			}
			else{
				$hatalar[] = "Hiç Öğrenciniz Yok!";
				$hata = true;
			}
		}
		else{
			$hatalar[] =  "Sistemde Hata Oluştu Lütfen Daha Sonra Tekrar Deneyin!";
			$hata = true;
		}

		if($hata === true){
			loadView("hatalar.php",$hatalar);
		}
	}
	function kullaniciGetir(){
		$kullanicilar=new DBIstatislik();

		$kullaniciAdedi = $kullanicilar->kullaniciAdedi();
		$adet = $kullaniciAdedi;
		if($adet !== false){
		$sayfa = isset($_GET["s"]) ? $_GET["s"] : 0;
		$limit = 20;
		$ksayisi = $adet;
		$ssayisi = ceil($ksayisi/$limit);
		if(empty($sayfa) || !is_numeric($sayfa)){
			$sayfa = 1;
		}if($sayfa > $ssayisi){
			$sayfa = $ssayisi;
		}if($sayfa < 1){
			$sayfa = 1;
		}
		$baslangic   = ($sayfa-1) * $limit;

		$hata = false;
		$hatalar = array();

		$dizi=$kullanicilar->kullanicilariGetir($baslangic,$limit);
		$sayac=1;
		if($dizi !== false){
			if($dizi !== 0){
				?>
				<div class="col-md-8">
				<?php
				for($i=0;$i<count($dizi);$i++){
					$kayitlar=array("kullanici"=>$dizi[$i],"sayac"=>$sayac++,
					"toplamsayac"=>count($dizi),"ssayisi"=>$ssayisi);

					loadView("istatislik/dersKullanicileriGetir.php",$kayitlar);

				}
				?>
				</div>
				<?php
			}
			else{
				$hatalar[] = "Sistemde Hiç Kullanıcı Yok";
			}
		}
		else{
			$hatalar[] = "Sistemde Hata Oluştu.Lütfen Daha Sonra Tekrar Deneyiniz!";
		}
	}
	if($hata === true){
		loadView("hatalar.php",$hatalar);
	}
}
}

if(!empty($_GET["id"])){
	if($_SESSION["yetki"]=="2" ||
	$_SESSION["id"]==$_GET["id"] ||
	$this->grubundami()){

	$object=new DersIstatislik();
	if($_SESSION["yetki"]=="2"){
		$dersistatislik = new DersIstatislik();
		$dersistatislik->kullaniciGetir();

		if(!empty($_GET["kulid"]) && !empty($_GET["id"])){
			header("Location:IstatislikleriGor.php?id=".$_GET["id"]."&kulid=".$_GET["kulid"]."&alt_islem=ders");
		}
	}

	if($_SESSION["yetki"]=="4"){
		header("Location:IstatislikleriGor.php?id=".$_GET["id"]."&alt_islem=ders");
	}
	if($_SESSION["yetki"]=="1" || $_SESSION["yetki"]=="8" || $_SESSION["yetki"]=="16" || $_SESSION["yetki"]=="32"){
		$object->grubundami();
	}

	}
}



loadView("footer.php");
ob_end_flush();
?>
