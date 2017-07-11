<?php function IstatistikDropDown(){ ?>
<li class="dropdown-submenu">
	<a href="/web/app/controller/istatislikler.php?id=<?php echo $_SESSION["id"]; ?>">Oyun İstatislikleri</a>
	<ul class="dropdown-menu">
		<li><?php echo "<a href=istatislikler.php?id=".$_SESSION["id"]."&islem=bilgisayarkarsi>Bilgisayar</a>" ?></li>
		<li class="divider"></li>
		<li><?php echo "<a href=istatislikler.php?id=".$_SESSION["id"]."&islem=kullaniciyakarsi>Oyuncu</a>" ?></li>
	</ul>
</li>
<?php } ?>

<?php function YetkilendirmeDropDown(){ ?>
<li class="dropdown-submenu">
	<a href="#">Yetkilendirme</a>
	<ul class="dropdown-menu">
		<li><a href="/web/app/controller/yetkiata.php"> Yetki Ata </a></li>
		<li class="divider"></li>
		<li><a href="/web/app/controller/ogretmenyetkiler.php"> Öğretmen Yetkileri </a></li>
		<li class="divider"></li>
		<li><a href="/web/app/controller/banla.php">Üye Aktif/Pasif</a></li>
	</ul>
</li>
<?php } ?>

<?php function OyunDropDown(){ ?>
<li class="dropdown-submenu">
	<a href="#">Oyunlar</a>
	<ul class="dropdown-menu">
		<li><a href="/web/app/controller/aieskimaclar.php">Eski Maçlar</a></li>
		<li class="divider"></li>
		<li><a href="/web/app/controller/karsiliklioyun.php">Karşılıklı Oyunlar</a></li>
		<li class="divider"></li>
		<li class="dropdown-submenu">
			<a href="/web/app/controller/oyun.php?l=1">Oyun</a>
			<ul class="dropdown-menu">
				<li><a href="/web/app/controller/oyun.php?l=1">1. Seviye</a></li>
				<li class="divider"></li>
				<li><a href="/web/app/controller/oyun.php?l=2">2. Seviye</a></li>
				<li class="divider"></li>
				<li><a href="/web/app/controller/oyun.php?l=3">3. Seviye</a></li>
				<li class="divider"></li>
				<li><a href="/web/app/controller/oyun.php?l=4">4. Seviye</a></li>
			</ul>
		</li>
	</ul>
</li>
<?php } ?>


<?php function OgretmeneOzel(){ ?>

	<?php if($_SESSION['yetki'] == YETKI_OGRETMEN_DERS || $_SESSION['yetki'] == YETKI_OGRETMEN_DERS_OGRENCI){ ?>
		<li><a href="/web/app/controller/alistirmaekle.php">Alıştırma Oluştur</a></li>
		<li><a href="/web/app/controller/dersekle.php">Ders Ekle</a></li>
		<li class="divider"></li>
	<?php } ?>


	<li>
		<a href="/web/app/controller/gruplar.php">
			Gruplar
		</a>
	</li>
	<?php if($_SESSION['yetki'] == YETKI_OGRETMEN_OGRENCI || $_SESSION['yetki'] ==  YETKI_OGRETMEN_DERS_OGRENCI) { ?>
		<li>
			<a href="/web/app/controller/kullaniciekle.php?islem=kullaniciekle">
				Yeni Kullanıcı Ekle
			</a>
		</li>
	<?php } ?>
<?php } ?>


<?php function AdmineOzel(){ ?>
	<li><a href="/web/app/controller/kategoriYonetimi.php"> Kategori Yönetimi </a></li>
	<li class="divider"></li>
	<li><a href="/web/app/controller/kullaniciekle.php?islem=kullaniciekle">Yeni Kullanıcı Ekle</a></li>
	<li class="divider"></li>
	<li><a href="/web/app/controller/kullaniciekle.php?islem=ogretmenekle">Yeni Öğretmen Ekle</a></li>
	<li class="divider"></li>
	<li><a href="/web/app/controller/kullanicilar.php">Kullanıcılar</a></li>
	<li class="divider"></li>
	<li class="dropdown-submenu">
		<a href="#">Haber</a>
		<ul class="dropdown-menu">
			<li><a href="/web/app/controller/haberler.php?islem=haberekle">Haber Ekle</a></li>
			<li class="divider"></li>
			<li><a href="/web/app/controller/haberler.php?islem=duyuruekle">Duyuru Ekle</a></li>
		</ul>
	<li class="divider"></li>
	<li><a href="/web/app/controller/alistirmaekle.php">Alıştırma Ekle</a></li>
	</li>
<?php } ?>

<?php
function MesajKutusu(){
	$okundumu = "hayir";
	$mesajkutusu = new DBKullanici();
	$gelenmesajlar = $mesajkutusu->mesajKutusu($okundumu);
	?>

	<li class="dropdown">
		<a href="/web/app/controller/mesaj_kutusu.php?islem=mesaj_kutusu"  href="#" class="btn btn-default" data-toggle="dropdown" role="button" aria-expanded="false"> <span class="glyphicon glyphicon-envelope"></span> Mesaj Kutusu<span class="badge"><?php if($gelenmesajlar!==false) echo $gelenmesajlar; ?></span> </a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="/web/app/controller/mesaj_kutusu.php?islem=mesaj_kutusu"><span class="glyphicon glyphicon-comment"></span> Yeni Mesaj</a></li>
			<li><a href="/web/app/controller/mesaj_kutusu.php?islem=gelen_mesajlar"><span class="glyphicon glyphicon-arrow-down"></span> Gelen Mesaj</a></li>
			<li><a href="/web/app/controller/mesaj_kutusu.php?islem=gonderilen_mesajlar"><span class="glyphicon glyphicon-arrow-up"></span> Giden Mesaj</a></li>
	   </ul>
	</li>
<?php } ?>

<?php function OgrenciIstatistikleriDropDown(){ ?>
	<li class="dropdown-submenu">
		<a href="/web/app/controller/istatislikler.php?id=<?php echo $_SESSION["id"]; ?>">Öğrenci İstatislikleri</a>
		<ul class="dropdown-menu">
			<li>
				<a href=istatislikler.php?id=<?php echo $_SESSION["id"];?>&islem=bilgisayarkarsi>
					Bilgisayara Karşı
				</a>
			</li>
			<li class="divider"></li>
			<li>
				<a href=istatislikler.php?id=<?php echo $_SESSION["id"];?>&islem=kullaniciyakarsi>
					Oyuncuya Karşı
				</a>
			</li>
			<li class="divider"></li>
			<li>
				<a href="/web/app/controller/dersistatislik.php?id=<?php echo $_SESSION["id"]; ?>">
					Alıştırma
				</a>
			</li>
		</ul>
	</li>
<?php } ?>

<?php function KendiIstatistiklerimDropDown(){ ?>
	<li class="dropdown-submenu">
		<a href="#">İstatisliklerim</a>
		<ul class="dropdown-menu">
			<li>
				<a href=/web/app/controller/IstatislikleriGor.php?id=<?php echo $_SESSION["id"];?>&islem=bilgisayarkarsi&alt_islem=oyun>
					Bilgisayara Karşı
				</a>
			</li>
			<li class="divider"></li>
			<li>
				<a href=/web/app/controller/IstatislikleriGor.php?id=<?php echo $_SESSION["id"];?>&islem=kullaniciyakarsi&alt_islem=oyun>
					Oyuncuya Karşı
				</a>
			</li>
			<li class="divider"></li>
			<li>
				<a href="/web/app/controller/IstatislikleriGor.php?id=<?php echo $_SESSION["id"]; ?>&alt_islem=ders">
					Alıştırma
				</a>
			</li>
		</ul>
	</li>
<?php } ?>
