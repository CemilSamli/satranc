<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<script src="/web/app/assets/javascript/jquery-1.10.1.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="/web/app/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="/web/app/assets/css/style.css" rel="stylesheet">


</head>
<body>
<?php
session_start();

include_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/utils.php");
require_once("header_helper.php");
loadModel("DBKullanici.php");
loadModel("DBKategori.php");
loadModel("DBOnline.php");

$gelenmesajlar = 0;
$bekleyenOyunlar = 0;
if(isset($_SESSION['id'])) {

	$dbKategori = new DBKategori();
	$alistirmalar = $dbKategori->kategorileriGetir(1);
	$dersler = $dbKategori->kategorileriGetir(0);

	$oyun = new DBOnline();
	$dizi = $oyun->eskiMaclar($_SESSION["id"],-1);
	if($dizi !== false){
		if($dizi != 0){
			for($i=0;$i<count($dizi);$i++){
				$kayitlar = array("bilgi"=>$dizi[$i]);
				$fenString = $oyun->oyunSirasiGetir($kayitlar["bilgi"]["id"]);
				$parca = explode(" ",$fenString);
				if($kayitlar["bilgi"]["kazananID"] == -1){
					if($kayitlar["bilgi"]["beyazID"] == $_SESSION["id"]){
						if($parca[1] == "w"){
							$bekleyenOyunlar++;
						}
					}
					if($kayitlar["bilgi"]["siyahID"] == $_SESSION["id"]){
						if($parca[1] == "b"){
							$bekleyenOyunlar++;
						}
					}
				}
			}
		}
	}

	$gelenIstekler = $oyun->gelenIstekler();



}

?>
<div class="container-fluid">
	<header class="page-header">
		<nav class="navbar navbar-default">
			<div class="container-fluid">

				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/"><span class="glyphicon glyphicon-knight"></span>Satranç Eğitim  </a>
				</div>


				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">

						<?php
						if(empty($_SESSION["oturum"])){
							echo "<li><a href=/web/app/controller/giris.php>Giriş Yap!</a></li>";
							echo "<li><a href=/web/app/controller/uyeol.php> Aramıza Katıl! </a></li>";
						}
						echo "<li><a href=/web/app/controller/haberler.php?islem=haberler> <span class='glyphicon glyphicon-pushpin'></span>Haberler!</a></li>";
						echo "<li><a href=/web/app/controller/haberler.php?islem=duyurular><span class='glyphicon glyphicon-bullhorn'></span> Duyurular! </a></li>";
						if(!empty($_SESSION["oturum"])){
						echo "<li><a href='/web/app/controller/bildirim.php'><span class='glyphicon glyphicon-envelope' title='Bildirimler' class='badge'><font color='green'>(".count($gelenIstekler).")</font></span></a></li>
						<li><a href='/web/app/controller/karsiliklioyun.php'><span class='glyphicon glyphicon-random' title='Karşılıklı Oyunlar' class='badge'><font color='green'>($bekleyenOyunlar)</font></span></a></li>";
						}

						?>
						</ul>
					</ul>

					<ul class="nav navbar-nav navbar-right">


						<!-- Dersler Başlangıç -->
						<?php

						if(!empty($_SESSION["oturum"])) { ?>
						<li class="dropdown ">
							<a id="dLabel"  data-toggle="dropdown" data-target="#" class="btn btn-default" >
								<span class="glyphicon glyphicon-book"></span> Dersler <span class="caret"></span>
							</a>
							<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">


								<?php
								if(count($dersler) > 0){
									foreach ($dersler as $key => $value) {
									?>
										<li class="dropdown-submenu">
											<a href="#"><?php echo $value[3]; ?></a>
											<ul class="dropdown-menu">
												<li class="divider"></li>
												<?php if(isset($value[0])){ ?>
													<li><a href="/web/app/controller/kategori.php?tur=ders&zorluk=0&id=<?php echo $key;?>">Kolay</a></li>
													<li class="divider"></li>
												<?php } ?>

												<?php if(isset($value[1])){ ?>
													<li><a href="/web/app/controller/kategori.php?tur=ders&zorluk=1&id=<?php echo $key;?>">Orta</a></li>
													<li class="divider"></li>
												<?php } ?>

												<?php if(isset($value[2])){ ?>
													<li><a href="/web/app/controller/kategori.php?tur=ders&zorluk=2&id=<?php echo $key;?>">Zor</a></li>
													<li class="divider"></li>
												<?php } ?>
											</ul>
										</li>

										<?php
											echo "<li class='divider'>asdads</li>";

										?>
								<?php
									}
								}
							}
							?>
							<?php if(isset($value[3])){ ?>
									<li><a href="/web/app/controller/dersekle.php">Ders Ekle</a></li>
									<li class="divider"></li>
							<?php } ?>
							</ul>
						</li>

						<!-- Dersler Son -->
						<!-- Alıştırmalar Başlangıç -->
						<?php

						if(!empty($_SESSION["oturum"])) { ?>

						<li class="dropdown ">
							<a id="dLabel"  data-toggle="dropdown" data-target="#" class="btn btn-default" >
								<span class=" glyphicon glyphicon-bookmark"></span> Alıştırmalar <span class="caret"></span>
							</a>
							<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
								<li class="divider"></li>

								<?php
								if(count($alistirmalar) > 0){
									foreach ($alistirmalar as $key => $value) {
									?>
										<li class="dropdown-submenu">
											<a href="#"><?php echo $value[3]; ?></a>
											<ul class="dropdown-menu">
												<li class="divider"></li>
												<?php if(isset($value[0])){ ?>
													<li><a href="/web/app/controller/kategori.php?tur=alistirma&zorluk=0&id=<?php echo $key;?>">Kolay</a></li>
													<li class="divider"></li>
												<?php } ?>

												<?php if(isset($value[1])){ ?>
													<li><a href="/web/app/controller/kategori.php?tur=alistirma&zorluk=1&id=<?php echo $key;?>">Orta</a></li>
													<li class="divider"></li>
												<?php } ?>

												<?php if(isset($value[2])){ ?>
													<li><a href="/web/app/controller/kategori.php?tur=alistirma&zorluk=2&id=<?php echo $key;?>">Zor</a></li>
													<li class="divider"></li>
												<?php } ?>
											</ul>
										</li>
										<?php
										 echo "<li class='divider'></li>";

										?>

								<?php
									}
								}
							}
							?>
							</ul>
						</li>



<!-- Alıştırmalar Son -->


					<?php if(!empty($_SESSION["oturum"]) && $_SESSION['yetki'] == YETKI_ADMIN){ ?>
						<li class="dropdown ">
							<a id="dLabel"  data-toggle="dropdown" data-target="#" class="btn btn-default" >
								<span class="glyphicon glyphicon-scale"></span> Yönetici <span class="caret"></span>
							</a>
							<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
								
								<?php OyunDropDown(); ?>

								<li class="divider"></li>

								<?php KendiIstatistiklerimDropDown() ?>

								<li class="divider"></li>

								<?php OgrenciIstatistikleriDropDown() ?>

								<li class="divider"></li>

								<?php YetkilendirmeDropDown() ?>

								<li class="divider"></li>

								<?php AdmineOzel() ?>

							</ul>
						</li>
						<?php } ?>
						<?php if(!empty($_SESSION["oturum"]) && ( $_SESSION['yetki'] == YETKI_OGRETMEN_DERS || $_SESSION['yetki'] == YETKI_OGRETMEN_OGRENCI || $_SESSION['yetki'] == YETKI_OGRETMEN || $_SESSION['yetki'] == YETKI_OGRETMEN_DERS_OGRENCI)){ ?>

						<li class="dropdown">
							<a href="#"  data-toggle="dropdown" class="btn btn-default" role="button" aria-expanded="false"><span class="glyphicon glyphicon-blackboard"></span> Öğretmen <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">

								<?php OyunDropDown(); ?>

								<li class="divider"></li>

								<?php KendiIstatistiklerimDropDown() ?>

								<li class="divider"></li>

								<?php OgrenciIstatistikleriDropDown() ?>

								<li class="divider"></li>

								<?php OgretmeneOzel(); ?>

							</ul>
						</li>
						<?php } ?>
						<?php if(!empty($_SESSION["oturum"]) && $_SESSION['yetki'] == YETKI_UYE){ ?>
						<li class="dropdown">
							<a href="#" class="btn btn-default" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-grain"></span> Öğrenci <span class="caret"></span></a>
							<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">

								<?php OyunDropDown(); ?>
								<li class="divider"></li>

								<li><a href="/web/app/controller/odev.php?c=odevlerim">Ödevler</a></li>

								<li class="divider"></li>

								<?php KendiIstatistiklerimDropDown() ?>

							</ul>
						</li>
						<?php } ?>

						<?php if(!empty($_SESSION["oturum"])){?>

							<?php MesajKutusu(); ?>
							<li class="dropdown ">
							<a id="dLabel"  data-toggle="dropdown" data-target="#" class="btn btn-default" >

							<?php if(YETKI_UYE==$_SESSION["yetki"]){?><span class="glyphicon glyphicon-education"></span> <?php } ?><?php if(YETKI_ADMIN==$_SESSION["yetki"]){?><span class="glyphicon glyphicon-user"></span> <?php } ?><?php if(YETKI_OGRETMEN==$_SESSION["yetki"]){?><span class="glyphicon glyphicon-briefcase"></span> <?php } ?><?php echo  ucwords(strtolower($_SESSION["kullaniciadi"])); ?> <span class="caret"></span>

							</a>
								<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
								   <?php
										echo "<li><a href=/web/app/controller/profilim.php?islem=profilim><span class='glyphicon glyphicon-cog'></span> Profil </a> </li>";
								   echo "<li><a href=/web/app/controller/profilim.php?islem=sifredegistir><span class='glyphicon glyphicon-refresh'></span> Parola Değiştir</a></li>";
								  ?><li><a href="/web/app/controller/giris.php?islem=cikis<?php if(!empty($_GET["a"])){ echo "&a=mobil";  } ?>"><span class='glyphicon glyphicon-off'></span> Çıkış </a></li> <?php
								   ?>
									<li></li>
								</ul>
							</li>
<?php
						}
						?>
					</ul>
				</div>
			</div>
		</nav>

	</header>
