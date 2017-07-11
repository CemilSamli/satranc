<?php
	ob_start();
	include_once("../model/utils.php");
	loadView("header.php");

?>
<div class="row">
	<?php

	class haberler{
		/* Veritabanından haberler cekilir  */
		function haber(){
			$turu="haber";
			$haberler=new DBKullanici();

			$adet = $haberler->haberSayisi();

			$sayfa = isset($_GET["s"]) ? $_GET["s"] : 0;
			$limit = 10;
			$ksayisi = $adet;
			$ssayisi = ceil($ksayisi/$limit);
			if(empty($sayfa) || !is_numeric($sayfa)){
				$sayfa = 1;
			}if($sayfa > $ssayisi){
				$sayfa = $ssayisi;
			}if($sayfa < 1){
				$sayfa = 1;
			}
			$baslangic = ($sayfa-1) * $limit;


			$haber=$haberler->haberler($turu,$baslangic,$limit);
			$sayac=1;

			$hata = false;
			$hatalar = array();

			if($haber !== false){
				if($haber !== 0){
						?><div class="col-md-8 col-md-offset-2"><div class="panel panel-danger"><?php
					for($i=0;$i<count($haber);$i++){
						$kayitlar=array("veriler"=>$haber[$i],"sayac"=>$sayac++,
						"toplamsayac"=>count($haber),"turu"=>$turu,"ssayisi"=>$ssayisi);
					?>

						   <?php loadView("haberler/habergoster.php",$kayitlar); ?>
					<?php
					}
						?></div></div><?php
				} else {
					$hatalar[] = "Hiç Haber Yok";
					$hata = true;
				}
			} else {
				$hatalar[] = "Sistemde Hata Olustu! Lütfen Daha Sonra Tekrar Deneyin!";
				$hata = true;
			}

			if($hata === true){
				loadView("hatalar.php",$hatalar);
			}
		}

		/* Veritabanından duyurular cekilir  */
		function duyuru(){//duyurularin gosterildigi kısım
			$turu="duyuru";
			$duyurular=new DBKullanici();

			$adet = $duyurular->duyuruSayisi();

			$sayfa = isset($_GET["s"]) ? $_GET["s"] : 0;
			$limit = 10;
			$ksayisi = $adet;
			$ssayisi = ceil($ksayisi/$limit);
			if(empty($sayfa) || !is_numeric($sayfa)){
				$sayfa = 1;
			}if($sayfa > $ssayisi){
				$sayfa = $ssayisi;
			}if($sayfa < 1){
				$sayfa = 1;
			}
			$baslangic = ($sayfa-1) * $limit;



			$duyuru=$duyurular->duyurular($turu,$baslangic,$limit);
			$sayac=1;
			$hatalar = array();
			$hata = false;

			if($duyuru !== false){
				if($duyuru !== 0){ ?>
					<div class="col-md-8 col-md-offset-2"><div class="panel panel-danger">
					<?php
					for($i=0;$i<count($duyuru);$i++){
						$kayitlar=array("veriler"=>$duyuru[$i],"sayac"=>$sayac++,
						"turu"=>$turu,"ssayisi"=>$ssayisi,"toplamsayac"=>count($duyuru));


						 loadView("haberler/habergoster.php",$kayitlar);

						header("Refresh:60;url=haberler.php?islem=duyurular");
					} ?>
					</div>
					</div>
					<?php
				}
				else{
					$hatalar[] = "Hiç Duyuru Yok!";
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

		/* haberduyuru ekleme sayfasından gelen bilgilere gore haberin veritabanına kaydetme   */
		function haberekle(){
			$baslik=$_POST["baslik"];
			$icerik=$_POST["icerik"];
			$tarih = date("Y.m.d H:i:s");
			$rand=substr(md5(rand()),0,10);
			$resimadi=$_FILES["resim"]["name"];
			$resimtmpadi=$_FILES["resim"]["tmp_name"];
			$type=$_FILES["resim"]["type"];
			$uzanti=end(explode(".",$resimadi));
			$konum="/web/app/assets/image/haberduyururesimler/".$rand.".".$uzanti;
			$turu="haber";
			$hatalar = array();
			
			
			
			if(!empty($baslik) && !empty($icerik)){
				if(!empty($_FILES["resim"]["name"])){
					if($type=="image/png" || $type=="image/jpg" || $type=="image/jpeg"){
						if(move_uploaded_file($resimtmpadi,$konum)){}
						else{ echo "Resim Yuklenirken Hata Olustu"; $konum=""; }
					}
					else{ echo "Resim Sadece png,jpg ve jpeg Turunde Olabilir"; $konum=""; }
				}
				else{
					$konum="";
				}
				$haberduyuru=new DBKullanici();
				$haberduyuru->haberduyuruekle($baslik,$icerik,$turu,$konum,$tarih);



			   header("Location:haberler.php?islem=haberler");

			}
			else{
				$hatalar[] = "Lütfen Gerekli Alanları Boş Bırakmayın!";
				loadView("hatalar.php",$hatalar);
			}
			
		}

		/* haberduyuru ekleme sayfasından gelen bilgilere gore duyurunun veritabanına kaydetme  */
		function duyuruekle(){
			$baslik=$_POST["baslik"];
			$icerik=$_POST["icerik"];
			$turu="duyuru";
			$tarih = date("Y.m.d H:i:s");
			$hatalar = array();

			if(!empty($baslik) && !empty($icerik)){
				$konum="";
				$haberduyuru=new DBKullanici();
				$haberduyuru->haberduyuruekle($baslik,$icerik,$turu,$konum,$tarih);
				header("Location:haberler.php?islem=duyurular");
			}
			else{
				$hatalar[] = "Lütfen Gerekli Alanları Boş Bırakmayın!";
				loadView("hatalar.php",$hatalar);
			}
		}
	}
	$haber=new haberler();
	if($_GET["islem"]=="haberler"){
		$haber->haber();
	}
	else if($_GET["islem"]=="duyurular"){
		$haber->duyuru();
	}
	else if($_SESSION["yetki"] == YETKI_ADMIN && ($_GET["islem"]=="haberekle" || $_GET["islem"]=="duyuruekle")){

		?>
		<div class="col-md-12">
			<div class="well well-lg">
		 <?php   loadView("haberler/haberduyurueklemeformu.php"); ?>
		</div></div>



	<?php
	}
	else if($_SESSION["yetki"] == YETKI_ADMIN && $_GET["islem"]=="haberekleme"){
		$haber->haberekle();
	}
	else if($_SESSION["yetki"] == YETKI_ADMIN && $_GET["islem"]=="duyuruekleme"){
		$haber->duyuruekle();
	}
	else {
		header("Location:giris.php");
	}

	echo "</div>";

	loadView("footer.php");


	ob_end_flush();
?>
