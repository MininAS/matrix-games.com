var i = Number ();
var ii = Number ();
var iii = Number ();
var XxX = 17;
var YyY = 24;
var QqQ = 22;
var i_N_mine = 500;
var i_canvasState = 0;
var i_Nxodov = 0;
var i_Nscore = 0;
var s_gameDB = String('');
var a_gameDB = [];
var i_nextMine = 0;
var flag_PLAY = true;
var flag_DOWN = false;
var flag_CLICK = false;
var flag_GAMEOVER = false;
var flag_SCORE = false;
var flag_P = false; // Нажатие правой при нажатой левой клавиши мыши

var a_block = [];
for (i=0; i<=XxX; i++)	a_block[i] = new Array ();

e = document.getElementById('game'); // Игровое поле
e.style.width = XxX * QqQ + QqQ + "px";
e.classList.toggle('noselect');
e.addEventListener('touchmove', function(event) {
  event.preventDefault();
}, false);


	var myElement = document.createElement ('div');   // Столбец указывающий на количество мин
	document.getElementById('game').appendChild(myElement);
	myElement.id = 'tag_Mine';
	myElement.classList.add('windowTitle');
	myElement.style.height = YyY*QqQ-100 + 'px';

	var tag_Mine = document.createElement ('img');
	document.getElementById('tag_Mine').appendChild(tag_Mine);
	tag_Mine.src = 'img/sapper_12.png';

	var myN_Mine = document.createElement ('p');
	document.getElementById('tag_Mine').appendChild(myN_Mine);
	myN_Mine.innerHTML = '500';
	myN_Mine.style.bottom = -22+'px';

	<!--Создаем массив игрового поля-->
function f_greateGame ()
{
	fragment = document.createDocumentFragment();
	for (ii=YyY; ii>=0; ii--)
	{
		for (i=XxX; i>=0; i--)
		{
			var myElem = fragment.appendChild(document.createElement('img'));
			myElem.src = 'img/sapper_9.png';
			myElem.onclick = f_onClick;
			myElem.addEventListener('touchstart', f_onClick_R);
			myElem.doom = 0;
			myElem.view = 0;
			myElem.verify = function ()
			{
				if (this.doom > 0 && this.doom < 9)
				{
					this.src = 'img/sapper_'+this.doom+'.png';
					this.view = 1;
				}
				if (this.doom == 0 && this.view == 0) {this.view = 2; return true;}
			}
			myElem.verify_C = function ()
			{
				if (this.doom >= 0 && this.doom < 9 && this.view != 11)
				{
					this.src = 'img/sapper_'+this.doom+'.png';
					this.view = 1;
					if (this.doom == 0) this.view = 2;
					return true;
				}
				if (this.doom == 11)
				{
					if (this.view == 0)
					{
						for (ii=0; ii<=YyY; ii++)
						{
							for (i=0; i<=XxX; i++)
							{
								if  (a_block[i][ii].doom == 11) a_block[i][ii].src = 'img/sapper_10.png';
							}
						}
						f_endGame ();
						return false;
					}
					if (this.view == 11) return true;

				}
			}
			myElem.onmousedown = f_onClick_R;
			myElem.onmouseup = function (){flag_P = false;};
			a_block[i][ii] = myElem;
		}
	}
	document.getElementById('game').appendChild(fragment);

	document.getElementById('k_pauseGame').style.display = 'inline-block';
	setInterval ('f_time ()', 1000);
}
			<!--Перенаправляем правую кнопку мыши и вкл.клавиши-->
document.oncontextmenu = new Function ("return false;");
document.onkeypress = f_KeyPress;
document.onkeydown = f_KeyPress;

function f_KeyPress (event)
{
	if (flag_PLAY == true)
	{
		event = (event) ? event : window.event;
		evt = (event.keyCode) ? event.keyCode : event.which;
		if (evt == 32) {window_info ('pause'); return false;}
	}
}


function f_paint() // Зарисовка пустых клеток
{
	var f_OK = true;
	while (f_OK == true)
	{
		f_OK = false;
		for (ii=0; ii<=YyY; ii++)
		{
			for (i=0; i<=XxX; i++)
			{
				if  (a_block[i][ii].view == 2)
				{
					a_block[i][ii].view = 1; a_block[i][ii].src = 'img/sapper_'+a_block[i][ii].doom+'.png';
					if (i != 0) 				if (a_block[i-1][ii].verify()) f_OK = true;
					if (i != XxX)				if (a_block[i+1][ii].verify()) f_OK = true;
					if (ii != 0)				if (a_block[i][ii-1].verify()) f_OK = true;
					if (ii != YyY)				if (a_block[i][ii+1].verify()) f_OK = true;
					if (i != 0 && ii != 0)		if (a_block[i-1][ii-1].verify()) f_OK = true;
					if (i != 0 && ii != YyY) 	if (a_block[i-1][ii+1].verify()) f_OK = true;
					if (i != XxX && ii != 0) 	if (a_block[i+1][ii-1].verify()) f_OK = true;
					if (i != XxX && ii != YyY) 	if (a_block[i+1][ii+1].verify()) f_OK = true;
				}
			}
		}
	}
}

function f_onClick_R (event) // Правая кнопка мыши
{
	if (flag_PLAY == true && flag_PAUSE == false)
	{
		e = (event) ? event : window.event;
		if (e.type == 'touchstart')
		{
			if (e.touches.length == 2){e.button = 2;}
			if (e.touches.length == 3){e.button = 1;}
		}
		if (e.button == 2 && flag_P == false)
		{
			if (this.view == 0) {this.src = 'img/sapper_11.png'; this.view = 11;}
			else {this.src = 'img/sapper_9.png'; this.view = 0;}
			e.stopPropagation();
			e.preventDefault();
			f_verify ();
			return false;
		}
		if (e.button == 0) flag_P = true;
		if (e.button == 1 || (flag_P == true && e.button == 2))
		{
			if (this.view == 1) {this.view = 3; f_onClick_C ();}
			return false;
		}
	}
}


function f_onClick (event) // Левая кнопка мыши
{
	if (flag_PLAY == true && flag_PAUSE == false)
	{
		e = (event) ? event : window.event;
		if (e.which == 1 || e.button == 0)
		{
			if (this.doom > 0 && this.doom < 9 && this.view == 0) {this.src = 'img/sapper_'+this.doom+'.png'; this.view = 1;}
			if (this.doom == 0 && this.view == 0)
			{
				this.view = 2; // На обработку
				f_paint ();
			}
			if (this.doom == 11 && this.view == 0) // Взорвался
			{
				for (ii=0; ii<=YyY; ii++)
				{
					for (i=0; i<=XxX; i++)
					{
						if  (a_block[i][ii].doom == 11) a_block[i][ii].src = 'img/sapper_10.png';
					}
				}
				f_endGame ();
			}
			else f_verify ();
		}
	}
}


function f_onClick_C () // Нажатие левой при нажатой правой - открываем клетки вокруг.
{
	f_OK = true;
	for (ii=0; ii<=YyY; ii++)
	{
		for (i=0; i<=XxX; i++)
		{
			if  (a_block[i][ii].view == 3)
			{
				a_block[i][ii].view = 1;
				if (f_OK == true && i != 0)		f_OK = a_block[i-1][ii].verify_C();
				if (f_OK == true && i != XxX)	f_OK = a_block[i+1][ii].verify_C();
				if (f_OK == true && ii != 0)	f_OK = a_block[i][ii-1].verify_C();
				if (f_OK == true && ii != YyY)	f_OK = a_block[i][ii+1].verify_C();
				if (f_OK == true && i != 0 && ii != 0)		f_OK = a_block[i-1][ii-1].verify_C();
				if (f_OK == true && i != 0 && ii != YyY)	f_OK = a_block[i-1][ii+1].verify_C();
				if (f_OK == true && i != XxX && ii != 0)	f_OK = a_block[i+1][ii-1].verify_C();
				if (f_OK == true && i != XxX && ii != YyY)	f_OK = a_block[i+1][ii+1].verify_C();
			}
		}
	}
	if (f_OK == true)  {f_paint (); f_verify ();}
}


function f_verify ()
{
	flag_PLAY = false;
	var f_OK = true;
	for (ii=0; ii < 5; ii++)
	{
		for (i=0; i<=XxX; i++)
		{
			if (a_block[i][ii].view == 0) f_OK = false;
			if (a_block[i][ii].view == 11 && a_block[i][ii].doom != 11) f_OK = false;
		}
	}
	if (i_Nxodov != i_N_mine){
			// Роняем экран внис на клетку при верном обнаружении мин через интервал
		if (f_OK == true) setTimeout (" flag_DOWN = true; f_Shift (); f_verify ();", 100);
			// Закончили скрол, теперь возобновляем игру (пауза для исключения неверного нажатия при сколе)
		else setTimeout ("flag_PLAY = true; if (flag_DOWN == true) {f_paintDoom(); flag_DOWN = false;}", 100);
	}
	else {flag_DOWN = false; f_endGame ();}
}

function f_time ()
{
	if (flag_PAUSE == false && flag_GAMEOVER == false) i_Nscore --;
	if (typeof document.getElementById ('myNballov') == 'object') document.getElementById ('myNballov').innerHTML = i_Nscore;
}

function f_Shift ()
{
	if (flag_DOWN == true)
	{
		for (i=0; i<=XxX; i++) {i_Nscore += a_block[i][0].doom; if (a_block[i][0].doom == 11) i_Nxodov++;}

		document.getElementById ('myNballov').innerHTML = i_Nscore;
		for (ii=0; ii < YyY; ii++)
		{
			for (i=0; i<=XxX; i++)
			{
				a_block[i][ii].doom = a_block[i][ii+1].doom;
				a_block[i][ii].view = a_block[i][ii+1].view;
				a_block[i][ii].src = a_block[i][ii+1].src;
			}
		}
		ii = YyY;
		for (i=0; i<=XxX; i++)
		{
			a_block[i][ii].doom = 0;
			a_block[i][ii].view = 0;
			a_block[i][ii].src = 'img/sapper_9.png';
			if (i_nextMine <= i_N_mine) {
				if (a_gameDB[i_nextMine] == 0) {
					a_block[i][ii].doom = 11;
					i_nextMine++;
				}
				a_gameDB[i_nextMine]--;
			}
		}
	tag_Mine.style.height =  100-Math.ceil (100*i_Nxodov/i_N_mine) + '%';
	myN_Mine.innerHTML = 500-i_Nxodov;
	myN_Mine.style.bottom = i_Nxodov - 22 +'px';
	}
}

function f_paintDoom()
{
	for (ii=0; ii<=YyY; ii++)
	{
		for (i=0; i<=XxX; i++)
		{
			if (a_block[i][ii].doom != 11) {
				a_block[i][ii].doom = 0;
				if (i != 0)				if (a_block[i-1][ii].doom == 11) a_block[i][ii].doom ++;
				if (i != XxX)			if (a_block[i+1][ii].doom == 11) a_block[i][ii].doom ++;
				if (ii != 0)			if (a_block[i][ii-1].doom == 11) a_block[i][ii].doom ++;
				if (ii != YyY)			if (a_block[i][ii+1].doom == 11) a_block[i][ii].doom ++;
				if (i != 0 && ii != 0)	if (a_block[i-1][ii-1].doom == 11) a_block[i][ii].doom ++;
				if (i != 0 && ii != YyY) if (a_block[i-1][ii+1].doom == 11) a_block[i][ii].doom ++;
				if (i != XxX && ii != 0) if (a_block[i+1][ii-1].doom == 11) a_block[i][ii].doom ++;
				if (i != XxX && ii != YyY) if (a_block[i+1][ii+1].doom == 11) a_block[i][ii].doom ++;
				if (a_block[i][ii].view == 1) a_block[i][ii].src = 'img/sapper_'+a_block[i][ii].doom+'.png';
			}
		}
	}
	tag_Mine.style.height =  100-Math.ceil (100*i_Nxodov/i_N_mine) + '%';
}

function f_newGame ()
{
	a_gameDB = [];
	s_gameDB = '';
	for (ii=0; ii<=YyY; ii++)
	{
		for (i=0; i<=XxX; i++)
		{
			a_block[i][ii].doom = 0;
			a_block[i][ii].view = 0;
			a_block[i][ii].src = 'img/sapper_9.png';
		}
	}
	for (i=i_N_mine; i>0; i--)
	{
		tmp = Math.ceil (Math.random ()*(i/30)+Math.random ()*7);
		a_gameDB.push(tmp);
		s_gameDB += tmp+'/';
	}

	i_nextMine = 0;
	for (ii=0; ii<=YyY; ii++)
	{
		for (i=0; i<=XxX; i++)
		{
			if (a_gameDB[i_nextMine] == 0) {
				a_block[i][ii].doom = 11;
				i_nextMine++;
			}
			a_gameDB[i_nextMine]--;
		}
	}
	f_paintDoom ();
	document.getElementById('canvasState').value = '0';
	document.getElementById('game_sport').style.display = 'none';
	i_Nxodov = 0;
	i_Nscore = 100;
	flag_PLAY = true;
	flag_GAMEOVER = false;
}

function f_oldGame(i_game)
{
	var req = getXmlHttp();
	req.onreadystatechange = function()
		{
		 	if (req.readyState == 4)
			{
				if (req.status == 200)
				{
					i_tmp = req.responseText;
					i_canvasState = i_game;
					str = i_tmp.substr (0, 1);
					if (str > 0 && str <= 9)
					{
						a_gameDB = [];
						s_gameDB = i_tmp;
						a_gameDB = i_tmp.split("/");

						for (ii=0; ii<=YyY; ii++)
						{
							for (i=0; i<=XxX; i++)
							{
								a_block[i][ii].doom = 0;
								a_block[i][ii].view = 0;
								a_block[i][ii].src = 'img/sapper_9.png';
							}
						}

						i_nextMine = 0;
						for (ii=0; ii<=YyY; ii++)
						{
							for (i=0; i<=XxX; i++)
							{
								if (a_gameDB[i_nextMine] == 0) {
									a_block[i][ii].doom = 11;
									i_nextMine++;
								}
								a_gameDB[i_nextMine]--;
							}
						}
						f_paintDoom ();
						i_Nxodov = 0;
						i_Nscore = 100;
						flag_PLAY = true;
						flag_GAMEOVER = false;
						document.getElementById('canvasState').value = i_canvasState;
						document.getElementById('game_sport').style.display = 'inline';
						document.getElementById('game_sport').innerHTML = '№ ' + document.getElementById('canvasState').value;
					}
					else
					{
						window_info ('text_info', i_tmp);
						flag_PLAY = false;
					}
				}
			}
   		}
	req.open('GET', 'ajax_game_load.php?theme=sapper&canvasState=' + i_game, true);
	req.send(null);
}
function f_endGame()
{
	flag_PLAY = false;
	flag_GAMEOVER = true;
	document.getElementById('mess').value = s_gameDB + "\t" + i_Nxodov + "\t" + i_Nscore;
	window_info ('text_info');
	if (i_Nscore > 100) {
		f_fetchUpdateContent('info_div', 'ajax_game_save.php', 'mess='+document.getElementById('mess').value+'&theme=sapper&canvasState='+document.getElementById('canvasState').value);
		setTimeout ("f_fetchUpdateContent('user_top_middle', 'ajax_user_top_game.php', 'theme=sapper')", 3000);
	} else {
		document.getElementById('info_div').innerHTML = 'Слишком маленький результат, попробуйте сыграть ще раз.';
	}
}
