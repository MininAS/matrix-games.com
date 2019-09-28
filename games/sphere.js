var a_block = new Array ();
var XxX = 15;
var YyY = 12;
var QqQ = 48;
var i_Nballov = 0;
var s_myElement = String('');
var flag_ANI = 0;
var flag_DOWN = false;
var flag_CLICK = false;
var flag_SHIFT = false;
var flag_SCORE = false;
var o_elm1;					// Ёлемент выбранный первым
var o_elm2;						// вторым
var i_Nverify = 0;    			// количество проверок за один ход
var i_Nball;					// кол-во на удаление
var i_Nball_1;				// кол-во в одном направлении и одном цвете
var i_Nball_2;
var i_Nball_3;
var i_Nball_4;

document.getElementById('game').style.width = XxX * QqQ+"px";

	var o_ScrollScore = document.createElement ('p');
	document.getElementById('box_center').appendChild(o_ScrollScore);
	o_ScrollScore.id = 'myN_ballov';
	o_ScrollScore.style.zIndex = 100;
	o_ScrollScore.className = 'border_inset';
	o_ScrollScore.style.position = 'absolute';

	<!--—оздаем массив игрового пол¤-->
function f_greateGame ()
{
	for (i=1; i<=(XxX * YyY); i++)
	{
		var myElement_ = document.createElement ('div');
		document.getElementById('game').appendChild(myElement_);
		var myElement = document.createElement ('img');
		myElement_.appendChild(myElement);
		myElement_.style.position = 'relative';
		myElement.style.position  = 'absolute';
		myElement_.style.display = 'inline-grid';
		myElement.style.left = 0+'px';
		myElement.style.top = 0+'px';
		myElement_.style.width = QqQ+'px';
		myElement_.style.height = QqQ+'px';
		myElement_.style.background = 'url(img/ball_0.png)';
		myElement.src = 'img/ball_0.png';
		myElement.style.zIndex = 99;
		myElement.index = (i-1);
		myElement.colTmp = 0;
		myElement.onclick = f_down;
		myElement.qx = 0;
		myElement.qy = 0;
	}
	myElement = document.getElementById('game')
	a_block = myElement.getElementsByTagName('img');
	f_Shift ();
}
function animate(lt, i)
{
	if (lt == 'l') {o_elm1.qx += i; o_elm2.qx += -(i);}
	if (lt == 't') {o_elm1.qy += i; o_elm2.qy += -(i);}
	o_elm1.style.left = o_elm1.qx + 'px';
	o_elm1.style.top = o_elm1.qy +'px';
	o_elm2.style.left = o_elm2.qx + 'px';
	o_elm2.style.top = o_elm2.qy +'px';

	if (o_elm1.qx == 48 || o_elm1.qy == 48 || o_elm2.qx == 48 || o_elm2.qy == 48)
	{
		src = o_elm1.src; o_elm1.qx = 0; o_elm2.qx = 0; o_elm1.qy = 0; o_elm2.qy = 0;
		o_elm1.src = o_elm2.src; o_elm1.style.left = '0px'; o_elm1.style.top = '0px';
		o_elm2.src = src; o_elm2.style.left = '0px'; o_elm2.style.top = '0px'; f_verify ();
	}
	else setTimeout ("animate('"+lt+"', "+i+");", 10);
}
function f_down ()
{
	if (flag_PLAY == true && this.color != 0)
	{
		if (flag_CLICK == false)
		{
			flag_CLICK = true;
			this.style.outline = '3px dashed #444';
			o_elm1 = this;
		}
		else
		{
			if (this.index == (o_elm1.index + 1) || this.index == (o_elm1.index - 1)
				|| this.index == (o_elm1.index + XxX) || this.index == (o_elm1.index - XxX))
			{
				o_elm2 = this;
				i_Nverify = 0;
				i_motion++;
				o_elm1.style.outline = '0px';
				if (o_elm1.color >= 8) {flag_CLICK = false; flag_PLAY = false; f_superBall ();}
				else
				{
					color = o_elm1.color;
					o_elm1.color = this.color;
					o_elm2.color = color;
					if (f_verify_ ())
					{
						flag_PLAY = false;
						if (o_elm2.index == (o_elm1.index + 1)) animate('l', 1);
						if (o_elm2.index == (o_elm1.index - 1)) animate('l', -1);
						if (o_elm2.index == (o_elm1.index + XxX)) animate('t', 1);
						if (o_elm2.index == (o_elm1.index - XxX)) animate('t', -1);
					}
					else
					{
						color = o_elm1.color;
						o_elm1.color = o_elm2.color;
						o_elm2.color = color;
					}
					flag_CLICK = false;
				}
			}
			else
			{
				o_elm1.style.outline = '0px';
				this.style.outline = '3px dashed #444';
				o_elm1 = this;
			}
		}
	}
}

function f_superBall ()
{
	if (o_elm1.color == 8)
	{
		i = Math.ceil((o_elm1.index+1) / XxX);
		ii = o_elm1.index - (XxX*(i-1))+1;
		if (o_elm2.index == (o_elm1.index + 1) || o_elm2.index == (o_elm1.index - 1))
		{
			for (ii=1; ii<=XxX; ii++)
			{
				iii = XxX*(i-1)+ii - 1;
				if (a_block[iii].color != 0) a_block[iii].colTmp = 1;
			}
		}
		if (o_elm2.index == (o_elm1.index + XxX) || o_elm2.index == (o_elm1.index - XxX))
		{
			for (i=1; i<=YyY; i++)
			{
				iii = XxX*(i-1)+ii - 1;
				if (a_block[iii].color != 0) a_block[iii].colTmp = 1;
			}
		}
		i_Nverify += 2;
		f_delet ();
	}
	if (o_elm1.color == 9)
	{
		for (i=0; i<a_block.length; i++) if (a_block[i].color == o_elm2.color) {a_block[i].colTmp = 1;}
		o_elm1.colTmp = 1;
		i_Nverify += 4;
		f_delet ();
	}
	if (o_elm1.color >= 11)
	{
		color = o_elm2.color;
		for (i=0; i<a_block.length; i++) if (a_block[i].color == color)
		{
			a_block[i].color = o_elm1.color - 10;
			a_block[i].src = 'img/ball_'+a_block[i].color+'.png';
		}
		o_elm1.color -= 10;
		o_elm1.src = 'img/ball_'+o_elm1.color+'.png';
		i_Nverify += 10;
		f_verify ();
	}
}

function f_verify_ () // Проверяем, что в различных направлениях есть по три шара
{
	i = Math.ceil((o_elm1.index+1) / XxX);
	ii = o_elm1.index - (XxX*(i-1))+1;
	i_Nball_1 = 1;
	if ((ii+1)<=XxX) if (o_elm1.color == a_block[o_elm1.index+1].color) {i_Nball_1++; if ((ii+2)<=XxX) if (o_elm1.color == a_block[o_elm1.index+2].color) {i_Nball_1++;}}
	if ((ii-1) >= 1) if (o_elm1.color == a_block[o_elm1.index-1].color) {i_Nball_1++; if ((ii-2) >= 1) if (o_elm1.color == a_block[o_elm1.index-2].color) {i_Nball_1++;}}

	i_Nball_2 = 1;
	if ((i+1)<=YyY) if (o_elm1.color == a_block[o_elm1.index+XxX].color)	{i_Nball_2++; if ((i+2)<=YyY) if (o_elm1.color == a_block[o_elm1.index+XxX+XxX].color) {i_Nball_2++;}}
	if ((i-1) >= 1) if (o_elm1.color == a_block[o_elm1.index-XxX].color)	{i_Nball_2++; if ((i-2) >= 1) if (o_elm1.color == a_block[o_elm1.index-XxX-XxX].color) {i_Nball_2++;}}

	i = Math.ceil((o_elm2.index+1) / XxX);
	ii = o_elm2.index - (XxX*(i-1))+1;
	i_Nball_3 = 1;
	if ((ii+1)<=XxX) if (o_elm2.color == a_block[o_elm2.index+1].color) {i_Nball_3++; if ((ii+2)<=XxX) if (o_elm2.color == a_block[o_elm2.index+2].color) {i_Nball_3++;}}
	if ((ii-1) >= 1) if (o_elm2.color == a_block[o_elm2.index-1].color) {i_Nball_3++; if ((ii-2) >= 1) if (o_elm2.color == a_block[o_elm2.index-2].color) {i_Nball_3++;}}

	i_Nball_4 = 1;
	if ((i+1)<=YyY) if (o_elm2.color == a_block[o_elm2.index+XxX].color)	{i_Nball_4++; if ((i+2)<=YyY) if (o_elm2.color == a_block[o_elm2.index+XxX+XxX].color) {i_Nball_4++;}}
	if ((i-1) >= 1) if (o_elm2.color == a_block[o_elm2.index-XxX].color) {i_Nball_4++; if ((i-2) >= 1) if (o_elm2.color == a_block[o_elm2.index-XxX-XxX].color) {i_Nball_4++;}}

	if (i_Nball_1>2 || i_Nball_2>2 || i_Nball_3>2 || i_Nball_4>2) return true;
	else return false;
}

function f_verify ()
{
	i_Nverify++;
	var flag_OK = false;
	for (i=1; i<=YyY; i++)
	{
		for (ii=1; ii<=XxX; ii++)
		{
			iii = XxX*(i-1)+ii - 1;
			if (a_block[iii].color == 0) continue;
			if ((i == 1 || i==YyY) && ii != 1 && ii != XxX)
			{
				if (a_block[iii].color == a_block[iii-1].color && a_block[iii].color == a_block[iii+1].color)
				{
					a_block[iii].colTmp = 1; a_block[iii-1].colTmp = 1; a_block[iii+1].colTmp = 1; flag_OK = true;
				}
			}
			if ((ii == 1 || ii==XxX) && i != 1 && i != YyY)
			{
				if (a_block[iii].color == a_block[iii-XxX].color && a_block[iii].color == a_block[iii+XxX].color)
				{
					a_block[iii].colTmp = 1; a_block[iii-XxX].colTmp = 1; a_block[iii+XxX].colTmp = 1; flag_OK = true;
				}
			}
			if (ii > 1 && ii < XxX && i > 1 && i < YyY)
			{
				if (a_block[iii].color == a_block[iii-1].color && a_block[iii].color == a_block[iii+1].color)
				{
					a_block[iii].colTmp = 1; a_block[iii-1].colTmp = 1; a_block[iii+1].colTmp = 1; flag_OK = true;
				}
				if (a_block[iii].color == a_block[iii-XxX].color && a_block[iii].color == a_block[iii+XxX].color)
				{
					a_block[iii].colTmp = 1; a_block[iii-XxX].colTmp = 1; a_block[iii+XxX].colTmp = 1; flag_OK = true;
				}
			}
		}
	}
	if  (flag_OK == false)       // —катываем шары
	{
		for (i=YyY; i>=2; i--)
		{
			for (ii=1; ii<=XxX; ii++)
			{
				iii = XxX*(i-1)+ii - 1;
				if (a_block[iii].color == 0)
				{
					if (a_block[iii-XxX+1].color != 0 && ii != XxX) // ѕриаритет скатывани¤ у правой стороны
					{
						flag_OK = true;
						i_Nball = iii-XxX+1;
						for (i_=(i-1); i_>=1; i_--)
						{
							iii = XxX*(i_-1)+ii - 1 + 1;
							if (a_block[iii].color != 0) i_Nball = iii;
							else break;
						}
						a_block[i_Nball-1].color = a_block[i_Nball].color;	a_block[i_Nball].color = 0;
						a_block[i_Nball-1].src = a_block[i_Nball].src;		a_block[i_Nball].src = 'img/ball_0.png'
					}
					else if (ii != 1) if (a_block[iii-XxX-1].color != 0)
					{
						flag_OK = true;
						i_Nball = iii-XxX-1;
						for (i_=(i-1); i_>=1; i_--)
						{
							iii = XxX*(i_-1)+ii - 1 - 1;
							if (a_block[iii].color != 0) i_Nball = iii;
							else break;
						}
						a_block[i_Nball+1].color = a_block[i_Nball].color;	a_block[i_Nball].color = 0;
						a_block[i_Nball+1].src = a_block[i_Nball].src;		a_block[i_Nball].src = 'img/ball_0.png';
					}
				}
				if (flag_OK == true) break;
			}
			if (flag_OK == true) break;
		}
		if (flag_OK == false) f_verify_over ();
		else flag_DOWN = true;
	}
	else f_delet ();
}

function f_verify_over () // Проверка на конец игры
{
	var flag_OK = false;
	for (var iii=0; iii<a_block.length; iii++)
	{
		if (a_block[iii].color != 0)
		{
			o_elm1 = a_block[iii];
			var i = Math.ceil((o_elm1.index+1) / XxX);
			var ii = o_elm1.index - (XxX*(i-1))+1;
			if (ii > 1)
			{
				if (a_block[iii].color >= 8 && a_block[iii-1].color != 0) flag_OK = true;
				o_elm2 = a_block[iii-1];
				color = o_elm1.color; o_elm1.color = o_elm2.color; o_elm2.color = color;
				if (f_verify_ () && o_elm1.color != 0) flag_OK = true;
				color = o_elm1.color; o_elm1.color = o_elm2.color; o_elm2.color = color;
			}
			if (ii < XxX)
			{
				if (a_block[iii].color >= 8 && a_block[iii+1].color != 0) flag_OK = true;
				o_elm2 = a_block[iii+1];
				color = o_elm1.color; o_elm1.color = o_elm2.color; o_elm2.color = color;
				if (f_verify_ () && o_elm1.color != 0) flag_OK = true;
				color = o_elm1.color; o_elm1.color = o_elm2.color; o_elm2.color = color;
			}
			if (i > 1)
			{
				if (a_block[iii].color >= 8 && a_block[iii-XxX].color != 0) flag_OK = true;
				o_elm2 = a_block[iii-XxX];
				color = o_elm1.color; o_elm1.color = o_elm2.color; o_elm2.color = color;
				if (f_verify_ () && o_elm1.color != 0) flag_OK = true;
				color = o_elm1.color; o_elm1.color = o_elm2.color; o_elm2.color = color;
			}
			if (i < YyY)
			{
				if (a_block[iii].color >= 8 && a_block[iii+XxX].color != 0) flag_OK = true;
				o_elm2 = a_block[iii+XxX];
				color = o_elm1.color; o_elm1.color = o_elm2.color; o_elm2.color = color;
				if (f_verify_ () && o_elm1.color != 0) flag_OK = true;
				color = o_elm1.color; o_elm1.color = o_elm2.color; o_elm2.color = color;
			}
		}
		if (flag_OK == true) {flag_PLAY = true; break;}
	}
	if (flag_OK == false) f_endGame ();
}

function f_delet ()
{
	if (i_Nball_1 == 3 && i_Nball_2 == 3) {o_elm1.color = 9; o_elm1.src = 'img/ball_9.gif'; o_elm1.colTmp =0;}
	if (i_Nball_3 == 3 && i_Nball_4 == 3) {o_elm2.color = 9; o_elm2.src = 'img/ball_9.gif'; o_elm2.colTmp =0;}
	if (i_Nball_1 == 4 || i_Nball_2 == 4) {o_elm1.color =  8; o_elm1.src = 'img/ball_8.gif'; o_elm1.colTmp =0;}
	if (i_Nball_3 == 4 || i_Nball_4 == 4) {o_elm2.color =  8; o_elm2.src = 'img/ball_8.gif'; o_elm2.colTmp =0;}
	if (i_Nball_1 == 5 || i_Nball_2 == 5) {o_elm1.color += 10; o_elm1.src = 'img/ball_'+o_elm1.color+'.gif'; o_elm1.colTmp =0;}
	if (i_Nball_3 == 5 || i_Nball_4 == 5) {o_elm2.color += 10; o_elm2.src = 'img/ball_'+o_elm2.color+'.gif'; o_elm2.colTmp =0;}
	if ((i_Nball_1 >= 3 || i_Nball_2 >= 3) && (i_Nball_3 >= 3 || i_Nball_4 >= 3))
	{
		if (o_elm1.color <= 7 ) {o_elm1.color = 9; o_elm1.src = 'img/ball_9.gif'; o_elm1.colTmp =0;}
		else {if (o_elm2.color <= 7 ) {o_elm2.color = 9; o_elm2.src = 'img/ball_9.gif'; o_elm2.colTmp =0;}}
	}
	i_Nball_1 = 0; i_Nball_2 = 0; i_Nball_3 = 0; i_Nball_4 = 0;

	var i_Nball = 0;
	for (i=0; i<a_block.length; i++)
	{
		if (a_block[i].colTmp == 1)
		{
			i_Nball++;
			flag_ANI ++;
			ani_hide(a_block[i], 100);
			ii = i;
		}
	}

	xy=getOffset(a_block[ii].parentNode);
	i_Nballov = i_Nball * i_Nverify;
	i_Scroll = 0;
	o_ScrollScore.innerHTML = i_Nballov;
	o_ScrollScore.style.display = 'block';
	i_ScrollY = xy.top;
	o_ScrollScore.style.left = xy.left+'px';

	flag_SCORE = true;
	i_score += i_Nballov;
	document.getElementById ('myNballov').innerHTML = i_score;
}

function ani_hide(elm, o)
{
	elm.style.opacity =  (o / 100);
	elm.style.filter = 'Alpha(opacity=' + o + ')';
	if (o > 0) {o -= 10; setTimeout (function () {ani_hide(elm, o);}, 50)}
	else setTimeout (function ()
		{
			elm.src = 'img/ball_0.png';
			elm.colTmp = 0;
			elm.color = 0;
			elm.style.opacity = 1;
			elm.style.filter = 'Alpha(opacity=' + 100 + ')';
			flag_ANI --;
			if (flag_ANI == 0) flag_DOWN = true;
		}, 300);
}

function f_Shift ()
{
	if (flag_DOWN == true)
	{
		var flag_OK = false;
		for (i = (YyY-1); i >=1; i--)
		{
			for (ii=1; ii<=(XxX); ii++)
			{
				iii = XxX*(i-1)+ii - 1;
				if (a_block[iii].color != 0 && a_block[iii+XxX].color == 0)
				{
					a_block[iii+XxX].color = a_block[iii].color;
					a_block[iii+XxX].src = a_block[iii].src;
					a_block[iii].color = 0;
					a_block[iii].src = 'img/ball_0.png';
					flag_OK = true;
				}
			}
		}
		if (flag_OK == false)
		{
			flag_DOWN = false;
			f_verify ();
		}
	}
	if (flag_SCORE == true)
	{
		o_ScrollScore.style.opacity =  1 - (i_Scroll / 100);
		o_ScrollScore.style.filter = 'Alpha(opacity=' + (100 - i_Scroll) + ')';
		o_ScrollScore.style.top = i_ScrollY - i_Scroll +'px';
		if (i_Scroll <= 100) {i_Scroll++; i_Scroll++;}
		else {i_Scroll = 0; o_ScrollScore.style.display = 'none'; flag_SCORE = false;}
	}
	setTimeout ("f_Shift ()", 100);
}

function f_newGame ()
{
	for (i=0; i<a_block.length; i++){
		a_block[i].color = Math.ceil (Math.random ()*7);
		a_block[i].src = 'img/ball_'+a_block[i].color+'.png';
	}
	for (i=1; i<=(XxX-2); i++){
		while (a_block[i].color == a_block[i+1].color
			&& a_block[i].color == a_block[i-1].color) a_block[i].color = Math.ceil (Math.random ()*7);
		a_block[i].src = 'img/ball_'+a_block[i].color+'.png';
	}
	for (i=(XxX*(YyY-1)+1); i<=(XxX*YyY-2); i++){
		while (a_block[i].color == a_block[i+1].color
			&& a_block[i].color == a_block[i-1].color) a_block[i].color = Math.ceil (Math.random ()*7);
		a_block[i].src = 'img/ball_'+a_block[i].color+'.png';
	}
	for (i=(XxX); i<(XxX*(YyY-1)); i++){
		while ((a_block[i].color == a_block[i+1].color
			&& a_block[i].color == a_block[i-1].color)
			|| (a_block[i].color == a_block[i+XxX].color
			&& a_block[i].color == a_block[i-XxX].color)) a_block[i].color = Math.ceil (Math.random ()*7);
		a_block[i].src = 'img/ball_'+a_block[i].color+'.png';
	}
	for (i=0; i<a_block.length; i++)
	{
		i_canvasKeymap += a_block[i].color;
		a_block[i].colTmp = 0;
	}
	flag_SHIFT = false;
}

function f_oldGame()
{
	for (i=0; i<=(XxX*YyY)-1; i++){
		a_block[i].color = Number(i_canvasKeymap.substr (i, 1));
		a_block[i].src = 'img/ball_'+a_block[i].color+'.png';
		s_myElement += a_block[i].color;
	}
	flag_SHIFT = false;
}
