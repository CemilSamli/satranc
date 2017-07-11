<label class="control-label" for="inputFirstName"><b>Aranacak Kelime:</b></label>
							      <input type="text" id="inputFirstName" name="arama" placeholder="Arama metni">
								  
							      <span id="arama-kontrol" class="help-inline"></span>
								  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript">
	$(function(){
		/* Aranan deðer */
		$("input[name='arama']").keyup(function(){
			/* Deðeri alýyoruz */
			var deger = "arama="+$(this).val();
			/* Aldýðýmýz deðeri POST ediyoruz */
			$.ajax({
				type: "POST",
				url: "test_ajax.php?s=arama",
				data: deger,
				/* Gelen cevabý ekrana bastýrýyoruz */
				success : function(cevap){
					$("#arama-kontrol").show().html(cevap);
				}
			});
		})
	});
</script>