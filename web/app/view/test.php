<div class="col-md-12">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<input type="text" class="form-control"  name="arama" placeholder="ARA">
			</div>
		</div>
	</div>
</div>
<span id="arama-kontrol" class="help-inline"></span>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript">
	$(function(){
		/* Aranan de�er */
		$("input[name='arama']").keyup(function(){
			/* De�eri al�yoruz */
			var deger = "arama="+$(this).val();
			/* Ald���m�z de�eri POST ediyoruz */
			$.ajax({
				type: "POST",
				url: "ara.php?s=arama&sayfa=<?php echo $args; if(!empty($_GET["altislem"])){ echo "&altislem=".$_GET["altislem"]; } if(!empty($_GET["islem"])){ echo "&islem=".$_GET["islem"]; } if(!empty($_GET["id"])) { echo "&id=".$_GET["id"]; }  ?>",
				data: deger,
				/* Gelen cevab� ekrana bast�r�yoruz */
				success : function(cevap){
					$("#arama-kontrol").show().html(cevap);
				}
			});
		})
	});
</script>
