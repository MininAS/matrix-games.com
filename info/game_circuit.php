<?php
switch($_GET["lang"]){
    case "eng":
        ?>
		&nbsp&nbsp&nbsp&nbsp
        Two poles are placed below of the right and left corners -
        do a short circuit between them by turning and connecting them in a serial chain.
        Closed conductors will burn and bring you points.
        You can get even more points by connecting the lamps around the edges.<br>
        &nbsp&nbsp&nbsp&nbsp
        After the first short circuit, you will still have conductors that fall down,
        you can moving them by mouse dragging in the direction you need,
        if the cell is not busy in this direction.
        <?
        break;

    default:
    	?>
		&nbsp&nbsp&nbsp&nbsp
        Внизу по углам находятся два полюса - устройте между ними короткое замыкание,
        поворачивая и соединяя их в последовательную цепочку.
        Замкнутые проводники, будут сгорать и приносить вам очки. Еще больше очков можно получить,
        подключая лампочки по краям.<br>
        &nbsp&nbsp&nbsp&nbsp
        После первого замыкании у вас останутся проводники которые упадут вниз,
        вы можете двигать их перетаскивая мышью в нужном вам направлении,
        если в этом направлении клетка не занята.
		<?
}
?>
