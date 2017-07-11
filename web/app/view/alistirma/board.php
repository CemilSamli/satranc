<link rel="stylesheet" href="/web/app/assets/css/chessboard.css" />
<script src="/web/app/assets/javascript/chess.js"></script>
<script src="/web/app/assets/javascript/json3.min.js"></script>
<script src="/web/app/assets/javascript/jquery-1.10.1.min.js"></script>
<script src="/web/app/assets/javascript/chessboard.js"></script>
<script src="/web/app/assets/javascript/alistirmaoyun.js"></script>

<input type="hidden" id="fen" value="<?php echo $args["args"][0]; ?>"/>
<input type="hidden" id="sinir" value="<?php echo $args["args"][1]; ?>"/>
<input type="hidden" id="id" value="<?php echo $args["args"][3]; ?>"/>

<div class="row">
	<div class="col-md-9">

		<div class="oyun">
			<div id="board" style="width: auto; max-width: 90%;"></div>
		</div>
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
                        <p> <?php echo $args["args"][2]; ?> </p>
                    </div>
                    <div class="panel-footer">
                        <p> <span id="status"></span></p>
                    </div>



                </div>
            </div>
		</div>
	</div>
	
	<hr/>
	<div class="col-md-3">
		<button onclick="goBack()" type="button" class="btn btn-danger btn-block">Geri Dön</button>
	</div>
	<?php 
		$index = -1;
		if(count($args["alistirmalar"])>0){
			for($i=0;$i<count($args["alistirmalar"]);$i++){
				if($args["alistirmaID"] == $args["alistirmalar"][$i]){
					$index = $i;
				}
			}
			if($index != -1 && ($index+1) < count($args["alistirmalar"])){
	?>
	
	<?php
	if($index > 0){
	?>
	
	<div class="col-md-3">
		<a class="btn btn-success btn-block" href="/web/app/controller/alistirma.php?islem=oyun&id=<?php echo $args["alistirmalar"][$index-1]; ?>>Alıştırma<?php echo $index-1; ?>" role="button">Önceki</a>
	</div>
	
	<?php }  ?>
	
	<div class="col-md-3">
		<a class="btn btn-primary btn-block" href="/web/app/controller/alistirma.php?islem=oyun&id=<?php echo $args["alistirmalar"][$index]; ?>>Alıştırma<?php echo $index; ?>" role="button">Tekrar Oyna</a>
	</div>
	
	<div class="col-md-3">
		<a class="btn btn-success btn-block" href="/web/app/controller/alistirma.php?islem=oyun&id=<?php echo $args["alistirmalar"][$index+1]; ?>>Alıştırma<?php echo $index+1; ?>" role="button">Sonraki</a>
	</div>
	
	<?php
	}
	else{
		?>
	<div class="col-md-3">
		<a class="btn btn-primary btn-block" href="/web/app/controller/alistirma.php?islem=oyun&id=<?php echo $args["alistirmalar"][$index]; ?>>Alıştırma<?php echo $index; ?>" role="button">Tekrar Oyna</a>
	</div>
	<?php
	}
	}
	?>
	
			
        
</div>
<script>
    function goBack() {
        window.history.back();
    }
</script>

