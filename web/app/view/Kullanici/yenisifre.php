
<div class="row">
    <div class="col-md-6 col-md-offset-3">

        <form class="form-inline" action="/web/app/controller/sifremiunuttum.php?islem=<?php echo $_GET["islem"]; ?>&altislem=<?php echo $_GET["altislem"]; ?> " method=post>
            <div class="form-group">
                <label for="exampleInputName2">Yeni Şifre:</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="sifre" placeholder="Yeni Şifre">
                </div>
            </div>

			<div class="form-group">
                <label for="exampleInputName2">Yeni Şifre Tekrar:</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="sifretekrar" placeholder="Yeni Şifre Tekrar">
                </div>
            </div>

            <div class="form-group">

                <input type="submit" class="btn btn-primary" value="GÖNDER" >
            </div>
        </form>
    </div>
</div>

</form>
