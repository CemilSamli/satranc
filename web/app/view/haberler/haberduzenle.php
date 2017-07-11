<?php
if($args["sayac"] == 1){
	?>
	<table border="1">
	<tr><td>Sıra</td><td> <?php if($_GET["islem"] == "haber"){ echo "Haber Başlık"; }else{ echo "Duyuru Başlık";}  ?></td><td>Duzenle</td><td>Sil</td></tr>
	<?php
}
?>
<tr>
	<td> <?php echo $args["sayac"]; ?> </td><td><?php echo $args["veriler"]["baslik"]; ?></td>
	<td><a href="/web/app/controller/haberduzenle.php?islem=<?php echo $_GET["islem"]; ?>&altislem=duzenle&id=<?php echo $args["veriler"]["id"]; ?>">Duzenle</a></td>
	<td><a href="/web/app/controller/haberduzenle.php?islem=<?php echo $_GET["islem"]; ?>&altislem=sil&id=<?php echo $args["veriler"]["id"]; ?>">Sil</a></td>
</tr>

<?php
if($args["toplam"] == $args["sayac"]){
	?>
</table>
<?php
} 
?>
