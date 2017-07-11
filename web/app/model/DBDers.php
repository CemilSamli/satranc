<?php
require_once("DBHelper.php");
class DBDers {
	private $mysqli;

	public function __construct(){
		$this->mysqli = DBHelper::getConnection();
	}

	public function dersEkle($fenBaslangic, $aciklama){
		$query = "insert into dersler(fenBaslangic, tarihEklenme, aciklama) values(?,now(),?);";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('ss', $fenBaslangic, $aciklama);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function hamleEkle($dersID, $hamleler){
		$query = "insert into dershamleleri(dersID, hamleler) values(?,?);";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('is', $dersID, $hamleler);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function dersGetir($dersID){
		$query = "select fenBaslangic, tarihEklenme, aciklama from dersler where id=?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $dersID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($fen, $tarih, $aciklama);
				$hamleler = null;
				/* fetch values */
				if ($stmt->fetch()) {
					$hamleler = array("fen" => $fen, "tarih" => $tarih, "aciklama" => $aciklama);
					/*$hamleler[] = $fen;
					$hamleler[] = $tarih;
					$hamleler[] = $aciklama;*/
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

	public function hamleleriGetir($dersID){
		$query = "select hamleler from dershamleleri where dersID=?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $dersID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($hamle);

				$hamleler = "";
				/* fetch values */
				if ($stmt->fetch()) {
					$hamleler = $hamle;
				}


				/* close statement */

				$stmt->close();
				//echo $hamleler;
				return $hamleler;

			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return false;
	}

	public function dersAciklamasi($dersID){
		$query = "select aciklama from dersler where id=?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $dersID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($aciklama);
				$hamleler = "";
				/* fetch values */
				if ($stmt->fetch()) {
					$stmt->close();
					return $aciklama;
				}
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return "";
	}

	public function hamleleriSil($dersID){
		$query = "delete from dershamleleri where dersID=?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $dersID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
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
}
