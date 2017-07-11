<?php

	if($args["sayac"]==1){
	?>
		<table  id="example" class="table table-striped table-bordered nowrap table table-hover">
		<thead>
		<tr><th>Ad Soyad</th><th>Yetki</th><th>Durum</th><th>Engelle</th><th>Engeli Kaldır</th><th>Aktif Olma Tarihi</th></tr>
		</thead>

		<tbody>
	<?php
	}
	echo "<tr><td>".ucwords(strtolower($args["bilgi"]["adSoyad"]))."</td>";

	if($args["bilgi"]["yetki"]=="1"){//ogretmen
		echo "<td>Normal Öğretmen</td>";
	}
	else if($args["bilgi"]["yetki"]=="8"){
		echo "<td>Ders Ekleme Çıkarma</td>";
	}
	else if($args["bilgi"]["yetki"]=="16"){
		echo "<td>Öğrenci Ekleme</td>";
	}
	else if($args["bilgi"]["yetki"]=="32"){
		echo "<td>Ders-Öğrenci Ekleme</td>";
	}
	else if($args["bilgi"]["yetki"]=="2"){//admin
		echo "<td>Admin</td>";
	}
	else{
		echo "<td>Normal Kullanıcı</td>";
	}

	echo "<td>";

	if(strtotime($args["bilgi"]["engelSuresi"])<strtotime(date("Y:m:d H:i:s"))){
		echo "Engelli Değil</td>
		<td><a href=/web/app/controller/banlagun.php?islem=engelle&id=".$args["bilgi"]["id"].">Engelle</a></td>
		<td>Engelli Değil</td>";
	}
	else{
		echo "Engelli</td>";
		echo "<td>Engelli</td>
		<td><a href=/web/app/controller/banlagun.php?islem=engelikaldir&id=".$args["bilgi"]["id"].">Engeli Kaldır</a></td>";
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
