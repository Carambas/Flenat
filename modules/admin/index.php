<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ////////////////////////////
// ///// Проверка на доступ
if ($user_id['grup'] == 1) $moder = true;

if ($moder) {
    switch ($_REQUEST['act']) {
        // ////////////////////////////
        // ///// настройки
        case "setting" :
            include ROOT_DIR . '/modules/admin/setting/index.php';
            break; 
        // ////////////////////////////
        // ///// страницы
        case "pages" :
            include ROOT_DIR . '/modules/admin/pages/index.php';
            break; 
        // ////////////////////////////
        // ///// новости
        case "news" :
            include ROOT_DIR . '/modules/admin/news/index.php';
            break; 
        // ////////////////////////////
        // ///// загрузки
        case "downloads" :
            include ROOT_DIR . '/modules/admin/downloads/index.php';
            break; 
        // ////////////////////////////
        // ///// оптимизация
        case "optimization" :
            @$db->query ("OPTIMIZE TABLE `book`");
            @$db->query ("OPTIMIZE TABLE `flood`");
            @$db->query ("OPTIMIZE TABLE `online`");
            @$db->query ("OPTIMIZE TABLE `pages`");
            @$db->query ("OPTIMIZE TABLE `users`");
            @$db->query ("OPTIMIZE TABLE `comments_files`");
            @$db->query ("OPTIMIZE TABLE `news`");
            @$db->query ("OPTIMIZE TABLE `message`");
            @clear_cache ();
            info('Успешно', 'База оптимизирована. Кэш очищен.');
			$buffer = <<<HTML
<div class="block"><a href="{$config['home_url']}index.php?do=admin">Вернуться назад</a></div>
HTML;
            break; 
        // ////////////////////////////
        // ///// выводим стандартно
        default :
            $mysql_size = formatsize(mysql_size());
            $cache_size = formatsize(dirsize("cache"));

            $buffer .= <<<HTML
<div class="post_add">
Размер кэша: {$cache_size}<br>
Размер базы: {$mysql_size}<br>
</div>
<div class="link"><a href="{$config['home_url']}index.php?do=admin&act=optimization">Оптимизация</a></div>
<div class="link"><a href="{$config['home_url']}index.php?do=admin&act=setting">Настройки сайта</a></div>
<div class="link"><a href="{$config['home_url']}index.php?do=admin&act=pages">Статические страницы</a></div>
<div class="link"><a href="{$config['home_url']}index.php?do=admin&act=news">Управление новостями</a></div>
<div class="link"><a href="{$config['home_url']}index.php?do=admin&act=downloads">Файловый архив</a></div>
HTML;
    } 
} else {
    $buffer .= 'У вас нету доступа к данному разделу сайта.';
} 
// ////////////////////////////
// ///// собираем
$tpl->copy_tpl = $buffer;
$tpl->compile('content');
$tpl->clear();

?>