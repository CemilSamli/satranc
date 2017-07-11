

<?php

if($args["sayac"]==1){
?>

<table  id="example" class="table table-striped table-bordered nowrap table table-hover">
<thead>
<tr><th>Adı Soyadı</th><th>Yetkisi</th></tr>
</thead>

<tbody>
<?php
}

echo "<tr><td><a href=mesajgonder.php?id=".$args["kisiler"]["id"].">
".ucwords(strtolower($args["kisiler"]["adSoyad"]))."</a></td><td>";
if($args["kisiler"]["yetki"]==="4"){
	echo "Kullanıcı";
}
else if($args["kisiler"]["yetki"] === "2"){
	echo "Admin";
}
else{
	echo "Öğretmen";
}
echo "</td></tr>";

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
