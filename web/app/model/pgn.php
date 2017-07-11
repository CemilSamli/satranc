<?php

/*
if(isset($_POST['t'])){
	$controller = $_POST['t'];
} else {
	$controller = "";
}

switch($controller){
	case "pgn":
		ViewPgnParser();
		break;
	default :
		ViewIndex();
}
*/
function parsePGN($filePGN){
	$meta_data = stream_get_meta_data($filePGN);
	$filename = $meta_data["uri"];
	$parser = new PgnParser($filename);
	var_dump($parser->getGames()[0]);
	exit();
	$bilinenTaglar = array("Event", "Site", "Date", "Round", "White", "Black", "Result");

	$tags = array();
	$oyunlar = array();
	$hamleler = array();


	$gameCount = 0;
	$counted = false;
	while(!feof($filePGN)){
		$line = fgets($filePGN);
		//echo $line."<br>";
		$yorum = "";

/*
		preg_match_all("/{([^}]+)}|:(.+)/",
		    "adsf{5tt}rey {68fgg}sd:ag" ,
		    $out, PREG_SET_ORDER);
		echo "<br>";
		foreach($out[2] as $o){
			echo "$o + ";
		}
*/
		//yorumları kaldırır
		$line = preg_replace("/({[^}]+})|(:.+)/", "", $line);
		//çok satırlı yorumları kaldırır
		if(strpos($line, "{") !== false){
			$lineold = preg_replace("/{.+/", "", $line);
			while(!feof($filePGN)){
				$linenew = fgets($filePGN);
				if(strpos($linenew, "}") !== false){
					$linenew = preg_replace("/.+}/", "", $linenew);
					$line = $lineold.$linenew;
					break;
				}
			}
		}


		if(substr($line, 0, 1) === '['){ // tag parse
			//echo "as<br>";
			preg_match("/\[(\S+)\s(.+)\]/", $line, $matches);
			if(in_array($matches[1], $bilinenTaglar)){
				$tags[] = array($matches[1], $matches[2]);
			}
		} else { // hamle parse
			$arr = preg_split("/ /", $line, NULL, PREG_SPLIT_NO_EMPTY);
			foreach($arr as $parca){
			//echo "ssas<br>";
				$sonuc = preg_match('/(^\d+\.)|(\$\S+)/', $parca, $sn);
				if(((is_int($sonuc) && $sonuc ==0 ) || $sonuc === false) && strlen($parca)>1){
					if(preg_match("/\d+-\d+/", $parca)){
						$gameCount += 1;
						$oyunlar[] = array($hamleler, $parca);
						$hamleler = array();
						//echo "Sonuc: $parca<br><br>"; // maç sonucu
					} else {
						$hamleler[] = $parca;
						//echo $parca."<br>"; // hamle
					}
				}
				//var_dump($sn); echo "<br>";
			}
		}
		if($gameCount > 10){
			break;
		}
		//echo "$line<br>";
	}
	fclose($filePGN);
	//var_dump($oyunlar);
	return $oyunlar;
}
/*
function ViewPgnParser(){

	//var_dump ($_FILES["pgnFile"]);
	if($_FILES["pgnFile"]["type"] !== "application/x-chess-pgn")
		return -1;

	$filePGN = fopen($_FILES["pgnFile"]["tmp_name"], "r") or die("Unable to open 'counts'!");

	parsePGN($filePGN);
}

function ViewIndex(){
?>
	<form method="POST" action="index.php" enctype="multipart/form-data">
		<input type="file" id="pgnFile" name="pgnFile" />
		<input type="hidden" name="t" value="pgn" />
		<input type="submit" value="Kaydet" />
	</form>
<?php
}*/
?>
