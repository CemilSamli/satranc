<?php

require_once("DBHelper.php");
class DBOgretmen{
	private $mysqli;

	function __construct(){
		$this->mysqli = DBHelper::getConnection();
	}

	/* Ogretmen kullaniciekleme.php formundakii bilgileri doldurarak sisteme yeni bir kullanici ekler */
	function yeniUyeEkle($adSoyad,$kullaniciAdi,$tarihDogum,$email,$tarihKayit,$yetki,$mesaj,$passHash){
		$passHash=md5(sha1(md5($passHash)));

		$query="insert into kullanicilar(adSoyad,kullaniciAdi,email,passHash,tarihKayit,tarihDogum,yetki)
		values(?,?,?,?,?,?,?);";
		if($stmt = $this->mysqli->prepare($query)){
			$stmt->bind_param('ssssssi', $adSoyad, $kullaniciAdi, $email, $passHash, $tarihKayit, $tarihDogum, $yetki);
			$mail=mail($email,"Satranc Sistemine Kayıt",$mesaj,"http://www.satrancegitim.com");
			if (!$stmt->execute()) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
			}
			if($mail){
				echo "basarılı";
			}
			else{
				echo "Email Gönderilirken Hata Oluştu!";
			}
			return $stmt->insert_id;
		} else {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($this->mysqli)), E_USER_ERROR);
		}
		return -1;
	}

	/* uye ekleme kısmında forma girilen kullaniciadi ve email in baska bir kullanıcıda olup olmadıgı denetleniyor */
	function uyekontrol($kullaniciadi,$email){//ayni kullanici adina sahip baska bir kullanici varmi?
		$sorgu=$this->mysqli->query("select kullaniciAdi,email from kullanicilar where kullaniciAdi='$kullaniciadi' or email='$email';");
		if($sorgu !== false){
			if($kayit=$sorgu->fetch_array()){
				return false;
			}
		}
		return true;
	}




}

?>
