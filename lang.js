window.translation_LIBRARY = [];
window.translationCallbacks = [];

function _l(str, element){
	var path = str.split ('/');
	var arr = window.translation_LIBRARY;
	var lastItem = path[path.length - 1];

	for (var i = 0; i < path.length; i++) {
		item = path[i];
		if (arr && arr[item] !== undefined) {
		    arr = arr[item];
		}
		else {
			if (element){
				window.translationCallbacks.push({
					originalText: str,
					element: element
				});
			}
			return lastItem;
		}
	}

    if (typeof arr === 'string') {
        return arr;
    }
    return lastItem;
}

function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

fetch('lang/' + getCookie('lang') + '/lang.json?lastVersion=1.51')
    .then(response => response.status == 200 ? response.json() : [])
	    .then (data => {
			window.translation_LIBRARY = data;
			window.translationCallbacks.forEach(item => {
				item.element.innerHTML = _l(item.originalText, item.element);
			});
			window.translationCallbacks = [];
		})
		.catch(error => {
			console.error('Error loading language file:', error);
			window.translation_LIBRARY = [];
		});
