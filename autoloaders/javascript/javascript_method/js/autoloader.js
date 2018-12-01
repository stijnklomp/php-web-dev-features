function loadJSfiles(jsFilesArray) {
	for(var index = 0; index < jsFilesArray.length; ++index) {
		var s = document.createElement('script');
		s.type = 'text/javascript';
		s.src = 'js/'+jsFilesArray[index]+'.js';
		head.appendChild(s);
	}
}
