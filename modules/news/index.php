<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

switch ($_REQUEST['act']) {
    case "news" :
        include ROOT_DIR . '/modules/news/news.php';
        break;

    default :

        if ($config['cache'] == '1') {
            $its_all = cache("news_count");

            if ($its_all == "") {
                $all_mod = $db->super_query("SELECT COUNT(*) as count FROM news");
                $its_all = $all_mod['count'];
                create_cache("news_count", $its_all);
            }
        } else {
            $all_mod = $db->super_query("SELECT COUNT(*) as count FROM  news");
            $its_all = $all_mod['count'];
        }
		$page = new page($its_all, $config['page']);

        if ($all_mod['count'] == 0) {
            info('Информация', 'Новости не найдены.');
        }
        $news_sql = $db->query("SELECT * FROM news ORDER BY id DESC LIMIT ".$page->go.", ".$config['page']."");

        while ($row = $db->get_row($news_sql)) {
            $tpl->load_tpl('news.tpl');
			$tpl->set_block("'\\[news_full\\](.*?)\\[/news_full\\]'si", "");
            $tpl->set('[news_small]', '');
            $tpl->set('[/news_small]', '');
			$tpl->set('{title}', check_full($row['name']));
            $tpl->set('{author}', '<a href="' . $PHP_SELF . '?do=profile&name=' . check_full($row['author']) . '">' . check_full($row['author']) . '</a>');

            $tpl->set('[title]', '<a href="' . $PHP_SELF . '?do=news&act=news&id=' . $row['id'] . '">');
            $tpl->set('[/title]', '</a>');

            $tpl->set('{text_small}', iOpis($row['text']));

			$tpl->set('{date}', iTime($row['date']));
            $tpl->set('{count}', intval($row['count']));
            $tpl->set('{comm_count}', intval($row['count_comms']));

            $tpl->compile('content');
            $tpl->clear();
        } 
		$tpl->copy_tpl = $page->listing($PHP_SELF.'?do=news');
        $tpl->compile('content');
        $tpl->clear();
        $db->free();
} 

?>