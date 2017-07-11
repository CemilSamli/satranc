<?php

if($args["sayac"]==1){
	if($args["turu"]=="haber")
		echo "<head><b><i><center>HABERLER</center></b></i></head>";
	else if($args["turu"]=="duyuru")
		echo "<head><b><i><center>DUYURULAR</center></b></i></head>";
}


echo "<table width=600><tr><td>".strtoupper($args["veriler"]["baslik"])."</td></tr>";
if($args["veriler"]["resim"]!=""){
	echo "<tr><td><img src=".$args["veriler"]["resim"]." height=50 width=200></td></tr>";
}
echo "<tr><td>".substr($args["veriler"]["icerik"],0,300)."<a href=devam.php?id=".$args["veriler"]["id"].">&nbsp&nbspDevami</a></td></tr>";
echo "<tr><td>Haber Eklenme Tarihi:".$args["veriler"]["tarih"]."</td></tr>";

if($args["toplam"]-1 == $args["sayac"]){
	echo "asdasd";
	echo "</table>";
}


?>
