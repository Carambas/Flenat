<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$id = intval($_GET['id']);

$cat_down = $db->super_query ("SELECT * FROM downloads_category WHERE id = '$id'");

if ($cat_down['name']) {
    if ($_GET['submit'] != true) {
        $cat_file = $db->super_query ("SELECT count(*) as count FROM downloads_files WHERE category = '$id'");
        if ($cat_file['count'] > 0) {
            info('Осторожно!', 'Категория содержит файлы! При её удалении файлы тоже будут удалены.');
        } 
        $buffer .= <<<HTML
<div class="post">Вы действительно хотите удалить категорию <b>{$cat_down['name']}</b>?<br>
<a href="{$PHP_SELF}?do=admin&act=downloads&act_2=category_del&id={$id}&submit=true">Удалить</a> | <a href="{$PHP_SELF}?do=admin&act=downloads&act_2=category">Отмена</a></div>
<div class="block"><a href="{$PHP_SELF}?do=admin&act=downloads&act_2=category">Вернуться назад</a></div>
HTML;
    } else {
        $cat_down_2 = $db->super_query ("SELECT * FROM downloads_category WHERE id_parent = '$id'");
        if ($cat_down_2['name']) {
            info('Ошибка', 'Чтобы удалить основную категорию, удалите вложенные.');
            $buffer .= <<<HTML
<div class="block"><a href="{$PHP_SELF}?do=admin&act=downloads&act_2=category">Вернуться назад</a></div>
HTML;
        } else {
            info('Успешно', 'Категория и входящие в неё файлы были успешно удалены.');
            $buffer .= <<<HTML
<div class="block"><a href="{$PHP_SELF}?do=admin&act=downloads&act_2=category">Вернуться назад</a></div>
HTML;
            $db->query ("DELETE FROM `downloads_category` WHERE id = '$id'");

            $files_del = $db->query ("SELECT * FROM `downloads_files` WHERE category = '$id'");

            while ($row = $db->get_row($files_del)) {
                @unlink(ROOT_DIR . '/uploads/downloads/files/' . $row['file']);
                @unlink(ROOT_DIR . '/uploads/downloads/screen/' . $row['screen']);
                @unlink(ROOT_DIR . '/uploads/downloads/screen_small/' . $row['screen']);
            } 

            $db->query ("DELETE FROM `downloads_files` WHERE category = '$id'");
        } 
    } 
} else {
    $db->query ("DELETE FROM `downloads_category` WHERE id = '$id'");
    @header ('Location: ' . $PHP_SELF . '?do=admin&act=downloads&act_2=category');
    exit;
} 

?>