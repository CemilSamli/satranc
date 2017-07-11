<?php
require_once("DBHelper.php");
class DBOyun {
	private $mysqli;

	public function __construct(){
		$this->mysqli = DBHelper::getConnection();
	}

	public function oyunEkle($userID,$seviye){
		$query = "insert into oyunlar(siyahID, beyazID, oyunTuru, kazananID, sureSiyah, sureBeyaz, puanSiyah, puanBeyaz,seviye) values(0,?,0,-1,0,0,0,0,?);";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('ii', $userID,$seviye);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;

	}

	public function oyunBilgisiEkle($oyunID, $siyahID, $beyazID, $oyunTuru, $kazananID, $sureSiyah, $sureBeyaz, $puanSiyah, $puanBeyaz){
		$renk = $puanBeyaz;
		$puan = $puanSiyah;
		$islem = 0;

		if($renk == 'b'){
			$query = "update oyunlar set siyahID = ? , beyazID = ? , oyunTuru = ? , kazananID = ? , sureSiyah = ? , sureBeyaz = ?, puanSiyah += $puan where id = ?;";
			$islem = 1;
		}else if($renk == 'w'){
			$query = "update oyunlar set siyahID = ? , beyazID = ? , oyunTuru = ? , kazananID = ? , sureSiyah = ?, sureBeyaz = ? , puanBeyaz += $puan where id = ?;";
			$islem = 1;
		}else{
			$query = "update oyunlar set siyahID = ? , beyazID = ? , oyunTuru = ? , kazananID = ? , sureSiyah = ? , sureBeyaz = ? , puanSiyah = ? , puanBeyaz = ? where id = ?;";
			$islem = 2;
		}


		if($stmt = $this->mysqli->prepare($query)){
			if($islem == 2){
				$stmt->bind_param('iiiiiiiii', $siyahID, $beyazID, $oyunTuru, $kazananID, $sureSiyah, $sureBeyaz, $puanSiyah, $puanBeyaz, $oyunID);
			}else{
				$stmt->bind_param('iiiiiii', $siyahID, $beyazID, $oyunTuru, $kazananID, $sureSiyah, $sureBeyaz, $oyunID);
			}

			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}

			//var_dump($stmt);
			//return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		//return -1;
	}

	public function kazananEkle($oyunID, $kazananID){

		$query = "update oyunlar set kazananID = ? where id = ?;";

		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('ii', $kazananID, $oyunID);

			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
	}


	public function hamleEkle($oyunID, $hamle){
		//var_dump($hamle);
		$query = "insert into oyunhamleleri(oyunID, hamleler) values(?,?);";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('is', $oyunID, $hamle);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	public function tekHamleEkle($oyunID, $hamle){
		$hamleler = $this->hamleleriGetir($oyunID).";;;".$hamle;
		//$aciklama = $this->dersAciklamasi($oyunID);
		//$this->hamleEkle($oyunID, $hamleler);

		$query = "update oyunhamleleri set hamleler = ? where oyunID = ?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('si', $hamleler, $oyunID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			//return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		//return -1;
	}

	public function hamleleriGetir($oyunID){
		$query = "select hamleler from oyunhamleleri where oyunID=?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $oyunID);
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
				return $hamleler;
			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
	}
	public function dersAciklamasi($oyunID){
		$query = "select aciklama from dersler where id=?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $oyunID);
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

	public function eskiMaclar($id){
		$sorgu = $this->mysqli->query("select id,beyazID,sureSiyah,sureBeyaz,puanSiyah,puanBeyaz,tarihOlusturma,seviye from oyunlar where beyazID='$id' and oyunTuru=0 and seviye <>0 order by tarihOlusturma desc;");
		if($sorgu === false){
			return false;
		}
		$donen_satir=$sorgu->num_rows;
		if($donen_satir>0){
			$maclar=array();
			$i=0;
			while($kayitlar=$sorgu->fetch_array())
			{
				$maclar[$i]=array("id"=>$kayitlar["id"],"sureSiyah"=>$kayitlar["sureSiyah"],"sureBeyaz"=>$kayitlar["sureBeyaz"],
				"puanSiyah"=>$kayitlar["puanSiyah"],"puanBeyaz"=>$kayitlar["puanBeyaz"],"tarihOlusturma"=>$kayitlar["tarihOlusturma"],"seviye"=>$kayitlar["seviye"]);
				$i++;
			}
			return $maclar;
		}
		else{
			return 0;
		}
	}

	public function oyunuKazanan($oyunID){
		$query = "select kazananID from oyunlar where id=?;";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('i', $oyunID);
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			} else {
				$stmt->bind_result($kazananID);
				/* fetch values */

				if ($stmt->fetch()) {
					$stmt->close();
					return $kazananID;
				}
				return -1;

			}
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}
}
