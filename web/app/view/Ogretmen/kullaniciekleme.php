
<form action="/web/app/controller/kullaniciekle.php?islem=<?php echo $_GET["islem"]; ?>" method="post">
    <div class="form-group">

        <input type="text" name="adsoyad" class="form-control" id="exampleInputEmail1" placeholder="Ad Soyad">
    </div>
    <div class="form-group">

        <input type="text" name="kullaniciadi" class="form-control" id="exampleInputPassword1" placeholder="Kullancı Adı">
    </div>
    <div class="form-group">
        <input type="email" name="email" class="form-control" id="exampleInputPassword1" placeholder="e-Posta">


    </div>

    <div class="form-group">
        <input type="date"  name="dogumtarih" class="form-control" id="exampleInputPassword1" placeholder="Doğum Tarihi">
		

    </div>
	<div class="form-group"> (örneğin; 10/01/1992)</div>
    <button type="submit" class="btn btn-warning">Kaydet</button>
</form>
