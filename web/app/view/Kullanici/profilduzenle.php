

<form class="form-horizontal" role="form" action="/web/app/controller/profilim.php?islem=profilduzenle" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label class="control-label col-sm-2" for="email">Ad Soyad:</label>
        <div class="col-sm-10">
            <input type="text"  name="adsoyad" class="form-control" value="<?php echo $args["adSoyad"] ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="pwd">e-Posta:</label>
        <div class="col-sm-10">
            <input type="email" name="eposta" class="form-control"  value="<?php echo $args["email"] ?>" >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="pwd">Fotoğraf:</label>
        <div class="col-sm-10">
            <input type="file" name="resim" value="<?php echo $args["avatar"]; ?>" class="form-control"  value="<?php echo $args["email"] ?>" >
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-success">Kaydet</button>
            <button type="reset" class="btn btn-danger">Temizle</button>
        </div>
    </div>
</form>

