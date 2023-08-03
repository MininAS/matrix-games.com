<?php
switch($_GET["lang"]){
    case "eng":
        ?>
		&nbsp&nbsp&nbsp&nbsp
		On the left is a game list whose variants were left by other players.
		Each of these games has own saved sub-game list, that is attempts list of the same field combination.
		Move mouse over and you will see if you can choose a game - it must not be started or have the best result by you, as the following markers will tell you:<br>
        <ul class = 'gameCheckbox key'><li class = 'openedGameCheckbox'></li></ul> - a mark points to your created game.<br>
		<ul class = 'gameCheckbox key'><li></li></ul> - games are which can be saved else.<br>
        <ul class = 'gameCheckbox key'><li class = 'wonGameCheckbox'></li></ul> - a mark points to your last saved result is best.<br>
        <ul class = 'gameCheckbox key'><li class = 'deletedGameCheckbox'></li></ul> - a mark points to game was removed. It can mean the message is outdated.<br>
		<ul class = 'gameCheckbox key'><li class = 'deletedGameCheckbox'></li><li class = 'deletedGameCheckbox'></li><li class = 'deletedGameCheckbox'></li></ul> - a scale displays life expectancy of game after a last best saved result. If last scale item is red so game will be removed soon.<br>
		Only five games can be saved (you should be registered).<br>
        If you saved at least one, you can compete with other players.
		&nbsp&nbsp&nbsp&nbsp
        What kind of competition is this - you choose someone else's game, and you are given the same layout of the field playing combination,
        which a player who opened this game layout.<br>
		<br>
        Game rules:<br>
		<br>
        <?php
        break;

    default:
    	?>
		&nbsp&nbsp&nbsp&nbsp
		С лева список с раскладками(слоями), варианты которых были оставлены другими игроками. Каждая из таких игр, имеет свой список сохраненных игр, что есть попытки одной и той же раскладки поля. Наведите курсор и вы увидите можете ли вы выбрать игру – она не должна быть начата вами или иметь лучший результат ваш, о чем вам подскажут следующие маркеры:<br>
		<ul class = 'gameCheckbox key'><li class = 'openedGameCheckbox'></li></ul> - укажет на количество, уже сохраненных вами игр.<br>
		<ul class = 'gameCheckbox key'><li></li></ul> - еще столько игр можно сохранить с нуля.<br>
        <ul class = 'gameCheckbox key'><li class = 'wonGameCheckbox'></li></ul> - ваш результат в этой игре лучший.<br>
        <ul class = 'gameCheckbox key'><li class = 'deletedGameCheckbox'></li></ul> - игра была удалена. Это может значить, что это сообщение просто устарело.<br>
		<ul class = 'gameCheckbox key'><li class = 'deletedGameCheckbox'></li><li class = 'deletedGameCheckbox'></li><li class = 'deletedGameCheckbox'></li></ul> - шкала отображает продолжительность жизни игры после последнего лучшего результата. Если последний элемент шкалы красный, игра скоро будет удалена.<br>
		Сохранить можно только пять игр (вы должны быть зарегистрированы), а сохранив хотя бы одну, вы можете соревноваться с другими игроками.<br>
		&nbsp&nbsp&nbsp&nbsp
		Что это за соревнования – вы выбираете чужую игру, и вам на поле предоставляется копия расклада игровой комбинации, которая была у игрока, который ее открыл. А смысл в том, что игра будет честной, и совершенно не зависеть от разного расклада фишек.<br>
		<br>
        Правила этой игры:
		<br>
		<?php
}

require("game_".$_GET["theme"].".php");
?>
