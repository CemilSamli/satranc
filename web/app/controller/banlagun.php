<?php
ob_start();
include_once("../model/utils.php");
loadModel("DBAdmin.php");
loadView("header.php");


$cfg = array(
	"uyeYetkisi" => isset($_SESSION['yetki']) ? $_SESSION['yetki'] : -1,
	"seviye" => array ()
);
yetkiKontrol($cfg);
echo "<div class=row>"; ?>

<div class= "col-md-offset-4 col-md-4">
<?php
class BanlaGun{
	function banla($sonbangun){
		if(!empty($_GET["islem"]) && !empty($_GET["id"])){
			$nesne=new DBAdmin();
			if($_GET["islem"]=="engelle"){
				$nesne->uyeEngelle($_GET["id"],$sonbangun);
			}
			else if($_GET["islem"]=="engelikaldir"){
				$nesne->uyeEngelle($_GET["id"],$sonbangun);
			}
			header("Location:banla.php");
		}
	}
}
if(!empty($_GET["islem"]) && !empty($_GET["id"]) && $_SESSION["yetki"] == YETKI_ADMIN){
	if($_GET["islem"] === "engelle"){
		loadView("Admin/banlamagun.php");
		if(!empty($_POST["banlamagun"])){
			$bangun=$_POST["banlamagun"];
			$sonbangun=date("Y:m:d H:i:s",strtotime("+$bangun days"));
			$object=new BanlaGun();
			$object->banla($sonbangun);
		}
		else{
			echo "<div class='alert alert-info' role='alert'>
			Lütfen Engelleme Süresini Seçin!
			</div>";
		}
	}
	else if($_GET["islem"]=="engelikaldir"){
		$sonbangun="0000-00-00 00:00:00";
		$object=new BanlaGun();
		$object->banla($sonbangun);
	}
	else{
		header("banla.php");
	}
}


echo "</div>";
echo "</div>";
loadView("footer.php");
ob_end_flush();
?>
