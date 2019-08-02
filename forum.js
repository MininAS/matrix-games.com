var e_newThemeInputField = document.querySelector('#formSendTheme [name="newThemeName"]');
var e_newMessageInputField = document.querySelector('#formSendMessage [name="string"]');
var e_forumSaveMessageTitle = document.querySelector('#formSendMessage .windowTitle > li');
var e_forumMessageBlock = document.getElementById('messageWindow');
var e_forumNewThemeSendButton = document.querySelector('#formSendTheme .k_enter');
var e_forumNewMessageSendButton = document.querySelector('#formSendMessage .k_enter');
var e_forumCloseEditModeButton = document.querySelector('#formSendMessage .k_close');
var e_forumKeyUp = document.getElementById('k_up');
var currentTheme = 0;
var parentTheme = 0;
var currentMess = 0;

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
			someText = elm.parentNode.parentNode.getElementsByTagName('p')[0].innerHTML;
			someText = f_convertSmilesAndTagFormat(someText);
			e_newMessageInputField.value = someText;
			e_newMessageInputField.focus();
            currentMess = elm.parentNode.parentNode.parentNode.getAttribute('message');
			e_forumCloseEditModeButton.style.display = 'block';
			e_forumSaveMessageTitle.innerHTML = 'Редактирование сообщения';
			break;
		}
		else if (elm.classList.contains('forum_delete_message_link')){
			currentMess = elm.parentNode.parentNode.parentNode.getAttribute('message');
			f_fetchSaving ('forum_delete_message.php?mess=' + currentMess, f_forumUpdateContent);
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
	if (currentMess == 0)
		f_fetchSaving ('forum_add_message.php?' +
			'string=' + string +
			'&theme=' + currentTheme, f_forumUpdateContent);
	if (currentMess != 0)
		f_fetchSaving ('forum_edit_message.php?' +
			'string=' + string +
			'&mess=' + currentMess, f_forumUpdateContent);
}

e_forumCloseEditModeButton.onclick = () => f_forumUpdateContent();

function f_forumUpdateContent(theme){
	theme = theme ? theme : currentTheme;
	f_fetchUpdateContent('messageWindow', 'forum_content.php?theme=' + theme);
	e_newThemeInputField.value = "";
	e_newMessageInputField.value = "";
	e_forumCloseEditModeButton.style.display = 'none';
	e_forumSaveMessageTitle.innerHTML = 'Новое сообщение';
	currentMess = 0;
	f_isWindowsHeightAlignment ();
	if (parentTheme != 0) f_changeInputFieldDisablement(e_newThemeInputField, true);
	else f_changeInputFieldDisablement(e_newThemeInputField, false);
	if (currentTheme == 0) f_changeInputFieldDisablement(e_newMessageInputField, true);
	else f_changeInputFieldDisablement(e_newMessageInputField, false);
}

function f_convertSmilesAndTagFormat(someText){
	someText = someText.replace('&lt;', '<');
	someText = someText.replace('&gt;', '>');
	someText = someText.replace('<br>', '\\r\\n');
	someText = someText.replace("<img src=\"smile/", '{[:');
	someText = someText.replace(".gif\">", ':]}');
	return someText
}
