<?php
switch($_GET["lang"]){
    case "eng":
        ?>
		&nbsp&nbsp&nbsp&nbsp
		To many who know the name of this game, is understanding what need to do.
		You should rotat and move to the sides falling figures of various shapes,
		must be folded in such a way that the horizontal rows are completely filled.
		A completely filled row is destroyed. <br>
        &nbsp&nbsp&nbsp&nbsp
        More points are gained if you destroy more than one row at once and if your removed rows are higher (a risk of losing is higher also).
        Control is by mouse or arrow keys and WASD.
        <?
        break;

    default:
    	?>
		&nbsp&nbsp&nbsp&nbsp
        Многим, кому известно название этой игры, суть ее ясна сразу. Падающие фигурки разной формы, 
        вращая их и двигая в стороны, нужно складывать таким образом, что бы горизонтальные 
        ряды заполнялись полностью. Полностью заполненный ряд уничтожается.<br>
        &nbsp&nbsp&nbsp&nbsp
        Большее количество баллов, набирается, если вы уничтожаете сразу больше чем один ряд
        и если выше ваши удаляемые ряды (риск проиграть - выше).
        Управление осуществляется мышью или клавишами стрелок и WASD.
		<?
}
?>