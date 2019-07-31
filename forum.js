var e_newThemeInputField = document.querySelector('#formSendTheme [name="newThemeName"]');
var e_newMessageInputField = document.querySelector('#formSendMessage [name="string"]');
var e_forumMessageBlock = document.getElementById('messageWindow');
var e_forumNewThemeSendButton = document.querySelector('#formSendTheme .k_enter');
var e_forumNewMessageSendButton = document.querySelector('#formSendMessage .k_enter');
var e_forumKeyUp = document.getElementById('k_up');
var currentTheme = 0;
var parentTheme = 0;

f_forumUpdateContent();

e_forumMessageBlock.onclick = function (event) {
	event = event || window.event;
	var elm = event.target;
	while (elm != e_forumMessageBlock) {
		if (elm.classList.contains('forum_theme')) {
			parentTheme = currentTheme;
			currentTheme = elm.getAttribute('theme');
			if (currentTheme != 0) e_forumKeyUp.style.display = 'block';
			else e_forumKeyUp.style.display = 'none';
			f_forumUpdateContent(currentTheme);
			f_isWindowsHeightAlignment();
			break;
		}
		else if (elm.classList.contains('forum_redaction_message_link')){
			f_fetchSaving ('forum_delete_message.php?message=' + message, f_forumUpdateContent);
			break;
		}
		else if (elm.classList.contains('forum_delete_message_link')){
			alert ("привет");
			message = elm.parentNode.parentNode.parentNode.getAttribute('message');
			f_fetchSaving ('forum_delete_message.php?mess=' + message, f_forumUpdateContent);
			break;
		}
		else elm = elm.parentNode;
	}
}

e_forumKeyUp.onclick = function () {
	currentTheme = parentTheme;
	if (parentTheme == 0) e_forumKeyUp.style.display = 'none';
	else parentTheme = 0;
	f_forumUpdateContent(parentTheme);
	f_isWindowsHeightAlignment ();
}

e_forumNewThemeSendButton.onclick = function () {
    var newThemeName = e_newThemeInputField.value;
		parameters =
			'newThemeName=' + newThemeName +
			'&theme=' + currentTheme;
	f_fetchSaving ('forum_add_theme.php?' + parameters, f_forumUpdateContent);
}

e_forumNewMessageSendButton.onclick = function () {
	var string = e_newMessageInputField.value;
		parameters =
			'string=' + string +
			'&theme=' + currentTheme;
	f_fetchSaving ('forum_add_message.php?' + parameters, f_forumUpdateContent);
}

function f_forumUpdateContent(theme){
	theme = theme ? theme : currentTheme;
	f_fetchUpdateContent('messageWindow', 'forum_content.php?theme=' + theme);
	e_newThemeInputField.value = "";
	e_newMessageInputField.value = "";
	f_isWindowsHeightAlignment ();
	if (parentTheme != 0) f_changeInputFieldDisablement(e_newThemeInputField, true);
	else f_changeInputFieldDisablement(e_newThemeInputField, false);
	if (currentTheme == 0) f_changeInputFieldDisablement(e_newMessageInputField, true);
	else f_changeInputFieldDisablement(e_newMessageInputField, false);
}
