var e_forumNewThemeInputField = document.querySelector('#formSendTheme [name="newThemeName"]');
var e_forumNewMessageInputField = document.querySelector('#formSendMessage [name="string"]');
var e_forumSaveMessageTitle = document.querySelector('#formSendMessage .windowTitle > li');
var e_forumMessageBlock = document.getElementById('messageWindow');
var e_forumDeleteConfirmPopup = document.getElementById('messDeleteConfirmPopup');
var e_forumDeleteConfirmButton = document.querySelector('#messDeleteConfirmPopup .k_enter');
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
		if (elm.classList.contains('forum_delete_item_link')){
			e_forumDeleteConfirmButton.deletingTheme = elm.parentNode.parentNode.parentNode.getAttribute('item');
        	e_forumDeleteConfirmPopup.style.display = 'block';
			x = event.pageX || event.clientX;
			y = event.pageY || event.clientY;
			e_forumDeleteConfirmPopup.style.left = x-350+'px';
			e_forumDeleteConfirmPopup.style.top = y-25+'px';
			break;
		}
		else if (elm.classList.contains('forum_theme')) {
			parentTheme = currentTheme;
			currentTheme = elm.getAttribute('item');
			if (currentTheme != 0) e_forumKeyUp.style.display = 'block';
			else e_forumKeyUp.style.display = 'none';
			f_forumUpdateContent(currentTheme);
			f_isWindowsHeightAlignment();
			break;
		}
		else if (elm.classList.contains('forum_redaction_message_link')){
			someText = elm.parentNode.parentNode.getElementsByTagName('p')[0].innerHTML;
			someText = f_convertSmilesAndTagFormat(someText);
			e_forumNewMessageInputField.value = someText;
			e_forumNewMessageInputField.focus();
            currentMess = elm.parentNode.parentNode.parentNode.getAttribute('item');
			e_forumCloseEditModeButton.style.display = 'block';
			e_forumSaveMessageTitle.innerHTML = 'Редактирование сообщения';
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

if (e_forumNewThemeSendButton)
	e_forumNewThemeSendButton.onclick = function () {
	    var newThemeName = e_forumNewThemeInputField.value;
			parameters =
				'string=' + newThemeName +
				'&theme=' + currentTheme;
		f_fetchSaving ('forum_add_theme.php', parameters, f_forumUpdateContent);
}

if (e_forumNewMessageSendButton)
	e_forumNewMessageSendButton.onclick = function () {
		var string = e_forumNewMessageInputField.value;
		if (currentMess == 0)
			f_fetchSaving ('forum_add_message.php?',
				'string=' + string +
				'&theme=' + currentTheme, f_forumUpdateContent);
		if (currentMess != 0)
			f_fetchSaving ('forum_edit_message.php?',
				'string=' + string +
				'&mess=' + currentMess, f_forumUpdateContent);
	}

if (e_forumCloseEditModeButton)
	e_forumCloseEditModeButton.onclick = () => f_forumUpdateContent();


if (e_forumDeleteConfirmButton)
	e_forumDeleteConfirmButton.onclick = function () {
		e_forumDeleteConfirmPopup.style.display = 'none';
		f_fetchSaving ('forum_delete_item.php?',
		'theme=' + this.deletingTheme, f_forumUpdateContent);
	}


if (e_forumDeleteConfirmPopup)
	e_forumDeleteConfirmPopup.onmouseleave = function () {
		e_forumDeleteConfirmPopup.style.display = 'none';
	}

function f_forumUpdateContent(theme){
	theme = theme ? theme : currentTheme;
	f_fetchUpdateContent('messageWindow', 'forum_content.php?theme=' + theme, null);
	if(e_forumNewThemeInputField){
		e_forumNewThemeInputField.value = "";
		e_forumNewMessageInputField.value = "";
		e_forumCloseEditModeButton.style.display = 'none';
		e_forumDeleteConfirmPopup.style.display = 'none';
		e_forumSaveMessageTitle.innerHTML = 'Новое сообщение';
		if (parentTheme != 0) f_changeInputFieldDisablement(e_forumNewThemeInputField, true);
		else f_changeInputFieldDisablement(e_forumNewThemeInputField, false);
		if (currentTheme == 0) f_changeInputFieldDisablement(e_forumNewMessageInputField, true);
		else f_changeInputFieldDisablement(e_forumNewMessageInputField, false);
	}
	currentMess = 0;
	f_isWindowsHeightAlignment ();
}

function f_convertSmilesAndTagFormat(someText){
	someText = someText.replace(/&lt;/g, '<');
	someText = someText.replace(/&gt;/g, '>');
	someText = someText.replace(/<br>/g, '\n');
	someText = someText.replace(/<img src=\"smile\//g, '{[:');
	someText = someText.replace(/.gif\">/g, ':]}');
	return someText
}
