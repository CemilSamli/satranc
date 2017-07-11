

<div class="col-md-2"></div>
<div class="col-md-8">
	<?php $dersKeys = array_keys($args['kategoriler']); ?>
	<table  id="example" class="table table-striped table-bordered nowrap table table-hover">
	<thead>
		<tr><th>Kategori Adı</th><th>Sil</th></tr>
	</thead>
	</tbody>
		<?php for ($i=$args['sayfa']*$args['veriSayisi']; $i<count($dersKeys) && $i<($args['sayfa']+1)*$args['veriSayisi']; $i++) { ?>
			<tr>
				<td><?php echo $args['kategoriler'][$dersKeys[$i]][3]; ?></td>
				<td><a href="?islem=sil&id=<?php echo $dersKeys[$i]; ?>">Sil</a></td>
			</tr>
		<?php } ?>
	</tbody></table>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#example').DataTable();
		} );
	</script>


    <br><br>
	<a class="btn btn-info btn-block" role="button" data-toggle=modal data-target=.derskaydet>Ekle</a>
	<br><br>


	<div class="modal fade derskaydet" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="get">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3>Kategori Ekle</h3>
					</div>
					<div class="modal-body">
						<div class="form-inline">
                            <div class="form-group">
                                <input class="form-control" placeholder="Kategori Adı"  type="text" name="isim" maxlength="100">
                            </div>
                            <div class="form-group">
                                <input class="btn btn-danger btn-block" type="submit" value="Ekle">
                            </div>

                        </div>






					</div>
                    <div class="modal-footer">
                        <input type="hidden" name="islem" value="ekle">
                        <input type="hidden" name="tur" value="ders">
                    </div>

				</form
			</div>
		</div>
	</div>


</div>
