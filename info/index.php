<?php
switch($_GET["lang"]){
    case "eng":
        ?>
		&nbsp&nbsp&nbsp&nbsp
		Menu: <br>
		- <img src = 'img/k_home.png' alt='Home'/> - the key appears only when you are outside the home page to return to it. <br>
        -  the widget of social network VKontakte and if you liked this site, be sure to click on it
        and don't forget to tell your friends! Of course, if you have a corresponding account.<br>
		- <img src = 'img/k_book.png' alt='Note book'/> - something like a small forum for discussions, comments and suggestions on the site.<br>
		- <img src = 'img/k_profile.png' alt='Your profile'/> - your personal page, here you can read and send messages to other users,
        change your data and monitor your progress. With this key you can sometimes see an letter <img src = 'img/letter.png' alt = 'Letter' />
        - this means that you have one or more unread messages. <br>
		- <img src = 'img/k_stat.png' alt='Statistic'/> - statistics depending on where you are,
        if you are on your profile page - then the information will be on your achievements,
        if in the game, then according to the results of records and medals collected for the whole time.<br>
		- <img src = 'img/k_pause.png' alt='Pause'/> - necessary in some games if you decide to leave game for a short time.<br>
		- <img src = 'img/k_new_game.png' alt='New game'/> - in play mode to start a new play combination.<br>
		- <img src = 'img/k_revert.png' alt='New game'/> - in play mode to revert one step back.<br>
		- <img src = 'img/k_save.png' alt='Save game'/> - in some games for manual saving (for example \"Shot circuit\").<br>
		- <img src = 'img/k_sound_on.png' alt='Sound'/> - turn on and off the sound.<br>
		- <img src = 'img/k_lang_rus.png' alt='Lang'/> - language change - Russian / English.<br>
		&nbsp&nbsp&nbsp&nbsp
		A TOP list of players in the page left window there are awarded points for well-played matches.
        The points are dynamic for both cases - grow in the process of visiting the site, and decreas - if the player visits less often.
        More detailed statistics can be viewed by clicking on a list user item.<br>
		&nbsp&nbsp&nbsp&nbsp
        Right side - blocks of games with attached tournament tables of prize places according currently,
        you can be here if you play better.
        Therefore, the table with medals is not permanent and will always change if someone played more better or game was removed.
        Records are already immortalized game results. You can be here also but it will be more difficult.<br>
		&nbsp&nbsp&nbsp&nbsp
		To select a game, you need to click on a game block by a picture or a  text, you will go to a zero game - this is a new layout of a field.
        Only five games can be saved (you must be registered).<br>
        <ul class = 'gameCheckbox key openedGameCheckbox'><li></li></ul> - a mark points to your created game.<br>
        <ul class = 'gameCheckbox key wonGameCheckbox'><li></li></ul> - a mark points to your last saved result is best.<br>
        <ul class = 'gameCheckbox key deletedGameCheckbox'><li></li></ul> - a mark points to game was removed. It can mean the message is outdated.<br>
        If you saved at least one, you can compete with other players.
        To compete, you need to choose a game from the tournament table or select it directly in the game
        in the "Layout Combinations" list on the page right side, where an fully list of games will be presented.<br>
		&nbsp&nbsp&nbsp&nbsp
        What kind of competition is this - you choose someone else's game, and you are given the same layout of the field playing combination,
        which a player who opened this game layout.<br><br>
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
		Let a game be fair.
        <?
        break;

    default:
    	?>
		&nbsp&nbsp&nbsp&nbsp
		Меню сайта: <br>
		- <img src = 'img/k_home.png' alt='На главную'/> - клавиша появляется только когда вы вне главной страницы для возврата к таковой. <br>
		- после клавиши \"Главная страница\" следует клавиша виджета \"ВКонтакте\" и если вам понравился этот сайт, обязательно на нее нажмите
		и не забудьте рассказать друзьям! <br>
		- <img src = 'img/k_book.png' alt='Книга пожеланий'/> - что - то вроде маленького форума для обсуждений, замечаний и просьб по сайту. <br>
		- <img src = 'img/k_profile.png' alt='Ваш профиль'/> - ваша личная страница, здесь можно читать и отправлять сообщения другим пользователям,
		изменять свои данные и наблюдать за своими достижениями. На этой клавише вы можете иногда наблюдать конвертик <img src = 'img/letter.png' alt='Конверт'/>
		- это означает, что у вас есть одно или несколько непрочитанных сообщений. <br>
		- <img src = 'img/k_stat.png' alt='Статистика'/> - статистика в зависимости от того где вы находитесь,
		если вы у себя на странице - то информация будет по вашим наградам,
		если в игре то по результатам рекордов и набранных медалей за все время. <br>
		- <img src = 'img/k_pause.png' alt='Пауза'/> - необходима в некоторых играх если вы вздумали отлучиться на не долго. <br>
		- <img src = 'img/k_new_game.png' alt='Начать заново'/> - в режиме игры для начала новой комбинации игры. <br>
		- <img src = 'img/k_revert.png' alt='New game'/> - в режиме игры отменяет последний сделанный шаг.<br>
		- <img src = 'img/k_save.png' alt='Сохранение игры'/> - в некоторых играх для ручного сохранения (например \"Замыкание\"). <br>
		- <img src = 'img/k_sound_on.png' alt='Звук'/> - включение и выключение звука. <br>
		- <img src = 'img/k_lang_rus.png' alt='Язык'/> - смена языка - русский/английский. <br>
		&nbsp&nbsp&nbsp&nbsp
		ТОП список играков в левом окне страницы - баллы там начисляются за хорошо сыгранные партии.
		Баллы данного счета динамичны как для роста в процессе посещения сайта, так и для уменьшения - если игрок заходит не так часто.
		Более подробная статистика может быть просмотренна, если кликнуть по пользователю из этого списка.<br>
		&nbsp&nbsp&nbsp&nbsp
		Правая сторона - блоки игр с прикрепленными к ним турнирными таблицами призовых мест по текущему времени,
		участников которых вы можете сдвинуть вниз, если у вас получится лучше.
		Следовательно, таблица с медалями не носит постоянного характера и всегда, будет меняться.
		Рекорды – это уже увековеченные партии в которых вы сможете сдвинуть соперника по баллам сыграв в любой из партии этой игры.
		&nbsp&nbsp&nbsp&nbsp
		Для выбора игры вам необходимо нажать по блоку игры по картинке или тексту, вы перейдете к нулевой игре - это новый расклад поля.
		Сохранить можно только пять игр (вы должны быть зарегистрированы),
		а сохранив хотя бы одну, вы можете соревноваться с другими игроками.<br>
		<ul class = 'gameCheckbox key openedGameCheckbox'><li></li></ul> - укажет на количество, уже сохранненых вами игр.<br>
        <ul class = 'gameCheckbox key wonGameCheckbox'><li></li></ul> - ваш результат в этой игре лучший.<br>
        <ul class = 'gameCheckbox key deletedGameCheckbox'><li></li></ul> - игра была удалена. Это может значить, что это сообщение просто устарело.<br>
		Для соревнования вам необходимо выбрать игру из турнирной таблицы или выбрать ее непосредственно в игре
		в списке «Комбинации полей» в правой части экрана, где будет представлен весь список партий.<br>
		&nbsp&nbsp&nbsp&nbsp
		Что это за соревнования – вы выбираете чужую игру, и вам на поле предоставляется копия расклада игровой комбинации,
		которая была у игрока, который ее открыл. А смысл в том, что игра будет честной, и совершенно не зависеть от разного расклада фишек.
		<?
}
?>
