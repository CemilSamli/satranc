<?php
require_once("DBHelper.php");
class DBAdmin{
	private $mysqli;

	function __construct(){
		$this->mysqli = DBHelper::getConnection();
	}

	function kullaniciAdedi(){
		$kullaniciadedi = $this->mysqli->query("select id from kullanicilar;");
		if($kullaniciadedi === false){
			return false;
		}
		$adet = $kullaniciadedi->num_rows;
		return $adet;
	}

	/*  kullanici adi alfebetik sırasına gore kullanıcıların tamammı getirir  */
	function getUsers($baslangic,$kacar){
		$sorgu=$this->mysqli->query("select id,adSoyad,kullaniciAdi,yetki,engelSuresi from kullanicilar order by kullaniciAdi LIMIT ".$baslangic.", ".$kacar.";");

		if($sorgu === false){
			return false;
		}
		$donen = $sorgu->num_rows;
		if($donen>0){
			$dizi=array();
			$i=0;
			while($kayitlar=$sorgu->fetch_array()){
				$dizi[$i]=array("id"=>$kayitlar["id"],"adSoyad"=>$kayitlar["adSoyad"],"kullaniciAdi"=>$kayitlar["kullaniciAdi"],
				"yetki"=>$kayitlar["yetki"],"engelSuresi"=>$kayitlar["engelSuresi"]);
				$i++;
			}
			return $dizi;
		}
		else{
			return 0;
		}
	}

	/* verilen id ye gore istenilen yetki veritabanından guncellenir  */
	function adminYetkiAta($id,$yetki){
		if($this->mysqli->query("update kullanicilar set yetki='$yetki' where id='$id';")){}
		else{ echo "<table><tr><td>Hata olustu</td></tr></table>"; }
	}

	function ogretmenAdedi(){
		$sorgu=$this->mysqli->query("select id from kullanicilar where yetki='1' or yetki='8' or yetki='16' or yetki='32';");
		if($sorgu === false){
			return false;
		}
		else{
			return $sorgu->num_rows;
		}
	}

	/* ogretmenleri veritabanınadan ceker normal ogretmen yetkisi:1 ders ekleme cıkarma yetkili ogretmen yetkisi:8 */
	function teachers($baslangic,$limit){
		$sorgu=$this->mysqli->query("select id,kullaniciAdi,adSoyad,yetki,engelSuresi from kullanicilar where yetki='1' or yetki='8' or yetki='16' or yetki='32' Limit $baslangic,$limit;");
		$dizi=array();
		if($sorgu === false){
			return false;
		}
		$donen = $sorgu->num_rows;
		if($donen>0){
			$i=0;
			while($kayitlar=$sorgu->fetch_array()){
				$dizi[$i]=array("id"=>$kayitlar["id"],"adSoyad"=>$kayitlar["adSoyad"],"kullaniciAdi"=>$kayitlar["kullaniciAdi"],"yetki"=>$kayitlar["yetki"],"engelSuresi"=>$kayitlar["engelSuresi"]);
				$i++;
			}
			return $dizi;
		}
		else{
			return 0;
		}
	}


	/* banla.php den gelen get[islem](engelle veya engeli kaldir) ve get[id](kullanici id si)  ye gore secili olan uyeyi engeller*/
	function uyeEngelle($id,$banlamagun){
		$query="update kullanicilar set engelSuresi=? where id=?;";
		if($ps = $this->mysqli->prepare($query)){
			$ps->bind_param('si',$banlamagun, $id);
			$ps->execute();
			$ps->close();
		}
	}

	/* mevcutguplar.php dosyası icinde cagrılan bu fonksiyon hocanın mevcut bulunan grublarını getirir  */
	function mevcutGruplar($id){
		$sorgu=$this->mysqli->query("select *from gruplar where kurucuID='$id' order by tarihKurulus desc;");
		if($sorgu === false){
			return false;
		}

		$satir_sayisi=$sorgu->num_rows;
		if($satir_sayisi>0){
			$dizi=array();
			$i=0;
			while($gruplar=$sorgu->fetch_array()){
				$dizi[$i]=array("id"=>$gruplar["id"],"adi"=>$gruplar["adi"],"tarihKurulus"=>$gruplar["tarihKurulus"]);
				$i++;
			}
			return $dizi;
		}
		return 0;
	}


	function grupOlustur($grupAdi){
		$tarih=date("Y:m:d H:i:s");
		$query="insert into gruplar(kurucuID,adi,tarihKurulus) values(?,?,?)";
		if($ps = $this->mysqli->prepare($query)){
			$ps->bind_param("iss",$_SESSION["id"],$grupAdi,$tarih);
			$ps->execute();
			$ps->close();
		}
		else{
			echo "Birşeyler Ters Gidiyor Sanki !";
		}
	}

	/* gruplar.php dosyasından secilen mevcut grubun uyelerini getirir  */
	function grupUyeGetir($grupID){
		$uyeIdGetir=$this->mysqli->query("select uyeID from grupuyeiliskisi where grupID='$grupID';");

		if($uyeIdGetir === false){
			return false;
		}

		$dizi = array();
		while($uyeler=$uyeIdGetir->fetch_array()){
			$uyeAdiGetir=$this->mysqli->query("select id,kullaniciAdi,adSoyad from kullanicilar where id='$uyeler[uyeID]';");
			$uyeAdi=$uyeAdiGetir->fetch_array();
			$dizi[]=array("uyeID"=>$uyeAdi["id"],"kullaniciAdi"=>$uyeAdi["kullaniciAdi"],"adSoyad"=>$uyeAdi["adSoyad"]);
		}
		return $dizi;
	}

	/*  mevcut grupta secili olan uyeyi siler */
	function grupUyeSil($uyeID){
		$query="delete from grupuyeiliskisi where uyeID=?;";
		if($ps = $this->mysqli->prepare($query)){
			$ps->bind_param("i",$uyeID);
			$ps->execute();
			$ps->close();
		}
	}

	function grupUyeAra($arama){
		$sorgu = $this->mysqli->query ("select id,adSoyad,kullaniciAdi,yetki from kullanicilar where yetki = '4' and adSoyad like '%$arama%';");

		if($sorgu === false){
			return false;
		}
		$donen = $sorgu->num_rows;
		if($donen>0){
			$dizi=array();
			$i=0;
			while($kayitlar=$sorgu->fetch_array()){
				$dizi[$i]=array("id"=>$kayitlar["id"],"adSoyad"=>$kayitlar["adSoyad"],"kullaniciAdi"=>$kayitlar["kullaniciAdi"]);
				$i++;
			}
			return $dizi;
		}
		else{
			return 0;
		}
	}

	function grupKullaniciVarmi($kullaniciAdi,$grupID){
		$kullaniciIdGetir=$this->mysqli->query("select id from kullanicilar where kullaniciAdi='$kullaniciAdi' and yetki='4';");

		if($kullaniciIdGetir === false){
			return false;
		}
		$satir_sayisi=$kullaniciIdGetir->num_rows;
		if($satir_sayisi==1){
			$kullaniciID=$kullaniciIdGetir->fetch_array();
			$ayniKullaniciVarmi=$this->mysqli->query("select id from grupuyeiliskisi where uyeID='$kullaniciID[id]' and grupID='$grupID';");
			if($ayniKullaniciVarmi === false){
				return false;
			}
			$donen=$ayniKullaniciVarmi->num_rows;
			if($donen == 0){
				return $kullaniciID["id"];
			}
			else{
				return 0;
			}
		}
		else{
			return -1;
		}
	}


	/*   gruplar.php dosyasından gelen mevcut gruplar bolumundeki formdan gelen kullaniciadi ile mevcut gruba uye eklenir */
	function grupUyeEkleme($kullaniciID,$grupID){
		$query="insert into grupuyeiliskisi(uyeID,grupID) values(?,?);";
		if($ps = $this->mysqli->prepare($query)){
			$ps->bind_param("ii",$kullaniciID,$grupID);
			$ps->execute();
			$ps->close();
		}
	}

	/* view/ogretmen/mevcut gruplar.php dosyasından secile grubun id si ile gruplar.php dosyasından cagrılan bu fonskiyon grubu siler  */
	function grupSil($grupID){
		$query="delete from gruplar where id=?;";
		$uyeleriSil="delete from grupuyeiliskisi where grupID=?;";
		if($ps = $this->mysqli->prepare($query)){
			$ps->bind_param("i",$grupID);
			$ps->execute();
			$ps->close();
		}
		if($ps1 = $this->mysqli->prepare($uyeleriSil)){
			$ps1->bind_param("i",$grupID);
			$ps1->execute();
			$ps1->close();
		}
	}

	/* gruplar.php dosyasında gruba tıklayınca gelen  id de bir  grubun olup olmadıgı kontrolu */
	function grupIDKontrol($id){
		$query=$this->mysqli->query("select id from gruplar where id='$id';");
		if($query!==false){
			$satir_sayisi=$query->num_rows;
			if($satir_sayisi>0){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return -1;
		}
	}

	/* girilen altislemdeki grup id giris yapan hocanın grubumu */
	function grupIDHocaKontrol($id){
		$query=$this->mysqli->query("select id from gruplar where id='$id' and kurucuID='$_SESSION[id]';");
		if($query!==false){
			$satir_sayisi=$query->num_rows;
			if($satir_sayisi>0){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return -1;
		}
	}

	function grupAdDuzenle($grupID,$yeniAd){
		$query="update gruplar set adi=? where id=?;";
		if($ps=$this->mysqli->prepare($query)){
			$ps->bind_param("si",$yeniAd,$grupID);
			if (!$ps->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			return true;
		}
		else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	/* Admin sectiği kullanicinin bilgilerini gormesi için */
	function kullaniciBilgileri($id){

		$query=$this->mysqli->query("select adSoyad,email,kullaniciAdi,tarihKayit,avatar,tarihSonGiris,tarihDogum from kullanicilar where id='$id';");
		if($query !== false){
			$satir_sayisi=$query->num_rows;
			if($satir_sayisi>0){
				$dizi = array();
				$i = 0;
				$kayitlar = $query->fetch_array();
				return $kayitlar;


				return $dizi;
			}
			else{
				return false;
			}
		}
		else{
			return -1;
		}
	}


	function aramaYap($arama){
		$sorgu = $this->mysqli->query ("select id,adSoyad,kullaniciAdi,yetki,engelSuresi from kullanicilar where adSoyad like '%$arama%';");

		if($sorgu === false){
			return false;
		}
		$donen = $sorgu->num_rows;
		if($donen>0){
			$dizi=array();
			$i=0;
			while($kayitlar=$sorgu->fetch_array()){
				$dizi[$i]=array("id"=>$kayitlar["id"],"adSoyad"=>$kayitlar["adSoyad"],"kullaniciAdi"=>$kayitlar["kullaniciAdi"],
				"yetki"=>$kayitlar["yetki"],"engelSuresi"=>$kayitlar["engelSuresi"]);
				$i++;
			}
			return $dizi;
		}
		else{
			return 0;
		}
	}

}
?>
