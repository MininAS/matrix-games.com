<?php
switch($_GET["lang"]){
    case "eng":
    	?>
		&nbsp&nbsp&nbsp&nbsp
        Select two balls located near. These should be changed between itself.
		This will be possible if this movement result allowes to get ball row or column with the same color,
		in order more amount than two. These lines will be removed with adding a point for per ball.
		Each subsequent automatic removal of balls gives a multiple increase of points for each ball.
        <table id ='instruct'>
            <tr>
                <td><img src ='img/sphere_info_1.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    Three balls in a horizontal or vertical row disappear,
					the upper balls fall down, and if at the same time rows of three or more balls are again combined,
					they are removed, and the points are doubled.
					Then, with the next atomatic match, they triple, and so on.<br>
                </td>
            </tr>
            <tr>
                <td><img src ='img/sphere_info_3.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    Four balls combining gives a super sphere -
					its further direction removes an entire row or column.
					Motion count increases at two.
                </td>
				<td><img src ='img/ball_8.gif'/></td>
            </tr>
            <tr>
                <td><img src ='img/sphere_info_2.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    Another super sphere is given by simultaneously combining two rows.
					Direct it at an adjacent sphere, you will destroy all spheres on the field of this color.
					Motion count increases at four.
                </td>
				<td><img src ='img/ball_9.gif'/></td>
            </tr>
            <tr>
                <td><img src ='img/sphere_info_6.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    When five spheres are combined, a faceted colored glass is appeared.
					Direct it to a sphere which you want to paint in the glass color.
					Motion count increases at ten.
                </td>
				<td><img src ='img/ball_11.gif'/></td>
            </tr>
        </table>
		Control:<br>
        &nbsp&nbsp - a click (a touch) selects some sphere;<br>
        &nbsp&nbsp - another click (another touch) selects another sphere or a new sphere
        - if the moving is not possible.<br><br>
        &nbsp&nbsp&nbsp&nbsp
        Source of gif animated images is site http://preloaders.net
		<?php
        break;

    default:
    	?>
		&nbsp&nbsp&nbsp&nbsp
        Выберите один шар, а затем шар рядом, с которым необходимо поменять их местами.
        Это будет возможно, если в результате этого движения будут совмещены ряды шаров
        с одинаковым цветом, в количестве более двух.
        Эти ряды будут удалены, добавляя по одному очку за шар.
        Каждое следующее автоматическое удаление шаров дает кратное увеличения очков за каждый шар.
        <table id ='instruct'>
            <tr>
                <td><img src ='img/sphere_info_1.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    Три шара в горизонтальный или вертикальный ряд исчезают,
                    верхние шары падают вниз и если при этом опять совмещаются ряды по три и более шаров,
                    то они удаляются, а очки удваиваются.
					Затем со следующим атоматическим совпадением они утраиваются и т.д.<br>
                </td>
            </tr>
            <tr>
                <td><img src ='img/sphere_info_3.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    При совмещении четырех шаров дается суперсфера.
                    Ее дальнейшее направление удаляет целиком ряд или столбец.
                    Количество ходов удваиваются.
                </td>
				<td><img src ='img/ball_8.gif'/></td>
            </tr>
            <tr>
                <td><img src ='img/sphere_info_2.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    Другая суперсфера дается при совмещении одновременно двух рядов.
                    Направив ее на соседнюю сферу вы уничтожите все сферы на поле этого цвета.
                    Количество ходов становиться на четверо больше.
                </td>
				<td><img src ='img/ball_9.gif'/></td>
            </tr>
            <tr>
                <td><img src ='img/sphere_info_6.png'/></td>
                <td>
                    &nbsp&nbsp&nbsp&nbsp
                    При совмещении пяти сфер образуется граненая цветная стекляшка,
                    ее наведите на ту сферу цвета которых вы желаете окрасить в цвет стекляшки.
                    Количество ходов становиться на десять больше.
                </td>
				<td><img src ='img/ball_11.gif'/></td>
            </tr>
        </table>
		Управление:<br>
        &nbsp&nbsp - один клик (одно касание) выбирает первую сферу;<br>
        &nbsp&nbsp - следующий клик (следующие касание) выбирает следующую сферу или же новую
		- если их сдвиг не возможен.<br><br>
        &nbsp&nbsp&nbsp&nbsp
        Изображения анимированных GIF-картинок взяты с сайта http://preloaders.net
		<?php
}
?>
