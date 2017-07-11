
<div class="row">
    <div class="col-md-3">
        <?php

        if($args["avatar"]!=""){
            echo "<img class='img-circle' height=150 width=150 src='".$args["avatar"]."?".rand()."'>";
        }
        else{
            echo "Resim Yok";
        }
        ?>
    </div>

    <div class="col-md-9">
        <h1><small>Kişisel Bilgiler</small></h1>
        <hr>
        <table class="table table-hover ">
            <tr class="info">
                <td>Ad Soyad:</td>
                <td><?php echo ucwords(strtolower($args["adSoyad"])) ?></td>
            </tr>
            <tr class="active">
                <td>Kullanıcı Adı:</td>
                <td><?php echo $args["kullaniciAdi"] ?></td>
            </tr>
            <tr class="info">
                <td>e-Posta:</td>
                <td><?php echo $args["email"] ?></td>
            </tr>
            <tr class="active">
                <td>D.Tarihi:</td>
                <td><?php echo $args["tarihDogum"] ?></td>
            </tr>
            <tr class="info">
                <td>Son Giriş Tarihi</td>
                <td><?php echo $args["tarihSonGiris"] ?></td>
            </tr>
           
           

           
        </table>
    </div>

</div>
