<?php
switch($_GET["lang"]){
    case "eng":
        ?>
        &nbsp&nbsp&nbsp&nbsp
        Clicking to barrel rolls increases them to a certain size, until they bursted.
        Bursted barrel roll is scatteing into different directions and increasing the size of neighboring barrel rolls.
        With each bursted barrel roll you get points opposite to their size.
        For each direct click on the barrel roll - you lose one point.
        You should be trying to burst barrel rolls with the help of neighboring ones. <br>
        &nbsp&nbsp&nbsp&nbsp
        A barrel roll with a light background gives a large growth of points if you never touch it.
        And also it affects all barrels vertically and horizontally increasing their weight by one. <br>
        <table id = 'instruct'>
            <tr>
                <td><img src = 'img/barrel_info_2.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    A blue, smallest barrel roll after a burst brings 11 points,
                    green = 9, yellow = 7, orange = 5, red = 3, purple = 1.
                    Each blue is a super barrel roll. If you click to it, it will become normal.
                </td>
            </tr>
            <tr>
                <td><img src = 'img/barrel_info_3.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    Points: -1 when you click on a barrel, its points are reduced at one.
                </td>
            </tr>
            <tr>
                <td><img src = 'img/barrel_info_4.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    Fragments from the bursted barrel roll disappear in the first barrel, increasing its size at one.
                </td>
            </tr>
            <tr>
                <td><img src = 'img/barrel_info_5.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    Fragments from the bursted super barrel fly over the entire game field, increasing size of all met barrels at two. <br>
            </tr>
        </table>
        <?php
        break;

    default:
        ?>
        &nbsp&nbsp&nbsp&nbsp
        Кликайте на бочки, увеличивая их до определенного момента, пока они не взорвутся.
        Разлетаясь на четыре части и увеличивая вес соседних бочек.
        С каждой взорванной бочки вы получаете баллы обратно их весу.
        За каждое нажатие на бочку - та теряет один балл.
        Вы должны стараться взрывать бочки с помощью соседних.<br>
        &nbsp&nbsp&nbsp&nbsp
        Бочка со светлым фоном дает большой прирост очков если вы ее ни разу ни троните.
        А также она влияет на все бочки по вертикали и горизонтали увеличивая их вес на единицу.<br>
        <table id ='instruct'>
            <tr>
                <td><img src ='img/barrel_info_2.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    Синяя, самая маленькая бочка при уничтожении приносит 11 очков,
                    зеленая = 9, желтая = 7, оранжевая = 5, красная = 3, фиолетовая = 1.
                    Каждая синяя является супербочкой. Если нажать на СУПЕРбочку мышью она станет обычной.
                </td>
            </tr>
            <tr>
                <td><img src ='img/barrel_info_3.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    При нажатии на бочку, ее баллы уменьшаются по одному за нажатие.
                </td>
            </tr>
            <tr>
                <td><img src ='img/barrel_info_4.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    Осколки от взорвавшейся бочки, пропадают в первой встречной соседней, увеличивая ее вес.
                </td>
            </tr>
            <tr>
                <td><img src ='img/barrel_info_5.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    Осколки от взорвавшейся супер бочки, пролетают все поле, увеличивая вес всех встреченных бочек на 2.<br>
                </td>
            </tr>
        </table>
        <?php
    }
?>
