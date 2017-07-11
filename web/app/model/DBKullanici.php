<?php
require_once("DBHelper.php");
class DBKullanici{
	private $mysqli;

	function __construct(){
		$this->mysqli = DBHelper::getConnection();
	}

	function kullaniciDogrumu($user, $password,$ip){
		$username=$user;
		$pass=$password;
		$giristarih = date("Y.m.d H:i:s");
		$sorgu=$this->mysqli->query("select id,adSoyad,kullaniciAdi,email,yetki,aktif from kullanicilar where kullaniciAdi='$username' and passHash='$pass';");
		$banlimi=$this->mysqli->query("select adSoyad,kullaniciAdi,engelSuresi from kullanicilar where kullaniciAdi='$username' and passHash='$pass' and engelSuresi>'$giristarih';");

		if($sorgu === false){
			return false;
		}
		else{
			if($banlimi === false){
				return false;
			}
			else{
			$bangunu=$banlimi->fetch_array();
			$donen_ban=$banlimi->num_rows;
			$donen_satir = $sorgu->num_rows;
			if($donen_satir>0){
				if($donen_ban==0){
					$aktif=1;
					while($kayitlar=$sorgu->fetch_array())
					{
						$_SESSION["oturum"]=true;
						$_SESSION["id"]=$kayitlar["id"];
						$_SESSION["adSoyad"]=$kayitlar["adSoyad"];
						$_SESSION["kullaniciadi"]=$kayitlar["kullaniciAdi"];
						$_SESSION["email"]=$kayitlar["email"];
						$_SESSION["yetki"]=$kayitlar["yetki"];
						$_SESSION["aktif"]=1;
						$_SESSION["ip"]=$ip;
					}
					$guncelle="update kullanicilar set aktif=? and tarihSonGiris=? where id=?;";
					if($ps = $this->mysqli->prepare($guncelle)){
						$ps->bind_param("isi",$aktif,$giristarih,$_SESSION["id"]);
						if (!$ps->execute()) {
							trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
						}
						if(!empty($_GET["a"])){
							if($_GET["a"] == "mobil"){
								header("Location:giris.php?a=mobil");
							}
						}else{
							header("Location:giris.php");
						}
					}
					else {
						trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
					}
				}
				else{
					echo "Bu Kullanici ".$bangunu["engelSuresi"]." Tarihine Kadar Engellenmiþtir!";
				}
			}
			else{
				return 0;
			}
		}
		}
	}

	function cikis(){
		$aktif=0;
		$query = "update kullanicilar set aktif=? where id=?;";
		if($ps = $this->mysqli->prepare($query)){
			$ps->bind_param("ii",$aktif,$_SESSION["id"]);
			if (!$ps->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
		}
		else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
	}

	function uyekontrol($kullaniciadi,$email){//ayni kullanici adina sahip baska bir kullanici varmi?
		$sorgu=$this->mysqli->query("select kullaniciAdi,email from kullanicilar where kullaniciAdi='$kullaniciadi' or email='$email';");
		if($sorgu !== false){
			if($kayit=$sorgu->fetch_array()){
				return false;
			}
		}
		return true;
	}

	function uyeOl($adsoyad,$kullaniciadi,$sifre,$eposta,$tarih,$avatar,$yetki,$dogumtarih){//uye olmasi icin
		$query="insert into kullanicilar(adSoyad,kullaniciAdi,passHash,email,tarihKayit,avatar,yetki,tarihDogum)values(?,?,?,?,?,?,?,?);";
		if($ps = $this->mysqli->prepare($query)){
			$ps->bind_param("ssssssis",$adsoyad,$kullaniciadi,$sifre,$eposta,$tarih,$avatar,$yetki,$dogumtarih);
			if (!$ps->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
		}
		else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
	}

	function mesajKutusu($okundumu){//gelenmesaj varmi
		$sorgu=$this->mysqli->query("select id from mesajlar where okundumu='hayir' and aliciid='$_SESSION[id]';");
		if($sorgu === false){
			return false;
		}
		$gelenmesajlar=$sorgu->num_rows;
		if($gelenmesajlar>0){
			return $gelenmesajlar;
		}
		else{
			return 0;
		}
	}

	function mesajKullanicilar($baslangic,$limit){//mesaj kutusundaki kullanici listesi
		$sorgu=$this->mysqli->query("select *from kullanicilar order by kullaniciAdi limit $baslangic,$limit;");
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$kullanicilar=array();
			$i=0;
			while($kayitlar=$sorgu->fetch_array())
			{
				$kullanicilar[$i]=array("id"=>$kayitlar["id"],"adSoyad"=>$kayitlar["adSoyad"],"yetki"=>$kayitlar["yetki"]);
				$i++;
			}
			return $kullanicilar;
		}
		else{
			return 0;
		}
	}

	function mesajKullaniciAdedi(){
		$sorgu=$this->mysqli->query("select *from kullanicilar;");
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			return $donen_satir;
		}
		else{
			return 0;
		}
	}
	function mesajAliciBilgileri($aliciid){
		$sorgu = $this->mysqli->query("select adSoyad from kullanicilar where id='$aliciid'");//alicinin adi ve soyadi cekildi
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$kayitlar=$sorgu->fetch_array();
			return $kayitlar;
		}
		else{
			return 0;
		}
	}

	function mesajGonder($gonderenid,$aliciid,$konu,$mesaj,$tarih,$okundumu){
		$ps=$this->mysqli->prepare("insert into mesajlar(gonderenID,aliciID,baslik,mesaj,tarihGonderilen,okundumu) values(?,?,?,?,?,?);");
		$ps->bind_param("iissss",$gonderenid,$aliciid,$konu,$mesaj,$tarih,$okundumu);
		$ps->execute();
		$ps->close();
	}

	function gonderilenMesajlar($baslangic,$limit){
		$sorgu=$this->mysqli->query("select *from mesajlar where gonderenid='$_SESSION[id]' order by tarihGonderilen desc limit $baslangic,$limit;");
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$dizi=array();
			$i=0;
			while($kayitlar=$sorgu->fetch_array()){
				$alici=$this->mysqli->query("select adSoyad from kullanicilar where id='$kayitlar[aliciID]'");
				$veri=$alici->fetch_array();

				$dizi[$i]=array("adSoyad"=>$veri["adSoyad"],"baslik"=>$kayitlar["baslik"],"mesaj"=>$kayitlar["mesaj"],
				"okunduTarih"=>$kayitlar["okunduTarih"],"tarihGonderilen"=>$kayitlar["tarihGonderilen"],
				"okundumu"=>$kayitlar["okundumu"],"id"=>$kayitlar["id"],"aliciID"=>$kayitlar["aliciID"],"gonderenID"=>$kayitlar["gonderenID"],"mesajiSilenID"=>$kayitlar["mesajiSilenID"]);
				$i++;
			}
			return $dizi;
		}
		else{
			return 0;
		}
	}

	function gonderilenMesajSayisi(){
		$query = $this->mysqli->query("select id from mesajlar where gonderenID='$_SESSION[id]';");
		if($query === false){
			return false;
		}
		$adet = $query->num_rows;
		if($adet > 0){
			return $adet;
		}
		return 0;
	}

	function gelenMesajlar($okundu,$tarih,$baslangic,$limit){
		$sorgu=$this->mysqli->query("select *from mesajlar where aliciID='$_SESSION[id]' order by tarihGonderilen desc limit $baslangic,$limit;");
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$dizi=array();
			$i=0;
			while($kayitlar=$sorgu->fetch_array()){
				$alici=$this->mysqli->query("select id,adSoyad from kullanicilar where id='$kayitlar[gonderenID]'");
				$veri=$alici->fetch_array();
				$ps=$this->mysqli->prepare("update mesajlar set okundumu=?,okunduTarih=? where gonderenID=?");
				$ps->bind_param("ssi",$okundu,$tarih,$veri["id"]);
				$ps->execute();
				$ps->close();
				$dizi[$i] = array("id"=>$kayitlar["id"],"adSoyad"=>$veri["adSoyad"],"baslik"=>$kayitlar["baslik"],
				"mesaj"=>$kayitlar["mesaj"],"tarihGonderilen"=>$kayitlar["tarihGonderilen"],"okundumu"=>$kayitlar["okundumu"],
				"okunduTarih"=>$kayitlar["okunduTarih"],"gonderenID"=>$kayitlar["gonderenID"],"aliciID"=>$kayitlar["aliciID"],"mesajiSilenID"=>$kayitlar["mesajiSilenID"]);
				$i++;
			}
			return $dizi;
		}
		else{
			return 0;
		}
	}

	function gelenMesajSayisi($aliciID){
		$query = $this->mysqli->query("select id from mesajlar where aliciID='$aliciID';");
		if($query === false){
			return false;
		}
		$adet = $query->num_rows;
		if($adet > 0){
			return $adet;
		}
		return 0;
	}

	function mesajSil($mesajid){
		$sorgu = $this->mysqli->query("select mesajiSilenID from mesajlar where id='$mesajid';");
		if($sorgu === false){
			return false;
		}
		$mesajiSilen = $sorgu->fetch_array();

		if($mesajiSilen["mesajiSilenID"] == 0){
			$ps=$this->mysqli->prepare("update mesajlar set mesajiSilenID=? where id=?;");
			$ps->bind_param("ii",$_SESSION["id"],$mesajid);
			$ps->execute();
			$ps->close();
		}
		else{
			$ps=$this->mysqli->prepare("delete from mesajlar where id=?;");
			$ps->bind_param("i",$mesajid);
			$ps->execute();
			$ps->close();
		}
	}

	function idDogrumu($id){
		$query = $this->mysqli->query("select id from kullanicilar where id='$id';");
		if($query === false){
			return false;
		}
		if($query->num_rows > 0){
			return 1;
		}
		return 0;
	}

	function profiliGoruntule(){
		$sorgu=$this->mysqli->query("select * from kullanicilar where id='$_SESSION[id]'");
		if($sorgu === false){
			return false;
		}
		$donen = $sorgu->num_rows;
		if($donen>0){
			$veriler=$sorgu->fetch_array();
			return $veriler;
		}
		else{
			return 0;
		}
	}

	function profilValueDegerGetir(){
		$sorgu=$this->mysqli->query("select *from kullanicilar where id='$_SESSION[id]'");
		if($sorgu === false){
			return false;
		}
		$donen = $sorgu->num_rows;
		if($donen>0){
			$veriler=$sorgu->fetch_array();
			return $veriler;
		}
		else{
			return 0;
		}
	}

	function girisYapanKullaniciBilgileri(){
		$query=$this->mysqli->query("select kullaniciAdi,adSoyad,email,avatar from kullanicilar where id='$_SESSION[id]';");//giris yapan kullanici bilgileri
		$veriler=$query->fetch_array();
		return $veriler;
	}

	function emailAdresiFarklimi($eposta){
		$sorgu=$this->mysqli->query("select email from kullanicilar where email='$eposta' and id<>'$_SESSION[id]';");//ayný eposta adresinde baska biri varmý?
		if($sorgu !== false){
			if($kayit=$sorgu->fetch_array()){
				return false;
			}
		}
		return true;
	}

	function profiliDuzenle($adsoyad,$eposta,$konum){
		$ps=$this->mysqli->prepare("update kullanicilar set adSoyad=? , email=? , avatar=? where id=? ;");
		$ps->bind_param("sssi",$adsoyad,$eposta,$konum,$_SESSION["id"]);
		$ps->execute();
		$ps->close();
		$_SESSION["email"]=$eposta;
		$_SESSION["adSoyad"]=$adsoyad;
	}

	function sifreKullaniciBilgiler(){
		$query=$this->mysqli->query("select id,passHash from kullanicilar where id='$_SESSION[id]';");//giris yapan kullanici bilgileri
		$veriler=$query->fetch_array();
		return $veriler;
	}

	function sifreDegistir($yenisifre){
		$ps=$this->mysqli->prepare("update kullanicilar set passHash=? where id=? ;");
		$ps->bind_param("si",$yenisifre,$_SESSION["id"]);
		$ps->execute();
		$ps->close();
	}

	function haberler($turu,$baslangic,$limit){
		$sorgu=$this->mysqli->query("select * from haberler where turu='$turu' order by id Desc limit $baslangic,$limit;");
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$haber=$sorgu->fetch_array();
			$dizi=array();
			for($i=0;$i<$donen_satir;$i++)
			{
				$dizi[$i]=array("id"=>$haber["id"],"baslik"=>$haber["baslik"],"icerik"=>$haber["icerik"],"resim"=>$haber["resim"],"tarih"=>$haber["tarih"]);
				$haber=$sorgu->fetch_array();
			}
			return $dizi;
		}
		else{
			return 0;
		}
	}

	function haberSil($id){
		$ps=$this->mysqli->prepare("delete from haberler where id=?;");
		$ps->bind_param("i",$id);
		$ps->execute();
		$ps->close();
	}

	function haberduyuruekle($baslik,$icerik,$turu,$konum,$tarih){
		$query = "insert into haberler(baslik,icerik,turu,resim,tarih) values(?,?,?,?,?);";
		if($ps = $this->mysqli->prepare($query)){
			$ps->bind_param("sssss",$baslik,$icerik,$turu,$konum,$tarih);
			if(!$ps->execute()){//patlar
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	function duyurular($turu,$baslangic,$limit){
		$sorgu=$this->mysqli->query("select * from haberler where turu='$turu' order by id Desc limit $baslangic,$limit;");
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		$dizi=array();
		if($donen_satir>0){
			$duyuru=$sorgu->fetch_array();
			for($i=0;$i<$donen_satir;$i++)
			{
				$dizi[$i]=array("id"=>$duyuru["id"],"baslik"=>$duyuru["baslik"],"icerik"=>$duyuru["icerik"],"resim"=>$duyuru["resim"],"tarih"=>$duyuru["tarih"]);
				$duyuru=$sorgu->fetch_array();
			}
			return $dizi;
		}
		else{
			return 0;
		}
	}

	function duyuruDuzenle($id,$baslik,$icerik){
		$ps=$this->mysqli->prepare("update haberler set baslik=?, icerik=? where id=?;");
		$ps->bind_param("ssi",$baslik,$icerik,$id);
		$ps->execute();
		$ps->close();
	}

	function haberduyurudevam($id){
		$sorgu=$this->mysqli->query("select *from haberler where id='$id';");
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		$dizi=array();
		if($donen_satir>0){
			$devam=$sorgu->fetch_array();
			for($i=0;$i<$donen_satir;$i++){
				$dizi[0]=array("id"=>$devam["id"],"baslik"=>$devam["baslik"],"icerik"=>$devam["icerik"],"resim"=>$devam["resim"],"tarih"=>$devam["tarih"],"turu"=>$devam["turu"]);
				$devam=$sorgu->fetch_array();
			}
			return $dizi;
		}
		else{
			return 0;
		}
	}

	function haberduzenle($id,$baslik,$icerik,$konum){
		$ps=$this->mysqli->prepare("update haberler set baslik=?, icerik=?,resim=? where id=?;");
		$ps->bind_param("sssi",$baslik,$icerik,$resim,$id);
		$ps->execute();
		$ps->close();
	}

	function haberSayisi(){
		$sorgu=$this->mysqli->query("select * from haberler where turu='haber';");
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			return $donen_satir;
		}
		else{
			return 0;
		}
	}

	function duyuruSayisi(){
		$sorgu=$this->mysqli->query("select * from haberler where turu='duyuru';");
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			return $donen_satir;
		}
		else{
			return 0;
		}
	}


	function sifremiUnuttum($email){
		$sorgu=$this->mysqli->query("select id from kullanicilar where email='$email';");
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir > 0){
			$id = $sorgu->fetch_array();
			$sayi = $id[0];
			return $sayi;
		}
		return 0;
	}

	function dogrulamaKodGuncelle($id,$kod){
		$query="update kullanicilar set aktivasyon=? where id=?;";
		if($ps = $this->mysqli->prepare($query)){
			$ps->bind_param("si",$kod,$id);
			if(!$ps->execute()){//patlar
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
		}
		else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
	}

	function aktivasyonKodunuAl($id){
		$sorgu=$this->mysqli->query("select aktivasyon from kullanicilar where id='$id';");
		if($sorgu === false){
			return false;
		}
		if($sorgu->num_rows > 0){
			$donen=$sorgu->fetch_array();
			$dizi=array();
			$dizi=array("aktivasyon" => $donen["aktivasyon"]);
			return $dizi;
		}
		else{
			return 0;
		}
	}

	function yeniSifreAl($id,$sifre){
		$ps=$this->mysqli->prepare("update kullanicilar set passHash=? where id=?;");
		$ps->bind_param("si",$sifre,$id);
		$ps->execute();
		$ps->close();
	}

	function aktivasyonKodGuncelle($id,$kod){
		$ps=$this->mysqli->prepare("update kullanicilar set aktivasyon=? where id=?;");
		$ps->bind_param("si",$kod,$id);
		$ps->execute();
		$ps->close();
	}

	function kullaniciVarmi($id){
		$sorgu = $this->mysqli->query("select id from kullanicilar where id='$id';");
		if($sorgu === false){
			return false;
		}
		if($sorgu->num_rows > 0){
			return 1;
		}
		else{
			return 0;
		}
	}
	/*
	function ogretmenleriBul(){
		
		$dizi = array();
		if($ps = $this->mysqli->prepare("select grupID from grupuyeiliskisi where uyeID=?;")){
			$ps->bind_param("i",$_SESSION["id"]);
			if($ps->execute()){
				$ps->bind_result($grupID);
				while($ps->fetch()) {
					if($stmt = $this->mysqli->prepare("select kurucuID from gruplar where id=?;")){
						$stmt->bind_param("i",$grupID);
						if($stmt->execute()){
							$stmt->bind_result($kurucuID);
							if($ogr = $this->mysqli->prepare("select adSoyad from kullanicilar where id=?;")){
								$ogr->bind_param("i",$kurucuID);
								$ogr->execute();
								$ogr->bind_result($adSoyad);
								$dizi[] = $adSoyad;
							}
						}
					}
				}
			}
		}
		else {
			echo "hata = ".mysqli_error;
		}
		return $dizi;
	}*/
	
}
?>
