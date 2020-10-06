<?php
switch($_GET["lang"]){
    case "eng":
        ?>
		&nbsp&nbsp&nbsp&nbsp
        The rules of this game are widely known: mines are scattered across the field in closed cells,
        you should go through it placing flags there where mines should be and open cells where they are not.<br>
        Control:<br>
        &nbsp&nbsp- the left mouse button (one touch) opens a cell if you are sure that there is no a mine;<br>
        &nbsp&nbsp- the right mouse button (two touches - one on somewhere at field, the second on a cell)
                    sets a flag where in your opinion is there should be a mine;<br>
        &nbsp&nbsp- the central mouse button or the left one with the right one pressed
                    (three touches - the third in a cell) can be used on a cell with number around which
                    flags are already placed.<br>
        In any case, always follow cells with numbers, they display a mines amount are located around.
        <?
        break;

    default:
    	?>
		&nbsp&nbsp&nbsp&nbsp
        Правила этой игры широко известны: в закрытых клетках по полю разбросаны мины,
        вы должны пройти по нему расставляя флаги в места, где мины должны быть и открывать клетки где их нет.<br>
        Управление:<br>
        &nbsp&nbsp- левой клавишей мыши (одно касание) вы открываете клетку если уверены, что там нет мины;<br>
        &nbsp&nbsp- правой клавишей (в два касания - одно на поле, второе на клетке)
                    вы ставите флаг, где по вашему мнению должна быть мина;<br>
        &nbsp&nbsp- центральную клавиша или левую при нажатой правой
                    (в три касания - третье по клетке) можно использовать на клетке с цифрами,
                    вокруг которой уже расставлены флаги.<br>
        В любом случае всегда ориентируйтесь по клеткам с цифрами, именно они указывают на количество мин расположенных вокруг.
		<?
}
?>
