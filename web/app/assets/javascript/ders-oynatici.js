var sihirbaz = function() {

//--- start example JS ---
var board,
  game = new Chess();
var sayac = 0,
	moves,
	oynat = false;
var timeoutID = [];

var init = function() {

	var value = document.getElementById("pgn").value;
	var fen = document.getElementById("fen").value;
	board.position(fen, false);
	game.load(fen);
	moves = value.split(";;;");

	console.log(moves);
	timeoutID.push(window.setTimeout(makeMove, 1000));
}

var makeMove = function() {
	//if((game.in_checkmate() === false || game.in_draw() === false) &&
	//	oynat === true){
	if(oynat && sayac < moves.length){
		game.move(moves[sayac++]);
		board.position(game.fen());
		timeoutID.push(window.setTimeout(makeMove, 1500));
		if(sayac >= moves.length)
			oynatDuraklat();
	}
};


document.getElementById("oynat").onclick = oynatDuraklat;
function oynatDuraklat () {
	var text;
	oynat = !oynat;
	console.log("Oynat: " + sayac + ", " + oynat);
	if(oynat){
		timeoutID.push(window.setTimeout(makeMove, 1000));
		text = "Duraklat";
	} else {
		text = "Oynat";
	}
	document.getElementById("oynat").innerHTML= text;
}

document.getElementById("ileri").onclick = function () {
	//console.log("İleri: " + sayac);
	game.move(moves[sayac++]);
	board.position(game.fen());
	if(sayac >= moves.length){
		sayac = moves.length-1;
	}
}

document.getElementById("geri").onclick = function () {
	sayac--;
	game.undo();
	board.position(game.fen());
	if(sayac < 0){
		sayac = 0;
	}
}

document.getElementById("yeniden").onclick = sifirla;
function sifirla () {
	game = new Chess();
	sayac = 0;
	board.position("rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1");
	for(var i=0; i<timeoutID.length; i++){
		clearTimeout(timeoutID[i]);
	}
	timeoutID = [];

	if(!oynat){
		oynatDuraklat();
	}
	timeoutID.push(window.setTimeout(makeMove, 1000));
};


var cfg = {
  position: 'start',
  moveSpeed: 'fast'
};
board = new ChessBoard('board', cfg);

init();
//--- end example JS ---

}; // end init()
$(document).ready(sihirbaz);
