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
	
	?>
	<tr><td><a href = "/web/app/controller/kullanicilar.php?s=<?php if(!empty($_GET["s"]) && is_numeric($_GET["s"])){ echo $_GET["s"]; } ?> &islem=<?php echo $args["kullanici"]["id"]; ?>">
	<?php echo ucwords(strtolower($args["kullanici"]["adSoyad"])); ?> </a></td><td> <?php echo $args["kullanici"]["kullaniciAdi"]; ?> </td></tr>

<?php
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
