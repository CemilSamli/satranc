var oyun = function() {

	//--- start example JS ---
	var board,
		game = new Chess();

	var statusEl = $('#status');


	var sureW = 0;

	var sureBaslangic = Date.now(),
		sureBitis = Date.now();


	var kazanan = 0;
	var hamle = 0;

	var hamleSiniri = 0;
	var id;

	var init = function() {
		game.load(document.getElementById("fen").value + " w - - 0 1")
		hamleSiniri = document.getElementById("sinir").value;
		id = document.getElementById("id").value;
		//window.setTimeout(makeMove, 1000);
	}

		var xmlhttp;
	var ekle = function () {

		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=ajaxResponse;
		xmlhttp.open("GET","alistirma.php?id="+id+"&islem=kaydet&sonuc="+game.turn()+"&sure="+Math.floor(sureW),true);
		xmlhttp.send();

	};

	function ajaxResponse(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			//document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
			console.log(xmlhttp.responseText);
		}
	}

	// do not pick up pieces if the game is over
	// only pick up pieces for White
	var onDragStart = function(source, piece, position, orientation) {
		//console.log(hamleSiniri);
		if(hamleSiniri<=0){
			return false;
		}
		if (game.in_checkmate() === true || game.in_draw() === true ||
		piece.search(/^b/) !== -1) {
			return false;
		}
	};

	var makeRandomMove = function() {
		var baslangic = Date.now();
		var possibleMoves = game.moves();

		// game over
		if (possibleMoves.length === 0) return;

		var randomIndex = Math.floor(Math.random() * possibleMoves.length);
		var hamle = game.move(possibleMoves[randomIndex]);

		board.position(game.fen());

		var moveColor = 'White';
		sureBaslangic = Date.now();
		// checkmate?

		sonuc();
		updateStatus();
	};

	var sonuc = function(){
		if(game.game_over() === true){
			//phpde skor bas
			ekle();
		}
	};

	var onDrop = function(source, target) {
		// see if the move is legal
		var move = game.move({
			from: source,
			to: target,
			promotion: 'q' // NOTE: always promote to a queen for example simplicity
		});

		// illegal move
		if (move === null) return 'snapback';

		hamleSiniri--;

		sureBitis = Date.now();
		var sure = (sureBitis-sureBaslangic)/1000;
		sureW += sure;
		sonuc();
		updateStatus();
		// make random legal move for black
		//if(hamleSiniri > 0)
		if(game.in_checkmate === true || game.in_draw === true){

		} else {
			window.setTimeout(makeRandomMove, 250);
		}
	};


	var updateStatus = function() {
		var status = '';

		var moveColor = 'Beyaz';
		if (game.turn() === 'b') {
			moveColor = 'Siyah';
		}

	  // checkmate?
		if (game.in_checkmate() === true) {
			if (game.turn() === 'b') {
				moveColor = 'Beyaz';
			}
			status = "<font color='green'>Oyun bitti...<br>Kazanan: " + moveColor + ".</font>";
			statusEl.html(status);
			return;
		}

	  // draw?
		else if (game.in_draw() === true) {
			status = 'Oyun bitti.';
		}

	  // game still on
		else {
			status = moveColor + ' oynayacak.';

		}
		if(hamleSiniri <= 0) {
			status = "<font color='red'>Hamle hakkınız kalmadı.</font>";
		}
		statusEl.html(status);
	};

	// update the board position after the piece snap
	// for castling, en passant, pawn promotion
	var onSnapEnd = function() {
		board.position(game.fen());
	};

	var cfg = {
		draggable: true,
		position: document.getElementById("fen").value,
		onDragStart: onDragStart,
		onDrop: onDrop,
		onSnapEnd: onSnapEnd
	};
	board = new ChessBoard('board', cfg);
	//console.log(game.load(document.getElementById("fen").value + " w - - 0 1"));
	//console.log(game.fen());
	init();
	//--- end example JS ---

}; // end init()
$(document).ready(oyun);
