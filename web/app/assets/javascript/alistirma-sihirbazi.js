var sihirbaz = function() {

//--- start example JS ---
var board,
	game = new Chess();

var fen;


document.getElementById("kaydet").onclick = function () {
		
		var form = document.createElement('form');
		form.name='formum';
		form.method = 'POST';
		form.action="alistirmaekle.php";

		var input = document.createElement('input');
		input.type='hidden';
		input.name = "islem";
		input.value = "kaydet";
		form.appendChild(input);
		
		/*
		alert(input.value);
		alert(form.elements["islem"].value);
		*/
		
		var input = document.createElement('input');
		input.type='hidden';
		input.name = "fen";
		input.value = board.fen();
		form.appendChild(input);
		//alert(form.elements["fen"].value);
		
		//alert(document.getElementById("hamleSiniri").value.length);
		//alert(document.getElementById("hamleSiniri").value);
		var input = document.createElement('input');
		input.type='hidden';
		input.name = "sinir";
		if(document.getElementById("hamleSiniri").value.length <= 0 ){
			input.value = 0;
		} else {
			input.value = document.getElementById("hamleSiniri").value;
		}
		form.appendChild(input);
		//alert(form.elements["sinir"].value);
		
		var input = document.createElement('input');
		input.type='hidden';
		input.name = "aciklama";
		input.value = document.getElementById("aciklama").value;
		form.appendChild(input);
		//alert(form.elements["aciklama"].value);
		
		var group = document.getElementsByName("zorluk");
		var input = document.createElement('input');
		input.type='hidden';
		input.name = "zorluk";
		for(var i = 0; i < group.length; i++) {
			if(group[i].checked == true) {
				input.value = group[i].value;
				//alert(group[i].value);
			}
		}
		form.appendChild(input);
		
		
		var input = document.createElement('input');
		var combo = document.getElementById("kategori");
		input.type='hidden';
		input.name = "kategori";
		input.value = combo.options[combo.selectedIndex].value;
		form.appendChild(input);
		//alert(input.value);
		
		document.body.appendChild(form);
		form.submit();		
};

document.getElementById("basKonum").onclick = sifirla;
function sifirla () {
	board.start();
};

document.getElementById("bosalt").onclick = bosalt;
function bosalt () {
	console.log(board);
	board.clear();
};

var cfg = {
	draggable: true,
	position: 'start',
	dropOffBoard: 'trash',
	sparePieces: true
};
board = new ChessBoard('board', cfg);


}; // end init()
$(document).ready(sihirbaz);
