<?php
require_once("DBHelper.php");
require_once("DBOdev.php");
class DBAlistirma {
	private $mysqli;

	public function __construct(){
		$this->mysqli = DBHelper::getConnection();
	}

	public function alistirmaEkle($fenBaslangic, $sinir, $aciklama){
		$query = "insert into alistirmalar(fenBaslangic, sinirHamle, aciklama,hocaID) values(?,?,?,?);";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('sisi', $fenBaslangic, $sinir, $aciklama,$_SESSION["id"]);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function alistirmaGetir($id){
		$query = "select id,fenBaslangic, sinirHamle, aciklama from alistirmalar where id = ?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $id);

			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
				return false;
			}

			$stmt->bind_result($id,$fenBaslangic,$sinirHamle,$aciklama);


			$alistirma = array();
			while ($stmt->fetch()) {
			array_push($alistirma, $fenBaslangic, $sinirHamle, $aciklama,$id);
			return $alistirma;
			  }

		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}


	public function kisiButunIstatistikleriGetir($uyeID){
		$query = "SELECT K.isim, E.zorluk, E.siteID, A.sonOynamaTarihi, A.oynamaSayisi FROM kategoriler K, kategoriElemanlari E, alistirmaistatistikleri A where E.tur=1 and A.uyeID = ? and E.kategoriID = K.id and A.alistirmaID = E.siteID order by K.id;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $uyeID);

			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
				return false;
			}
			$stmt->bind_result($isim, $zorluk, $siteID, $tarih, $oynamaSayisi);
			$istatsitikler = array();

			while ($stmt->fetch()) {
				$istatsitikler[] = array("isim" => $isim, "zorluk" => $zorluk, "sure" => 1, "tarih" => $tarih, "oynamaSayisi" => $oynamaSayisi);
			}
			$stmt->close();
			return $istatsitikler;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function istatistikleriGetir($uyeID){
		$query = "select alistirmaID, oynamaSayisi, sonOynamaTarihi from alistirmaistatistikleri where uyeID=?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $uyeID);

			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
				return false;
			}
			$stmt->bind_result($alistirmaID, $sure, $tarih);
			$istatsitikler = array();

			if ($stmt->fetch()) {
				$istatsitikler[] = array($alistirmaID, $sure, $tarih);
			}
			$stmt->close();
			return $istatsitikler;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function oynamaSayisiGetir($uyeID, $alistirmaID){
		$query = "select alistirmaID,uyeID,oynamaSayisi from alistirmaistatistikleri where alistirmaID = ? and uyeID=?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('ii', $alistirmaID, $uyeID);

			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
				return false;
			}
			$stmt->bind_result($alistirmaID,$uyeID,$oynamaSayisi);
			$value = 0;

			if ($stmt->fetch()) {
				$value = $oynamaSayisi;
			}
			$stmt->close();
			return $value;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function oynamaSuresiGetir($uyeID, $alistirmaID){
		$query = "select alistirmaID,uyeID,sure from alistirmaistatistikleri where alistirmaID = ? and uyeID=?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('ii', $alistirmaID, $uyeID);

			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
				return false;
			}
			$stmt->bind_result($alistirmaID,$uyeID,$sure);
			$value = 0;

			if ($stmt->fetch()) {
				$value = $sure;
			}
			$stmt->close();
			return $value;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function istatistikEkle($uyeID, $alistirmaID, $sure){
		$dbOdev = new DBOdev();
		$odevler = $dbOdev->alistirmaIcinOdevleriGetir($alistirmaID, $uyeID);
		foreach ($odevler as $odev){
			$dbOdev->odevSonucGuncelle($odev['id'], $uyeID, $alistirmaID);
		}

		$oynamaSayisi = $this->oynamaSayisiGetir($uyeID, $alistirmaID);
		$oynamaSuresi = $this->oynamaSuresiGetir($uyeID, $alistirmaID);
		$insert = false;

		if($oynamaSayisi++ == 0){
			$insert = true;
			$query = "insert into alistirmaistatistikleri(sonOynamaTarihi, oynamaSayisi,sure, uyeID, alistirmaID) values(now(),?,?,?,?);";
		} else {
			$query = "update alistirmaistatistikleri set sonOynamaTarihi = now(), oynamaSayisi = ?, sure=? where uyeID = ? and alistirmaID = ? ;";

			$sure = $oynamaSuresi.";".$sure;

		}
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('isii', $oynamaSayisi, $sure, $uyeID, $alistirmaID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			if ($insert){
				return $stmt->insert_id;
			}

		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function kategoriIDGetir($id){
		$query = "select kategoriID from kategorielemanlari where siteID=? and tur='1';";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $id);

			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
				return false;
			}
			$stmt->bind_result($kategoriID);
			$value = 0;
			if ($stmt->fetch()) {
				$value = $kategoriID;
			}
			$stmt->close();
			return $value;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function ToplamAlistirmalariGetir($kategoriID){
		$query = "select siteID from kategorielemanlari where kategoriID=? and tur='1';";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $kategoriID);

			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
				return false;
			}else{
				$stmt->bind_result($siteID);
				$idler = array();
				while($stmt->fetch()) {
					$idler[] = $siteID;
				}
				$stmt->close();
				return $idler;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}
}
