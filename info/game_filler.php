<?php
switch($_GET["lang"]){
    case "eng":
        ?>
		&nbsp&nbsp&nbsp&nbsp
        The game goal is to grab the whole field, changing the colors of your cells.
        Your first cell is a square placed in the lower right corner.
        The bot will prevent you to grab as many cells as possible you. It is placed the upper left corner.
        To grab a neighboring cell, you need to choose the same color.
        You can choose the color using a mouse or touch on the field use some cell, some color square. <br>
        If you choose your opponent’s color or yours then you will lose your a game move.
        <?php
        break;

    default:
    	?>
		&nbsp&nbsp&nbsp&nbsp
        Цель игры – захватить все поле, меняя цвета ваших клеток.
        Ваша первая клетка - это квадрат, расположенный в правом нижнем углу.
        С левого верхнего угла вам будет мешать бот.
        Что бы захватить соседнюю клетку вам необходимо выбрать такой же цвет.
        Цвет вы можете выбрать, используя указатель мыши на поле bbk касанием по экрану,
		любая клетка, любой цветной квадрат.<br>
        &nbsp&nbsp&nbsp&nbsp
        Если вы выберите цвет противника или свой тогда вы потеряете ход.
		<?php
}
?>
