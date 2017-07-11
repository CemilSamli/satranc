<?php

	if($args["sayac"]=="1"){
		echo "<table class=table border=1><th>Ad Soyad</th><th>Kullanıcı Adı</th></tr>";
	}
	echo "<tr><td><a href=/web/app/controller/IstatislikleriGor.php?id=".$_GET["id"]."&kulid=".$args["kullanici"]["id"]."&alt_islem=";
	if(!empty($_GET["islem"])){
		if($_GET["islem"]=="bilgisayarkarsi" || $_GET["islem"]=="kullaniciyakarsi"){
			echo "oyun&islem=".$_GET["islem"];
		}
		else{
			echo "ders";
		}
	}
	else{
		echo "ders";
	}
	echo ">".$args["kullanici"]["adSoyad"]."</a></td><td>".$args["kullanici"]["kullaniciAdi"]."</td></tr>";


?>
