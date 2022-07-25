<?php
switch($_GET["lang"]){
    case "eng":
        ?>
		&nbsp&nbsp&nbsp&nbsp
        You move numbers blocks impact them together by a mouse or touch
        or an arrow keys and WASD keys at a keyboard:

        <table id ='instruct'>
        <tr>
            <td><img src ='img/number_info_1.png'/></td>
            <td>
                &nbsp&nbsp&nbsp&nbsp
                If located nearby blocks contain equal numbers, then these blocks add up.<br>
                Points: 4 + 4 = 8
            </td>
        </tr>
        <tr>
            <td><img src ='img/number_info_2.png'/></td>
            <td>
                &nbsp&nbsp&nbsp&nbsp
                A multiplier appears in the upper right corner of the block after addition.
                Block multipliers multiply points.<br>
                Points: ( 32 * 4 ) + ( 32 * 5 ) = 288
            </td>
        </tr>
        <tr>
            <td><img src ='img/number_info_3.png'/></td>
            <td>
                &nbsp&nbsp&nbsp&nbsp
                The multiplier exists for only five moves. It fades with each move.
            </td>
        </tr>
        <tr>
            <td><img src ='img/number_info_4.png'/></td>
            <td>
                &nbsp&nbsp&nbsp&nbsp
                Two dark blocks at two angles are created so that you can mix the blocks between itself.
            </td>
        </tr>
        </table>
        <?php
        break;

    default:
    	?>
		&nbsp&nbsp&nbsp&nbsp
		Управляя мышью, касанием на экране или с помощью клавиш стрелок и WASD на клавиатуре, в четырех направлениях,
        вы сдвигаете блоки чисел, сталкивая их между собой:

        <table id ='instruct'>
        <tr>
            <td><img src ='img/number_info_1.png'/></td>
            <td>
                &nbsp&nbsp&nbsp&nbsp
                Если блоки расположенные рядом, носят числа одинаковой величины,
                то эти блоки складываются.<br>
                Очки: 4 + 4 = 8
            </td>
        </tr>
        <tr>
            <td><img src ='img/number_info_2.png'/></td>
            <td>
                &nbsp&nbsp&nbsp&nbsp
                В правом верхнем углу блока есть множитель, который появляется после сложения.
                Множители складываемых блоков умножают очьки.<br>
                Очки: ( 32 * 4 ) + ( 32 * 5 ) = 288
            </td>
        </tr>
        <tr>
            <td><img src ='img/number_info_3.png'/></td>
            <td>
                &nbsp&nbsp&nbsp&nbsp
                Множитель существует всего пять ходов и с каждым ходом он тускнеет,
                а затем и вовсе пропадет, если вы его не сложите с другим числом.
            </td>
        </tr>
        <tr>
            <td><img src ='img/number_info_4.png'/></td>
            <td>
                &nbsp&nbsp&nbsp&nbsp
                Два темных блока по двум углам создаются для того,
                что бы вы могли перемешать блоки между собой.
            </td>
        </tr>
        </table>
		<?php
}
?>
