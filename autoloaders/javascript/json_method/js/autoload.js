var head =  document.getElementsByTagName('head')[0];

// Load files into head
(function() {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200) {
			var object = JSON.parse(xhttp.responseText);
			for(var index = 0; index < object.length; ++index) {
				var path = '';
				if('path' in object[index]) {
					path = object[index]['path'];
				}
				var src = path+object[index]['src'];
				switch(object[index]['src'].split('.')[1]) {
					case 'css':
						var link = document.createElement('link');
						link.rel = 'stylesheet';
						link.type = 'text/css';
						link.href = src;
						head.appendChild(link);
						break;
					case 'js':
						var link = document.createElement('script');
						link.type = 'text/javascript';
						link.src = src;
						head.appendChild(link);
						break;
					default:
						var link = document.createElement('script');
						link.type = 'text/javascript';
						var src = document.createTextNode('require("'+src+'")');
						link.appendChild(src);
						head.appendChild(link);
				}
			}
		}
	};
	xhttp.open('GET', 'js/autoload.json');
	xhttp.send();
})();
