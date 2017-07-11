
    <?php

if($args["sayac"]==1){
	if($args["turu"]=="haber")


		echo "<div class='panel-heading'>HABERLER</div>";
	else if($args["turu"]=="duyuru")
		echo "<div class='panel-heading'>DUYURULAR</div>";

    echo "<div class='panel-body'><table class='table table-hover'>";
}
?>



    <tr>


        <td><a href=devam.php?id=<?php echo $args["veriler"]["id"]; ?> > <?php echo strtoupper($args["veriler"]["baslik"]); ?> </a></td>

     <?php echo "<td>Haber Eklenme Tarihi:".$args["veriler"]["tarih"]."</td>";

   ?>

     </tr>
<?php
if($args["toplamsayac"] == $args["sayac"]){
    echo "</table>";
	if($args["ssayisi"] > 1) {   ?>
    <div class="panel-footer">
                <nav>
                <ul class="pagination">


<?php
	for($i=0;$i<$args["ssayisi"];$i++){
		?>
		<li>
			<?php
		echo "<a href=/web/app/controller/haberler.php?islem=";
		if($args["turu"] == "haber"){
			echo "haberler";
		}
		else{
			echo "duyurular";
		}

		echo "&s=".($i+1).">".($i+1);

		echo "</a></li>";
	}

			?>

</ul>
</nav>
        </div>
		<?php   }
	}

?>
</div>