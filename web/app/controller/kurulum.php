<?php
include_once($_SERVER['DOCUMENT_ROOT']."/web/app/model/utils.php");
loadModel("DBSetup.php");
if(isset($_GET['controller'])){
	$controller = $_GET['controller'];
} else {
	$controller = "";
}
if($controller === "kur"){
	$dbHelper = new DBSetup();
	$sonuc = $dbHelper->setup();
	if($sonuc == 0){
		echo "OK";
	} else {
		echo "ERROR";
	}
	exit();
}

loadView("header.php");
loadView("sidebar.php");

?>

<!-- İçerik Başlangıç -->
<div class="icerik">

<?php

$dbHelper = new DBSetup();
$args['status'] = $dbHelper->status();

loadView("setup.php", $args);

?>

</div>
<!-- İçerik Bitiş -->

<?php
loadView("footer.php");
?>
