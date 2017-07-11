
<table  id="example" class="table table-striped table-bordered nowrap table table-hover">
<thead>
<tr><th>Tarih</th><th>Beyaz Puan</th><th>Siyah Puan</th><th>Beyaz Süre</th><th>Siyah Süre</th><th>Oyun Seviyesi</th><th>Oyuna Git</th></tr>
</thead>
</tbody>
<?php
	for($i=0;$i<count($args);$i++){
?>
	<tr>
		<td>
		<?php 
		echo $args[$i]["tarihOlusturma"];
		?>
		</td>

		<td>
		<?php 
		echo $args[$i]["puanBeyaz"];
		?>
		</td>

		<td>
		<?php 
		echo $args[$i]["puanSiyah"];
		?>
		</td>

		<td>
		<?php 
		echo $args[$i]["sureBeyaz"]." saniye";
		?>
		</td>

		<td>
		<?php 
		echo $args[$i]["sureSiyah"]." saniye";
		?>
		</td>

		<td>
		<?php 
		echo $args[$i]["seviye"];
		?>
		</td>

		<td>
		<a class="btn btn-default" role="button" href="/web/app/controller/oyun.php?oyunid=<?php echo $args[$i]["id"]; ?>&l=<?php echo $args[$i]["seviye"]; ?>">Oyuna Git</a>
		</td>
	</tr>

<?php
	}
?>
</tbody></table>

<script type="text/javascript">
	$(document).ready(function() {
		$('#example').DataTable();
	} );
</script>