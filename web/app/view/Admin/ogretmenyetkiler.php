<?php

if($args["sayac"]==1){
?>
	<table  id="example" class="table table-striped table-bordered nowrap table table-hover">
	<thead>
	<tr><th>Ad Soyad</th><th>Yetki</th><th>Normal</th><th>Ders Ekleme Çıkarma</th>
	<th>Öğrenci Ekleme</th><th>Ders-Öğrenci Ekleme</th><th>Durum</th></tr>
	</thead>
	</tbody>

<?php
}


echo "<tr><td>".ucwords(strtolower($args["veriler"]["adSoyad"]))."</td>";

if($args["veriler"]["yetki"]=="1"){
	echo "<td>Normal Öğretmen</td>
	<td>Mevcut Yetki</td>
	<td><a href=ogretmenyetkiler.php?islem=eklecikarogr&altislem=".$args["veriler"]["id"].">Yetki Ata</a></td>
	<td><a href=ogretmenyetkiler.php?islem=ogrenciogr&altislem=".$args["veriler"]["id"].">Yetki Ata</a></td>
	<td><a href=ogretmenyetkiler.php?islem=ogrencidersogr&altislem=".$args["veriler"]["id"].">Yetki Ata</a></td>
	";
}
else if($args["veriler"]["yetki"]=="8"){
	echo "<td>Ders Ekleme Çıkarma</td>
	<td><a href=ogretmenyetkiler.php?islem=normalogr&altislem=".$args["veriler"]["id"].">Yetki Ata</a></td>
	<td>Mevcut Yetki</td>
	<td><a href=ogretmenyetkiler.php?islem=ogrenciogr&altislem=".$args["veriler"]["id"].">Yetki Ata</a></td>
	<td><a href=ogretmenyetkiler.php?islem=ogrencidersogr&altislem=".$args["veriler"]["id"].">Yetki Ata</a></td>";
}


else if($args["veriler"]["yetki"]=="16"){
	echo "<td>Öğrenci Ekleme</td>
	<td><a href=ogretmenyetkiler.php?islem=normalogr&altislem=".$args["veriler"]["id"].">Yetki Ata</a></td>
	<td><a href=ogretmenyetkiler.php?islem=eklecikarogr&altislem=".$args["veriler"]["id"].">Yetki Ata</a></td>
	<td>Mevcut Yetki</td>
	<td><a href=ogretmenyetkiler.php?islem=ogrencidersogr&altislem=".$args["veriler"]["id"].">Yetki Ata</a></td>";
}
else if($args["veriler"]["yetki"]=="32"){
	echo "<td>Ders-Öğrenci Ekleme</td>
	<td><a href=ogretmenyetkiler.php?islem=normalogr&altislem=".$args["veriler"]["id"].">Yetki Ata</a></td>
	<td><a href=ogretmenyetkiler.php?islem=eklecikarogr&altislem=".$args["veriler"]["id"].">Yetki Ata</a></td>
	<td><a href=ogretmenyetkiler.php?islem=ogrenciogr&altislem=".$args["veriler"]["id"].">Yetki Ata</a></td>
	<td>Mevcut Yetki</td>";
}


$engelSuresi=strtotime($args["veriler"]["engelSuresi"]);
$bugun=strtotime(date("Y:m:d H:i:s"));

if($engelSuresi<$bugun)
	echo "<td>Aktif</td>";
else
	echo "<td>Engelli</td>";
echo "</tr>";

if($args["toplam"] == $args["sayac"]){
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