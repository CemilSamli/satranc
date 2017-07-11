<link rel="stylesheet" href="/web/app/assets/css/chessboard.css" />
<script src="/web/app/assets/javascript/chess.js"></script>
<script src="/web/app/assets/javascript/json3.min.js"></script>
<script src="/web/app/assets/javascript/jquery-1.10.1.min.js"></script>
<script src="/web/app/assets/javascript/chessboard.js"></script>
<script src="/web/app/assets/javascript/ders-oynatici.js"></script>

<input type="hidden" id="pgn" name="pgn" value='<?php echo $args['hamleler']; ?>'>
<input type="hidden" id="fen" name="fen" value='<?php echo $args['fen']; ?>'>
<!-- todo: yukarıdaki için güvenlik kontrolleri yap xss için -->



<div class="row">
	<div class="col-md-9">
        <div id="board" style="width: auto; max-width: 80%;"></div>
    </div>
	
	<div class="col-md-3">
	    <div class="row">
            <div class="col-md-12">

                <div class="panel panel-primary" >
                    <div class="panel-heading">

                           <small>
                               Açıklama
                           </small>

                    </div>
				
					<div class="panel-body" style="overflow-y: scroll; height:200px">
                        <p> <?php echo $args["aciklama"]; ?> </p>
                    </div>
                    <div class="panel-footer">
                        <p> <span id="status"></span></p>
                    </div>
				</div>
			</div>
		</div>
	</div>
	
	 <div class="col-md-3">
		<button type="button"  class="btn btn-danger btn-block yeniden-baslat" id="yeniden" data-toggle="tooltip" data-placement="top" title="Durdur"><span class="glyphicon glyphicon-stop"></span></button>
	</div>
	
	<div class="col-md-3">
		<button class="btn btn-success btn-block geri-ileri" id="geri" data-toggle="tooltip" data-placement="top" title="Geri"><span class="glyphicon glyphicon-backward"></span></button>
	</div>
	
	<div class="col-md-3">
		<button class="btn btn-primary btn-block oynat-duraklat" id="oynat" data-toggle="tooltip" data-placement="top" title="Oynat/Duraklat"><span class="glyphicon glyphicon-play"></span></button>
	</div>
	
	<div class="col-md-3">
		<button class="btn btn-success btn-block  geri-ileri" id="ileri" data-toggle="tooltip" data-placement="top" title="İleri"><span class="glyphicon glyphicon-forward"></span></button>
	</div>
	
	<div class="col-md-3">
		<button onclick="goBack()" type="button" class="btn btn-primary btn-block">Geri Dön</button>
	</div>
	
</div>
<script>
    function goBack() {
        window.history.back();
    }
</script>
<br/>

