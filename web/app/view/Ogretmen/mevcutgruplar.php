<div class="row">
<div class="col-md-12">

	<table class='table table-hover'>
		<thead>
			<tr>
				<th>Grup Adı</th>
				<th>Kuruluş Tarihi</th>
				<th></th>
				<th></th>
				<th>Grup Adı Değiştir</th>
				<th>Sil</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($args['gruplar'] as $grup) { ?>
			<tr>
				<td>
					<a href="/web/app/controller/gruplar.php?islem=mevcutgruplar&altislem=<?php echo $grup["id"]; ?>">
						<?php echo $grup["adi"]; ?>
					</a>
				</td>
				<td>
					<?php echo $grup["tarihKurulus"]; ?>
				</td>
				<td>
					<a href="odev.php?c=listele&tur=1
										&sorumluID=-1
										&grupID=<?php echo $grup["id"]; ?>">Grup Ödevleri</a>
				</td>
				<td>
					<a href="odev.php?c=odevVerForm&tur=1
										&grupID=<?php echo $grup["id"]; ?>">Gruba Ödev Ver</a>
				</td>
				<td>
					<a href="/web/app/controller/grupduzenle.php?grupid=<?php echo $grup["id"]; ?>">
						Grup Adı Değiştir
					</a>
				</td>
				<td>
					<a href="/web/app/controller/gruplar.php?altislem=sil&grupid=<?php echo $grup["id"]; ?>">
						Sil
					</a>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
</div>
<?php
//<td><a href=/web/app/controller/alistirmaistatistik.php?grupid=".$args["bilgi"]["id"].">İncele</a></td>

?>
