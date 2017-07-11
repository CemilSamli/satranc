<link rel="stylesheet" href="/web/app/assets/css/chessboard.css" />
<script src="/web/app/assets/javascript/chess.js"></script>
<script src="/web/app/assets/javascript/json3.min.js"></script>
<script src="/web/app/assets/javascript/jquery-1.10.1.min.js"></script>
<script src="/web/app/assets/javascript/chessboard.js"></script>
<script src="/web/app/assets/javascript/pvpoyun.js"></script>

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
                        <tr>
							<th>Hamle</th>
                            <th>Beyaz</th>
                            <th>Siyah</th>
                        </tr>
						<?php

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
							
						?>


                    </table>

                </div>
                <div class="panel-footer">
                    İyi Oyunlar...
                </div>
            </div>

			<div class="panel panel-info">
				<div class="panel-heading" id="durum"> Durum</div>
				<div class="panel-body" id="status">
            	</div>
            </div>
		</div>
	</div>

	<div class="col-md-10">
		<div class="panel panel-primary">
			<table class="table table-hover">
				<tr>
				<th>B. Puanı:</th>
				<td><span id="puanBeyaz"><?php
					if($args["macBilgileri"]["puanBeyaz"] != Null)
						echo $args["macBilgileri"]["puanBeyaz"];
					else
						echo "<center>-</center>";
					?></span></td>

				<th>S. Puanı:</th>
				<td><span id="puanSiyah"><?php
				if($args["macBilgileri"]["puanSiyah"] != Null)
					echo $args["macBilgileri"]["puanSiyah"];
				else
					echo "<center>-</center>";
				?></span></td>


				<th>B. Süresi:</th>
				<td><span id="sureBeyaz"><?php
				if($args["macBilgileri"]["sureBeyaz"] != Null)
					echo $args["macBilgileri"]["sureBeyaz"]." saniye";
				else
					echo "<center>-</center>";
				?></span></td>

				<th>S. Süresi:</th>
				<td><span id="sureSiyah"><?php
				if($args["macBilgileri"]["sureSiyah"] != Null)
					echo $args["macBilgileri"]["sureSiyah"]." saniye";
				else
					echo "<center>-</center>";
				?></span></td>

				</tr>
			</table>
		</div>
	</div>


</div>
