const e_profileMessageBlock = document.getElementById('messageWindow');
const e_profileNewMessageInputField = document.querySelector('#formSendMessage [name="string"]');
const e_profileNewMessageSendButton = document.querySelector('#formSendMessage .k_enter');
const e_profileNewMessageDestination = document.getElementById('dropDownUserList');
const e_profileAccountDeletionLink = document.getElementById('profileAccountDeletionLink');
const e_profileAccountDeletionPopup = document.getElementById('profileAccountDeletion');

f_profileUpdateContent();
f_fetchUpdateContent('dropDownUserList', 'drop_down_user_list.php', null);
f_fetchUpdateContent('user_top_middle', 'top_users.php', null);


e_profileMessageBlock.onclick = function (event) {
	event = event || window.event;
	var elm = event.target;
	while (elm != e_profileMessageBlock) {
		if (elm.classList.contains('profile_delete_item_link')){
			deletingMess = elm.parentNode.parentNode.parentNode.getAttribute('item');
			f_fetchSaving ('profile_delete_message.php', 'mess=' + deletingMess, f_profileUpdateContent);
			break;
		}
		else elm = elm.parentNode;
	}
}

e_profileNewMessageSendButton.onclick = function () {
	var string = e_profileNewMessageInputField.value;
	var user = e_profileNewMessageDestination.value;
	f_fetchSaving ('profile_send_message.php?',
		'string=' + string +
		'&user=' + user, f_profileUpdateContent);
}

e_profileAccountDeletionLink.onclick = function (event) {
	event = event || window.event;
	e_profileAccountDeletionPopup.style.display = 'block';
	x = event.pageX || event.clientX;
	y = event.pageY || event.clientY;
	e_profileAccountDeletionPopup.style.left = x-350+'px';
	e_profileAccountDeletionPopup.style.top = y-25+'px';
	return false;
}

e_profileAccountDeletionPopup.onmouseleave = function () {
    e_profileAccountDeletionPopup.style.display = 'none';
}

function f_profileUpdateContent(){
	f_fetchUpdateContent('messageWindow', 'profile_content.php', null);
	e_profileNewMessageInputField.value = "";
	e_profileNewMessageDestination.value = 0;
	f_isWindowsHeightAlignment ();
}
