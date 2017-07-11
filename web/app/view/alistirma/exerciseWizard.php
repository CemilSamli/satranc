<link rel="stylesheet" href="/web/app/assets/css/chessboard.css" />
<script src="/web/app/assets/javascript/chess.js"></script>
<script src="/web/app/assets/javascript/json3.min.js"></script>
<script src="/web/app/assets/javascript/jquery-1.10.1.min.js"></script>
<script src="/web/app/assets/javascript/chessboard.js"></script>
<script src="/web/app/assets/javascript/alistirma-sihirbazi.js"></script>



<input type="hidden" id="id" value="<?php echo isset($args['id']) ? $args['id']: -1; ?>"/>

<div class="row">
	<div class="col-md-6">
		<div id="board" style="width: 400px"></div>
	</div>

	<div class="col-md-6">
		<hr>
		<div class="row">
			<div class="col-md-6"> <button type="button" id="basKonum" class="btn btn-primary btn-lg btn-block">Başlangıç Konumu</button></div>
			<div class="col-md-6"> <button type="button" id="bosalt" class="btn btn-danger btn-lg btn-block">Tahtayı Boşalt</button></div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
				<textarea class="form-control" id="aciklama" name="aciklama" maxlength="1000" cols="30" rows="10" placeholder="Açıklama/Yorum"></textarea>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-6"> <button type="button" id="kaydet" class="btn btn-success btn-lg btn-block">Kaydet</button></div>
			<div class="col-md-6"><p>Hamle Sınırı: <input type="number" id="hamleSiniri" value="0"/></p></div>

		</div>
		<hr>
		<div class="row">
			<div class="col-md-6">
				<select class="form-control" name="kategori" id="kategori">
					<?php foreach ($args["kategoriler"] as $key => $value){ ?>

					<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-6">
                <div class="radio">
                    <label>
                        <input type="radio" name="zorluk" id="zorluk" value="0" checked>Kolay
                    </label>
                    <label>
                        <input type="radio" name="zorluk" id="zorluk" value="1" checked>Orta
                    </label>
                    <label>
                        <input type="radio" name="zorluk" id="zorluk" value="2" checked>Zor
                    </label>



                </div>

			</div>
		</div>




	</div>

</div>
