"use strict";
var init = function() {

	//--- start example JS ---
	var board;


	var statusEl = $('#status');
	var durumEl = $('#durum');
	var sureBeyazEl = $('#sureBeyaz');
	var sureSiyahEl = $('#sureSiyah');
	var puanSiyahEl = $('#puanSiyah');
	var puanBeyazEl = $('#puanBeyaz');

	var sure;

	var kSureBaslangic = Date.now(),
		kSureBitis = Date.now();

	var kazanan = 0;

	var hamleler = [];
	var hamleSayisi = 0;
	updateHamlelerFromTable();

	var table = document.getElementById("hamlelerTablo");
	var satir;

	var gameID = -1;
	var gotID = false;

	var fen;
	var oyuncuRenk = "b";


	var xmlhttp;
	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else { // code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			//console.log(xmlhttp.responseText);

			try{
				var json = JSON.parse(xmlhttp.responseText);
				if(json.hasOwnProperty('pvpData')){
					var data = json.pvpData;
					console.log(data);
				}
			} catch (e){

			}
		}
	};

	var QueryString = function() {
		// This function is anonymous, is executed immediately and
		// the return value is assigned to QueryString!
		var query_string = {};
		var query = window.location.search.substring(1);
		var vars = query.split("&");
		for (var i = 0; i < vars.length; i++) {
			var pair = vars[i].split("=");
			// If first entry with this name
			if (typeof query_string[pair[0]] === "undefined") {
				query_string[pair[0]] = decodeURIComponent(pair[1]);
				// If second entry with this name
			} else if (typeof query_string[pair[0]] === "string") {
				var arr = [query_string[pair[0]], decodeURIComponent(pair[1])];
				query_string[pair[0]] = arr;
				// If third or later entry with this name
			} else {
				query_string[pair[0]].push(decodeURIComponent(pair[1]));
			}
		}
		return query_string;
	}();



	var meydanOkuyanIsim = getCookie("meydanOkuyan").replace(/\+/g, ' ');
	var meydanOkunanIsim = getCookie("meydanOkunan").replace(/\+/g, ' ');

	var puanBeyaz = getCookie("puanBeyaz");
	var puanSiyah = getCookie("puanSiyah");
	var sureBeyaz = getCookie("sureBeyaz");
	var sureSiyah = getCookie("sureSiyah");

	fen = getCookie("fen");
	fen = decodeURIComponent(fen);
	fen = decodeURIComponent(fen);
	var renk = getCookie("renk");

	var game = new Chess(fen);

	var callback = function(result){
		//console.log(result.pvpData);
		fen = decodeURIComponent(result.pvpData.fen);
		board.position(fen);
		game.load(fen);
		puanBeyaz = result.pvpData.puanBeyaz;
		puanSiyah = result.pvpData.puanSiyah;
		sureBeyaz = result.pvpData.sureBeyaz;
		sureSiyah = result.pvpData.sureSiyah;
		var hamleDizisi = result.pvpData.hamleler.split(";;;");
		if (hamleDizisi.length > hamleler.length){
			addHamleToTable(hamleDizisi[hamleDizisi.length - 1]);
			hamleler.push(hamleDizisi[hamleDizisi.length - 1]);
		}
		updateStatus();

		if(renk == result.pvpData.oynayacak_renk){
			canPlay = true;
		}

		setTimeout(function(){
			$.getJSON("/web/app/controller/pvpjson.php?id="+QueryString.id+"&renk="+fen.split(" ")[1], callback);
		}, 2000);
	};
	$.getJSON("/web/app/controller/pvpjson.php?id="+QueryString.id+"&renk="+fen.split(" ")[1], callback);

	updateStatus();

	var pieces = fen.split(" ");
	var canPlay = pieces[1] == renk ? true : false;



	function hamleEkle(hamle) {

		//console.log("sure " + sureW);
		var request = "/web/app/controller/pvprest.php?fenk=" + encodeURIComponent(game.fen()) + "&id=" +
			encodeURIComponent(QueryString.id) + "&hamle=" + encodeURIComponent(hamle) + "&sb=" + encodeURIComponent(sure) + "&sw=" + encodeURIComponent(sure) +
			"&puan=" + encodeURIComponent(puan) + "&renk=" + encodeURIComponent(oyuncuRenk);
		if (game.in_checkmate() === true) {
			request += "&kid=" + game.turn() + "&oyunid=" + QueryString.id;
		} else if (game.in_draw() === true) {
			request += "&kid=" + game.turn() + "&oyunid=" + QueryString.id;
		}

		//console.log("puan hamleekle = " + puan);
		//console.log("request " + request);
		xmlhttp.open("GET", request, true);
		xmlhttp.send();

		//addHamleToTable(hamle);
	};

	function addHamleToTable(hamle){
		var table = document.getElementById("hamlelerTablo");
		var rows = table.rows;
		var lastRowCellCount = rows[rows.length - 1].cells.length;
		var satir;
		if (lastRowCellCount == 3) {
			satir = table.insertRow(-1);
			var cell = satir.insertCell(-1);
			cell.innerHTML = rows.length - 1;
		} else {
			satir = rows[rows.length - 1];
		}
		var cell = satir.insertCell(-1);

		cell.innerHTML = hamle;
	};

	function updateHamlelerFromTable(){
		var table = document.getElementById("hamlelerTablo");
		for (var i = 0, row; row = table.rows[i]; i++) {
			if (i == 0) continue;
			for (var j = 0, col; col = row.cells[j]; j++) {
				if (j == 0) continue;
				hamleler.push(col.innerHTML);
			}
		}
	}

	// do not pick up pieces if the game is over
	// only pick up pieces for White
	var onDragStart = function(source, piece, position, orientation) {
		if (canPlay == false) {
			return false;
		}
		//console.log(renk);
		if (game.in_checkmate() === true || game.in_draw() === true) {
			return false;
		}

		if (renk == "w") {
			if (piece.search(/^b/) !== -1) {
				return false;
			}
		} else {
			if (piece.search(/^w/) !== -1) {
				return false;
			}
		}
	};


	var onDrop = function(source, target) {
		// see if the move is legal
		var move = game.move({
			from: source,
			to: target,
			promotion: 'q' // NOTE: always promote to a queen for example simplicity
		});

		if (move === null) return 'snapback';

		if (move.san.indexOf("x") > -1) {
			puan++;
		}

		hamlePuan(move);
		oyuncuRenk = move.color;

		kSureBitis = Date.now();
		sure = (kSureBitis - kSureBaslangic) / 1000;

		//hamleler.push(move.san);
		hamleEkle(move.san);

		canPlay = false;

		updateStatus();

		board.position(game.fen());
	};


	function updateStatus() {
		var status = '';
		var durum = 'Durum';

		var moveColor = 'Beyaz';
		if (game.turn() === 'b') {
			moveColor = 'Siyah';
		}

		if (game.in_checkmate() === true) {
			if (game.turn() === 'b') {
				moveColor = 'Beyaz';
			}
			status = 'Oyun Bitti, Kazanan : ';
			if (moveColor == 'Siyah') {
				status += meydanOkunanIsim;
			} else {
				status += meydanOkuyanIsim;
			}
		} else if (game.in_draw() === true) {
			status = 'Oyun Bitti. Berabere!';
		} else {
			if (moveColor == 'Siyah') {
				status = meydanOkunanIsim + ' oynayacak.(Siyah Taş)';
			} else {
				status = meydanOkuyanIsim + ' oynayacak.(Beyaz Taş)';
			}

			if (moveColor == 'b') {
				puanSiyah = parseInt(puan) + parseInt(puanSiyah);
			} else {
				puanBeyaz = parseInt(puan) + parseInt(puanBeyaz);;
			}
		}

		statusEl.html(status);
		//durumEl.html(durum);
		puanBeyazEl.html(puanBeyaz);
		puanSiyahEl.html(puanSiyah);

		if (sureBeyaz != undefined) {
			sureBeyazEl.html(sureBeyaz);
		}
		if (sureSiyah != undefined) {
			sureSiyahEl.html(sureSiyah);
		}
		puan = 0;

	};

	function getCookie(cname) {
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') c = c.substring(1);
			if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
		}
		return "";
	}

	// update the board position after the piece snap
	// for castling, en passant, pawn promotion
	var onSnapEnd = function() {
		board.position(game.fen());
	};

	var cfg = {
		draggable: true,
		position: 'start',
		onDragStart: onDragStart,
		onDrop: onDrop,
		onSnapEnd: onSnapEnd
	};
	board = new ChessBoard('board', cfg);
	board.position(fen);
	//--- end example JS ---

}; // end init()
$(document).ready(init);


var puan = 0;

function hamlePuan(hamle) {
	var parca = hamle.san.split("");
	if (parca[1] == "x") {
		if (hamle.captured == "q") {
			puan = 5;
		} else if (hamle.captured == "r") {
			puan = 4;
		} else if (hamle.captured == "b") {
			puan = 3;
		} else if (hamle.captured == "n") {
			puan = 3;
		} else if (hamle.captured == "p") {
			puan = 2;
		}
	}

	for (var i = 0; i < parca.length; i++) {
		if (parca[i] == "+") {
			puan += 6;
		}
		if (parca[i] == "=") {
			puan += 7;
		}
		if (parca[i] == "#") {
			puan += 10;
		}
	}
	puan += 1;


}
