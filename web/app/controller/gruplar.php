<?php
	ob_start();
	include_once("../model/utils.php");
	loadModel("DBAdmin.php");
	loadView("header.php");

	$cfg = array(
		"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
		"seviye" => array (YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI,YETKI_OGRETMEN_DERS_OGRENCI)
	);
	yetkiKontrol($cfg);

?>
<div class="row">
	<div class="col-md-2">

	</div>
<?php

	class Gruplar{

		/*  Sisteme giris yapan Ogretmenin grup olustuması için form ve mevcut olan gruplarını gosterir  */
		function grup(){
			?>
			<div class="col-md-8">
			<?php loadView("Ogretmen/grupolustur.php"); ?>

	<?php
			$object=new DBAdmin();
			$dizi=$object->mevcutGruplar($_SESSION["id"]);
			$sayac=1;
			$hata = false;
			$hatalar = array();

			if($dizi !== false){
				if($dizi !== 0){
					loadView("Ogretmen/mevcutgruplar.php",array("gruplar" => $dizi));
				}
				else{
					$hatalar[] = "Hiç Grup Yok!";
					$hata = true;
				}
			}
			else{
				$hatalar[] = "Sistemde Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin!";
				$hata = true;
			}

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}

		}

		/*  Sisteme giris yapan ogretmenin secili olan grubunda duzenleme yapmasına saglayan fonksiyon */
		function mevcutgrupUye(){
			$object=new DBAdmin();
			$dizi=$object->grupUyeGetir($_GET["altislem"]);
			$sayac=1;
			$hatalar = array();
			$hata = false;

			?>
            </div>
            <div class="row">


			<div class="col-md-8 col-md-offset-2">
			<?php
			if($dizi !== false){
				loadView("Ogretmen/mevcutGrupUyeleri.php",array('uyeler' => $dizi));  // LİSTELEME

			} else{
				$hatalar[] = "Sistemde Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin!";
				$hata = true;
			}

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}

			?>
			</div>
            </div>
			<?php
		}

		/* Sisteme giris yapan ogretmenin yeni bir grup  olusturmasını saglar */
		function grupOlustur(){

			$hatalar = array();

			if(!empty($_POST["grupAdi"])){
				$object=new DBAdmin();
				$object->grupOlustur($_POST["grupAdi"]);
				header("Location:gruplar.php");
			}
			else{
				?>
				<div class="col-md-8">
				<?php loadView("Ogretmen/grupolustur.php"); ?>

		<?php
				$hatalar[] = "Grup Adı Boş Olamaz!";
				loadView("hatalar.php",$hatalar);
			}

		}
	}



	$object=new Gruplar();
	$mevcutGrup=new DBAdmin();
	$hata = false;
	$hatalar = array();

	if(!empty($_GET["islem"])){
		if($_GET["islem"]=="grupolustur"){
			$object->grupOlustur();
		}
		else if($_GET["islem"]=="mevcutgruplar"  && !empty($_GET["altislem"])){
			?> <div class="col-md-8"> <?php
			loadView("test.php","grup_uye_ekleme");  // EKLEME

			/* get islemi ile alınan altislem(grupid) den oyle bir grubun olup olmadıgının kontroulu */
			if($mevcutGrup->grupIDKontrol($_GET["altislem"])){
				/* girilen altislemdeki grup id giris yapan hocanın grubumu */
				if($mevcutGrup->grupIDHocaKontrol($_GET["altislem"])){
					$object->mevcutgrupUye();
					if(!empty($_GET["kullaniciAdi"])){//uye ekleme

						$kullaniciVarmi = $mevcutGrup->grupKullaniciVarmi($_GET["kullaniciAdi"],$_GET["altislem"]);

						if($kullaniciVarmi !== false){
							if($kullaniciVarmi !== -1){
								if($kullaniciVarmi !== 0){
									$mevcutGrup->grupUyeEkleme($kullaniciVarmi,$_GET["altislem"]);
									header("Location:gruplar.php?islem=mevcutgruplar&altislem=".$_GET["altislem"]);
								}
								else{
									$hatalar[] = "Aynı Kullanıcı Grupta Zaten Var!";
									$hata = true;
								}
							}
							else{
								$hatalar[] = "Böyle bir Kullanıcı Yok!";
								$hata = true;
							}
						}
						else{
							$hatalar[] = "Sistemde Hata Oluştu! Lütfen Daha Sonra Tekrar Deneyin!";
							$hata = true;
						}
					}
				}
				else{
					$hatalar[] = "Birşeyler Ters Gidiyor Sanki!";
					$hata = true;
				}
			}
			else{
				$hatalar[] = "Birşeyler Ters Gidiyor Sanki!";
				$hata = true;
			}

			if(!empty($_GET["id"])){//uye silme
				$mevcutGrup->grupUyeSil($_GET["id"]);
				header("Location:gruplar.php?islem=mevcutgruplar&altislem=".$_GET["altislem"]);
			}

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}?> </div> <?php

		}
		else{
			header("Location:gruplar.php");
		}
	}
	else{
		$object->grup();
	}
	if(!empty($_GET["grupid"])){
		if(!empty($_GET["altislem"])){
			if($_GET["altislem"] === "sil"){
				$mevcutGrup->grupSil($_GET["grupid"]);
				header("Location:gruplar.php");
			}
			else{
				echo "Birşeyler Ters Gidiyor Sanki !";
			}
		}
		else{
			header("Location:gruplar.php");
		}
	}


	loadView("footer.php");
	ob_end_flush();
?>
