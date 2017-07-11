	<?php
	if($args["veriler"]["turu"] == "haber"){ ?>
	<form action="/web/app/controller/haberduzenle.php?islem=haberduzenle&id=<?php echo $args["veriler"]["id"]; ?>" method="post" enctype="multipart/form-data">
	<?php
	}
	else{
		?>
		<form action="/web/app/controller/haberduzenle.php?islem=duyuruduzenle&id=<?php echo $args["veriler"]["id"]; ?>" method="post">
		<?php
	}
	?> 
	BASLIK<br><textarea name="baslik" cols="70" rows="5"><?php echo $args["veriler"]["baslik"]; ?></textarea><br><br>
	ICERIK<br><textarea name="icerik" cols="70" rows="20"><?php echo $args["veriler"]["icerik"]; ?></textarea><br><br>
	<?php if($args["veriler"]["turu"] == "haber"){ ?>
		RESIM EKLE<input type="file" name="resim"><br><br>;
		<?php
	}
	?>
	<input type="submit" value="Düzenle">
	</form>
