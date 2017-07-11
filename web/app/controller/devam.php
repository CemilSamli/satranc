<?php
	//ob_start();
	include_once("../model/utils.php");
	loadView("header.php");

	$cfg = array(
		"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
		"seviye" => array (YETKI_UYE, YETKI_OGRETMEN, YETKI_OGRETMEN_DERS, YETKI_OGRETMEN_OGRENCI,YETKI_OGRETMEN_DERS_OGRENCI)
	);
	yetkiKontrol($cfg);

?>
<div class="row">

		<?php

	/*  haberlerin ve duyuruların devamını gosterir.  */
	class devam{
		function devami(){
			if(preg_match("/[0-9]+/",$_GET["id"])){
				$id=$_GET["id"];
				$devam=new DBKullanici();
				$devami=$devam->haberduyurudevam($id);
				if($devami!=0){
                    ?>
                    <div class="col-md-8 col-md-offset-2">
                    <?php
                    for($i=0;$i<count($devami);$i++){
					$kayitlar=$devami[$i];
						  loadView("haberler/haberduyurudevami.php",$kayitlar); ?>



	<?php
					} ?>
                    </div>
    <?php
				}
			}
		}
	}
	$devam=new devam();
	$devam->devami();

	echo "</div>";
	loadView("footer.php");
	//ob_end_flush();
?>
