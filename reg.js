var e_regLoginInputField = document.getElementById('login');
var e_regPass1InputField = document.getElementById('pass1');
var e_regPass2InputField = document.getElementById('pass2');
var e_regEmailInputField = document.getElementById('e_mail');

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
	if (str == e_regLoginInputField.value)
	{
		var req = getXmlHttp();
		req.onreadystatechange = function()
		{
			if (req.readyState == 4)
			{
				if (req.status == 200)
				{
					result = req.responseText.substr (0, 1);
					if (result == 'f')
					{
						if (getCookie('vk_app_2729439') != undefined)
						{
							elm.innerHTML = '- есть такое имя в базе.';
							elm.style.color = 'blue';
							document.getElementById('pass1_').innerHTML = ' - пароль от этого имени.';
							f_changeInputFieldDisablement(e_regPass2InputField, true);
							f_changeInputFieldDisablement(e_regEmailInputField, true);
							elmt.disabled = false;
						}
						else
						{
							elm.innerHTML = '- простите, но такое имя занято.';
							elm.style.color = 'red';
						}
					}
					if (result == 't')
					{
						elm.innerHTML = '- это имя свободно!';
						document.getElementById('pass1_').innerHTML = ' - позволит входить минуя VK.';
						elm.style.color = 'green';
						f_changeInputFieldDisablement(e_regPass2InputField, false);
						f_changeInputFieldDisablement(e_regEmailInputField, false);
					}
				}
			}
		}
		req.open('POST', 'ajax_reg_verify_login.php', true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.send('login='+e_regLoginInputField.value);
	}
	else
	{
		elm.innerHTML = '- допустимые символы - .-_.';
		elm.style.color = 'red';
	}
}

function text_pass1 ()
{
	var elm = document.getElementById('pass1_');
	str = /.{4,}/.exec(e_regPass1InputField.value);
	if (str == e_regPass1InputField.value)
	{
		elm.innerHTML = '- ОК !';
		elm.style.color = 'green';
	}
	else
	{
		elm.innerHTML = '- пароль слишком короткий.';
		elm.style.color = 'red';
	}
	text_pass2 ();
}

function text_pass2 ()
{
	var elm = document.getElementById('pass2_');
	if (e_regPass1InputField.value == e_regPass2InputField.value)
	{
		elm.innerHTML = '- ОК !';
		elm.style.color = 'green';
	}
	else
	{
		elm.innerHTML = '- повторение пароля не совпадает.';
		elm.style.color = 'red';
	}
}

function text_e_mail ()
{
	var elm = document.getElementById('e_mail_');
	str = /^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/.exec(e_regEmailInputField.value);
	if (str == e_regEmailInputField.value)
	{
		elm.innerHTML = '- ОК !';
		elm.style.color = 'green';
	}
	else
	{
		elm.innerHTML = '- адрес не допустим.';
		elm.style.color = 'red';
	}
}

function reg_save ()
{
		get = 'login='+e_regLoginInputField.value
		+'&pass='+e_regPass1InputField.value;
		if (e_regEmailInputField) get+='&e_mail='+e_regEmailInputField.value;
		if (document.getElementById('photo')) get += '&photo='+document.getElementById('photo').value;
		if (document.getElementById('last_name')) get += '&last_name='+document.getElementById('last_name').value;
		if (document.getElementById('first_name')) get += '&first_name='+document.getElementById('first_name').value;
		f_fetchUpdateContent('info_div', 'ajax_reg_save.php', get);
		window_info ('text_info');
		setTimeout ("window.location.href='index.php';", 5000);
}
