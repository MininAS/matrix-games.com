var matrix = [];
var yourSquare = {};
var compSquare = {};
var XxX = 30;
var YyY = 20;
var i_squares_of_comp = 0;

const arrColor = ["White", "Blue", "Red", "Magenta", "Lime", "Orange", "Yellow"]

document.getElementById('game').style.width = XxX * 24+"px";

function f_greateGame(){
	for (i = 0; i <= (YyY - 1); i ++){
		let row = [];
		for (ii = 0; ii <= (XxX - 1); ii ++){

			var p = document.createElement ('div');
			document.getElementById('game').appendChild(p);
			p.style.display = 'inline-block';
			p.style.width = '24px';
			p.style.height = '24px';

			var e = document.createElement ('div');
			p.appendChild(e);
			e.onclick = f_clickHandler;
			e.i_x = ii;
			e.i_y = i;
			e.color = 0;
			e.tmpCol = 0;

			e.style.position = 'relative';
			e.style.borderRadius = '5px';
			e.style.willChange = 'left, top, width, height';
			e.style.transform = 'rotate(45deg)';
			e.style.transition = 'top 1s, left 1s, width 1s, height 1s';

			e.setDefaultStyle = function(){
				this.style.top = '-2px';
				this.style.left = '-2px';
				this.style.width = '26px';
				this.style.height = '26px';
				this.style.border = 'solid 1px';
	            this.style.boxShadow = 'inset 0 0 4px';
			}
			e.setElmColor = function(color){
				this.color = color;
				this.style.background = arrColor[color];
			}
            row.push(e);
		}
		matrix.push(row);
	}
    compSquare = matrix[0][0];
	yourSquare = matrix[YyY - 1][XxX - 1];

	var e = document.createElement ('div');
	p.appendChild(e);
	e.style.position = 'relative';
	e.style.top = '-38px';
	e.style.left = '-10px';
    e.style.border = 'solid 2px';
	e.style.width = '40px';
	e.style.height = '40px';
	e.style.borderRadius = '5px';
	e.style.boxShadow = 'inset 0 0 4px';
}

function f_clickHandler(){
	if (flag_PLAY != true) return;
	i_motion ++;
    newColor = this.color;
    flag_PLAY = false;
    if ((yourSquare.color != newColor) && (compSquare.color != newColor)){
		yourSquare.tmpCol = 1;
	    f_paint(newColor, f_clickHandler_);
	}
	else f_clickHandler_();
}

function f_clickHandler_(){
	yourSquare.tmpCol = 1;
   	i_score = f_getSquaresAmount();
	f_clearTmpCol();
	document.getElementById ('myNballov').innerHTML = i_score;
	compSquare.tmpCol = 1;
	newColor = f_calculateMaxAmountColorAround();
	compSquare.tmpCol = 1;
	f_paint(newColor, f_clickHandler__)
}

function f_clickHandler__(){
    f_clearTmpCol();
	yourSquare.tmpCol = 1;
	newColor = f_calculateMaxAmountColorAround();
    if (newColor == 0 && i_score > 20) f_endGame();
    else flag_PLAY = true;
}

function f_paint(newColor, callback){
	let flag_END = true;
	matrix.forEach(e => {
		e.forEach(e => {
            if (e.tmpCol == 1){
				arr = [];
				if ((e.i_x + 1) < XxX) arr.push(matrix[e.i_y][e.i_x + 1]);
				if ((e.i_x - 1) >= 0)  arr.push(matrix[e.i_y][e.i_x - 1]);
				if ((e.i_y + 1) < YyY) arr.push(matrix[e.i_y + 1][e.i_x]);
			    if ((e.i_y - 1) >= 0)  arr.push(matrix[e.i_y - 1][e.i_x]);
			 	arr.forEach(e_ => {
				 	if (e_.tmpCol == 0 && e_.color == e.color){
						e_.tmpCol = 2;
						flag_END = false;
					}
			 	});
				e.tmpCol = 3;
				e.setElmColor(newColor);
			}
		});
	});

	matrix.forEach(e => {
		e.forEach(e => {
            if (e.tmpCol == 2){
                e.tmpCol = 1;
			}
		});
	});

	if (flag_END) {
		matrix.forEach(e => {
			e.forEach(e => {
				if (e.tmpCol == 3 &&
				   ((e.i_x + 1) < XxX) &&
				   (matrix[e.i_y][e.i_x + 1].color == newColor) &&
				   ((e.i_x - 1) >= 0) &&
				   (matrix[e.i_y][e.i_x - 1].color == newColor) &&
				   ((e.i_y + 1) < YyY) &&
				   (matrix[e.i_y + 1][e.i_x].color == newColor) &&
				   ((e.i_y - 1) >= 0) &&
				   (matrix[e.i_y - 1][e.i_x].color == newColor)
			    ){
					e.style.top = '-8px';
					e.style.left = '-8px';
					e.style.width = '40px';
					e.style.height = '40px';
					e.style.border = 'solid 0px';
					e.style.boxShadow = 'none';
					e.style.willChange = 'auto';
			    }
			    e.tmpCol = 0
			});
		});
		callback ();
	}
	else setTimeout(f_paint, 0, newColor, callback);
}

function f_clearTmpCol(){
	matrix.forEach(e => {
		e.forEach(e => {
			e.tmpCol = 0
		});
	});
}

function f_getSquaresAmount(){
	let amount = 0;
    let flag_END = false;
	while (flag_END == false){
		flag_END = true;
		matrix.forEach(e => {
			e.forEach(e => {
	            if (e.tmpCol == 1){
					arr = [];
					if ((e.i_x + 1) < XxX) arr.push(matrix[e.i_y][e.i_x + 1]);
					if ((e.i_x - 1) >= 0)  arr.push(matrix[e.i_y][e.i_x - 1]);
					if ((e.i_y + 1) < YyY) arr.push(matrix[e.i_y + 1][e.i_x]);
				    if ((e.i_y - 1) >= 0)  arr.push(matrix[e.i_y - 1][e.i_x]);
				 	arr.forEach(e_ => {
					 	if (e_.tmpCol == 0 && e_.color == e.color){
							e_.tmpCol = 1;
							flag_END = false;
						}
				 	});
					e.tmpCol = 2; amount ++;
				}
			});
		});
	}
	return amount;
}

function f_calculateMaxAmountColorAround (){
	let colorsAmount = [0, 0, 0, 0, 0, 0, 0];
	f_getSquaresAmount();
	matrix.forEach(e => {
		e.forEach(e => {
			if (e.tmpCol == 2) e.tmpCol = 4;
		});
	});
	for (i = 1; i <= 6; i ++){
		if (compSquare.color == i ||
			yourSquare.color == i)
		    continue;
		matrix.forEach(e => {
			e.forEach(e => {
		           if (e.tmpCol == 4){
					let arr = [];
					if ((e.i_x + 1) < XxX) arr.push(matrix[e.i_y][e.i_x + 1]);
					if ((e.i_x - 1) >= 0)  arr.push(matrix[e.i_y][e.i_x - 1]);
					if ((e.i_y + 1) < YyY) arr.push(matrix[e.i_y + 1][e.i_x]);
				    if ((e.i_y - 1) >= 0)  arr.push(matrix[e.i_y - 1][e.i_x]);
				 	arr.forEach(e => {
					 	if (e.tmpCol == 0 && e.color == i){
							e.tmpCol = 1;
						}
				 	});
				}
			});
		});
		colorsAmount[i] = f_getSquaresAmount();
		matrix.forEach(e => {
			e.forEach(e => {
				if (e.tmpCol != 4) e.tmpCol = 0;
			});
		});
	}
	f_clearTmpCol();
	return colorsAmount.indexOf(
		Math.max.apply(null, colorsAmount)
	);
}

function f_newGame (){
	matrix.forEach(e => {
		 e.forEach(e => {
			color = Math.ceil (Math.random ()*6);
			e.setElmColor(color);
			i_canvasKeymap += color;
			e.setDefaultStyle();
		})
	})
	f_clearTmpCol();
}

function f_oldGame()
{
	for (i = 0; i <= (XxX - 1); i ++){
		for (ii = 0; ii <= (YyY - 1); ii++){
			e = matrix[ii][i];
			qq = i * 20 + ii;
			str = i_canvasKeymap.substr (qq, 1);
			e.setElmColor(str);
			e.setDefaultStyle();
		}
	}
}
