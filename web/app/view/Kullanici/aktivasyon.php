


<div class="row">
    <div class="col-md-6 col-md-offset-3">

        <form class="form-inline" action="/web/app/controller/sifremiunuttum.php?islem=aktivasyon&altislem=<?php echo $_GET["altislem"]; ?>" method=post>
            <div class="form-group">
                <label for="exampleInputName2">DOĞRULAMA KODU:</label>
                <input type="text" class="form-control" name="dogrulamakodu" placeholder="Doğrulama Kodu">
            </div>
            <div class="form-group">

                <input type="submit" class="btn btn-primary" value="GÖNDER" >
            </div>
        </form>
    </div>
</div>
