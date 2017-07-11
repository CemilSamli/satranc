
<form action="/web/app/controller/haberler.php?islem=<?php echo $_GET["islem"]."me"; ?>" method="post" enctype="multipart/form-data">
BASLIK<br><textarea name="baslik" cols="70" rows="5"></textarea><br><br>
ICERIK<br><textarea name="icerik" cols="70" rows="20"></textarea><br><br>
<?php
if($_GET["islem"]=="haberekle"){
  echo "RESIM EKLE<br><input type=file name=resim><br><br>";
}
?>
<input type="submit" value="EKLE">
</form>
