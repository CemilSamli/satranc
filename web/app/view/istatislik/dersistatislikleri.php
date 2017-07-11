
<head>
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css"/>
	
	
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>
</head>
<?php

	if($args["sayac"]=="1"){
		$toplamsure=0;
		?>
			<table  id="example" class="table table-striped table-bordered nowrap table table-hover">
			<thead>
			<tr><th>Sıra</th><th>Alıstırma</th><th>Hoca Adı</th><th>Son Oynama Tarihi</th><th>Oynama Sayisi</th><th>Zorluk</th><th>En iyi Süre</th></tr>
			</thead>

			<tbody>
		<?php
	}
	echo "<tr><td>".$args["sayac"]."</td>
	<td>".$args["bilgiler"]["isim"]."</td>
	<td>".ucwords(strtolower($args["bilgiler"]["hocaAdi"]))."</td>
	<td>".$args["bilgiler"]["sonOynamaTarihi"]."</td>
	<td>".$args["bilgiler"]["oynamaSayisi"]."</td><td>";
	if($args["bilgiler"]["zorluk"] == 0){
		echo "Kolay";
	}else if($args["bilgiler"]["zorluk"] == 1){
		echo "Orta";
	}
	else{
		echo "Zor";
	}
	
	
	
		$parca = array();
		if($args["bilgiler"]["sure"] != ''){
			echo "</td>";
			$parca = explode(";",$args["bilgiler"]["sure"]);
			if($parca[0] == ''){
				unset($parca[0]);
			}
			$minimum = min($parca);
		
			echo "<td>".$minimum."</td>";
		}else{
			echo "<td> - </td>";
		}
	
	echo "</tr>";

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
