
<div class="col-md-8">
	<table class="table">
		<thead>
			<tr>
				<th>Tür</th>
				<th>Kategori</th>
				<th>Zorluk</th>
				<th>Alıştırma Adedi</th>
				<th>Veriliş Tarihi</th>
				<th>Bitiş Tarihi</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($args["odevler"] as $key => $value){ ?>
			<tr class="<?php echo $value['durum']; ?>">
				<td>
					<?php echo $value['tur'] == 0 ? "Bireysel" : "Grup"; ?>
				</td>
				<td>
					<a href="/web/app/controller/kategori.php?tur=alistirma&zorluk=<?php echo $value['zorluk']; ?>&id=<?php echo $value['alistirmaKategoriID']; ?>">
						<?php echo $value['kategori']; ?>
					</a>
				</td>
				<td>
					<?php echo $value['zorluk_str']; ?>
				</td>
				<td>
					<?php echo $value['alistirmaAdet']; ?>
				</td>
				<td>
					<?php echo date_format(new DateTime($value['tarihVerilis']), 'Y-m-d'); ?>
				</td>
				<td>
					<?php echo date_format(new DateTime($value['tarihBitis']), 'Y-m-d'); ?>
				</td>

			</tr>
		<?php } ?>
		</tbody>
	</table>

	<button class="btn btn-lg btn-default pull-right" onclick="window.history.back()"> Geri </a>
</div>
