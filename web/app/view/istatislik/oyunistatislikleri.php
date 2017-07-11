<?php


if($args["sayac"]=="1"){
?>	
	<table  id="example" class="table table-striped table-bordered nowrap table table-hover">
	<thead>
	<tr><th>Siyah Taş</th><th>Beyaz Taş</th><th>Oyun Turu</th>
	<th>Tarih</th> <?php //<th>Kazanan</th> ?>
	<th>Beyaz Sure</th><th>Siyah Sure</th><th>Beyaz Puan</th><th>Siyah Puan</th>
	</tr>
	</thead>

	<tbody>
<?php
}

echo "<tr><td>".$args["siyahKullanici"]."</td><td>".$args["beyazKullanici"]."</td>
<td>";

if($args["veriler"]["oyunTuru"]=="0"){
	echo "Yapay Zeka";
}
else if($args["veriler"]["oyunTuru"]=="1"){
	echo "Karşılıklı";
}


echo "</td><td>".$args["veriler"]["tarihOlusturma"]."</td>";
//<td>".ucwords(strtolower($args["kazananKullanici"]))."</td>
echo "<td>".$args["veriler"]["sureBeyaz"]."</td><td>".$args["veriler"]["sureSiyah"]."</td><td>".$args["veriler"]["puanBeyaz"]."</td><td>".$args["veriler"]["puanSiyah"]."</td></tr>";



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