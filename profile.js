var e_profileMessageBlock = document.getElementById('messageWindow');
var e_profileNewMessageInputField = document.querySelector('#formSendMessage [name="string"]');
var e_profileNewMessageSendButton = document.querySelector('#formSendMessage .k_enter');
var e_profileNewMessageDestination = document.getElementById('dropDownUserList');

f_profileUpdateContent();
f_fetchUpdateContent('dropDownUserList', 'drop_down_user_list.php', null);
f_fetchUpdateContent('user_top_middle', 'top_users.php', null);


e_profileMessageBlock.onclick = function (event) {
	event = event || window.event;
	var elm = event.target;
	while (elm != e_profileMessageBlock) {
		if (elm.classList.contains('profile_delete_item_link')){
			deletingMess = elm.parentNode.parentNode.parentNode.getAttribute('item');
			f_fetchSaving ('profile_delete_message.php?mess=' + deletingMess, f_profileUpdateContent);
			break;
		}
		else elm = elm.parentNode;
	}
}

e_profileNewMessageSendButton.onclick = function () {
	var string = e_profileNewMessageInputField.value;
	var user = e_profileNewMessageDestination.value;
	f_fetchSaving ('profile_send_message.php?' +
		'string=' + string +
		'&user=' + user, f_profileUpdateContent);
}

function f_profileUpdateContent(){
	f_fetchUpdateContent('messageWindow', 'profile_content.php', null);
	e_profileNewMessageInputField.value = "";
	e_profileNewMessageDestination.value = 0;
	f_isWindowsHeightAlignment ();
}
