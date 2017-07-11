<script src="/web/app/assets/javascript/json3.min.js"></script>
<script src="/web/app/assets/javascript/jquery-1.10.1.min.js"></script>


<div class="col-md-8">
<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="c" value="pgnDetay" />
	<table class="table">
		<thead>
			<tr>
				<th>Ekle</th>
				<th>Oyun</th>
				<th>Kategori</th>
				<th>Zorluk</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($args["games"] as $key => $value){ ?>
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
					<label><?php echo $value->getEventSiteDatePrettyPrint(); ?>:</label>
					<br>
					<?php echo $value->getPlayersPrettyPrint(); ?>
				</td>
				<td class="form-group">
					<select class="form-control" name="kategori[<?php echo $key; ?>]" id="kategori">
						<?php foreach ($args["kategoriler"] as $kkey => $value){ ?>
						<option value="<?php echo $kkey; ?>"><?php echo $value; ?></option>
						<?php } ?>
					</select>
				</td>
				<td class="form-group">
					<label class="radio-inline">
						<input type="radio" name="zorluk[<?php echo $key; ?>]" id="zorluk" value="0">Kolay
					</label>
					<label class="radio-inline">
						<input type="radio" name="zorluk[<?php echo $key; ?>]" id="zorluk" value="1" checked>Orta
					</label>
					<label class="radio-inline">
						<input type="radio" name="zorluk[<?php echo $key; ?>]" id="zorluk" value="2">Zor
					</label>
				</td>

			</tr>
		<?php } ?>
		</tbody>
	</table>
	<input class="btn btn-lg btn-default pull-right" type="submit" value="Kaydet">
</form>
</div>
