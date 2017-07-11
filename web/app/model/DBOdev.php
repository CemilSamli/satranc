<?php
require_once("DBHelper.php");
class DBOdev {
	private $mysqli;

	public function __construct(){
		$this->mysqli = DBHelper::getConnection();
	}

	/* $tur : 0 ise kişiye, 1 ise gruba ödev
	 * $sorumluID: kişi yada grup id
	 *
	 */
	public function odevEkle($tur, $sorumluId, $grupId, $alistirmaKategoriID, $alistirmaAdet, $zorluk, $gunSiniri){
		$query = "insert into odevler(`tur`, `sorumluID`, `grupID`, `alistirmaKategoriID`, `zorluk`, `alistirmaAdet`, `tarihVerilis`, `tarihBitis`) values(?,?,?,?,?,?,now(),now() + interval ? day);";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('iiiiiii', $tur, $sorumluId, $grupId, $alistirmaKategoriID, $zorluk, $alistirmaAdet, $gunSiniri);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function odevleriGetir($tur, $sorumluID, $grupId){
		$query = "SELECT `id`, `tur`, `sorumluID`, `grupID`, `alistirmaKategoriID`, `zorluk`, `alistirmaAdet`, `tarihVerilis`, `tarihBitis` FROM `odevler` WHERE `grupID` = ? AND `tur` = ?";
		if ($tur == 0) $query = $query." AND `sorumluID` = ?";

		if($stmt = $this->mysqli->prepare($query)){
			if ($tur == 0)
				$stmt->bind_param('iii', $grupId, $tur, $sorumluID);
			else
				$stmt->bind_param('ii', $grupId, $tur);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$odevler = array();
				$result = $stmt->get_result();
				while($data = $result->fetch_assoc()){
					$odevler[] = $data;
				}
				$stmt->close();
				return $odevler;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	public function ogrenciIcinOdevleriGetir($sorumluID){
		$query = "SELECT  O.id, O.tur, O.sorumluID, O.grupID, O.alistirmaKategoriID, O.zorluk, O.alistirmaAdet, O.tarihVerilis, O.tarihBitis FROM odevler O, grupuyeiliskisi GU WHERE GU.grupID = O.grupID AND ((O.tur = 0 AND O.sorumluID = ?) OR (O.tur = 1 AND GU.uyeID = ?)) group by O.id";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('ii', $sorumluID, $sorumluID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$odevler = array();
				$result = $stmt->get_result();
				while($data = $result->fetch_assoc()){
					$odevler[] = $data;
				}
				$stmt->close();
				return $odevler;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	public function odevGetir($id){
		$query = "SELECT * FROM `odevler` WHERE `id` = ?";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $id);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$odev = null;
				$result = $stmt->get_result();
				if($data = $result->fetch_assoc()){
					$odev = $data;
				}
				$stmt->close();
				return $odev;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	public function alistirmaIcinOdevleriGetir($alistirmaID, $kullaniciID){
		$query = "SELECT  O.id, O.tur, O.sorumluID, O.grupID, O.alistirmaKategoriID, O.zorluk, O.alistirmaAdet, O.tarihVerilis, O.tarihBitis FROM kategorielemanlari KE, odevler O, alistirmalar A WHERE A.id = KE.siteID AND O.alistirmaKategoriID = KE.kategoriID AND A.id = ? AND O.sorumluID = ?";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('ii', $alistirmaID, $kullaniciID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$odevler = array();
				$result = $stmt->get_result();
				while($data = $result->fetch_assoc()){
					var_dump($data);
					$odevler[] = $data;
				}
				$stmt->close();
				return $odevler;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	public function alistirmaIcinGrupOdevleriGetir($alistirmaID, $kullaniciID, $grupID){

		$dba = new DBAlistirma();

		return $db->kategoriIDGetir($alistirmaID);

		$query = "SELECT  O.id, O.tur, O.sorumluID, O.grupID, O.alistirmaKategoriID, O.zorluk, O.alistirmaAdet, O.tarihVerilis, O.tarihBitis FROM odevler O, grupuyeiliskisi GU WHERE GU.grupID = O.grupID AND O.alistirmaKategoriID = ? AND O.grupID = ? AND GU.uyeID = ?";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('iii', $dba->kategoriIDGetir($alistirmaID), $grupID, $kullaniciID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$odevler = array();
				$result = $stmt->get_result();
				while($data = $result->fetch_assoc()){
					var_dump($data);
					$odevler[] = $data;
				}
				$stmt->close();
				return $odevler;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	public function odevSonucGuncelle($odev, $uyeID, $alistirmaID){
		$query = "INSERT IGNORE INTO odevdegerlendirmesi(odevIliskisiID, `uyeID`, `alistirmaID`) VALUES (?,?,?)";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('iii', $odev, $uyeID, $alistirmaID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	/*
	 * return: yapmamiş ise 0, yapmış ise 1
	 */
	public function odevYaptimi($odevId, $kullaniciID){
		$odev = $this->odevGetir($odevId);
		$query = "SELECT count(*) FROM odevdegerlendirmesi where odevIliskisiID = ? AND uyeID = ?";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('ii', $odevId, $kullaniciID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$durum = 0;
				$stmt->bind_result($count);
				$hamleler = null;
				if ($stmt->fetch()) {
					//echo "adet: ".$odev["alistirmaAdet"]." dbAdet:$count, ödev:$odevId, kullanici:$kullaniciID<br>";
					if($odev["alistirmaAdet"] <= $count)
						$durum = 1;
				}
				$stmt->close();
				return $durum;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	/* !!!KULLANMA!!!
	 * return: süre var ise 0, yapmamiş ise 1, yapmış ise 2
	 *
	 */
	public function odevDurumu($id, $kullaniciID){
		$query = "SELECT AI.sonOynamaTarihi, O.tarihVerilis, O.tarihBitis FROM kategorielemanlari KE, alistirmaistatistikleri AI, odevler O WHERE KE.tur = 1 AND KE.kategoriID = O.alistirmaKategoriID AND AI.alistirmaID = KE.siteID AND AI.uyeID = O.sorumluID AND O.sorumluID = ? AND O.id = ?";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('ii', $sorumluID, $id);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$sonuc = 0;
				$stmt->bind_result($sonOynama, $verilis, $bitis);
				$hamleler = null;
				if ($stmt->fetch()) {
					$sonOynama = strtotime($sonOynama);
					$verilis = strtotime($verilis);
					$bitis = strtotime($bitis);
					$curtime = time();

					if(($sonOynama-$verilis) > 0 && ($sonOynama-$bitis) > 0 ) {     //1800 seconds
					  //do stuff
					}

					$hamleler = array("fen" => $fen, "tarih" => $tarih, "aciklama" => $aciklama);
				}
				$stmt->close();
				return $sonuc;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}
/*
	public function dersGetir($dersID){
		$query = "select fenBaslangic, tarihEklenme, aciklama from dersler where id=?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $dersID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($fen, $tarih, $aciklama);
				$hamleler = null;
				if ($stmt->fetch()) {
					$hamleler = array("fen" => $fen, "tarih" => $tarih, "aciklama" => $aciklama);
				}

				$stmt->close();
				return $hamleler;

			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	public function dersSil($id){
		$query = "delete from dersler where id=?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $id);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}
	*/
}
