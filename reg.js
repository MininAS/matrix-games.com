var e_regLoginInputField = document.getElementById('login');
var e_regPass1InputField = document.getElementById('pass1');
var e_regPass2InputField = document.getElementById('pass2');
var e_regEmailInputField = document.getElementById('e_mail');

initInputsRegistration ();

function initInputsRegistration ()
{
	e_regLoginInputField.onkeyup = text_login;
	e_regPass1InputField.onkeyup = text_pass1;
	e_regPass2InputField.onkeyup = text_pass2;
	e_regEmailInputField.onkeyup = text_e_mail;
	document.getElementById('key_Registration_Saving').onclick = reg_save;

	if (getCookie('vk_app_2729439') != undefined) text_login ();
}

function text_login ()
{
	var elmt = e_regPass1InputField;
	var elm = document.getElementById('login_');
	str = /[A-Za-z0-9А-Яа-я_\.\-@Ё]+/.exec(e_regLoginInputField.value);
	if (str == e_regLoginInputField.value){
		fetch('ajax_reg_verify_login.php', {
			method: 'POST',
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			body: 'login='+e_regLoginInputField.value
		})
			.then (response => {
				if (response.status == 200) {
					return response.text()
				}
			})
			.then (result => {
				   	if (result == 1){
				   	    if (getCookie('vk_app_2729439') != undefined) {
				   	    	elm.innerHTML = ' - ' + _l('Profile/user with this name is existed.');
				   			elm.style.color = 'blue';
				   			document.getElementById('pass1_').innerHTML = ' - ' + _l('Profile/password of this name, please.');
				   			f_changeInputFieldDisablement(e_regPass2InputField, true);
				   			f_changeInputFieldDisablement(e_regEmailInputField, true);
				   			elmt.disabled = false;
				   		}
				   		else {
				   			elm.innerHTML = ' - ' + _l('Profile/the name is not available.');
				   			elm.style.color = 'red';
				   		}
				   	}
				   	if (result == 0) {
				   		elm.innerHTML = ' - ' + _l('Profile/the name is available!');
				   		elm.style.color = 'green';
				   		f_changeInputFieldDisablement(e_regPass2InputField, false);
				   		f_changeInputFieldDisablement(e_regEmailInputField, false);
				   	}
			})
	}
	else
	{
		elm.innerHTML = ' - ' + _l('Profile/valid characters') + ' - .-_.';
		elm.style.color = 'red';
	}
}

function text_pass1 ()
{
	var elm = document.getElementById('pass1_');
	str = /.{4,}/.exec(e_regPass1InputField.value);
	if (str == e_regPass1InputField.value)
	{
		elm.innerHTML = ' - ОК !';
		elm.style.color = 'green';
	}
	else
	{
		elm.innerHTML = ' - ' + _l('Profile/password is very shot.');
		elm.style.color = 'red';
	}
	text_pass2 ();
}

function text_pass2 ()
{
	var elm = document.getElementById('pass2_');
	if (e_regPass1InputField.value == e_regPass2InputField.value)
	{
		elm.innerHTML = ' - ОК !';
		elm.style.color = 'green';
	}
	else
	{
		elm.innerHTML = ' - ' + _l('Profile/second password is not match.');
		elm.style.color = 'red';
	}
}

function text_e_mail ()
{
	var elm = document.getElementById('e_mail_');
	str = /^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/.exec(e_regEmailInputField.value);
	if (str == e_regEmailInputField.value)
	{
		elm.innerHTML = ' - ОК !';
		elm.style.color = 'green';
	}
	else
	{
		elm.innerHTML = ' - ' + _l('Profile/address is invalid.');
		elm.style.color = 'red';
	}
}

function reg_save ()
{
	string = 'login='+e_regLoginInputField.value
	       + '&pass='+e_regPass1InputField.value;
	if (e_regEmailInputField) string += '&e_mail='+e_regEmailInputField.value;
	if (document.getElementById('photo')) string += '&photo='+document.getElementById('photo').value;
	if (document.getElementById('last_name')) string += '&last_name='+document.getElementById('last_name').value;
	if (document.getElementById('first_name')) string += '&first_name='+document.getElementById('first_name').value;
	f_requestAndHandleForPopup ('reg_save.php', string, ()=>{setTimeout ("window.location.href='index.php';", 5000)});
}
