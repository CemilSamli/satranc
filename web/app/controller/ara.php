<?php

	include_once("../model/utils.php");
	loadModel("DBAdmin.php");
	$object=new DBAdmin();
	$adet = $object->kullaniciAdedi();

	$sayfa = isset($_GET["s"]) ? $_GET["s"] : 0;
	$limit = 20;

	$sayfa_degerleri = sayfalama($sayfa,$limit,$adet);

	$baslangic = $sayfa_degerleri["baslangic"];
	$ssayisi = $sayfa_degerleri["ssayisi"];
	$dizi  =array();
	$uye = array();
	if(isset($_GET["s"])){
		if($_GET["s"] == "arama" && $_POST["arama"] !=""){
			$arama = $_POST["arama"];
			$dizi = $object->aramaYap($arama);
			$sayac = 1;

			if($dizi !== false){
				if($dizi !== 0){?>

				<div class="col-md-8">
					<?php
						for($i=0;$i<count($dizi);$i++){

							if(!empty($_GET["sayfa"])){
								if($_GET["sayfa"] == "banla"){
									$kayitlar=array("bilgi"=>$dizi[$i],"sayac"=>$sayac++,"toplamsayac"=>count($dizi),"ssayisi"=>$ssayisi);
									loadView("Admin/banla.php",$kayitlar);
								}
								if($_GET["sayfa"] == "mesaj_kutusu"){
									$kayitlar=array("kisiler"=>$dizi[$i],"sayac"=>$sayac++,"toplamsayac"=>count($dizi),"ssayisi"=>$ssayisi);
									loadView("mesajlar/mesajkutusu.php",$kayitlar);
								}
								if($_GET["sayfa"] == "yetkiata"){
									$kayitlar=array("bilgi"=>$dizi[$i],"sayac"=>$sayac++,"toplamsayac"=>count($dizi),"ssayisi"=>$ssayisi);
									loadView("Admin/yetkiata.php",$kayitlar);
								}
								if($_GET["sayfa"] == "istatislikler"){
									$kayitlar=array("kullanici"=>$dizi[$i],"sayac"=>$sayac++,"toplamsayac"=>count($dizi),"ssayisi"=>$ssayisi);
									loadView("istatislik/kullanicilariGoster.php",$kayitlar);
								}
								if($_GET["sayfa"] == "oyuncular"){
									$kayitlar=array("bilgi"=>$dizi[$i],"sayac"=>$sayac++,"toplam"=>count($dizi),"oyuncular"=>"1");
									loadView("karsiliklioyun/oyuncular.php",$kayitlar);
								}
								$yetkikontrol=array("kullaniciyetki"=>$dizi[$i]);
								if($_GET["sayfa"] == "ogretmenyetkileri"){
									if($yetkikontrol["kullaniciyetki"]["yetki"] != "4" && $yetkikontrol["kullaniciyetki"]["yetki"] != "2"){
										$kayitlar=array("veriler"=>$dizi[$i],"sayac"=>$sayac++,"toplam"=>count($dizi),"ssayisi"=>$ssayisi);
										loadView("Admin/ogretmenyetkiler.php",$kayitlar);
									}
								}
							}

						}?>
			</div>
			<?php
				}
				else{
					$hatalar[] = "Sistemde Hi� Kullan�c� Yok!";
					$hata = true;
				}
			}

			$uye = $object->grupUyeAra($arama);
			if($uye !== false){
				if($uye !== 0){?>

				<div class="col-md-8">
					<?php
					for($i=0;$i<count($uye);$i++){
						if(!empty($_GET["sayfa"])){
							if($_GET["sayfa"] == "grup_uye_ekleme"){
								$uyeler = array("kisiler"=>$uye[$i],"sayac"=>$sayac++,"toplamsayac"=>count($uye));
								loadview("Ogretmen/grupUyeEkle.php",$uyeler);
							}
						}
					}?>
			</div>
			<?php

				}
			}

		}
	}

?>
