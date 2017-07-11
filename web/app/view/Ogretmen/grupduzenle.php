


<div class="row">
    <div class="col-md-6 col-md-offset-3">

        <form class="form-inline" action="/web/app/controller/grupduzenle.php?grupid=<?php echo $_GET["grupid"]; ?>" method=post name=grupduzenle>
            <div class="form-group">
                <label for="exampleInputName2">Grup Adı:</label>
                <input type="text" class="form-control" name=grupyeniad placeholder="Yeni Ad">
            </div>
            <div class="form-group">

                <input type="submit" class="btn btn-primary" value="Değiştir" >
            </div>
            <div class="form-group">

                <a href="/web/app/controller/gruplar.php" type="submit" class="btn btn-danger"  >Geri </a>
            </div>


        </form>
    </div>
</div>

