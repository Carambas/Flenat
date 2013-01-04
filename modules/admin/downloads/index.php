<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

switch ($_REQUEST['act_2']) {
    case "category" :
        include ROOT_DIR . '/modules/admin/downloads/category.php';
        break;
    case "category_edit" :
        include ROOT_DIR . '/modules/admin/downloads/category_edit.php';
        break;
    case "category_add" :
        include ROOT_DIR . '/modules/admin/downloads/category_add.php';
        break;
    case "category_add_save" :
        include ROOT_DIR . '/modules/admin/downloads/category_add_save.php';
        break;
    case "category_del" :
        include ROOT_DIR . '/modules/admin/downloads/category_del.php';
        break;
    case "category_edit_save" :
        include ROOT_DIR . '/modules/admin/downloads/category_edit_save.php';
        break;
    case "category_into" :
        include ROOT_DIR . '/modules/admin/downloads/category_into.php';
        break;

    case "files_add" :
        include ROOT_DIR . '/modules/admin/downloads/files_add.php';
        break;
    case "files_add_save" :
        include ROOT_DIR . '/modules/admin/downloads/files_add_save.php';
        break;

    case "files_add_mass" :
        include ROOT_DIR . '/modules/admin/downloads/files_add_mass.php';
        break;
    case "files_add_mass_save" :
        include ROOT_DIR . '/modules/admin/downloads/files_add_mass_save.php';
        break;

    default :
        $buffer .= <<<HTML
<div class="link"><a href="{$config['home_url']}index.php?do=admin&act=downloads&act_2=category">Управление категориями</a></div>
<div class="link"><a href="{$config['home_url']}index.php?do=admin&act=downloads&act_2=files_add_mass">Массовая загрузка файлов</a></div>
<div class="link"><a href="{$config['home_url']}index.php?do=admin&act=downloads&act_2=files_add">Добавление файла</a></div>
<div class="block"><a href="{$PHP_SELF}?do=admin">Вернуться назад</a></div>
HTML;
        $tpl->copy_tpl .= $buffer;
        $tpl->compile('content');
        $tpl->clear();
        unset($buffer);
} 

?>