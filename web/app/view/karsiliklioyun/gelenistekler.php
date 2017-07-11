
<tr>
<td>
<?php echo $args["bilgi"]["adSoyad"]; ?>
</td>

<td>
<?php echo $args["bilgi"]["tarihIstek"]; ?> 
</td>


<?php
if($args["bilgi"]["durum"] == 0){  ?>
<td>
<a class="btn btn-info btn-block" role="button" href="/web/app/controller/multiplegame.php?islem=oyna&id=<?php echo $args["bilgi"]["id"]; ?> "> Oyna </a>
</td>
<td>
<a class="btn btn-danger btn-block" role="button" href="/web/app/controller/bildirim.php?islem=iptal&id=<?php echo $args["bilgi"]["id"];  ?> "> İptal </a>
</td>
<?php } else { ?>
<td>
<a class="btn btn-success btn-block" role="button" href="/web/app/controller/multiplegame.php?id=<?php echo $args["bilgi"]["oyunID"];  ?> "> Oyuna Git </a>
</td>
<td></td>
<?php } ?>
</tr>

