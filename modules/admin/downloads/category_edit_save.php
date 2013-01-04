<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

switch ($_REQUEST['submit']) {
    case "1" :
        $cat_id = intval($_REQUEST['cat_id']);
        $cat_name = check_full($_REQUEST['cat_name']);

        if (!$cat_name or !$cat_id) {
            info('Ошибка', 'Незаполнено одно из полей.');
        } else {
            $db->query("UPDATE downloads_category SET name = '$cat_name' WHERE id = '$cat_id'");
            info('Успешно', 'Категория успешно обновлена.');
            $buffer .= <<<HTML
<div class="block"><a href="{$PHP_SELF}?do=admin&act=downloads&act_2=category">Вернуться назад</a></div>
HTML;
            if ($config['cache'] == '1') @clear_cache();
        } 
        break;

    case "2" :
        $cat_id = intval($_REQUEST['cat_id']);
        $cat_id2 = intval($_REQUEST['cat_id2']);

        if ($cat_id == "" and $cat_id2 == "") {
            info('Ошибка', 'Незаполнено одно из полей.');
        } else {
            $cat_down = $db->super_query ("SELECT * FROM downloads_category WHERE id = '$cat_id2'");
            $id = $cat_down['id_parent'];

            $cat_down2 = $db->super_query ("SELECT count(id_parent) as count FROM downloads_category WHERE id_parent = '$cat_id2'");
            $id_parent = $cat_down2['count'];

            if ($id_parent != 0) {
                info('Ошибка', 'Нельзя переносить главные категории в подкатегории. ');
            } else {
                if ($cat_id2 == $cat_id) {
                    info('Ошибка', 'Нельзя перенести категорию в ту же категорию.');
                } else {
                    $db->query("UPDATE downloads_category SET id_parent = '$cat_id' WHERE id = '$cat_id2'");
                    info('Успешно', 'Категория успешно обновлена.');
                    if ($config['cache'] == '1') @clear_cache();
                } 
            } 
        } 
        $buffer .= <<<HTML
<div class="block"><a href="{$PHP_SELF}?do=admin&act=downloads&act_2=category">Вернуться назад</a></div>
HTML;
        break;

    default :
        info('Ошибка', 'Действие отменено.');
        $buffer .= <<<HTML
<div class="block"><a href="{$PHP_SELF}?do=admin&act=downloads&act_2=category">Вернуться назад</a></div>
HTML;
} 

?>