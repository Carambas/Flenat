<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$cat_info = array ();

$db->query ("SELECT * FROM downloads_category ORDER BY position DESC");
while ($row = $db->get_row ()) {
    $cat_info[$row['id']] = array ();

    foreach ($row as $key => $value) {
        $cat_info[$row['id']][$key] = stripslashes ($value);
    } 
} 

$buffer .= <<<HTML
<div class="post_add">
<form  method="post" action="{$PHP_SELF}?do=admin&act=downloads&act_2=category_add_save">
Основная категория:<br>
HTML;

$buffer .= '<select name="cat_id">' . downloads_cat_select() . '</select><br>';

$buffer .= <<<HTML
Название категории:<br>
<input id="add" type="text" name="cat_name"><br>
<input type="submit" value="Добавить"></form></div>
HTML;

$buffer .= <<<HTML
<div class="block"><a href="{$PHP_SELF}?do=admin&act=downloads&act_2=category">Вернуться назад</a></div>
HTML;

?>