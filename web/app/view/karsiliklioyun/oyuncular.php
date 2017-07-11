<?php
if($args["sayac"] == 1){

	if(empty($args["oyuncular"])){
		?>
			<div class="panel panel-primary">
			<div class="panel-body" style="overflow-y: scroll; height:500px">
			<table class="table table-striped table-bordered nowrap table-hover">
			<tbody>
		<?php
		loadView("test.php","oyuncular");
	}else{
		?>
			<table class="table table-striped table-bordered nowrap table-hover">
		<?php
	}
}
?>
<tr>
	<td>
	<?php if($args["bilgi"]["aktif"] == 1){ ?> <img src="/web/app/assets/image/okundu.png"></img> <?php } else{ ?>
	<img src="/web/app/assets/image/okunmadi.jpg"></img> <?php  } ?>
	</td>

	<td>
	<?php echo $args["bilgi"]["adSoyad"]; ?>
	</td>

	<td>
	<a class="btn btn-primary btn-block" href="/web/app/controller/karsiliklioyun.php?id=<?php echo $args["bilgi"]["id"]  ?> " role="button"> Meydan Oku </a>
	</td>
</tr>

<?php
	if($args["toplamsayac"] == $args["sayac"]){
	?>
		</tbody></table>
	</div>
	<?php
	}
?>
