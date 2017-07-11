 <form action="/web/app/controller/mesajgonder.php?id=<?php echo $args["id"]; ?>" method=post class="form-horizontal">
     <div class="form-group">
         <?php echo "Kime : ".ucwords(strtolower($args["adSoyad"])); ?>
     </div>
     <div class="form-group">
         <input class="form-control" name="konu" type="text" size="24" placeholder="Konu">
     </div>
     <div class="form-group">
         <textarea class="form-control" name="mesaj" rows="10" cols="20" placeholder="Mesaj"></textarea>
     </div>
     <div class="form-group">
         <button  type="submit" class="btn-block btn-success btn" >Gönder</button>
     </div>
     <div class="form-group">
         <a href="/web/app/controller/mesaj_kutusu.php?islem=mesaj_kutusu" class="btn btn-info btn-block">Geri Dön</a>
     </div>





</form>
