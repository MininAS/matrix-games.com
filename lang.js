fetch('lang/' + getCookie('lang') + '/lang.json?lastVersion=1')
	.then (response => {
		if (response.status == 200)
		    return response.json();
	})
	    .then (data => window.translation_LIBRARY = data)

function _l(str){
	path = str.split ('/');
	arr = window.translation_LIBRARY;
	path.forEach(item => {
		arr = arr[item] || item;
	});
    return arr;
}

function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}
