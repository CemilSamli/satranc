<?php
include_once("../model/utils.php");
loadModel("DBOnline.php");
loadModel("DBOyun.php");

// How often to poll, in microseconds (1,000,000 μs equals 1 s)
define('MESSAGE_POLL_MICROSECONDS', 1000000);
// How long to keep the Long Poll open, in seconds
define('MESSAGE_TIMEOUT_SECONDS', 25);
// Timeout padding in seconds, to avoid a premature timeout in case the last call in the loop is taking a while
define('MESSAGE_TIMEOUT_SECONDS_BUFFER', 5);

// Automatically die after timeout (plus buffer)
set_time_limit(MESSAGE_TIMEOUT_SECONDS+MESSAGE_TIMEOUT_SECONDS_BUFFER);

// Counter to manually keep track of time elapsed (PHP's set_time_limit() is unrealiable while sleeping)
$counter = MESSAGE_TIMEOUT_SECONDS;

$oyun = new DBOnline();
$db = new DBOyun();

session_start();
$fenString = $oyun->fenStringCek($_GET["id"]);
$parca = explode(" ",$fenString);
$sonRenk = $parca[1];
$renk = $oyun->renkBul($_GET["id"]);
session_write_close();

while($counter > 0)
{
	session_start();
	$fenString = $oyun->fenStringCek($_GET["id"]);
	$parca = explode(" ",$fenString);
	$sonRenk = $parca[1];
	$renk = $oyun->renkBul($_GET["id"]);
    // Check for new data (not illustrated)
	//header('Content-Type: application/json');
	//echo json_encode(array("pvpData" => $_GET['renk'].",".$sonRenk));
	//exit();
    if($_GET['renk'] != $sonRenk)
    {
        // Break out of while loop if new data is populated
        break;
    }
    else
    {
		// Close the session prematurely to avoid usleep() from locking other requests
		session_write_close();
        // Otherwise, sleep for the specified time, after which the loop runs again
        usleep(MESSAGE_POLL_MICROSECONDS);

        // Decrement seconds from counter (the interval was set in μs, see above)
        $counter -= MESSAGE_POLL_MICROSECONDS / 1000000;
    }
}


$fen = rawurlencode ($fenString);

$oyuncuisimleri = $oyun->oyuncuIsimGetir($_GET["id"]);

$sonRenk = "Beyaz";
if($fenString != ""){
	$parca = explode(" ",$fenString);
	$sonRenk = $parca[1];
}

$dizi = $oyun->oyunHamleleri($_GET["id"]);
$kazananID = $db->oyunuKazanan($_GET["id"]);

$macBilgileri = $oyun->eskiMaclar($_SESSION["id"],$_GET["id"]);


$obj = new stdClass();
$obj->fen = $fen;
$obj->kullanici_renk = $renk;
$obj->oynayacak_renk = $sonRenk;
$obj->meydanOkuyanIsim = $oyuncuisimleri[0];
$obj->meydanOkunanIsim = $oyuncuisimleri[1];
$obj->meydanOkuyanID = $oyuncuisimleri[2];
$obj->meydanOkunanID = $oyuncuisimleri[3];
$obj->kazananID = $kazananID;
$obj->puanBeyaz = $macBilgileri[0]["puanBeyaz"];
$obj->puanSiyah = $macBilgileri[0]["puanSiyah"];
$obj->sureBeyaz = $macBilgileri[0]["sureBeyaz"];
$obj->sureSiyah = $macBilgileri[0]["sureSiyah"];
$obj->hamleler = $oyun->oyunHamleleriString($_GET["id"]);
header('Content-Type: application/json');
echo json_encode(array("pvpData" => $obj));
exit();


?>
