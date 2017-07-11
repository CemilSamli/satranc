
<div class="panel panel-info">
    <div class="panel-heading">
        <?php echo strtoupper($args["baslik"]); ?>
    </div>
    <div class="panel-body">
        <?php
        if($args["resim"] != ""){ ?>
        <img src="<?php echo $args['resim']; ?>" height=240 width=360" alt="" style="float:left"/>
        <?php }  ?>
        <p>
            <?php
                 echo $args["icerik"];
            ?>
        </p>
    </div>
    <div class="panel-footer">
        <?php echo "Tarih".$args["tarih"];  ?>
        <button onclick="goBack()" type="button" class="btn btn-default ">Geri Dön</button>

    </div>
</div>
<script>
    function goBack() {
        window.history.back();
    }
</script>
