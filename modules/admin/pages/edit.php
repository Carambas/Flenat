<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
$page = intval ($_REQUEST['id']);
$static_result = $db->super_query("SELECT * FROM pages WHERE id='$page'");
if ($static_result['name']) {
    $buffer .= <<<HTML
<div class="post_add">
<form  method="post" action="{$PHP_SELF}?do=admin&act=pages&act_2=edit_save">
<input type="hidden" name="page_id" value="{$static_result['id']}"><br>
Название страницы:<br>
<input id="add" type="text" name="page_name" value="{$static_result['name']}"><br>
Текст страницы: <br>
<textarea name="page_text" rows="25" cols="45">{$static_result['text']}</textarea><br>
<input type="submit" value="Сохранить"></form>
</div>
<div class="block"><a href="{$PHP_SELF}?do=admin&act=pages">Вернуться назад</a></div>
HTML;
} else {
    $buffer .= <<<HTML
<div class="text">Указанной страницы несуществует.</div>
<div class="block"><a href="{$PHP_SELF}?do=admin&act=pages">Вернуться назад</a></div>
HTML;
} 

?>