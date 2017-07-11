<?php

	if($args["sayac"] == 1){
	?>
		<table  id="example" class="table table-striped table-bordered nowrap table table-hover">
		<thead>
		<tr><th>Ad Soyad</th><th>Yetki</th><th>Admin Yetkisi</th><th>Öğretmen Yetkisi</th><th>Kullanıcı Yetkisi</th><th>Durum</th><th>Aktif Olma Tarihi</th></tr></thead>

		<tbody>
	<?php
	}


	echo "<tr><td>".ucwords(strtolower($args["bilgi"]["adSoyad"]))."</td>";
	if($args["bilgi"]["yetki"]=="1" || $args["bilgi"]["yetki"]=="8" || $args["bilgi"]["yetki"]=="16" || $args["bilgi"]["yetki"]=="32"){
		echo "<td>Öğretmen</td>
		<td><a href=yetkiata.php?islem=adminyetkiata&id=".$args["bilgi"]["id"].">Yetki Ata</a></td>
		<td>Mevcut Yetki</td>
		<td><a href=yetkiata.php?islem=normalyetkiata&id=".$args["bilgi"]["id"].">Yetki Ata</a></td>";
	}
	else if($args["bilgi"]["yetki"]=="2"){
		echo "<td>Admin</td>
		<td>Mevcut Yetki</td>
		<td><a href=yetkiata.php?islem=ogryetkiata&id=".$args["bilgi"]["id"].">Yetki Ata</a></td>
		<td><a href=yetkiata.php?islem=normalyetkiata&id=".$args["bilgi"]["id"].">Yetki Ata</a></td>";
	}
	else if($args["bilgi"]["yetki"]=="4"){
		echo "<td>Normal Kullanıcı</td>
		<td><a href=yetkiata.php?islem=adminyetkiata&id=".$args["bilgi"]["id"].">Yetki Ata</a></td>
		<td><a href=yetkiata.php?islem=ogryetkiata&id=".$args["bilgi"]["id"].">Yetki Ata</a></td>
		<td>Mevcut Yetki</td>";
	}
	$engelSuresi=strtotime($args["bilgi"]["engelSuresi"]);
	$bugun=strtotime(date("Y:m:d H:i:s"));
	if($engelSuresi>$bugun){
		echo "<td>Engelli</td>";
	}
	else{
		echo "<td>Aktif</td>";
	}
	echo "<td>".$args["bilgi"]["engelSuresi"]."</td></tr>";

	if($args["toplamsayac"] == $args["sayac"]){
	?>
	</tbody></table>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#example').DataTable();
		} );
	</script>
	<?php
		}
	?>