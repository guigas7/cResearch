var btn = document.getElementById('enviar');
console.log(btn);
var ga = document.getElementsByClassName('groupa');
var hida = document.getElementById('groupa');
document.onclick = function() {
	if (checkSat()) {
		console.log("turning to false");
		document.getElementById('enviar').disabled = false;
		document.getElementById('ermsg').style.visibility = "hidden";
		console.log(document.getElementById('ermsg'));
	} else {
		console.log("turning to true");
		document.getElementById('enviar').disabled = true;
		document.getElementById('ermsg').style.visibility = "visible";
		console.log(document.getElementById('ermsg'));
	}
}

function checkSat() {
	console.log("checkSat");
	var fields = document.getElementsByClassName('inclusion');
	var groups = document.getElementsByClassName('group');
	var can = 0;
	for (var j = 0; j < fields.length; j++) {
		console.log("checking field: ", fields[j].id, "...", fields[j].checked);
		can += (fields[j].checked ? 1 : 0);
	}
	can += checkGroup('groupa');
	can += checkGroup('groupb');
	console.log("amount of correct fields: ", can);
	console.log("correct fields needed: ", fields.length + groups.length);
	if (can == fields.length + groups.length) {
		return true; // satisfy
  	} else {
  		return false; // doesn't satisfy
  	}
}

function checkGroup(group_name) {
	var checks = document.getElementsByClassName(group_name);
	var can = 0;
	console.log("check: ", group_name);
	for (var j = 0; j < checks.length; j++) {
		console.log("checking in group: ", j, "...", checks[j].checked);
		can += (checks[j].checked ? 1 : 0);
	}
	console.log("amount of checked boxes: ", can);
	if (can >= 1) {
		return 1; // increment value
  	} else {
  		return 0; // don't increment value
  	}
}