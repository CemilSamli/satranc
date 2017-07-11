<table class="table table-hover">
    <tr>
        <th>Oyun Adı</th>
        <th>Zorluk Seviyesi</th>
        <th>Tarih</th>
        <th>Oyun Sayısı</th>
    </tr>
	<tr>
	<?php
	/*
	("isim" => $isim, "zorluk" => $zorluk, "sure" => $sure, "tarih" => $tarih, "oynamaSayisi" => $oynamaSayisi)
	*/
	$istatistikler = $args['istatistikler'];
	for ($i=$args['sayfa']*$args['sayfaBasinaSatir']; $i<count($istatistikler) && $i<($args['sayfa']+1)*$args['sayfaBasinaSatir']; $i++){
    $level=$istatistikler[$i]['zorluk'];
	?>
		<tr>
			<td>
				<?php echo $istatistikler[$i]['isim']; ?>
			</td>
        <td>
            <?php if($level == 0 ) echo "Kolay"; ?>
            <?php if($level == 1 ) echo "Normal"; ?>
            <?php if($level == 2 ) echo "Zor"; ?>
        </td>

        <td>
            <?php echo $istatistikler[$i]['tarih']; ?>
        </td>	<td>
            <?php echo $istatistikler[$i]['oynamaSayisi']; ?>
        </td>
		</tr>
	<?php
	}
	?>
	</tr>
</table>
<nav>
    <ul class="pagination">
<?php
	$sayfaBaslangic = ($args['sayfa']-5)<=0 ? 0 : $args['sayfa']-5;
	$sayfaBitis = ($args['sayfa']+5)>= count($istatistikler)/$args['sayfaBasinaSatir'] ? count($istatistikler)/$args['sayfaBasinaSatir'] : $args['sayfa']+5;
	for ($i=$sayfaBaslangic; $i < $sayfaBitis; $i++) {
         echo "<li><a href='#' onclick=\"reveal(this, ".$args['uyeID'].", ".($i+1).")\">$i</a></li>";

	}
	?>




    </ul>
</nav>