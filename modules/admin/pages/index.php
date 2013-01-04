<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

switch ($_REQUEST['act_2']) {
    case "add" :
        include ROOT_DIR . '/modules/admin/pages/add.php';
        break;
    case "add_save" :
        include ROOT_DIR . '/modules/admin/pages/add_save.php';
        break;
    case "edit" :
        include ROOT_DIR . '/modules/admin/pages/edit.php';
        break;
    case "edit_save" :
        include ROOT_DIR . '/modules/admin/pages/edit_save.php';
        break;
    case "del" :
        include ROOT_DIR . '/modules/admin/pages/del.php';
        break;

    default :

        if ($_REQUEST['submit_del']) info('Успешно', 'Страница с ID: ' . intval($_REQUEST['submit_del']) . ' была успешно удалена.');

        if ($config['cache'] == '1') {
            $its_all = cache("pages_count");

            if ($its_all == "") {
                $all_mod = $db->super_query("SELECT COUNT(*) as count FROM pages");
                $its_all = $all_mod['count'];
                create_cache("pages_count", $its_all);
            }
        } else {
            $all_mod = $db->super_query("SELECT COUNT(*) as count FROM  pages");
            $its_all = $all_mod['count'];
        }
		
		$page = new page($all_all, $config['page']);

        if ($its_all == 0) info('Страницы не найдены.', 'Добавьте статическую страницу. ');

        $pages_sql = $db->query("SELECT * FROM pages ORDER BY id DESC LIMIT ".$page->go.", ".$config['page']."");
		
        while ($row = $db->get_row($pages_sql)) {
            $i ++;
            $buffer .= <<<HTML
<div class="post"><img src="{$config['home_url']}img.php?pic=ball" alt=""> <a href="{$config['home_url']}index.php?do=pages&act=page&id={$row['id']}">{$row['name']}</a> <small>[<a href="{$config['home_url']}index.php?do=admin&act=pages&act_2=edit&id={$row['id']}">Изменить</a>] [<a href="{$config['home_url']}index.php?do=admin&act=pages&act_2=del&id={$row['id']}">Удалить</a>]</small><br>
<small>ID: {$row['id']} | Просмотров: {$row['count']}</small><br></div>
HTML;
        } 

        $buffer .= <<<HTML
<div class="link"><a href="{$PHP_SELF}?do=admin&act=pages&act_2=add">Добавить страницу</a></div>
<div class="link"><a href="{$PHP_SELF}?do=admin">Вернуться назад</a></div>
HTML;
        $tpl->copy_tpl .= $buffer;
		$tpl->copy_tpl .= $page->listing($PHP_SELF.'?do=pages');
        $tpl->compile('content');
        $tpl->clear();
        $db->free();
        unset($buffer);
} 

?>