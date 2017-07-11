<table class="table">

    <?php
	$sayac = 0;
    echo "<tr>";
    //var_dump($args);
    $tur = $args['tur']==0 ? "dersizle.php?c=" : "alistirma.php?islem=oyun&id=" ;
    $turAdi = $args['tur']==0 ? "Ders" : "Alıştırma" ;
    for($i=0;$i<count($args['idler']);$i++){
        $link = $tur.$args['idler'][$i];
        if($i%4==0){
            echo "<tr>";

        }
		for($a=0;$a<count($args["yapilanlar"]);$a++){
			if($args["yapilanlar"][$a] == $args["idler"][$i]){
				echo "<td>"."<a  href='$link>$turAdi$i' class='btn btn-success btn-block btn-lg' role='button'>".$turAdi." ".($i+1)."</a>"."</td>";
				$sayac = 1;
			}
		}
		if($sayac == 0){
			echo "<td>"."<a  href='$link>$turAdi$i' class='btn btn-info btn-block btn-lg' role='button'>".$turAdi." ".($i+1)."</a>"."</td>";
			
		}
		$sayac = 0;
    }


    echo "</tr>";
?>

</table>