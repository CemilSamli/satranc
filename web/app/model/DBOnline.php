<?php
require_once("DBHelper.php");
class DBOnline{
	private $mysqli;

	function __construct(){
		$this->mysqli = DBHelper::getConnection();
	}

	function kullanicilar(){
		$sorgu = $this->mysqli->query("select id,adSoyad,aktif from kullanicilar where id<>'$_SESSION[id]' order by aktif desc;");
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$kullanicilar=array();
			$i=0;
			while($kayitlar=$sorgu->fetch_array())
			{
				$kullanicilar[$i]=array("id"=>$kayitlar["id"],"adSoyad"=>$kayitlar["adSoyad"],"aktif"=>$kayitlar["aktif"]);
				$i++;
			}
			return $kullanicilar;
		}
		else{
			return 0;
		}
	}

	function ekle($meydanOkunanID,$meydanokuyanIP){
		$tarih = date("Y-m-d H:i:s");
		$durum = 0;
		$query = "insert into karsiliklioyunlar(meydanOkuyanID,meydanOkunanID,durum,tarihIstek,meydanOkuyanIP) values(?,?,?,?,?);";
		if($ps = $this->mysqli->prepare($query)){
			$ps->bind_param("iiiss",$_SESSION["id"],$meydanOkunanID,$durum,$tarih,$meydanOkuyanIP);
			if(!$ps->execute()){//patlar
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}

		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	function gelenIstekler(){
		$sorgu = $this->mysqli->query("select id,meydanOkuyanID,meydanOkunanID,tarihIstek,durum,oyunID from karsiliklioyunlar where meydanOkunanID ='$_SESSION[id]' and durum='0' order by tarihIstek desc;");

		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		$kullanicilar=array();
			$i=0;
			while($kayitlar=$sorgu->fetch_array())
			{
				$isimBul = $this->mysqli->query("select id,adSoyad from kullanicilar where id = '$kayitlar[meydanOkuyanID]';");
				$isim=$isimBul->fetch_array();
				$kullanicilar[$i]=array("id"=>$kayitlar["id"],"durum"=>$kayitlar["durum"],"adSoyad"=>$isim["adSoyad"],
				"isimid"=>$isim["id"],"tarihIstek"=>$kayitlar["tarihIstek"],"oyunID"=>$kayitlar["oyunID"]);
				$i++;
			}

			return $kullanicilar;
	}

	function gidenIstekler(){
		$sorgu = $this->mysqli->query("select id,meydanOkuyanID,meydanOkunanID,tarihIstek,durum,oyunID from karsiliklioyunlar where meydanOkuyanID ='$_SESSION[id]' and durum='0' order by tarihIstek desc;");

		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$kullanicilar=array();
			$i=0;
			while($kayitlar=$sorgu->fetch_array())
			{
				$isimBul = $this->mysqli->query("select id,adSoyad from kullanicilar where id = '$kayitlar[meydanOkunanID]';");
				$isim=$isimBul->fetch_array();
				$kullanicilar[$i]=array("id"=>$kayitlar["id"],"durum"=>$kayitlar["durum"],"adSoyad"=>$isim["adSoyad"],
				"isimid"=>$isim["id"],"tarihIstek"=>$kayitlar["tarihIstek"],"oyunID"=>$kayitlar["oyunID"]);
				$i++;
			}

			return $kullanicilar;
		}
		else{
			return 0;
		}
	}

	function gidenIstekIptal($id){
		$query="delete from karsiliklioyunlar where id=?;";
		if($ps = $this->mysqli->prepare($query)){
			$ps->bind_param("i",$id);
			$ps->execute();
			$ps->close();
		}
	}

	function oyunIslemTamamla($id,$oyunID,$durum,$tarihKabul,$meydanOkunanIP){
		$query="update karsiliklioyunlar set tarihKabul=? , durum=? , oyunID=? , meydanOkunanIP=? where id=?;";
		if($ps=$this->mysqli->prepare($query)){
			$ps->bind_param("siisi",$tarihKabul,$durum,$oyunID,$meydanOkunanIP,$id);
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

	function meydanOkuyanIDBul($oyunID){
		$sorgu = $this->mysqli->query("select id,meydanOkuyanID from karsiliklioyunlar where id ='$oyunID';");

		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$kayitlar=$sorgu->fetch_array();
			$meydanOkuyanID=$kayitlar["meydanOkuyanID"];
			return $meydanOkuyanID;
		}
		else{
			return 0;
		}
	}

	function oyunOlustur($meydanOkuyanID,$meydanOkunanID,$oyunTuru,$tarihKabul,$karsilikliOyunID){

		$query = "insert into oyunlar(siyahID,beyazID,oyunTuru,tarihOlusturma,karsilikliOyunID) values(?,?,?,?,?);";
		if($ps = $this->mysqli->prepare($query)){
			$ps->bind_param("iiisi",$meydanOkunanID,$meydanOkuyanID,$oyunTuru,$tarihKabul,$karsilikliOyunID);
			if(!$ps->execute()){//patlar
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}

		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	function oyunIDBul($id){
		$sorgu = $this->mysqli->query("select id,karsilikliOyunID from oyunlar where karsilikliOyunID ='$id';");

		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$kayitlar=$sorgu->fetch_array();
			$karsilikliOyunID=$kayitlar["id"];
			return $karsilikliOyunID;
		}
		else{
			return 0;
		}
	}

	function ilkHamleOlustur($oyunID,$hamle){
		$query = "insert into oyunhamleleri(oyunID,hamleler) values(?,?);";
		if($ps = $this->mysqli->prepare($query)){
			$ps->bind_param("is",$oyunID,$hamle);
			if(!$ps->execute()){//patlar
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}

		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	function ilkFenStringEkle($ilkFen,$oyunID){
		$query="update oyunlar set sonFenString=? where id=?;";
		if($ps=$this->mysqli->prepare($query)){
			$ps->bind_param("si",$ilkFen,$oyunID);
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

	function fenKaydet($oyunID,$fen,$sb,$sw,$puan,$renk){
		if($renk == 'b'){
			$query="update oyunlar set sonFenString=?, sureSiyah=sureSiyah+?, puanSiyah=puanSiyah+? where id=?;";
			if($ps=$this->mysqli->prepare($query)){
				$ps->bind_param("siii",$fen,$sw,$puan,$oyunID);
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
		else{
			$query="update oyunlar set sonFenString=?, sureBeyaz=sureBeyaz+?, puanBeyaz=puanBeyaz+? where id=?;";
			if($ps=$this->mysqli->prepare($query)){
				$ps->bind_param("siii",$fen,$sw,$puan,$oyunID);
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


	}

	function fenStringCek($oyunID){
		$sorgu = $this->mysqli->query("select id,sonFenString from oyunlar where id ='$oyunID';");

		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$kayitlar=$sorgu->fetch_array();
			$fen=$kayitlar["sonFenString"];
			return $fen;
		}
		else{
			return 0;
		}
	}

	function renkBul($oyunID){
		$sorgu = $this->mysqli->query("select id,beyazID,siyahID from oyunlar where id ='$oyunID';");
		$meydanOkuyanID = $this->mysqli->query("select id,meydanOkuyanID,meydanOkunanID from karsiliklioyunlar where oyunID ='$oyunID';");
		if($sorgu === false && $meydanOkuyanID === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$kayitlar=$sorgu->fetch_array();
			$meydanOkuyan=$meydanOkuyanID->fetch_array();
			if($kayitlar["beyazID"] == $_SESSION["id"] && $meydanOkuyan["meydanOkuyanID"] == $_SESSION["id"]){
				return "w";
			}
			else{
				return "b";
			}
		}
		else{
			return 0;
		}
	}

	public function oyunHamleleri($id){
		$query = "select oyunID,hamleler from oyunhamleleri where oyunID = ?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $id);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($id, $hamleString);
				$hamleler = array();
				/* fetch values */
				while ($stmt->fetch()) {
					$hamleler[]=array("hamleler"=>$hamleString);
				}
				/* close statement */
				$stmt->close();
				return $hamleler;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	public function oyunHamleleriString($id){
		$query = "select oyunID,hamleler from oyunhamleleri where oyunID = ?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $id);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($id, $hamleString);
				$hamleler = "";
				/* fetch values */
				if ($stmt->fetch()) {
					$hamleler = $hamleString;
				}
				/* close statement */
				$stmt->close();
				return $hamleler;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	function eskimaclar($id,$oyunID){
		if($oyunID == -1){
			$sorgu = $this->mysqli->query("select id,beyazID,siyahID,sureSiyah,sureBeyaz,puanSiyah,puanBeyaz,tarihOlusturma,seviye,kazananID from oyunlar where (beyazID='$id' or siyahID='$id') and oyunTuru=1 and seviye=0 order by tarihOlusturma desc;");
		}else{
			$sorgu = $this->mysqli->query("select id,beyazID,siyahID,sureSiyah,sureBeyaz,puanSiyah,puanBeyaz,tarihOlusturma,seviye,kazananID from oyunlar where id='$oyunID';");
		}
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$maclar=array();
			$i=0;
			$karsi = 0;
			while($kayitlar=$sorgu->fetch_array())
			{
				if($id == $kayitlar["beyazID"]){
					$karsi = $kayitlar["siyahID"];
				}else{
					$karsi = $kayitlar["beyazID"];
				}
				$sorgu1 = $this->mysqli->query("select id,adSoyad from kullanicilar where id='$karsi';");
				if($sorgu === false){
					return false;
				}
				$karsiAd = $sorgu1->fetch_array();
				$maclar[$i]=array("id"=>$kayitlar["id"],"sureSiyah"=>$kayitlar["sureSiyah"],"sureBeyaz"=>$kayitlar["sureBeyaz"],"adSoyad"=>$karsiAd["adSoyad"],
				"puanSiyah"=>$kayitlar["puanSiyah"],"puanBeyaz"=>$kayitlar["puanBeyaz"],"tarihOlusturma"=>$kayitlar["tarihOlusturma"],
				"seviye"=>$kayitlar["seviye"],"beyazID"=>$kayitlar["beyazID"],"siyahID"=>$kayitlar["siyahID"], "kazananID" => $kayitlar["kazananID"]);
				$i++;
			}
			return $maclar;
		}
		else{
			return 0;
		}
	}

	function oyunSirasiGetir($oyunID){
		$query = "select sonFenString from oyunlar where id=?;";

		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $oyunID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($sonFenString);
				/* fetch values */

				if ($stmt->fetch()) {
					$stmt->close();
					return $sonFenString;
				}
			}
			return -1;
		}else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;

	}

	function oyuncuIsimGetir($oyunID){
		$query = "select meydanOkuyanID, meydanOkunanID from karsiliklioyunlar where oyunID=?;";
		$isimler = array();
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $oyunID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($meydanOkuyanID,$meydanOkunanID);
				/* fetch values */

				if ($stmt->fetch()) {

					$stmt->close();
					$meydanOkuyan = "select adSoyad from kullanicilar where id=?;";
					if($stmt = $this->mysqli->prepare($meydanOkuyan)){
						$stmt->bind_param('i', $meydanOkuyanID);
						if (!$stmt->execute()) {
							trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
						} else {
							$stmt->bind_result($meydanOkuyanIsim);
							/* fetch values */

							if ($stmt->fetch()) {
								$stmt->close();
								$isimler[0] = $meydanOkuyanIsim;
							}
						}
					}


					$meydanOkunan = "select adSoyad from kullanicilar where id=?;";
					if($stmt = $this->mysqli->prepare($meydanOkunan)){
						$stmt->bind_param('i', $meydanOkunanID);
						if (!$stmt->execute()) {
							trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
						} else {
							$stmt->bind_result($meydanOkunanIsim);
							/* fetch values */

							if ($stmt->fetch()) {
								$stmt->close();
								$isimler[1] = $meydanOkunanIsim;
							}
						}

					}
				}
				$isimler[2] = $meydanOkuyanID;
				$isimler[3] = $meydanOkunanID;

			}
			return $isimler;
		}else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;

	}
}

?>
