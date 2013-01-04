<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

if ($config['book_on'] == 1) $config_book_on_1 = 'selected';
else $config_book_on_0 = 'selected';
if ($config['book_on_user'] == 1) $config_book_on_user_1 = 'selected';
else $config_book_on_user_0 = 'selected';

$buffer = <<<HTML
<div class="post_add">
<form  method="post" action="{$PHP_SELF}?do=admin&act=setting&act_2=save">
Доступ гостям:<br>
<select name="save_con[book_on]">
<option value="1" {$config_book_on_1}>Открыт</option>
<option value="0" {$config_book_on_0}>Закрыт</option>
</select><br>
Доступ пользователям:<br>
<select name="save_con[book_on_user]">
<option value="1" {$config_book_on_user_1}>Открыт</option>
<option value="0" {$config_book_on_user_0}>Закрыт</option>
</select></div>
<div class="post_add">
<input type="submit" value="Сохранить"></form></div>
<div class="block"><a href="{$config['home_url']}index.php?do=admin&act=setting">Вернуться назад</a></div>
HTML;

?>