
<div class="col-md-8">
<form action="?" method="post" >
	<input type="hidden" name="c" value="ver" />
	<input type="hidden" name="tur" value="<?php echo $args['tur']; ?>" />
	<input type="hidden" name="grupId" value="<?php echo $args['grupId']; ?>" />
	<?php if($args['tur'] == 0) { ?>
		<input type="hidden" name="sorumluID" value="<?php echo $args['sorumluID']; ?>" />
	<?php } ?>

	<table class="table">
		<thead>
			<tr>
				<th>Ekle</th>
				<th>Kategori</th>
				<th>Zorluk</th>
				<th>Alıştırma Adedi</th>
				<th>Gün Sınırı</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($args["kategoriler"] as $key => $value){ ?>
			<tr>
				<td>
					<div class="checkbox">
						<label>
							<input type="hidden" name="ekle[<?php echo $key; ?>]" value="0">
							<input type="checkbox" name="ekle[<?php echo $key; ?>]" value="1">
						</label>
					</div>
				</td>
				<td>
					<span value="<?php echo $key; ?>"><?php echo $value[3]; ?></span>
				</td>
				<td class="form-group">


						<?php if (isset($value[0]) && $value[0]==1) {?>
						<label class="radio-inline">
							<input type="radio" name="zorluk[<?php echo $key; ?>]" id="zorluk" value="0" checked>Kolay
						</label>
						<?php } if (isset($value[1]) && $value[1]==1) {?>
						<label class="radio-inline">
							<input type="radio" name="zorluk[<?php echo $key; ?>]" id="zorluk" value="1" checked>Orta
						</label>
						<?php } if (isset($value[2]) && $value[2]==1) {?>
						<label class="radio-inline">
							<input type="radio" name="zorluk[<?php echo $key; ?>]" id="zorluk" value="2" checked>Zor
						</label>
						<?php } ?>
				</td>
				<td>
					<input type="number" name="adet[<?php echo $key; ?>]" value="0"/>
				</td>
				<td>
					<input type="number" name="gunSiniri[<?php echo $key; ?>]" value="7"/>
				</td>

			</tr>
		<?php } ?>
		</tbody>
	</table>

	<?php if($args['tur'] == 0) { ?>
		<a class="btn btn-lg btn-default pull-right" href="/web/app/controller/gruplar.php?islem=mevcutgruplar
					&altislem=<?php echo $args['grupId']; ?>">İptal</a>
	<?php } else { ?>
		<a class="btn btn-lg btn-default pull-right" href="/web/app/controller/gruplar.php">İptal</a>
	<?php } ?>
	<input class="btn btn-lg btn-default pull-right" type="submit" value="Ödev Ver">
</form>
</div>
