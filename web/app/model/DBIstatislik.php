<?php
require_once("DBHelper.php");
class DBIstatislik{

	private $mysqli;

	function __construct(){
		$this->mysqli = DBHelper::getConnection();
	}

	function oyunIstatislikleriGetir($id,$islem,$baslangic,$limit){
		if($islem=="bilgisayarkarsi"){//and (siyahID='0' or beyazID='0')
			$sorgu=$this->mysqli->query("select *from oyunlar where (siyahID='$id' or beyazID='$id') and oyunTuru='0' limit $baslangic,$limit;");
		}
		else{// siyahID<>'0' and beyazID<>'0'
			$sorgu=$this->mysqli->query("select *from oyunlar where (siyahID='$id' or beyazID='$id') and oyunTuru='1' limit $baslangic,$limit;");
		}
		if($sorgu === false){
			return false;
		}

		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$dizi=array();
			$i=0;
			while($maclar=$sorgu->fetch_array()){
				$dizi[$i]=array("id"=>$maclar["id"],"siyahID"=>$maclar["siyahID"],"beyazID"=>$maclar["beyazID"],
				"oyunTuru"=>$maclar["oyunTuru"],"tarihOlusturma"=>$maclar["tarihOlusturma"],
				"alistirmaID"=>$maclar["alistirmaID"] ,"kazananID"=>$maclar["kazananID"],
				"sureSiyah"=>$maclar["sureSiyah"],"sureBeyaz"=>$maclar["sureBeyaz"],
				"puanSiyah"=>$maclar["puanSiyah"],"puanBeyaz"=>$maclar["puanBeyaz"]);
				$i++;
			}
			return $dizi;
		}
		else{
			return 0;
		}
	}

	function toplamOyunSayisi($id,$islem){
		if($islem=="bilgisayarkarsi"){//and (siyahID='0' or beyazID='0')
			$sorgu=$this->mysqli->query("select id from oyunlar where (siyahID='$id' or beyazID='$id') and oyunTuru='0';");
		}
		else{// siyahID<>'0' and beyazID<>'0'
			$sorgu=$this->mysqli->query("select id from oyunlar where (siyahID='$id' or beyazID='$id') and oyunTuru='1';");
		}
		if($sorgu === false){
			return false;
		}
		return $sorgu->num_rows;
	}

	function toplamOyunKazanimi($id,$islem){
		if($islem=="bilgisayarkarsi"){//and (siyahID='0' or beyazID='0')
			$sorgu=$this->mysqli->query("select id from oyunlar where (siyahID='$id' or beyazID='$id') and oyunTuru='0' and kazananID='$id';");
		}
		else{// siyahID<>'0' and beyazID<>'0'
			$sorgu=$this->mysqli->query("select id from oyunlar where (siyahID='$id' or beyazID='$id') and oyunTuru='1' and kazananID='$id';");
		}

		if($sorgu === false){
			return false;
		}

		return $sorgu->num_rows;
	}
	function berabereOyunSayisi($id,$islem){
		if($islem=="bilgisayarkarsi"){//and (siyahID='0' or beyazID='0')
			$sorgu=$this->mysqli->query("select id from oyunlar where (siyahID='$id' or beyazID='$id') and oyunTuru='0' and kazananID='-2';");
		}
		else{// siyahID<>'0' and beyazID<>'0'
			$sorgu=$this->mysqli->query("select id from oyunlar where (siyahID='$id' or beyazID='$id') and oyunTuru='1' and kazananID='-2';");
		}

		if($sorgu === false){
			return false;
		}

		return $sorgu->num_rows;
	}
	function devamEdenOyunSayisi($id,$islem){
		if($islem=="bilgisayarkarsi"){//and (siyahID='0' or beyazID='0')
			$sorgu=$this->mysqli->query("select id from oyunlar where (siyahID='$id' or beyazID='$id') and oyunTuru='0' and kazananID='-1';");
		}
		else{// siyahID<>'0' and beyazID<>'0'
			$sorgu=$this->mysqli->query("select id from oyunlar where (siyahID='$id' or beyazID='$id') and oyunTuru='1' and kazananID='-1';");
		}

		if($sorgu === false){
			return false;
		}

		return $sorgu->num_rows;
	}

	function oyunIstatislikleriSayisi($id,$islem){
		if($islem === "bilgisayarkarsi"){//and (siyahID='0' or beyazID='0')
			$sorgu=$this->mysqli->query("select *from oyunlar where oyunTuru='0' and (beyazID='$id') and siyahID='0';");
		}
		else{// siyahID<>'0' and beyazID<>'0'
			$sorgu=$this->mysqli->query("select *from oyunlar where oyunTuru='1' and (siyahID='$id' or beyazID='$id') and siyahID<>'0';");
		}
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

	/* kullaıcı istatislikleri icin verilen kazananid, beyazid veya siyahid den kullanıcı adını veritanından ceker  */
	function iddenKullaniciBul($id){
		if($id === "0"){
			return "Bilgisayar";
		}
		$sorgu=$this->mysqli->query("select kullaniciAdi from kullanicilar where id='$id';");
		if($sorgu === false){
			return false;
		}
		$donen=$sorgu->num_rows;
		if($donen>0){
			while($kayit=$sorgu->fetch_array()){
				return $kayit["kullaniciAdi"];
			}
		}
		else{
			return 0;
		}
	}


	/* sistemem giris yapan ogretmenin grubunda olan ogrencileri getirir */
	function grupdami($kurucuID){
		$query=$this->mysqli->query("select id from gruplar where kurucuID='$kurucuID';");
		if($query === false){
			return false;
		}
		$satir=$query->num_rows;
		if($satir>0){
			$grupid=$query->fetch_array();
			$uyeID=$this->mysqli->query("select uyeID from grupuyeiliskisi where grupID='$grupid[id]';");
			if($uyeID === false){
				return false;
			}
			$uye_sayisi=$uyeID->num_rows;
			if($uye_sayisi>0){
				$dizi=array();
				$i=0;
				while($uyeler=$uyeID->fetch_array()){
					$uye=$this->mysqli->query("select id,adSoyad,kullaniciAdi from kullanicilar where id='$uyeler[uyeID]';");
					$satir_sayisi=$uye->num_rows;
					if($satir_sayisi>0){
						$uyeBilgi=$uye->fetch_array();
						$dizi[$i]=array("id"=>$uyeBilgi["id"],"adSoyad"=>$uyeBilgi["adSoyad"],"kullaniciAdi"=>$uyeBilgi["kullaniciAdi"]);
					}
					$i++;
				}
				return $dizi;
			}
			return 0;
		}
		else{
			return 0;
		}
	}

	function kullaniciAdedi(){
		$sorgu=$this->mysqli->query("select id from kullanicilar where yetki='4' order by kullaniciAdi;");
		if($sorgu === false){
			return false;
		}
		$donen = $sorgu->num_rows;
		if($donen>0){
			return $donen;
		}
		else{
			return 0;
		}
	}

	/* istatislikler sayfasında admin ve ogretmenin kullanıcıların istatisliklerini gormesi icin */
	function kullanicilariGetir($baslangic,$limit){
		$sorgu=$this->mysqli->query("select id,adSoyad,kullaniciAdi,yetki from kullanicilar where yetki='4' order by adSoyad limit $baslangic,$limit;");
		$dizi=array();
		if($sorgu === false){
			return false;
		}
		$donen = $sorgu->num_rows;
		if($donen>0){
			$i=0;
			while($kayitlar=$sorgu->fetch_array()){
				$dizi[$i]=$kayitlar;
				$i++;
			}
			return $dizi;
		}
		else{
			return 0;
		}
	}

	/* $_GET["kulid"]  ile alınan kullanıcının istatisliklerini getirir*/
	function dersIstatislikleriGetir($id,$baslangic,$limit){
		$query = "SELECT K.isim, E.zorluk, E.siteID, A.sonOynamaTarihi, A.oynamaSayisi,A.sure, KA.adSoyad FROM alistirmalar AL, kullanicilar KA, kategoriler K, kategorielemanlari E, alistirmaistatistikleri A
		where AL.id=A.alistirmaID and KA.id=AL.hocaID and E.tur=1 and A.uyeID = ? and E.kategoriID = K.id and A.alistirmaID = E.siteID order by K.id;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $id);

			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
				return false;
			}
			$stmt->bind_result($isim, $zorluk, $siteID, $tarih, $oynamaSayisi,$sure,$hocaAdi);
			$istatsitikler = array();

			while ($stmt->fetch()) {
				$istatsitikler[] = array("isim" => $isim, "zorluk" => $zorluk, "sure" => $sure, "sonOynamaTarihi" => $tarih, "oynamaSayisi" => $oynamaSayisi,"hocaAdi"=>$hocaAdi);
			}
			$stmt->close();
			return $istatsitikler;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;

		/*
		$query=$this->mysqli->query("select id,uyeID,alistirmaID,sonOynamaTarihi,oynamaSayisi from alistirmaistatistikleri where uyeID='$id' limit $baslangic,$limit;");
		if($query === false){
			return false;
		}
		$satir_sayisi=$query->num_rows;
		if($satir_sayisi>0){
			$i=0;
			$dizi=array();
			while($kayitlar=$query->fetch_array()){
				$hocaID=$this->mysqli->query("select id,hocaID from alistirmalar where id='$kayitlar[alistirmaID]';");
				if($hocaID->num_rows>0){
					$hocaIDGetir=$hocaID->fetch_array();
					$hocaAdi=$this->mysqli->query("select id,adSoyad from kullanicilar where id='$hocaIDGetir[hocaID]';");


					$hocaAdiGetir=$hocaAdi->fetch_array();
					$dizi[$i]=array("hocaAdi"=>$hocaAdiGetir["adSoyad"], "alistirmaID"=>$kayitlar["alistirmaID"],
					"sonOynamaTarihi"=>$kayitlar["sonOynamaTarihi"], "oynamaSayisi"=>$kayitlar["oynamaSayisi"]);

				}
				$i++;
			}
			return $dizi;
		}
		else{
			return 0;
		}*/
	}

	function dersIstatislikleriSayisi($id){
		$query=$this->mysqli->query("select alistirmaID,tarihOlusturma,sureBeyaz from oyunlar where oyunturu='2' and beyazID='$id';");
		if($query === false){
			return false;
		}
		$satir_sayisi=$query->num_rows;
		if($satir_sayisi>0){
			return $satir_sayisi;
		}
		else{
			return 0;
		}

	}

}


?>
