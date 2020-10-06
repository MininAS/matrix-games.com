<?php
switch($_GET["lang"]){
    case "eng":
        ?>
		&nbsp&nbsp&nbsp&nbsp
        Hover mouse over the squares so you can see that they get focus
        if several them located near have the same color.
        A mouse click will remove the squares from the field.
        A points sum is equal an  arithmetic progression of the number of squares removed simultaneously.
        You can see the amount in the tooltip if you hover over them.
        <?
        break;

    default:
    	?>
		&nbsp&nbsp&nbsp&nbsp
        Наведите мышь на квадраты, и вы увидите, что они получают фокус,
        если их несколько рядом одного цвета. Клик мышью удалит квадраты с поля.
        Сумма очков, равна арифметической прогрессии от количества одновременно удаляемых квадратов.
        Сумму вы можете найти в подсказке, если наведете курсор на них.
		<?
}
?>
