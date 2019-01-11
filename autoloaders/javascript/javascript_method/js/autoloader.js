function loadJSfiles(jsFilesArray) {
	for(var index = 0; index < jsFilesArray.length; ++index) {
		var s = document.createElement('script');
		s.type = 'text/javascript';
		s.src = director[index][1]+jsFilesArray[index][0]+'.js';
		head.appendChild(s);
	}
}
