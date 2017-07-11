<script type="text/javascript">
	function reveal(element, id, sayfa){
		var content = document.getElementsByClassName("content"+id)[0];

		var xmlhttp;
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				//xmlhttp.responseText
				content.innerHTML=xmlhttp.responseText;
				//console.log(xmlhttp.responseText);
			}
		}
		var request = "?islem=icerik&grupid="+getCookie("gid")+
						"&uyeid="+id+
						"&sayfa="+getCookie("sayfa")+
						"&sayfaic="+sayfa;
		xmlhttp.open("GET", request, true);
		xmlhttp.send();
	}

	function getCookie(cname) {
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for(var i=0; i<ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1);
			if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
		}
		return "";
	}
</script>

	<tr>
        <div class="panel panel-danger">
            <div class="panel-heading">Alıştırma İstatistikleri</div>
	<?php

	for ($i=0; $i<count($args['uyeler']); $i++) {
	?>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="headingOne<?php echo $i ?>">
                    <h4 class="panel-title">
                        <a onclick="reveal(this, '<?php echo $args['uyeler'][$i]['id']; ?>', 1)" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <?php echo $args['uyeler'][$i]['adSoyad']; ?>
                        </a>
                    </h4>
                </div>
            </div>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne<?php echo $i ?>">
            <div class="panel-body">
                <p class="content<?php echo $args['uyeler'][$i]['id']; ?>"> </p>
            </div>
        </div>

	<?php
	}
	?>

            </div>


