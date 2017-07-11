<?php

	if($args["sayac"]=="1"){
		?>
			<table  id="example" class="table table-striped table-bordered nowrap table table-hover">
			<thead>
			<tr><th>Ad Soyad</th><th>Kullanıcı Adı</th></tr>
			</thead>

			<tbody>
		<?php
		echo "<tr><td><a href=/web/app/controller/istatislikler.php?id=".$_GET["id"]."&kulid=".$_SESSION["id"]."&islem=".$_GET["islem"].">".$_SESSION["adSoyad"]."
		(Siz)</a></td><td>".$_SESSION["kullaniciadi"]."</td></tr>";
		
	}
	echo "<tr><td><a href=/web/app/controller/istatislikler.php?id=".$_GET["id"]."&kulid=".$args["kullanici"]["id"]."&islem=".$_GET["islem"].">
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