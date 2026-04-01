<?php
switch($_GET["lang"]){
    case "eng":
        ?>
		&nbsp&nbsp&nbsp&nbsp
        Two poles are placed of the right and left corners -
        do a short circuit between them by turning and connecting them in a serial chain.
        You can get even more points by connecting the lamps.<br>
        <?php
        break;

    default:
    	?>
		&nbsp&nbsp&nbsp&nbsp
        По углам находятся два полюса - устройте между ними короткое замыкание,
        поворачивая и соединяя их в последовательную цепочку.
        Еще больше очков можно получить, подключая лампочки.<br>
		<?php
}
?>
