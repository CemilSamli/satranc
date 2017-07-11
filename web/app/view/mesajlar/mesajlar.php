<?php

if($args["sayac"] == "1"){
	echo "<table class=table table-hover>";
}
if($args["kayitlar"]["mesajiSilenID"] !== $_SESSION["id"] || $args["kayitlar"]["aliciID"] === $args["kayitlar"]["gonderenID"]){
$asma=$args["sayac"];

echo "<tr><td>".ucwords(strtolower($args["kayitlar"]["adSoyad"]))."</td>
<td><a  data-toggle=modal data-target=.abc".$asma.">".$args["kayitlar"]["baslik"]."</a></td>
<td>".$args["kayitlar"]["tarihGonderilen"]."</td>
<td><a href=/web/app/controller/mesajsil.php?islem=".$_GET["islem"]."&id=".$args["kayitlar"]["id"].">Sil</a></td>
<td><a href=/web/app/controller/mesajgonder.php?islem=cevapver&id=";
if($_GET["islem"] == "gonderilen_mesajlar"){
	echo $args["kayitlar"]["aliciID"];
}

else if($_GET["islem"] == "gelen_mesajlar"){
	echo $args["kayitlar"]["gonderenID"];
}

echo ">Cevap Ver</a></td><td>";
if($args["kayitlar"]["okundumu"] == "evet"){
	?> <img src="/web/app/assets/image/okundu.png"> <?php
}
else if($args["kayitlar"]["okundumu"] == "hayir"){
	?> <img src="/web/app/assets/image/okunmadi.jpg"> <?php
}


echo "</td></tr>";


if($args["toplamsayac"] == $args["sayac"]){
	echo "</table>";
	if($args["ssayisi"] > 1) {   ?>
<nav>
<ul class="pagination">


<?php
	for($i=0;$i<$args["ssayisi"];$i++){
		?>
		<li>
			<?php
		echo "<a href=/web/app/controller/mesaj_kutusu.php?islem=".$_GET["islem"]."&s=".($i+1).">".($i+1);

		echo "</a></li>";
	}

			?>

</ul>
</nav>
		<?php   }
	}
}
?>


<div class="modal fade abc<?php echo $asma ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><?php echo $args["kayitlar"]["baslik"] ?></h3>
                <h5> <?php echo $args["kayitlar"]["adSoyad"]; ?></h5>
                <h5><?php echo $args["kayitlar"]["tarihGonderilen"]; ?></h5>
            </div>
            <div class="modal-body">
                <p><?php echo $args["kayitlar"]["mesaj"]; ?></p>
            </div>
        </div>
    </div>
</div>
