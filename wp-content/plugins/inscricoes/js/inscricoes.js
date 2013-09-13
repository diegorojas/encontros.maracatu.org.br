function formataCEP(e,src,mask) {
	if(window.event) { _TXT = e.keyCode; } 
	else if(e.which) { _TXT = e.which; }
	if(_TXT > 47 && _TXT < 58) { 
		var i = src.value.length; 
		var saida = mask.substring(0,1); 
		var texto = mask.substring(i)
		if(texto.substring(0,1) != saida) { src.value += texto.substring(0,1); } 
		return true; 
	} 
	else{ 
		if (_TXT != 8) { return false; } 
		else { return true; }
	}
}