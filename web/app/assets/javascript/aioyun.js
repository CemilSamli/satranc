
var init = function(search_depth, skill) {
	return function() {
		//--- start example JS ---
		var board;

		var statusEl = $('#status');
		var durumEl = $('#durum');
		var scoreW = 0,
			scoreB = 0;

		var sureB = 0,
			sureW = 0;

		var kSureBaslangic = Date.now(),
			kSureBitis = Date.now();

		var kazanan = 0;

		var hamleler = [];
		var hamleSayisi = 0;

		var table = document.getElementById("hamlelerTablo");
		var satir;

		var gameID = -1;
		var gotID = false;

		var fen;
		var ilkoyunid;
		var id;


		var engine = new Worker('/web/app/assets/javascript/stockfish.js');
		engine.onmessage = function(event) {
			var match = event.data.match(
				/^bestmove ([a-h][1-8])([a-h][1-8])([qrbk])?/);
			if (match) {
				if (game.game_over()) {
					return;
				}

				var baslangic = Date.now();
				if (game.moves().length === 0) return;

				var hamle = game.move({
					from: match[1],
					to: match[2],
					promotion: match[3]
				});

				// hangi tasın alındıgı bilgisi
				tasBilgisi(ilkfen, game.fen(), hamle);
				//yapilan hamlenin puanını getirir
				yapilanHamlePuanGetir(hamle);

				//console.log(hamle);
				if (hamle.san.indexOf("x") > -1) {
					if (puanBlack != -1) {
						scoreB += puanBlack;
					}
					scoreB += (toplamPuan + 1);
					toplamPuan = 0;
				}

				hamleler.push(hamle.san);
				hamleEkle(hamle.san);

				//console.log("White: "+scoreW + "  Black: "+scoreB);
				board.position(game.fen());

				var moveColor = 'White';

				// checkmate?

				var bitis = Date.now();
				var sure = (bitis - baslangic) / 1000;
				sureB += sure;
				//console.log(sure);
				sonuc(hamle.san);
				updateStatus();

				kSureBaslangic = Date.now();
				//console.log("white süre: "+sureW + "  black süre: "+ sureB);


				puanBlack = 0;
				puanWhite = 0;
			} else if(match = event.data.match(/^info/)){
				//console.log(event.data);
			}
		};

		function uciCmd(cmd) {
			engine.postMessage(cmd);
		}
		uciCmd('setoption name Skill Level value ' + skill);


		var hamleturu;

		fen = getCookie("fen");
		fen = decodeURIComponent(fen);
		fen = decodeURIComponent(fen);

		ilkoyunid = getCookie("ilkoyunid");
		id = getCookie("ilkoyunid");

		var game = new Chess(fen);

		var pieces = fen.split(" ");
		//console.log(pieces[1]);
		//console.log(document.cookie);

		var xmlhttp;

		function hamleEkle(hamle) {
			//console.log(document.cookie);

			if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else { // code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					//document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
					console.log(xmlhttp.responseText);
					//var length = xmlhttp.responseText.length;
					//if(xmlhttp.responseText.substr(3,2) === "ID"){
					//id = parseInt(xmlhttp.responseText.substring(6,length));
					//console.log("id="+id+", "+xmlhttp.responseText.substring(6,length));
					//gotID = true;
					//}
				}
			};

			var request = "/web/app/controller/oyunai.php?";

			if (hamleler.length <= 0) {
				request += "fenk=" + game.fen() + "&hamle=" + hamle + "&uid=" + getCookie(
					"uid") + "&ilkoyunid=" + ilkoyunid + "&c=ilkhamle" + "&oyunID=" + id;
			} else {
				request += "fenk=" + game.fen() + "&oyunID=" + id + "&hamle=" + hamle +
					"&sb=" + sureB + "&sw=" + sureW + "&pb=" + scoreB + "&pw=" + scoreW +
					"&uid=" + getCookie("uid") + "&c=hamle";
			}
			//console.log("request: " + request);

			//var hamle = hamleler.join(";;;");
			//console.log(hamle);
			xmlhttp.open("GET", request, true);
			xmlhttp.send();

			var table = document.getElementById("hamlelerTablo");
			if (hamleSayisi++ % 2 == 0) {
				satir = table.insertRow(-1);
				var cell = satir.insertCell(-1);
				cell.innerHTML = (hamleSayisi + 1) / 2;
			}
			var cell = satir.insertCell(-1);

			cell.innerHTML = hamle;


		};


		// do not pick up pieces if the game is over
		// only pick up pieces for White
		var onDragStart = function(source, piece, position, orientation) {
			if (game.in_checkmate() === true || game.in_draw() === true ||
				piece.search(/^b/) !== -1) {
				return false;
			}
		};

		var makeRandomMove = function() {
			var moves = '';
			var history = game.history({verbose: true});
			for(var i = 0; i < history.length; ++i) {
				var move = history[i];
				moves += ' ' + move.from + move.to + (move.promotion ? move.promotion : '');
			}

            //uciCmd('position startpos moves' + moves);
			uciCmd('position fen ' + game.fen());
			uciCmd('go depth ' + search_depth);


		};

		var sonuc = function(hamle) {
			console.log(game.in_checkmate()+" "+game.in_draw());
			if (game.in_checkmate() === true) {
				if (game.turn() === 'b') {
					scoreW += 10;
				} else {
					scoreB += 10;
				}
				var request = "oyunai.php?";
				request += "c=hamle&fenk=" + game.fen() + "&oyunID=" + id + "&hamle=" + encodeURIComponent(hamle) +
					"&sb=" + sureB + "&sw=" + sureW + "&pb=" + scoreB + "&pw=" + scoreW +
					"&uid=" + getCookie("uid") + "&kid=" + game.turn();

				//alert('blttl');			
				//console.log(request+'\n');

				xmlhttp.open("GET", request, true);
				xmlhttp.send();

			}
			else if (game.in_draw() === true) { //game.in_checkmate === true || game.in_draw === true
				//phpde skor bas
				//alert('ekle');
				var request = "oyunai.php?";
				request += "c=hamle&fenk=" + game.fen() + "&oyunID=" + id + "&hamle=" + encodeURIComponent(hamle) +
					"&sb=" + sureB + "&sw=" + sureW + "&pb=" + scoreB + "&pw=" + scoreW +
					"&uid=" + getCookie("uid") + "&kid=" + -2;

				//alert('blttl');			
				//console.log(request+'\n');

				xmlhttp.open("GET", request, true);
				xmlhttp.send();

				
				//ekle();
			}
		};


		var onDrop = function(source, target) {
			// see if the move is legal
			var move = game.move({
				from: source,
				to: target,
				promotion: 'q' // NOTE: always promote to a queen for example simplicity
			});
			//console.log(move);
			// illegal move
			if (move === null) return 'snapback';
			//console.log(move.san);
			if (move.san.indexOf("x") > -1) {
				scoreW++;
			}
			hamleEkle(move.san);
			hamleler.push(move.san);


			kSureBitis = Date.now();
			var sure = (kSureBitis - kSureBaslangic) / 1000;
			sureW += sure;

			//console.log("white süre: "+sureW + "  black süre: "+ sureB);

			sonuc();
			updateStatus();
			// make random legal move for black
			window.setTimeout(makeRandomMove, 250);
		};

		var ilkfen;
		var updateStatus = function() {
			var status = '';
			var durum = 'Durum';

			var moveColor = 'Beyaz';
			if (game.turn() === 'b') {
				moveColor = 'Siyah';
			}

			if (game.turn() == 'b') {
				var possibleMoves = game.moves(); //siyah hanlesi
				ilkfen = game.fen();
			}


			// checkmate?
			if (game.in_checkmate() === true) {
				if (game.turn() === 'b') {
					moveColor = 'Beyaz';
					status = 'Oyun Bitti, Kazanan : ' + moveColor;
					status += "<br><br>Siyah Süre: " + sureB.toFixed(3) + " saniye<br>Beyaz Süre: " + sureW.toFixed(3) + " saniye";
					status += "<br>Siyah Puan: " + scoreW + "<br>Beyaz Puan: " + scoreB;
					durum +="<img src='/web/app/assets/image/happy.png' height='25' width='25'></img>";
				}
				else{
					moveColor = 'Siyah';
					status = 'Oyun Bitti. Kazanan : ' + moveColor;
					status += "<br>Siyah Süre: " + sureB.toFixed(3) + " saniye<br>Beyaz Süre: " + sureW.toFixed(3) + " saniye";
					status += "<br>Siyah Puan: " + scoreB + "<br>Beyaz Puan: " + scoreW;
					durum += "<img src='/web/app/assets/image/sad.png' height='25' width='25'></img>";
				}
			}

			// draw? 
			else if (game.in_draw() === true) {
				
				status = 'Oyun Bitti. Berabere!';
				status += "<br>Siyah Süre: " + sureB.toFixed(3) +
					" saniye<br>Beyaz Süre: " + sureW.toFixed(3) + " saniye";
				status += "<br>Siyah Puan: " + scoreB + "<br>Beyaz Puan: " + scoreW;
				durum +=
					"<img src='/web/app/assets/image/sad.png' height='25' width='25'></img>";
			}

			// game still on
			else {
				status = '<br>' + moveColor + ' oynayacak...'+ '<br><br>';

			}

			statusEl.html(status);
			durumEl.html(durum);
			// console.log("movecolor = "+moveColor);
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
	};
}; // end init()
$(document).ready(init(depth, skill));



var puanBlack = -1;
var puanWhite = -1;

function tasBilgisi(ilkfen, sonfen, hamle) {
	var parca1 = ilkfen.split(" ");
	var parca1 = parca1[0].split("/");
	var parca2 = sonfen.split(" ");
	var parca2 = parca2[0].split("/");
	var hamleParca = hamle.san.split("");

	for (var a = 0; a < hamleParca.length; a++) {
		if (hamleParca[a] == "x") {
			for (var i = 0; i < parca1.length; i++) {
				var parca_1 = parca1[i].split("");
				var parca_2 = parca2[i].split("");
				for (var j = 0; j < parca_1.length; j++) {
					var number = parca_2[j];
					if (parca_1[j] != parca_2[j] && parca_1[j] != undefined && parca_2[j] !=
						undefined) {
						// siyah icin tasalma puan hesabı
						if (hamle.color == "b") {
							if (parca_1[j] == "Q") {
								puanBlack = 5;
							} else if (parca_1[j] == "R") {
								puanBlack = 4;
							} else if (parca_1[j] == "N" || parca_1[j] == "B") {
								puanBlack = 3;
							} else if (parca_1[j] == "P") {
								puanBlack = 2;
							}
						} else {
							// beyaz icin tas alma puan hesabı
							if (parca_1[j] == "q") {
								puanWhite = 5;
							} else if (parca_1[j] == "r") {
								puanWhite = 4;
							} else if (parca_1[j] == "n" || parca_1[j] == "b") {
								puanWhite = 3;
							} else if (parca_1[j] == "p") {
								puanWhite = 2;
							}
						}
					}
				}
			}
		}
	}
}


var toplamPuan = 0;

function yapilanHamlePuanGetir(yapilanHamle) {
	var parca = yapilanHamle.san.split("");
	for (var i = 0; i < parca.length; i++) {
		if (parca[i] == "+") {
			toplamPuan += 7;
		}
		if (parca[i] == "=") {
			toplamPuan += 8;
		}
	}
}
