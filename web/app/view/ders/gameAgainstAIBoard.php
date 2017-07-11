<link rel="stylesheet" href="/web/app/assets/css/chessboard.css" />
<script src="/web/app/assets/javascript/chess.js"></script>
<script src="/web/app/assets/javascript/json3.min.js"></script>
<script src="/web/app/assets/javascript/jquery-1.10.1.min.js"></script>
<script src="/web/app/assets/javascript/chessboard.js"></script>
<?php
if($args["seviye"][0] == 1){
	echo "<script>var skill=2; var depth=1;</script>";
} else if($args["seviye"][0] == 2){
	echo "<script>var skill=4; var depth=2;</script>";
} else if($args["seviye"][0] == 3){
	echo "<script>var skill=14; var depth=6;</script>";
} else if($args["seviye"][0] == 4){
	echo "<script>var skill=20; var depth=10;</script>";
} else {
	echo "<script>var skill=2; var depth=1;</script>";
}
?>
<script src="/web/app/assets/javascript/aioyun.js"></script>



<div class="row">
	<div class="col-md-6">

		<div class="oyun">
			<div id="board" style="width: 400px"></div>

		</div>

	</div>
	<div class="col-md-4">

		<div class="aciklama">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    Hamleler
                </div>
                <div class="panel-body" style="overflow-y: scroll; height:250px">
                    <table class="table table-hover" id="hamlelerTablo"  border="1">
                        <tr> <th>Hamle</th>
                            <th>Beyaz</th>
                            <th>Siyah</th>
                        </tr>

						<?php
							if($args["bilgi"] != -1){
							$hamle = explode(";;;",$args["bilgi"]["hamleler"]);
							$a = 1;
							for($i=1;$i<count($hamle);$i++){
							if($i%2 == 1){
								?> <tr><td><?php echo $a; $a++; ?></td> <?php
							}

							echo "<td>".$hamle[$i]."</td>";

							if($i%2 == 0){
								?> </tr> <?php
							}

							}
							}
						?>

                    </table>

                </div>
                <div class="panel-footer">
                    İyi Oyunlar...
                </div>
            </div>


			<div class="panel panel-primary">
				<?php
					if($args["kazananID"] != -1){
						if($args["kazananID"] == 1){//kazanan session o zaman yesil
						?>
							<div class="panel-heading" style="background-color:#5cb85c; border-color:#5cb85c">
							<img src="/web/app/assets/image/happy.png" height="25" width="25"> Durum</div>
						<?php
						}else if($args["kazananID"] == 2){//kazanan bilgisayar o zaman kırmızı
						 ?>
							<div class="panel-heading" style="background-color:#d43f3a; border-color:#d43f3a">
							<img src="/web/app/assets/image/sad.png" height="25" width="25"> Durum</div>

							<?php
						}else{
							?>
								<div class="panel-heading" style="background-color:#d58512; border-color:#d58512"> Durum</div>
							<?php
						}
					}
					else {
					?>
						<div class="panel-heading"><span id="durum"> Durum </span></div>
					<?php
						}
					?>
					<span id="status"></span>
            </div>
		</div>
	</div>

</div>
