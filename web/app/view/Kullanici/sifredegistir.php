<div class="col-md-8">
    <div class="row">

            <form class="form-horizontal"  action="/web/app/controller/profilim.php?islem=degistir" method="post">
                <div class="form-group">

                    <div class="col-md-offset-4 col-md-4">
                        <input type="password" class="form-control"  name="eskisifre"  placeholder="Mevcut Parola">
                    </div>
                </div>
                <div class="form-group">

                    <div class="col-md-offset-4 col-md-4">
                        <input type="password" class="form-control"  name="yenisifre" placeholder="Yeni Parola">
                    </div>
                </div>
                <div class="form-group">

                    <div class="col-md-offset-4 col-md-4">
                        <input type="password" class="form-control"  name="yenisifretekrar" placeholder="Yeni Parola Tekrar">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-4 col-md-4">
                        <a href="/web/app/controller/profilim.php?islem=profilduzenleme_formu" class="btn btn-danger btn-block">Geri</a>

                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-offset-4 col-md-4">
                        <button type="submit" class="btn btn-success btn-block">Kaydet</button>
                    </div>
                </div>
            </form>


    </div>
</div>




