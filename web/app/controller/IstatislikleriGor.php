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



class IstatislikleriGor{

	function OyunIstatislikGoster($id){
		$object = new DBIstatislik();

		$hatalar = array();
		$hata = false;

		$adet = $object->oyunIstatislikleriSayisi($id,$_GET["islem"]);
		if($adet !== false){
			$sayfa = isset($_GET["s"]) ? $_GET["s"] : 0;
			$limit = 20;
			$sayfa_degerleri = sayfalama($sayfa,$limit,$adet);

			$baslangic = $sayfa_degerleri["baslangic"];
			$ssayisi = $sayfa_degerleri["ssayisi"];

			$dizi=$object->oyunIstatislikleriGetir($id,$_GET["islem"],$baslangic,$limit);

			$toplamOyun = $object->toplamOyunSayisi($id,$_GET["islem"]);
			$toplamKazanim = $object->toplamOyunKazanimi($id,$_GET["islem"]);
			$toplamDevamEden = $object->devamEdenOyunSayisi($id,$_GET["islem"]);
			$toplamBerabere =  $object->berabereOyunSayisi($id,$_GET["islem"]);
			
			$toplamKaybedilen = $toplamOyun-$toplamKazanim-$toplamDevamEden-$toplamBerabere;
			?>
			<div class="panel panel-info"><div class="panel-heading">Genel Durum</div>
			<div class="panel-body">

			<?php
			echo "Toplam Oyun Sayısı: </b> $toplamOyun &nbsp&nbsp&nbsp&nbsp&nbsp
			<b><font color=green>Kazanılan Oyun Sayısı: </b> $toplamKazanim</font> &nbsp&nbsp&nbsp&nbsp&nbsp<b><font color=red>
			Kaybedilen Oyun Sayısı: </b>$toplamKaybedilen</font> &nbsp&nbsp&nbsp&nbsp&nbsp <b><font color=skyblue>
			Berabere Oyun Sayısı:</b>$toplamBerabere</font> &nbsp&nbsp&nbsp&nbsp&nbsp <b><font color=#D9954E>Devam Eden Oyun Sayısı:</b> $toplamDevamEden</font>
			</div></div>";

			if($dizi !== false){
				if($dizi !== 0){
					$sayac=1;
					for($i=0;$i<count($dizi);$i++){
						$kayitlar=$dizi[$i];

						$siyahKullanici=$object->iddenKullaniciBul($kayitlar["siyahID"]);
						$beyazKullanici=$object->iddenKullaniciBul($kayitlar["beyazID"]);
						$kazananKullanici=$object->iddenKullaniciBul($kayitlar["kazananID"]);



						if($siyahKullanici !== false && $beyazKullanici !== false && $kazananKullanici !== false){
							$tumVeriler=array("veriler"=>$dizi[$i], "siyahKullanici"=>$siyahKullanici,"beyazKullanici"=>$beyazKullanici,
							"kazananKullanici"=>$kazananKullanici,"sayac"=>$sayac++,"toplamsayac"=>count($dizi),"ssayisi"=>$ssayisi);

							loadView("istatislik/oyunistatislikleri.php",$tumVeriler);
						}
						else{
							?>
							<div class="alert alert-info" role="alert">
								Sistemde Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin!
							</div>
							<?php
						}
					}
				}
				else{
					$hatalar[] = "Hiç Maç Yok!";
					$hata = true;
				}
			}
			else{
				$hatalar[] = "Sistemde Hata Oluştu. Lütfen Daha Sonra Tekrar Deneyin!";
				$hata = true;
			}

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}
		}
	}

	/* Ders istatislikeri gosterir */
	function DersIstatislikGor($id){
		$object=new DBIstatislik();

		$hatalar = array();
		$hata = false;

		$adet = $object->dersIstatislikleriSayisi($id);
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

			$dizi=$object->dersIstatislikleriGetir($id,$baslangic,$limit);

			if($dizi !== false){
				if($dizi !== 0){
					$sayac=1;
					for($i=0;$i<count($dizi);$i++){
						$kayitlar=array("bilgiler"=>$dizi[$i],"sayac"=>$sayac++,
						"toplamsayac"=>count($dizi),"ssayisi"=>$ssayisi);
						loadView("istatislik/dersistatislikleri.php",$kayitlar);
					}
				}
				else {
					$hatalar[] = "Hiç Alıştırma Yok!";
					$hata = true;
				}
			}
			else{
				$hatalar[] = "Sistemde Hata Oluştu. Lütfen Daha Sonra Tekrar Deneyin!";
				$hata = true;
			}

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}
		}
	}
}

$object=new IstatislikleriGor();
if(!empty($_GET["id"]) && $_SESSION["id"] === $_GET["id"]){
	if( !empty($_GET["alt_islem"]) ){
		if(!empty($_GET["kulid"])){
			if($_GET["alt_islem"] === "ders"){
				$object->DersIstatislikGor($_GET["kulid"]);
			}
			if($_GET["alt_islem"]=="oyun" && !empty($_GET["islem"])){
				if($_GET["islem"]=="bilgisayarkarsi" || $_GET["islem"]=="kullaniciyakarsi"){
					$object->OyunIstatislikGoster($_GET["kulid"]);
				}else{
					header("Location:istatislikler.php");
				}
			}
		} else {
			if($_GET["alt_islem"] === "ders"){
				echo "<br>BOOOM!!!</br>";
				$object->DersIstatislikGor($_GET["id"]);
			} else if($_GET["alt_islem"] === "oyun"){
				$object->OyunIstatislikGoster($_GET["id"]);
			}
		}
	}
}



loadView("footer.php");
ob_end_flush();
?>
