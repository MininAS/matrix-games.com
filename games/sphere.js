var a_block = new Array ();
var XxX = 15;
var YyY = 12;
var QqQ = 48;
var i_Nballov = 0;
var s_myElement = String('');
var flag_ANI = 0;
var flag_SELECTED = false;
var o_elm1;					// Ёлемент выбранный первым
var o_elm2;						// вторым
var i_Nverify = 0;    			// количество проверок за один ход
var i_Nball;					// кол-во на удаление

document.getElementById('game').style.width = XxX * QqQ+"px";
f_showSoundButton ();

	<!--Создаем массив игрового поля-->
function f_greateGame (){
	for (i=1; i<=(XxX * YyY); i++){
		var myElement_ = document.createElement ('div');
		document.getElementById('game').appendChild(myElement_);
		var myElement = document.createElement ('img');
		myElement_.appendChild(myElement);
		myElement.style.position  = 'relative';
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
		myElement.onclick = f_clickHandler;
		myElement.setSphereColor = function (color){
			this.style.zIndex = 99;
			this.color = color;
			this.colTmp = 0;
			ext = (color <= 7) ? 'png' : 'gif';
		    this.src = 'img/ball_' + color + '.' + ext;
		}
		myElement.degree = 0;
		myElement.setSphereDegree = function (degree){
			this.degree = degree;
			this.style.transform = 'rotate(' + this.degree + 'deg)';
		}
	}
	myElement = document.getElementById('game')
	a_block = myElement.getElementsByTagName('img');
	a_block = Array.from(a_block);
}

function f_spheresCastling (elm1, elm2){
	o_elm1.style.transition = 'top 0.5s linear, left 0.5s linear';
	o_elm2.style.transition = 'top 0.5s linear, left 0.5s linear';

	o_elm1.addEventListener('transitionend', f_spheresCastlingFinish);
	o_elm1.addEventListener('webkitTransitionEnd', f_spheresCastlingFinish);

	if (elm1.offsetLeft == elm2.offsetLeft){
		if (elm1.offsetTop > elm2.offsetTop)
		    [elm1, elm2] = [elm2, elm1];
		elm1.style.top = 48 + 'px';
		elm2.style.top = -48 + 'px';
	}
	else if (elm1.offsetTop == elm2.offsetTop){
	    if (elm1.offsetLeft > elm2.offsetLeft)
		    [elm1, elm2] = [elm2, elm1];
		elm1.style.left = 48 + 'px';
		elm2.style.left = -48 + 'px';
	}
	f_playSound('sphere_shift');
}

function f_spheresCastlingFinish(){
	o_elm1.removeEventListener('transitionend', f_spheresCastlingFinish);
	o_elm1.removeEventListener('webkitTransitionEnd', f_spheresCastlingFinish);
	o_elm1.style.transition = 'none';
	o_elm2.style.transition = 'none';
	o_elm1.style.left = '0px';
	o_elm1.style.top = '0px';
	o_elm2.style.left = '0px';
	o_elm2.style.top = '0px';
	[o_elm1.degree, o_elm2.degree] = [o_elm2.degree, o_elm1.degree];
	[o_elm1.color, o_elm2.color] = [o_elm2.color, o_elm1.color];
    o_elm1.setSphereColor(o_elm1.color);
	o_elm1.setSphereDegree(o_elm1.degree);
	o_elm2.setSphereColor(o_elm2.color);
	o_elm2.setSphereDegree(o_elm2.degree);

	b_sequencesIsExist = f_verifySequences ();
	f_detectSuperBall ();
	if (b_sequencesIsExist)
		f_delet ();
}

function f_clickHandler (){
	if (flag_PLAY == true && this.color != 0){
		if (flag_SELECTED == false){
			flag_SELECTED = true;
			o_elm1 = this;
			o_elm1.style.outline = '3px dashed #444';
		}
		else {
			o_elm1.style.outline = 'inherit';
			o_elm2 = this;
			if (o_elm2.index == (o_elm1.index + 1) ||
				o_elm2.index == (o_elm1.index - 1) ||
				o_elm2.index == (o_elm1.index + XxX) ||
				o_elm2.index == (o_elm1.index - XxX))
			{
				i_Nverify = 0;
				i_motion++;
				flag_SELECTED = false;
				flag_PLAY = false;

				if (o_elm1.color >= 8)
					f_useSuperSphere ();
				else {
					if (f_verifyTurnAbility ())
						setTimeout(f_spheresCastling (o_elm1, o_elm2), 1000);
					else {
					    flag_SELECTED = true;
						flag_PLAY = true;
						o_elm1 = this;
						o_elm1.style.outline = '3px dashed #444';
					}
				}
			}
			else {
				o_elm1 = this;
				o_elm1.style.outline = '3px dashed #444';
			}
		}
	}
}

function f_useSuperSphere (){
	if (o_elm1.color == 8){
		i = Math.ceil((o_elm1.index+1) / XxX);
		ii = o_elm1.index - (XxX*(i-1))+1;
		if (o_elm2.index == (o_elm1.index + 1) || o_elm2.index == (o_elm1.index - 1)){
			for (ii=1; ii<=XxX; ii++){
				iii = XxX*(i-1)+ii - 1;
				if (a_block[iii].color != 0) a_block[iii].colTmp = 1;
			}
		}
		if (o_elm2.index == (o_elm1.index + XxX) || o_elm2.index == (o_elm1.index - XxX)){
			for (i=1; i<=YyY; i++){
				iii = XxX*(i-1)+ii - 1;
				if (a_block[iii].color != 0) a_block[iii].colTmp = 1;
			}
		}
		i_Nverify += 2;
		f_playSound('sphere_super');
		f_delet ();
	}

	if (o_elm1.color == 9){
		for (i=0; i<a_block.length; i++) if (a_block[i].color == o_elm2.color) {a_block[i].colTmp = 1;}
		o_elm1.colTmp = 1;
		i_Nverify += 4;
		f_playSound('sphere_super');
		f_delet ();
	}

	if (o_elm1.color >= 11){
		color = o_elm2.color;
		for (i=0; i<a_block.length; i++) if (a_block[i].color == color){
			a_block[i].color = o_elm1.color - 10;
			a_block[i].src = 'img/ball_'+a_block[i].color+'.png';
		}
		o_elm1.color -= 10;
		o_elm1.src = 'img/ball_'+o_elm1.color+'.png';
		i_Nverify += 10;
		if (f_verifySequences ()){
			f_playSound('sphere_super');
		    f_delet ();
		}
	}
}

function f_verifyTurnAbility (){
    [o_elm1.color, o_elm2.color] = [o_elm2.color, o_elm1.color];
	let a_balls = [];
	[o_elm1, o_elm2].forEach((elm) => {
        let ball1 = ball2 = 1;
		let i = Math.ceil((elm.index + 1) / XxX);
		let ii = elm.index - (XxX * (i - 1)) + 1;
		let color = elm.color;

	    for (n = 1; n <= 2; n++)
			if ((ii + n) <= XxX)
				if (a_block[elm.index + n].color == color)
					ball1 ++;
				else break;

	    for (n = 1; n <= 2; n++)
			if ((ii - n) >= 1)
				if (a_block[elm.index - n].color == color)
					ball1 ++;
				else break;

	    for (n = 1; n <= 2; n++)
			if ((i + n) <= YyY)
				if (a_block[elm.index + XxX * n].color == color)
					ball2 ++;
				else break;

	    for (n = 1; n <= 2; n++)
			if ((i - n) >= 1)
				if (a_block[elm.index - XxX * n].color == color)
					ball2 ++;
				else break;

        a_balls.push(ball1, ball2);
    });
    [o_elm1.color, o_elm2.color] = [o_elm2.color, o_elm1.color];
	return a_balls.some((item) => {return item >= 3});
}

function f_verifySequences (){
	i_Nverify++;
	var flag_OK = false;
	for (i=1; i<=YyY; i++){
		for (ii=1; ii<=XxX; ii++){
			iii = XxX*(i-1)+ii - 1;
			if (a_block[iii].color == 0) continue;
			if ((i == 1 || i==YyY) && ii != 1 && ii != XxX){
				if (a_block[iii].color == a_block[iii-1].color && a_block[iii].color == a_block[iii+1].color){
					a_block[iii].colTmp = 1; a_block[iii-1].colTmp = 1; a_block[iii+1].colTmp = 1; flag_OK = true;
				}
			}
			if ((ii == 1 || ii==XxX) && i != 1 && i != YyY){
				if (a_block[iii].color == a_block[iii-XxX].color && a_block[iii].color == a_block[iii+XxX].color){
					a_block[iii].colTmp = 1; a_block[iii-XxX].colTmp = 1; a_block[iii+XxX].colTmp = 1; flag_OK = true;
				}
			}
			if (ii > 1 && ii < XxX && i > 1 && i < YyY){
				if (a_block[iii].color == a_block[iii-1].color && a_block[iii].color == a_block[iii+1].color){
					a_block[iii].colTmp = 1; a_block[iii-1].colTmp = 1; a_block[iii+1].colTmp = 1; flag_OK = true;
				}
				if (a_block[iii].color == a_block[iii-XxX].color && a_block[iii].color == a_block[iii+XxX].color){
					a_block[iii].colTmp = 1; a_block[iii-XxX].colTmp = 1; a_block[iii+XxX].colTmp = 1; flag_OK = true;
				}
			}
		}
	}
	return flag_OK;
}

function f_detectSuperBall (){
    let a_balls = [];
    [o_elm1, o_elm2].forEach((elm) => {

        let ball1 = ball2 = 1;
		let i = Math.ceil((elm.index + 1) / XxX);
		let ii = elm.index - (XxX * (i - 1)) + 1;
		let color = elm.color;

	    for (n = 1; n <= 2; n++)
			if ((ii + n) <= XxX)
				if (a_block[elm.index + n].color == color)
					ball1 ++;
				else break;

	    for (n = 1; n <= 2; n++)
			if ((ii - n) >= 1)
				if (a_block[elm.index - n].color == color)
					ball1 ++;
				else break;

	    for (n = 1; n <= 2; n++)
			if ((i + n) <= YyY)
				if (a_block[elm.index + XxX * n].color == color)
					ball2 ++;
				else break;

	    for (n = 1; n <= 2; n++)
			if ((i - n) >= 1)
				if (a_block[elm.index - XxX * n].color == color)
					ball2 ++;
				else break;

        a_balls.push(ball1, ball2);
    });

	if (a_balls[0] == 5 || a_balls[1] == 5){
		color = o_elm1.color += 10;
		o_elm1.setSphereColor(color);
	}

	if (a_balls[2] == 5 || a_balls[3] == 5){
        color = o_elm2.color += 10;
		o_elm2.setSphereColor(color);
	}

    if (a_balls.some((item) => {return item == 5;})) return;

    if (a_balls[0] >= 3 && a_balls[1] >= 3)
		o_elm1.setSphereColor(9);

	if (a_balls[2] >= 3 && a_balls[3] >= 3)
		o_elm2.setSphereColor(9);

    if (a_balls[0] >= 3 && a_balls[1] >= 3 || a_balls[2] >= 3 && a_balls[3] >= 3) return;

	if ((a_balls[0] >= 3 || a_balls[1] >= 3) && (a_balls[2] >= 3 || a_balls[3] >= 3)){
		o_elm1.setSphereColor(9);
		return;
	}

	if (a_balls[0] == 4 || a_balls[1] == 4)
		o_elm1.setSphereColor(8);

	if (a_balls[2] == 4 || a_balls[3] == 4)
		o_elm2.setSphereColor(8);
}

function f_delet (){
	var i_Nball = 0;
	let o_lastBall = {};

	a_block.forEach((item) => {
		if (item.colTmp == 1){
			i_Nball ++;
			f_hideSphere(item);
			o_lastBall = item;
		}
	});

	i_Nballov = i_Nball * i_Nverify;
	i_Scroll = 0;
	f_scrollScore (o_lastBall, i_Nballov);
	i_score += i_Nballov;
	document.getElementById ('myNballov').innerHTML = i_score;
}

function f_hideSphere (elm){
	flag_ANI ++;
	elm.style.transition = 'opacity 1s';
	elm.addEventListener('transitionend', f_hideSphereFinish);
	elm.addEventListener('webkitTransitionEnd', f_hideSphereFinish);
	elm.style.opacity = 0;
}

function f_hideSphereFinish(){
	this.removeEventListener('transitionend', f_hideSphereFinish);
	this.removeEventListener('webkitTransitionEnd', f_hideSphereFinish);
	this.style.transition = 'none';
    this.setSphereColor(0);
	this.setSphereDegree(0);
	this.style.opacity = 1;
	flag_ANI --;
	if (flag_ANI == 0) f_shperesFail ();
}

function f_shperesFail (){
	let flag_OK = false;
	flag_ANI = 0;
	for (i = (YyY-1); i >=1; i--){
		for (ii=1; ii<=(XxX); ii++){
			iii = XxX*(i-1)+ii - 1;
			let elm = a_block[iii];
			if (elm.color != 0 && a_block[iii + XxX].color == 0){
                f_shpereShift (elm, 'down')
				flag_OK = true;
			}
		}
	}
	if (flag_OK == false)
		if (f_verifySequences ())
			f_delet ();
		else f_rollSphere ();
}

function f_shpereShift (elm, direct){
	if (flag_NEWSTART) return;
	if (elm.style.top != '0px' || elm.style.left != '0px') return;
	flag_ANI ++;
	elm.style.zIndex = 100;
	elm.addEventListener('transitionend', f_spheresShiftFinish);
	elm.addEventListener('webkitTransitionEnd', f_spheresShiftFinish);
	elm.style.transition = 'top 0.2s linear';

	elm.style.top = QqQ + 'px';

	if (direct == 'down') return;
	elm.style.transition = 'top 0.3s ease-in, left 0.3s ease-out, transform 0.3s linear';
	if (direct == 'left'){
		elm.style.left = -QqQ + 'px';
		elm.setSphereDegree(elm.degree - 90);
	}
	if (direct == 'right'){
		elm.style.left = QqQ + 'px';
		elm.setSphereDegree(elm.degree + 90);
    }
}

function f_spheresShiftFinish(){
	this.removeEventListener('transitionend', f_spheresShiftFinish);
	this.removeEventListener('webkitTransitionEnd', f_spheresShiftFinish);
	this.style.transition = 'none';
	let i = 0;
	if (this.style.left == (QqQ + 'px')) i = 1;
	if (this.style.left == (-QqQ + 'px')) i = -1;
	a_block[this.index + i + XxX].setSphereColor(this.color);
	a_block[this.index + i + XxX].setSphereDegree(this.degree);
	if ((this.index + XxX + XxX + i) < (XxX * YyY) && a_block[this.index + XxX + XxX + i].color != 0)
	    f_playSound('sphere_impact');
	this.style.top = 0 + 'px';
	this.style.left = 0 + 'px';
	this.setSphereDegree(0);
    this.setSphereColor(0);
	flag_ANI --;
	if (flag_ANI == 0) f_shperesFail ();
}

function f_rollSphere (){
	flag_OK = false;
	for (ii = 1; ii <= XxX; ii ++){
		for (i = YyY; i >= 2; i --){
			iii = XxX * (i - 1) + ii - 1;
			if (a_block[iii].color == 0){
				if (ii > 1 && a_block[iii - XxX - 1].color != 0 && a_block[iii - XxX].color == 0){
					flag_OK = true;
					i_Nball = iii - XxX - 1;
					for (i_ = (i - 1); i_ >= 1; i_ --){
						i_ii = XxX * (i_ - 1) + ii - 1 - 1;
						if (a_block[i_ii].color != 0) i_Nball = i_ii;
						else break;
					}
					f_shpereShift (a_block[i_Nball], 'right');
					break;
				}
				if (ii < XxX && a_block[iii - XxX + 1].color != 0 && a_block[iii - XxX].color == 0){
					flag_OK = true;
					i_Nball = iii - XxX + 1;
					for (i_ = (i - 1); i_ >= 1; i_ --){
						i_ii = XxX * (i_ - 1) + ii - 1 + 1;
						if (a_block[i_ii].color != 0) i_Nball = i_ii;
						else break;
					}
					f_shpereShift (a_block[i_Nball], 'left');
					break;
				}
			}
		}
	}
	if (flag_OK == false)
		if (f_verifyGameOver ()) flag_PLAY = true;
		else f_endGame ();
}

function f_verifyGameOver () {
	return a_block.filter((elm) => {
		return elm.color > 0;
	}).some((elm) => {
		o_elm1 = elm;
		var i = Math.ceil((o_elm1.index+1) / XxX);
		var ii = o_elm1.index - (XxX*(i-1))+1;
		if (ii < XxX && a_block[o_elm1.index + 1].color != 0){
			o_elm2 = a_block[o_elm1.index + 1];
			if (o_elm1.color >= 8 || o_elm2.color >= 8 || f_verifyTurnAbility ()) return true;
		}
		if (i < YyY && a_block[o_elm1.index + XxX].color != 0){
			o_elm2 = a_block[o_elm1.index + XxX];
			if (o_elm1.color >= 8 || f_verifyTurnAbility ()) return true;
		}
	});
}

function f_newGame ()
{
	if (flag_ANI != 0){
		setTimeout(f_newGame, 200);
		return;
	}

	a_block.forEach((elm) => elm.setSphereColor(Math.ceil (Math.random ()*7)));

	while (f_verifySequences ()){
		a_block.filter((elm) => {
			return  elm.colTmp == 1;
		}).forEach((elm) => {
			elm.setSphereColor(Math.ceil (Math.random ()*7));
			elm.setSphereDegree(0);
		})
	}
	a_block.forEach((elm) => i_canvasKeymap += elm.color);

	flag_NEWSTART = false;
}

function f_oldGame()
{
	if (flag_ANI != 0){
		setTimeout(f_oldGame, 200);
		return;
	}

	a_block.forEach((elm) => {
		elm.setSphereColor(Number(i_canvasKeymap.substr (elm.index, 1)));
		elm.setSphereDegree(0);
	});

	flag_NEWSTART = false;
}
