
<?php

	if($args["sayac"]=="1"){
		?>
			<table  id="example" class="table table-striped table-bordered nowrap table table-hover">
			<thead>
			<tr><th>Ad Soyad</th><th>Kullanıcı Adı</th></tr>
			</thead>

			<tbody>
		<?php
	}
	echo "<tr><td><a href=/web/app/controller/dersistatislik.php?id=".$_GET["id"]."&kulid=".$args["kullanici"]["id"].">
	".ucwords(strtolower($args["kullanici"]["adSoyad"]))."</a></td><td>".$args["kullanici"]["kullaniciAdi"]."</td></tr>";

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
