<?php
if($args["sayac"] == 1){ ?>

	<table  id="eskimaclar" class="table table-striped table-bordered nowrap table table-hover">
	<thead>
	<tr><td>Tarih</td><td>Rakip</td><td>Git</td></tr>
	</thead>
	<tbody>

<?php
}
?>

<tr>
<td>
<?php
if($args["bilgi"]["tarihOlusturma"] != Null)
echo $args["bilgi"]["tarihOlusturma"];
?>
</td>

<td>
<?php
if($args["bilgi"]["adSoyad"] != Null)
echo $args["bilgi"]["adSoyad"];
else
	echo "<center>-</center>";
?>
</td>


<td>

<?php
	if($args["bilgi"]["kazananID"] == -1){
		if(($args["bilgi"]["beyazID"] == $_SESSION["id"]) && ($args["renk"] == "w") || ($args["bilgi"]["beyazID"] != $_SESSION["id"]) && ($args["renk"] == "b")){
			?>
			<a class="btn btn-success btn-block" href="/web/app/controller/multiplegame.php?id=<?php echo $args["bilgi"]["id"]; ?>" role="button">Git</a>
			<?php
		} else {
			?>
			<a class="btn btn-danger btn-block" href="/web/app/controller/multiplegame.php?id=<?php echo $args["bilgi"]["id"]; ?>" role="button">Git</a>
			<?php
		}
	} else if($args["bilgi"]["kazananID"] == $_SESSION["id"]){
		?>
		<a class="btn btn-default btn-block" href="/web/app/controller/multiplegame.php?id=<?php echo $args["bilgi"]["id"]; ?>" role="button">Kazandınız</a>
		<?php
	} else {
		?>
		<a class="btn btn-default btn-block" href="/web/app/controller/multiplegame.php?id=<?php echo $args["bilgi"]["id"]; ?>" role="button">Kaybettiniz</a>
		<?php
	}

 ?>
</td>
</tr>
<?php
	if($args["toplamsayac"] == $args["sayac"]){
	?>
	</tbody></table>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#eskimaclar').DataTable();
		} );
	</script>
	<?php
		}
	?>
