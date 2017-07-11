Engelleme Süresi Gün Cinsinden :
<form class="form-horizontal" action="/web/app/controller/banlagun.php?islem=<?php echo $_GET["islem"]; ?>&id=<?php echo $_GET["id"]; ?>" method="post">
	<?php
	echo "<select name=banlamagun multiple class=form-control>";
	for($i=1;$i<=45;$i++){
		echo "<option value=$i>$i</option>";
	}
	echo "</select>";
	?>
	<br>
	<input  type="submit" value="ENGELLE" class="btn btn-danger btn-block">

    <a href="/web/app/controller/banla.php" type="button" class="btn btn-info btn-block">Geri Dön</a>

</form>
