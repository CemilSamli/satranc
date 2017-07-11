<?php
require_once("DBHelper.php");
class DBSetup{
	private $mysqli;
	private $version = 1;

	function __construct(){
		$this->mysqli = DBHelper::getConnection(); //static fonksiyon çağırımı
	}

	function status(){
		$query = "show tables like 'config'";
		if($result = $this->mysqli->query($query)){
			if($result->num_rows > 0){
				$query = "select version from config;";
				if($result = $this->mysqli->query($query)){
					if($row = $result->fetch_row()){
						return (int)$row[0];
					}
				}
			}
			//var_dump($result);
			if($result !== true)
				$result->free();
		}
		return -1;
	}

	///
	/// Veritabanı tablolarının silinip tekrar oluşturulmasını sağlar.
	/// Return: Başarılı olursa 0, aksi takdirde hata kodunu dönderir.
	///
	function setup(){
		$query = "
					drop table if exists config;
					create table config (
						id int auto_increment primary key,
						version int
					);
					insert into config(version) values(".$this->version.");

					drop table if exists kullanicilar;
					create table kullanicilar(
						id int auto_increment primary key,
						kullaniciAdi varchar(20),
						adSoyad varchar(30),
						email varchar(256),
						passHash varchar(256),
						tarihKayit timestamp,
						tarihSonGiris timestamp,
						tarihDogum datetime,
						avatar varchar(150),
						puan int,
						seviye int,
						yetki smallint,
						aktif tinyint,
						aktivasyon varchar(6),
						engelSuresi timestamp
					);

					drop table if exists mesajlar;
					create table mesajlar(
						id int auto_increment primary key,
						gonderenID int,
						aliciID int,
						okundumu tinyint,
						mesaj varchar(1000),
						baslik varchar(100),
						tarihGonderilen datetime,
						okunduTarih datetime
					);

					drop table if exists oyunlar;
					create table oyunlar(
						id int auto_increment primary key,
						siyahID int,
						beyazID int,
						oyunTuru int /* 0->yapayzeka, 1->p2p, 2->alistirma */ ,
						alistirmaID int /* Eğer alistirma oyunu ise alistirmanin IDsi*/,
						tarihOlusturma timestamp,
						kazananID int,
						sureSiyah int,
						sureBeyaz int,
						puanSiyah int,
						puanBeyaz int,
						karsiliklioyunID int,
						sonFenString varchar(200)
					);

					drop table if exists oyunIciMesajlar;
					create table oyunIciMesajlar(
						id int auto_increment primary key,
						gonderenId int,
						aliciID int,
						mesaj varchar(500),
						tarih timestamp
					);

					/*Bilgisayara yada rakibe karşı yapılan oyunların hamleleri tutulacak. FIDE tarafından belirlenmiş olan 75 hamle sınırı kullanılmakta.*/
					drop table if exists oyunhamleleri;
					create table oyunhamleleri(
						id int auto_increment primary key,
						oyunID int,
						hamleler varchar(1000)
					);

					drop table if exists alistirmalar;
					create table alistirmalar (
						id int auto_increment primary key,
						fenBaslangic varchar(100),
						hocaID int,
						sinirHamle int,
						aciklama varchar(1000)
					);

					/* Odev iliskisi */
					drop table if exists odevler;
					create table odevler(
						id int auto_increment primary key,
						tur tinyint, /* öğrenciye(0) yada gruba(1) verildiğini gösteriyor*/
						ogrenciID int,
						hocaID int,
						alistirmaID int,
						tarihVerilis timestamp,
						tarihBitis timestamp
					);

					drop table if exists dersler;
					create table dersler(
						id int auto_increment primary key,
						fenBaslangic varchar(100),
						tarihEklenme date,
						aciklama varchar(1000)
					);

					/*ders olarak eklenen oyunların hamleleri kaydedilecek */
					drop table if exists dershamleleri;
					create table dershamleleri(
						id int auto_increment primary key,
						dersID int,
						hamleler varchar(1000)
					);

					drop table if exists gruplar;
					create table gruplar(
						id int auto_increment primary key,
						kurucuID int,
						adi varchar(200),
						tarihKurulus timestamp
					);

					drop table if exists grupuyeiliskisi;
					create table grupuyeiliskisi(
						id int auto_increment primary key,
						grupID int,
						uyeID int
					);

					drop table if exists odevdegerlendirmesi;
					create table odevdegerlendirmesi(
						id int auto_increment primary key,
						odevIliskisiID int,
						uyeID int,
						sonuc tinyint /* geçti(0) kaldı(1) */
					);

					drop table if exists haberler;
					create table if not exists haberler (
					  id int(11) NOT NULL auto_increment primary key,
					  baslik varchar(100),
					  icerik text,
					  turu varchar(45),
					  resim text,
					  tarih datetime
					);

					drop table if exists kategoriler;
					create table kategoriler(
						id int auto_increment primary key,
						isim varchar(100)
					);

					drop table if exists kategoriElemanlari;
					create table kategoriElemanlari(
						id int auto_increment primary key,
						kategoriID int,
						zorluk tinyint /* kolay(0), orta(1), zor(2) */,
						tur tinyint /* ders(0), alistirma(1) */,
						siteID int
					);

					drop table if exists alistirmaIstatistikleri;
					create table alistirmaIstatistikleri(
						id int auto_increment primary key,
						uyeID int,
						alistirmaID int,
						sonOynamaTarihi DATETIME,
						oynamaSayisi int
					);
					
					drop table if exists karsiliklioyunlar;
					create table alistirmaIstatistikleri(
						id int auto_increment primary key,
						meydanOkuyanID int,
						meydanOkunanID int,
						durum int,
						tarihIstek DATETIME,
						tarihKabul DATETIME,
						oyunID int,
						meydanOkunanIP varchar(20),
						meydanOkunanIP varchar(20)
					);

					";

		if ($this->mysqli->multi_query($query)) {
			$i = 1;
			do {
				$i++;
			} while ($this->mysqli->next_result());
		}
		if ($this->mysqli->errno) {
			trigger_error("$i. sql komutunda hata var.", E_USER_NOTICE);
			//echo "$i. sql komutunda hata var.\n<br>".$this->mysqli->error;
			//var_dump("s", $this->mysqli->error);
		}


		return $this->mysqli->errno;
	}
}
?>
