
<table class="table">
	<thead>
		<tr>
			<th>Sıra</th>
			<th>Kullanıcı Adı</th>
			<th>Ad Soyad</th>
			<th></th> <?php // TODO: grup ödevlerini ayrı linkte göster ?>
			<th></th>
			<th>Sil</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 1;
		foreach($args['uyeler'] as $uye) {
		?>
		<tr>
			<td><?php echo $i++; ?></td>
			<td><?php echo $uye["kullaniciAdi"]; ?></td>
			<td><?php echo $uye["adSoyad"]; ?></td>
			<td>
				<a href="odev.php?c=listele&tur=0
									&sorumluID=<?php echo $uye["uyeID"]; ?>
									&grupID=<?php echo $_GET["altislem"]; ?>">Ödevler</a>
			</td>
			<td>
				<a href="odev.php?c=odevVerForm&tur=0
									&sorumluID=<?php echo $uye["uyeID"]; ?>
									&grupID=<?php echo $_GET["altislem"]; ?>">Ödev Ver</a>
			</td>
			<td>
				<a href="gruplar.php?islem=mevcutgruplar&altislem=<?php echo $_GET["altislem"]; ?>
					&id=<?php echo $uye["uyeID"]; ?>">
					Sil
				</a>
			</td>
		</tr>
		<?php } ?>

	</tbody>
</table>

<a class="btn btn-lg btn-default pull-right" href="gruplar.php"> Geri </a>
