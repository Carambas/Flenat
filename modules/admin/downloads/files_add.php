<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$cat_info = array ();

$db->query ("SELECT * FROM downloads_category ORDER BY position DESC");
while ($row = $db->get_row ()) {
    $cat_info[$row['id']] = array ();

    foreach ($row as $key => $value) {
        $cat_info[$row['id']][$key] = stripslashes ($value);
    } 
}

if (!function_exists ("zip_open")) $add_zip = '<font color="red">Не поддерживается</font>';
else $add_zip = '<font color="green">Поддерживается</font>';

$buffer .= <<<HTML
<div class="post_add">
<form enctype="multipart/form-data" method="post" action="{$PHP_SELF}?do=admin&act=downloads&act_2=files_add_save">
Выбирите категорию:<br>
HTML;

$buffer .= '<select name="file_cat">' . downloads_cat_select() . '</select><br>';

$buffer .= <<<HTML
Название файла:<br>
<input id="add" type="text" name="file_title"><br>
Файл:<br>
<input id="add" type="file" name="file_file"><br>
<input type="checkbox" name="archive" value="1"> Упаковать в ZIP ({$add_zip})<br>
Скриншот:<br>
<input id="add" type="file" name="file_screen"><br>
Описание файла: <small><a href="{$PHP_SELF}?do=others&act=bbcode">[Стили]</a> <a href="{$PHP_SELF}?do=others&act=smiles">[Смайлы]</a></small><br>
<textarea name="description"></textarea><br>
<input type="submit" value="Добавить"></form></div>
<div class="block"><a href="{$PHP_SELF}?do=admin&act=downloads&act_2=downloads">Вернуться назад</a></div>
HTML;

?>