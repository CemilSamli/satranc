<?php
require_once("DBHelper.php");
class DBKategori {
	private $mysqli;


	public function __construct(){
		$this->mysqli = DBHelper::getConnection();
	}

	public function kategoriEkle($isim){
		$query = "insert into kategoriler(isim) values(?);";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('s', $isim);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function kategoriSil($id){
		$query = "delete from kategoriler where id=?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $id);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
	}

	public function elemanEkle($kategoriID, $zorluk, $tur, $siteID){
		$query = "insert into kategorielemanlari(kategoriID, zorluk, tur, siteID) values(?,?,?,?);";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('iiii', $kategoriID, $zorluk, $tur, $siteID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function elemanSil($id){
		$query = "delete from kategorielemanlari where id = ?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $id);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
	}

	/**
	 * $tur: 0 => Ders, 1 => Alıştırma
	 */
	public function kategorileriGetir($tur, $elemaniOlmayanlar = false){
		if($elemaniOlmayanlar === true){
			$query = "select id, isim from kategoriler";
		} else {
			$query = "select distinct K.id, K.isim, E.zorluk from kategoriler K, kategorielemanlari E where E.kategoriID = K.id and E.tur = ? order by kategoriID";
		}

		if($stmt = $this->mysqli->prepare($query)){
			if (!$elemaniOlmayanlar) $stmt->bind_param('i', $tur);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				if($elemaniOlmayanlar === true){
					$stmt->bind_result($id, $isim);
				} else {
					$stmt->bind_result($id, $isim, $zorluk);
				}
				$isimler = array();
				while($stmt->fetch()){
					$isimler[$id][3] = $isim;
					if($elemaniOlmayanlar === true){
						$isimler[$id][0] = 1;
					} else {
						$isimler[$id][$zorluk] = 1;
					}
					//$isimler[$id][9][] = $zorluk;
				}
				//{id, isim, }
				/* close statement */
				$stmt->close();
				return $isimler;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
	}
	
	public function kategoriGetir($id){
		$query = "select isim from kategoriler where id=?";

		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $id);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($isim);
				$kategori = "";
				if($stmt->fetch()){
					$kategori = $isim;
				}
				$stmt->close();
				return $kategori;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
	}


	public function siteIDleriniGetir($kategorID, $zorluk, $tur){
		$query = "select siteID,kategoriID,zorluk,tur from kategorielemanlari where kategoriID=? and zorluk=? and tur=?;";


		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('iii', $kategorID, $zorluk, $tur);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($siteID,$kategoriID,$zorluk,$tur);
				$idler = array();
				while($stmt->fetch()){
					$idler[] = $siteID;
					//echo "asd";
				}
				//var_dump($idler);
				/* close statement */
				$stmt->close();
				return $idler;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
	}


///////////////////////////////***************************//////////////////////////
	public function agacGetir($tur){
		$tur = $tur === 0 ? 0 : 1;
		//$queryDers = "select K.id, E.siteID from kategoriler K, kategoriElemanlari E where E.kategoriID = K.id and tur=0 order by kategoriID";
		$queryAlistirma = "select K.id, E.zorluk, E.siteID from kategoriler K, kategorielemanlari E where E.kategoriID = K.id and tur=? order by kategoriID";


		if($stmt = $this->mysqli->prepare($queryAlistirma)){
			$stmt->bind_param('i', $tur);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($id, $zorluk, $siteID);
				$isimler = null;
				while($stmt->fetch()){
					$ar = array($zorluk, $siteID);
					$isimler[$id][] = $ar;
				}

				/* close statement */
				$stmt->close();
				return $isimler;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
	}


	public function alistirmaGrubuEkle($isim){
		$query = "insert into kategoriler(isim, tur) values(?,1);";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('s', $isim);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function yapilanAlistirmaGetir(){
		$query = "select alistirmaID from alistirmaistatistikleri where uyeID=?;";

		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $_SESSION["id"]);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($alistirmaID);
				$yapilanlar = array();
				while($stmt->fetch()){
					$yapilanlar[] = $alistirmaID;
				}
				$stmt->close();
				return $yapilanlar;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
	}


}
