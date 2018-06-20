// Don't allow special characters in text input
function blockSpecialChar(key){
	var Key = key.keyCode;
	if(Key == 62 || Key == 60 || Key == 39 || Key == 38 || Key == 34){
		return false;
	}
	else{
		return Key;
	}
}