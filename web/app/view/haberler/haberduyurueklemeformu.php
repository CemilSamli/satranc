

<form action="/web/app/controller/haberler.php?islem=<?php echo $_GET["islem"]."me"; ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <input type="text" name="baslik" class="form-control" placeholder="Konu">
    </div>
    <div class="form-group">
        <textarea class="form-control" rows="10" name="icerik"  placeholder="Mesaj"></textarea>

    </div>
    <?php
    if($_GET["islem"]=="haberekle"){ ?>
        <div class="form-group">
            <input type="file" name="resim" class="form-control" id="exampleInputPassword1">
        </div>


    <?php
    }
    ?>

    <button type="submit" class="btn btn-default">EKLE</button>
</form>
