 <?php
 switch($_GET["lang"]){
    case "eng":
        ?>
 		&nbsp&nbsp&nbsp&nbsp
        You knock out a group of different colors stones from above by stone from the bottom of the screen.
        Stones colors should be the same. If the group consists of 2 or more stones,
        then they crumble and disappear from the field.
        <table id = 'instruct'>
            <tr>
                <td><img src = 'img/bouncer_info_1.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    Stones of other colors can be cut off from the main massif.
                    It brings points equal to the arithmetic progression of amount of fallen off stones.
                </td>
            </tr>
        </table>
        <br>
        &nbsp&nbsp&nbsp&nbsp
        When you try to knock another color, a stone joins to a massif, and the massif will add one row in weight.
        <?
        break;

    default:
        ?>
 		&nbsp&nbsp&nbsp&nbsp
        Разного цвета камнями с нижней части экрана вы выбиваете группу камней сверху, того же цвета,
        если группа состоит из 2-х и более камней то они крошатся и исчезают с поля.
        <table id = 'instruct'>
            <tr>
                <td><img src = 'img/bouncer_info_1.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    Оторванные от основного массива камни другого цвета
                    приносят баллы равные арифметической прогрессии от количества отвалившихся камней.
                </td>
            </tr>
        </table>
        <br>
        &nbsp&nbsp&nbsp&nbsp
        При ударе в другой цвет, камень выброшенный вами присоединяется к верхним камням,
        а весь каменный массив прибавит в весе на один ряд.
        <?
 }
 ?>
