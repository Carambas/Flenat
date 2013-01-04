<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
switch ($_REQUEST['act_2']) {
    case "site" :
        include ROOT_DIR . '/modules/admin/setting/site.php';
        break;
    case "book" :
        include ROOT_DIR . '/modules/admin/setting/book.php';
        break;
    case "style" :
        include ROOT_DIR . '/modules/admin/setting/style.php';
        break;
    case "online" :
        include ROOT_DIR . '/modules/admin/setting/online.php';
        break;
    case "save" :
        include ROOT_DIR . '/modules/admin/setting/save.php';
        break;
    case "down" :
        include ROOT_DIR . '/modules/admin/setting/down.php';
        break;

    default :

        $buffer .= <<<HTML
<div class="link"><a href="{$config['home_url']}index.php?do=admin&act=setting&act_2=style">Настройки оформления</a></div>
<div class="link"><a href="{$config['home_url']}index.php?do=admin&act=setting&act_2=down">Настройки файлового архива</a></div>
<div class="link"><a href="{$config['home_url']}index.php?do=admin&act=setting&act_2=book">Настройки гостевой</a></div>
<div class="link"><a href="{$config['home_url']}index.php?do=admin&act=setting&act_2=online">Настройки онлайна</a></div>
<div class="link"><a href="{$config['home_url']}index.php?do=admin&act=setting&act_2=site">Настройки сайта</a></div>
<div class="block"><a href="{$config['home_url']}index.php?do=admin">Вернуться назад</a></div>
HTML;
} 

?>