<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

switch ($_REQUEST['act_2']) {
    case "add" :
        include ROOT_DIR . '/modules/admin/news/add.php';
        break;
    case "add_save" :
        include ROOT_DIR . '/modules/admin/news/add_save.php';
        break;
    case "edit" :
        include ROOT_DIR . '/modules/admin/news/edit.php';
        break;
    case "edit_save" :
        include ROOT_DIR . '/modules/admin/news/edit_save.php';
        break;
    case "del" :
        include ROOT_DIR . '/modules/admin/news/del.php';
        break;

    default :

        if ($_REQUEST['submit_del']) info('Успешно', 'Новость с ID: ' . intval($_REQUEST['submit_del']) . ' была успешно удалена.');

        $all_mod = $db->super_query("SELECT COUNT(*) as count FROM  news");
		$its_all = $all_mod['count'];

        if ($its_all == 0) info('Новости не найдены.', 'Добавьте новую новость. ');
		
		$page = new page($its_all, $config['page']);
		
		$news_sql = $db->query("SELECT * FROM news ORDER BY id DESC LIMIT ".$page->go.", ".$config['page']."");

        while ($row = $db->get_row($news_sql)) {
            $buffer .= <<<HTML
<div class="post">
<img src="{$config['home_url']}img.php?pic=ball" alt=""> <a href="{$config['home_url']}index.php?do=news&act=news&id={$row['id']}"><strong>{$row['name']}</strong></a> <small>[<a href="{$config['home_url']}index.php?do=admin&act=news&act_2=edit&id={$row['id']}">Изменить</a>] [<a href="{$config['home_url']}index.php?do=admin&act=news&act_2=del&id={$row['id']}">Удалить</a>]</small><br>
<small>ID: {$row['id']} | Просмотров: {$row['count']} | Комментариев: {$row['count_comm']}</small><br></div>
HTML;
        } 

        $buffer .= <<<HTML
<div class="link"><a href="{$PHP_SELF}?do=admin&act=news&act_2=add">Добавить новость</a></div>
<div class="link"><a href="{$PHP_SELF}?do=admin">Вернуться назад</a></div>
HTML;
		$tpl->copy_tpl .= $page->listing($PHP_SELF.'?do=admin&act=news');
        $tpl->copy_tpl .= $buffer;
        $tpl->compile('content');
        $tpl->clear();
        $db->free();
        unset($buffer);
} 

?>