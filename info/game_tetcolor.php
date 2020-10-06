<?php
switch($_GET["lang"]){
    case "eng":
        ?>
		&nbsp&nbsp&nbsp&nbsp
		As well as about Tetris, there is no need to say much about this game.
		You should rotat and move to the sides falling figures of various of different colors,
		which must be folded on the bottom of the glass.
        If there are more than two of the same color in a horizontal,
		vertical or diagonal position, the cubes disappear, bringing points.
		The more difficult the combination, the more points. <br>
        &nbsp&nbsp&nbsp&nbsp
        Control is by mouse or arrow keys and WASD.
        <?
        break;

    default:
    	?>
		&nbsp&nbsp&nbsp&nbsp
        Так же как и про Тетрис про эту игру многого говорить не надо. Падающие блоки из трех квадратов 
        разного цвета, вращая их и двигая в стороны, нужно складывать на низ стакана. 
        При совпадении  одинакового цвета в горизонтальном, вертикальном или диагональном положении, в количестве более двух, 
        кубики исчезают, принося баллы. Чем сложнее комбинация, тем больше баллов.<br>
        &nbsp&nbsp&nbsp&nbsp
		Управление осуществляется мышью или клавишами стрелок и WASD.
		<?
}
?>