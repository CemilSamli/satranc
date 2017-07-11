


<?php

	if($args["sayac"]=="1"){
		echo "<table class=table table-hover>";
	}
	echo "<tr><td>".ucwords(strtolower($args["kisiler"]["adSoyad"]))."</td>
	<td>".$args["kisiler"]["kullaniciAdi"]."</td>
	<td> <a href=/web/app/controller/gruplar.php?islem=".$_GET["islem"]."&altislem=".$_GET["altislem"]."&kullaniciAdi=".$args["kisiler"]["kullaniciAdi"]." >EKLE</a> </td> </tr>";


	if($args["toplamsayac"] == $args["sayac"]){
		echo "</table>";
	}

	?>

