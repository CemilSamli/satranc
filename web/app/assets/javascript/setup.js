var sihirbaz = function() {

	var status = document.getElementById("status");
	var basarisiz = document.getElementById("basarisiz");
	var basarili = document.getElementById("basarili");
	var kurulu = document.getElementById("kurulu");
	var kuruluDegil = document.getElementById("kuruludegil");
	var loading = document.getElementById("loading");

	if(status<0){
		kuruluDegil.style.display = "block";
		console.log("kurulu");
	} else {
		kurulu.style.display = "block";
	}


	document.getElementById("kur").onclick = function () {
		return; // TODO: fix this.
		var xmlhttp;
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else {// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				kurulu.style.display = "none";
				kuruluDegil.style.display = "none";
				loading.style.display = "none";
				console.log(xmlhttp.responseText);
				if(xmlhttp.responseText === "OK") {
					basarili.style.display = "block";
				} else {
					basarisiz.style.display = "block";
				}
			}
		}
		xmlhttp.open("GET","?controller=kur",true);
		xmlhttp.send();
		loading.style.display = "block";

	}
};
$(document).ready(sihirbaz);
