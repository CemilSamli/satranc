var sihirbaz = function() {

//--- start example JS ---
var board,
  game = new Chess();

var statusEl = $('#status'),
	fenEl = $('#fen'),
	pgnEl = $('#pgn');

var fen;

var hamleler = [];

document.getElementById("sec").onclick = function () {
	fen = board.fen() + " w - - 0 1";
	board.destroy();
	board = new ChessBoard('board', cfg);

	board.position(fen, false);
	game.load(fen);
	var el = document.getElementById("sec").parentNode.parentNode;
	el.parentNode.removeChild(el);
}

document.getElementById("yukle").onclick = function () {
	//if(game.in_checkmate() === true || game.in_draw() === true){

		var result = "";
		var moveColor = 'White';
		if (game.turn() === 'b') {
			result = " 0-1";
		} else if (game.turn() === 'w') {
			result = " 1-0";
		}


		var form = document.createElement('form');
		form.method = "post";
		form.action = "dersekle.php";


		var input = document.createElement('input');
		input.type = "hidden";
		input.name = "c";
		input.value = "tahtaYukleme";
		form.appendChild(input);

		var input = document.createElement('input');
		input.type = "hidden";
		input.name = "fen";
		input.value = fen;
		form.appendChild(input);
		console.log(fen);
		var input = document.createElement('input');
		input.type = "hidden";
		input.name = "pgn";
		input.value = hamleler.join(";;;");
		form.appendChild(input);

		var input = document.createElement('input');
		input.type = "hidden";
		input.name = "aciklama";
		input.value = document.getElementById("aciklama").value;
		form.appendChild(input);

		var group = document.getElementsByName("zorluk");
		var input = document.createElement('input');
		input.name = "zorluk";

		for(var i = 0; i < group.length; i++) {
			if(group[i].checked == true) {
				input.value = group[i].value;
			}
		}
		form.appendChild(input);

		var input = document.createElement('input');
		var combo = document.getElementById("kategori");
		input.name = "kategori";
		input.value = combo.options[combo.selectedIndex].value;
		form.appendChild(input);

		form.submit();
	//}
	console.log(hamleler.join(";;;"));

};
/*
document.getElementById("startPos").onclick = sifirla;
function sifirla () {
	document.getElementById("startPos").disabled = true;
	fen = board.fen();
};
*/
// do not pick up pieces if the game is over
// only pick up pieces for the side to move
var onDragStart = function(source, piece, position, orientation) {
  if (game.game_over() === true ||
	  (game.turn() === 'w' && piece.search(/^b/) !== -1) ||
	  (game.turn() === 'b' && piece.search(/^w/) !== -1)) {
	return false;
  }
};

var onDrop = function(source, target, piece, newPos, oldPos, orientation) {
  // see if the move is legal
  var move = game.move({
	from: source,
	to: target,
	promotion: 'q' // NOTE: always promote to a queen for example simplicity
  });

  //console.log(move);
  // illegal move
  if (move === null) return 'snapback';

	  hamleler.push(move.san);
	  console.log(hamleler);
	fenEl.html(piece.substr(1,1) + target);
  updateStatus();
};

// update the board position after the piece snap
// for castling, en passant, pawn promotion
var onSnapEnd = function() {
  board.position(game.fen());
};

var updateStatus = function() {
  var status = '';

  var moveColor = 'White';
  if (game.turn() === 'b') {
	moveColor = 'Black';
  }

  // checkmate?
  if (game.in_checkmate() === true) {
	status = 'Game over, ' + moveColor + ' is in checkmate.';
  }

  // draw?
  else if (game.in_draw() === true) {
	status = 'Game over, drawn position';
  }

  // game still on
  else {
	status = moveColor + ' to move';

	// check?
	if (game.in_check() === true) {
	  status += ', ' + moveColor + ' is in check';
	}
  }

  statusEl.html(status);
  //fenEl.html(game.fen());
  pgnEl.html(game.pgn());
};

var cfg = {
  draggable: true,
  position: 'start',
  onDragStart: onDragStart,
  onDrop: onDrop,
  onSnapEnd: onSnapEnd
};
var cfgPos = {
	draggable: true,
	position: 'start',
	dropOffBoard: 'trash',
	sparePieces: true
};
board = new ChessBoard('board', cfgPos);


//game.header('Black', 'Mikhail Tal');

updateStatus();
//--- end example JS ---

}; // end init()
$(document).ready(sihirbaz);
