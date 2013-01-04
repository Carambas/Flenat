<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
$buffer .= <<<HTML
<div class="post_add">
<form  method="post" action="{$PHP_SELF}?do=admin&act=news&act_2=add_save">
Название новости:<br>
<input id="add" type="text" name="news_name" value="{$static_result['name']}"><br>
Текст новости (Кратко): <br>
<textarea name="news_text">{$static_result['text']}</textarea><br>
Текст новости (Полно): <br>
<textarea name="news_text_full">{$static_result['text_full']}</textarea><br>
<input type="submit" value="Сохранить"></form>
</div>
<div class="block"><a href="{$PHP_SELF}?do=admin&act=news">Вернуться назад</a></div>
HTML;

?>