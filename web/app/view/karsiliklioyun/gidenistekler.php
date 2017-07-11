
<tr>
<td>
<?php echo $args["bilgi"]["adSoyad"]; ?>
</td>

<td>
<?php echo $args["bilgi"]["tarihIstek"]; ?> 
</td>

<td>
<?php
if($args["bilgi"]["durum"] == 0){  ?>
<a class="btn btn-danger btn-block" role="button" href="/web/app/controller/bildirim.php?islem=iptal&id=<?php echo $args["bilgi"]["id"];  ?> "> İptal </a>
<?php } else { ?>
<a  class="btn btn-success btn-block" role="button" href="/web/app/controller/multiplegame.php?id=<?php echo $args["bilgi"]["oyunID"];  ?> "> Oyuna Git </a>
<?php } ?>
</td>
</tr>

