<?php
require_once("DBHelper.php");
class DBGrup {
	private $mysqli;

	public function __construct(){
		$this->mysqli = DBHelper::getConnection();
	}

	public function uyeListesiGetir($grupID){
		$query = "select K.id, K.adSoyad from grupuyeiliskisi G, kullanicilar K where grupID= ? and G.uyeID = K.id;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $grupID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($uyeID, $adSoyad);
				$uyeler = null;
				while ($stmt->fetch()) {
					$uyeler[] = array("id" => $uyeID, "adSoyad" => $adSoyad);
				}
				$stmt->close();
				return $uyeler;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	public function ogrencisimi($grupID, $uyeID, $ogretmenID){
		$query = "select uyeID from grupuyeiliskisi iliski, gruplar G where iliski.grupID = G.id and grupID= ? and iliski.uyeID = ? and G.kurucuID = ?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('iii', $grupID, $uyeID, $ogretmenID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($id);
				$ogrencisi = false;
				/* fetch values */
				if ($stmt->fetch()) {
					$ogrencisi = true;
				}

				/* close statement */
				$stmt->close();
				return $ogrencisi;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	public function gruplariGetir($ogretmenID){
		$query = "select id from gruplar where kurucuID = ?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $ogretmenID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($id);
				$gruplar = array();
				/* fetch values */
				while ($stmt->fetch()) {
					$gruplar[] = $id;
				}
				/* close statement */
				$stmt->close();
				return $gruplar;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	public function uyesiOlunanGruplar($kullaniciID){
		$query = "SELECT G.id, G.kurucuID, G.adi FROM grupuyeiliskisi GI, gruplar G WHERE G.id = GI.uyeID AND G.id = ? ";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $kullaniciID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$datas = array();
				$result = $stmt->get_result();
				while($data = $result->fetch_assoc()){
					$datas[] = $data;
				}
				$stmt->close();
				return $datas;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}
}
