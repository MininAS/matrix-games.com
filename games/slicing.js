const matrix = [];
const XxX = 20;
const YyY = 15;
const square_size = 32;
var Nsquare = 0;
var flag_ANI = 0;
var i_canvasLayoutRevert = "";
var i_scoreRevert = 0;

var selectedSquareAmount = 0;
var selectedSquareAmountBox = document.createElement('p');

const arrColor = ["White", "Blue", "Red", "Magenta", "Lime", "Yellow"];

f_showElementById('k_revert');
f_showElementById('k_sound');

function f_greateGame() {
	document.oncontextmenu = new Function("return false;");
	e_gameElementsContainer.style.width = XxX * square_size + "px";
	e_gameElementsContainer.onmousedown = f_catchRightButton;

	for (i = 0; i <= (YyY - 1); i++) {
		let row = [];
		for (ii = 0; ii <= (XxX - 1); ii++) {

			var p = document.createElement('div');
			e_gameElementsContainer.appendChild(p);
			p.style.display = 'inline-block';
			p.style.width = square_size + 'px';
			p.style.height = square_size + 'px';

			var e = document.createElement('div');
			p.appendChild(e);
			e.onmouseover = f_moveSquareOver;
			e.onmouseout = f_moveSquareOut;
			e.onmousemove = f_moveSquare;
			e.onclick = f_slice;
			e.i_x = ii;
			e.i_y = i;
			e.color = 0;
			e.tmpCol = 0;
			e.style.zIndex = (YyY - i) * (XxX - ii);

			e.style.position = 'relative';
			e.style.borderRadius = '5px';
			e.style.border = 'solid 1px';
			e.style.willChange = 'left, top, width, height';

			e.setDefaultStyle = function () {
				this.style.boxShadow = 'inset -2px -2px 4px';
				this.style.top = 0 + 'px';
				this.style.left = 0 + 'px';
				this.style.width = (square_size - 3) + 'px';
				this.style.height = (square_size - 3) + 'px';
			}
			e.setSelectedStyle = function () {
				this.style.boxShadow = 'inset 2px 2px 4px';
			}
			e.setElmColor = function (color) {
				this.color = color;
				this.style.background = arrColor[color];
			}
			e.setElmColor(0);
			e.setDefaultStyle();
			row.push(e);
		}
		matrix.push(row);
	}
	document.getElementById('body').appendChild(selectedSquareAmountBox);
	selectedSquareAmountBox.innerHTML = 0;
	selectedSquareAmountBox.className = 'border_inset toolTip';
	selectedSquareAmountBox.style.display = 'none';
}

function f_catchRightButton(e) {
	if (e.button != 2 || !flag_PLAY) return;
	e.stopPropagation();
	e.preventDefault();
	f_revertLastMotion();
	f_moveSquareOut();
	f_moveSquareOver(e);
	return false;
}

function f_moveSquareOver(e) {
	let elm = e.target;
	if (!flag_PLAY) return;
	if (elm.color == 0) return;
	f_clearTmpCol();
	elm.tmpCol = 1;
	selectedSquareAmount = f_getSquaresAmount();
	if (selectedSquareAmount < 2) return;
	matrix.forEach(e => {
		e.forEach(e => {
			if (e.tmpCol == 2) {
				e.setSelectedStyle();
			}
		});
	});

	let score = 0;
	for (i = 1; i <= selectedSquareAmount; i++) { score += i; }
	selectedSquareAmountBox.innerHTML = score;
	selectedSquareAmountBox.style.display = 'block';
}

function f_moveSquareOut() {
	if (!flag_PLAY) return;
	matrix.forEach(e => {
		e.forEach(e => {
			if (e.tmpCol == 2) {
				e.setDefaultStyle();
			}
		});
	});
	selectedSquareAmount = 0;
	selectedSquareAmountBox.style.display = 'none';
}

function f_moveSquare(evnt) {
	selectedSquareAmountBox.style.top = (evnt.pageY || evnt.clientY) - 20 + 'px';
	selectedSquareAmountBox.style.left = (evnt.pageX || evnt.clientX) + 30 + 'px';
}

function f_getSquaresAmount() {
	let amount = 0;
	let flag_END = false;
	while (flag_END == false) {
		flag_END = true;
		matrix.forEach(e => {
			e.forEach(e => {
				if (e.tmpCol == 1) {
					f_getNeighbourSquares(e).forEach(e_ => {
						if (e_.tmpCol == 0 && e_.color == e.color) {
							e_.tmpCol = 1;
							flag_END = false;
						}
					});
					e.tmpCol = 2; amount++;
				}
			});
		});
	}
	return amount;
}

function f_getNeighbourSquares(e) {
	const arr = [];
	if ((e.i_x + 1) < XxX) arr.push(matrix[e.i_y][e.i_x + 1]);
	if ((e.i_x - 1) >= 0) arr.push(matrix[e.i_y][e.i_x - 1]);
	if ((e.i_y + 1) < YyY) arr.push(matrix[e.i_y + 1][e.i_x]);
	if ((e.i_y - 1) >= 0) arr.push(matrix[e.i_y - 1][e.i_x]);
	return arr;
}

function f_slice(e) {
	if (!flag_PLAY) return;
	if (selectedSquareAmount < 2) return;
	flag_PLAY = false;

	i_canvasLayoutRevert = "";
	matrix.forEach(e => {
		e.forEach(e => {
			i_canvasLayoutRevert += e.color;
			i_scoreRevert = i_score;
		});
	});

	selectedSquareAmountBox.style.display = 'none';
	let score = 0;
	for (i = 1; i <= selectedSquareAmount; i++)
		score += i;
	i_score = i_score + score;
	e_scoreViewer.innerHTML = i_score;
	f_scrollScore(e.target, score);
	i_motion++;
	matrix.forEach(e => {
		e.forEach(e => {
			if (e.tmpCol == 2) {
				f_startHiddingAnimation(e);
			}
		});
	});
}

function f_revertLastMotion() {
	if (flag_GAMEOVER) return;
	if (i_canvasLayoutRevert == "") return;
	let index = 0;
	e_scoreViewer.innerHTML = i_score = i_scoreRevert;
	matrix.forEach(e => {
		e.forEach(e => {
			e.setElmColor(i_canvasLayoutRevert.substr(index, 1));
			index++;
		});
	});
}

function f_startHiddingAnimation(elm) {
	flag_ANI++;
	elm.style.transition = 'top .1s, left .1s, width .1s, height .1s';
	elm.addEventListener('transitionend', f_stopHiddingAnimation);
	elm.style.top = (square_size / 2) + 'px';
	elm.style.left = (square_size / 2) + 'px';
	elm.style.width = 0 + 'px';
	elm.style.height = 0 + 'px';
}

function f_stopHiddingAnimation() {
	this.removeEventListener('transitionend', f_stopHiddingAnimation);
	flag_ANI--;
	this.style.transition = 'none';
	this.setDefaultStyle();
	this.setElmColor(0);
	if (flag_ANI == 0) f_analizeSquaresMoving();
};

function f_analizeSquaresMoving() {
	let flag_END = true;
	f_clearTmpCol();
	selectedSquareAmount = 0;
	for (y = (YyY - 1); y >= 1; y--) {
		for (x = 0; x <= (XxX - 1); x++) {
			if (matrix[y][x].color == 0 && matrix[y - 1][x].color != 0) {
				f_startSquaresMoving(matrix[y - 1][x]);
				flag_END = false;
			}
		}
	}
	if (flag_END)
		f_analizeEmptyColumn();
}

function f_startSquaresMoving(elm) {
	flag_ANI++;
	elm.style.transition = 'top .1s';
	elm.addEventListener('transitionend', f_stopSquaresMoving);
	elm.style.top = square_size + 'px';
}

function f_stopSquaresMoving() {
	f_playSound('slicing_impact');
	flag_ANI--;
	this.removeEventListener('transitionend', f_stopSquaresMoving);
	this.style.transition = 'none';
	this.style.top = 0 + 'px';
	matrix[this.i_y + 1][this.i_x].setElmColor(this.color);
	this.setElmColor(0);
	if (flag_ANI == 0) f_analizeSquaresMoving();
}

function f_analizeEmptyColumn() {
	let flag_END = true;
	for (x = 0; x <= (XxX - 2); x++) {
		if (matrix[YyY - 1][x].color != 0 && matrix[YyY - 1][x + 1].color == 0) {
			f_startColumnMoving(x);
			flag_END = false;
		}
	}
	if (flag_END) f_finish();
}

function f_startColumnMoving(x) {
	for (y = (YyY - 1); y >= 0; y--) {
		elm = matrix[y][x];
		if (elm.color == 0) continue;
		flag_ANI++;
		elm.style.transition = 'left .1s';
		elm.addEventListener('transitionend', f_stopColumnMoving);
		elm.style.left = square_size + 'px';
	}
}

function f_stopColumnMoving() {
	f_playSound('slicing_column_moving');
	flag_ANI--;
	this.removeEventListener('transitionend', f_stopColumnMoving);
	this.style.transition = 'none';
	this.style.left = 0 + 'px';
	matrix[this.i_y][this.i_x + 1].setElmColor(this.color);
	this.setElmColor(0);
	if (flag_ANI == 0) f_analizeEmptyColumn();
}

function f_finish() {
	for (y = (YyY - 1); y >= 1; y--) {
		for (x = 0; x <= (XxX - 1); x++) {
			if (matrix[y][x].color == 0) continue;
			if (f_getNeighbourSquares(matrix[y][x]).some(e => {
				return matrix[y][x].color == e.color
			})
			) {
				flag_PLAY = true;
				elm = document.querySelector('#game div div:hover');
				evnt = new MouseEvent('mouseover');
				if (elm) elm.dispatchEvent(evnt);
				return;
			}
		}
	}
	f_endGame();
}

function f_clearTmpCol() {
	matrix.forEach(e => {
		e.forEach(e => {
			e.tmpCol = 0
		});
	});
}

function f_newGame() {
	matrix.forEach(e => {
		e.forEach(e => {
			color = Math.ceil(Math.random() * 5);
			e.setElmColor(color);
			i_canvasKeymap += color;
			e.setDefaultStyle();
		})
	})
	f_clearTmpCol();
}

function f_oldGame() {
	let i = 0;
	matrix.forEach(e => {
		e.forEach(e => {
			str = i_canvasKeymap.substr(i, 1);
			e.setElmColor(str);
			e.setDefaultStyle();
			i++;
		})
	})
}