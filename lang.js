window.translation_LIBRARY = [];
window.translationCallbacks = [];

function _l(str, element){
	path = str.split ('/');
	arr = window.translation_LIBRARY;
	path.forEach(item => {
		if (arr[item]) {
		    arr = arr[item];
		}
		else {
			if (element){
				window.translationCallbacks.push({
					originalText: str,
					element: element
				});
			}
			return str;
		}
	});
    return arr;
}

function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}


fetch('lang/' + getCookie('lang') + '/lang.json?lastVersion=1.5')
	.then (response => {
		if (response.status == 200)
		    return response.json();
	})
	    .then (data => {
			window.translation_LIBRARY = data;
			window.translationCallbacks.forEach(item => {
				item.element.innerHTML = _l(item.originalText, item.element);
			});
			window.translationCallbacks = [];
		})
