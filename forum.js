const e_forumNewThemeInputField = document.querySelector('#formSendTheme [name="newThemeName"]');
const e_forumNewMessageInputField = document.querySelector('#formSendMessage [name="newForumItemText"]');
const e_forumSaveMessageTitle = document.querySelector('#formSendMessage .windowTitle > li');
const e_menuMainItem = document.querySelector('#menu_main_item');
const e_menuMiddleItem = document.querySelector('#menu_middle_item');
const e_menuLastItem = document.querySelector('#menu_last_item');
const e_forumMessageBlock = document.getElementById('messageWindow');
const e_forumDeleteConfirmPopup = document.getElementById('messDeleteConfirmPopup');
const e_forumDeleteConfirmButton = document.querySelector('#messDeleteConfirmPopup .k_enter');
const e_forumNewThemeSendButton = document.querySelector('#formSendTheme .k_enter');
const e_forumNewMessageSendButton = document.querySelector('#formSendMessage .k_enter');
const e_forumCloseEditModeButton = document.querySelector('#formSendMessage .k_close');
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
			f_forumUpdateContent(currentTheme);
			break;
		}
		else if (elm.classList.contains('forum_redaction_message_link')){
			someText = elm.parentNode.parentNode.getElementsByTagName('p')[0].innerHTML;
			someText = f_convertSmilesAndTagFormat(someText);
			e_forumNewMessageInputField.value = someText;
			e_forumNewMessageInputField.focus();
            currentMess = elm.parentNode.parentNode.parentNode.getAttribute('item');
			e_forumCloseEditModeButton.style.display = 'block';
			e_forumSaveMessageTitle.innerHTML = _l("Forum/Message editing");
			break;
		}
		else elm = elm.parentNode;
	}
}

if (e_forumNewThemeSendButton)
	e_forumNewThemeSendButton.onclick = function () {
		if (e_forumNewThemeInputField.disabled == true) return;
	    var newThemeName = e_forumNewThemeInputField.value;
		parameters =
			'messageText=' + newThemeName +
			'&theme=' + currentTheme;
		f_requestAndHandleForPopup ('forum_add_theme.php', parameters, f_forumUpdateContent);
}

if (e_forumNewMessageSendButton)
	e_forumNewMessageSendButton.onclick = function () {
		if (e_forumNewMessageInputField.disabled == true) return;
		var string = e_forumNewMessageInputField.value;
		if (currentMess == 0)
			f_requestAndHandleForPopup ('forum_add_message.php?',
				'messageText=' + string +
				'&theme=' + currentTheme, f_forumUpdateContent);
		if (currentMess != 0){
			f_requestAndHandleForPopup ('forum_edit_message.php?',
				'messageText=' + string +
				'&mess=' + currentMess, f_forumUpdateContent);
			e_forumSaveMessageTitle.innerHTML = _l("Forum/New message");
		}
	}

if (e_forumCloseEditModeButton)
	e_forumCloseEditModeButton.onclick = () => {
		f_forumUpdateContent();
	    e_forumSaveMessageTitle.innerHTML = _l("Forum/New message");
	}

if (e_forumDeleteConfirmButton)
	e_forumDeleteConfirmButton.onclick = function () {
		e_forumDeleteConfirmPopup.style.display = 'none';
		f_requestAndHandleForPopup ('forum_delete_item.php?',
		'theme=' + this.deletingTheme, f_forumUpdateContent);
	}


if (e_forumDeleteConfirmPopup)
	e_forumDeleteConfirmPopup.onmouseleave = function () {
		e_forumDeleteConfirmPopup.style.display = 'none';
	}

function f_forumUpdateContent(theme){
	theme = theme ? theme : currentTheme;
	f_fetchUpdateContent('messageWindow', 'forum_content.php?theme=' + theme, f_setThemeNames);
	if(e_forumNewThemeInputField){
		e_forumNewThemeInputField.value = "";
		e_forumNewMessageInputField.value = "";
		e_forumCloseEditModeButton.style.display = 'none';
		e_forumDeleteConfirmPopup.style.display = 'none';
		if (parentTheme != 0)
			f_changeInputFieldDisablement(e_forumNewThemeInputField, true);
		else
			f_changeInputFieldDisablement(e_forumNewThemeInputField, false);
		if (currentTheme == 0)
			f_changeInputFieldDisablement(e_forumNewMessageInputField, true);
		else
			f_changeInputFieldDisablement(e_forumNewMessageInputField, false);
	}
	currentMess = 0;
}

function f_setThemeNames (){
	if (currentTheme == 0){
		e_menuMainItem.classList.toggle("link", false);
		e_menuMiddleItem.classList.toggle("link", false);
		e_menuMiddleItem.style.height = '0px';
		e_menuLastItem.style.height = '0px';
	}
	else if (parentTheme == 0){
		e_menuMainItem.classList.toggle("link", true);
		e_menuMiddleItem.classList.toggle("link", false);
		e_menuMiddleItem.innerHTML = document.querySelector('#menu_middle_item_hidden').innerHTML;
		e_menuMiddleItem.style.height = '25px';
		e_menuLastItem.style.height = '0px';
	}
	else {
		e_menuMiddleItem.classList.toggle("link", true);
		e_menuLastItem.innerHTML = document.querySelector('#menu_last_item_hidden').innerHTML;
		e_menuLastItem.style.height = '25px';
	}
	f_AlignWindowsHeight ();
}

function f_convertSmilesAndTagFormat(someText){
	someText = someText.replace(/&lt;/g, '<');
	someText = someText.replace(/&gt;/g, '>');
	someText = someText.replace(/<br>/g, '\n');
	someText = someText.replace(/<img src=\"smile\//g, '{[:');
	someText = someText.replace(/.gif\">/g, ':]}');
	return someText
}

e_menuMainItem.onclick = () => {
	currentTheme = parentTheme = 0;
	f_forumUpdateContent(parentTheme);
}

e_menuMiddleItem.onclick = () => {
	currentTheme = parentTheme;
	parentTheme = 0;
	f_forumUpdateContent(parentTheme);
}