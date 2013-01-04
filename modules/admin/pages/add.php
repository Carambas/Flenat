<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
$buffer .= <<<HTML
<div class="post_add">
<form  method="post" action="{$PHP_SELF}?do=admin&act=pages&act_2=add_save">
Название страницы:<br>
<input id="add" type="text" name="page_name" value="{$static_result['name']}"><br>
Текст страницы: <br>
<textarea name="page_text" rows="25" cols="45">{$static_result['text']}</textarea><br>
<input type="submit" value="Сохранить"></form></div>
<div class="block"><a href="{$PHP_SELF}?do=admin&act=pages">Вернуться назад</a></div>
HTML;

?>